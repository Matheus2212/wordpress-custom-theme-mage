<?php
/**
 * Self-hosted theme updater.
 *
 * Lets the theme be updated from the WordPress dashboard (Appearance →
 * Themes / Dashboard → Updates) whenever the tracked GitHub branch has a
 * higher "Version:" than the installed one — i.e. on every merge that bumps
 * the version in style.css. No plugin required.
 *
 * Requirements on the server:
 *  - Outbound HTTPS access to github.com / api.github.com.
 *  - Filesystem write access to wp-content/themes (define FS_METHOD 'direct'
 *    in wp-config.php if WordPress asks for FTP credentials).
 *
 * Optional wp-config.php constants:
 *  - define( 'MAGE_UPDATE_REPO', 'owner/repo' );      // default below
 *  - define( 'MAGE_UPDATE_BRANCH', 'main' );          // branch to track
 *  - define( 'MAGE_GITHUB_TOKEN', 'ghp_xxx' );        // required for PRIVATE repos
 *                                                     // (fine-grained PAT, Contents: read)
 *
 * @package mage
 */

if ( ! defined( 'MAGE_UPDATE_REPO' ) ) {
	define( 'MAGE_UPDATE_REPO', 'Matheus2212/wordpress-custom-theme-mage' );
}
if ( ! defined( 'MAGE_UPDATE_BRANCH' ) ) {
	define( 'MAGE_UPDATE_BRANCH', 'main' );
}

if ( ! class_exists( 'Mage_Theme_Updater' ) ) :

	class Mage_Theme_Updater {

		/** @var string Theme folder slug (e.g. "mage"). */
		private $slug;

		/** @var string GitHub "owner/repo". */
		private $repo;

		/** @var string Branch to track. */
		private $branch;

		/** @var string Transient cache key. */
		private $cache_key;

		public function __construct() {
			$this->slug      = get_template();
			$this->repo      = MAGE_UPDATE_REPO;
			$this->branch    = MAGE_UPDATE_BRANCH;
			$this->cache_key = 'mage_updater_' . md5( $this->repo . '|' . $this->branch );

			add_filter( 'pre_set_site_transient_update_themes', array( $this, 'inject_update' ) );
			add_filter( 'upgrader_source_selection', array( $this, 'fix_source_dir' ), 10, 4 );
			add_filter( 'http_request_args', array( $this, 'authorize_download' ), 10, 2 );
			add_action( 'upgrader_process_complete', array( $this, 'flush_cache' ), 10, 0 );
		}

		/** Build GitHub API request args, adding auth for private repos. */
		private function api_args() {
			$args = array(
				'timeout' => 15,
				'headers' => array(
					'Accept'     => 'application/vnd.github+json',
					'User-Agent' => 'Mage-Theme-Updater',
				),
			);
			if ( defined( 'MAGE_GITHUB_TOKEN' ) && MAGE_GITHUB_TOKEN ) {
				$args['headers']['Authorization'] = 'Bearer ' . MAGE_GITHUB_TOKEN;
			}
			return $args;
		}

		/** Fetch the remote style.css and read its "Version:" header (cached). */
		private function remote_version() {
			$cached = get_transient( $this->cache_key );
			if ( false !== $cached ) {
				return $cached;
			}

			$url  = sprintf( 'https://api.github.com/repos/%s/contents/style.css?ref=%s', $this->repo, rawurlencode( $this->branch ) );
			$args = $this->api_args();
			$args['headers']['Accept'] = 'application/vnd.github.raw+json'; // raw file body

			$response = wp_remote_get( $url, $args );
			$version  = '';

			if ( ! is_wp_error( $response ) && 200 === wp_remote_retrieve_response_code( $response ) ) {
				$body = wp_remote_retrieve_body( $response );
				if ( preg_match( '/^[ \t\/*#@]*Version:\s*(.+)$/mi', $body, $matches ) ) {
					$version = trim( $matches[1] );
				}
			}

			// Cache for 6 hours (also caches "no version" to avoid hammering the API).
			set_transient( $this->cache_key, $version, 6 * HOUR_IN_SECONDS );
			return $version;
		}

		/** Add the update to the themes update transient when a newer version exists. */
		public function inject_update( $transient ) {
			if ( empty( $transient->checked ) ) {
				return $transient;
			}

			$installed = wp_get_theme( $this->slug )->get( 'Version' );
			$remote    = $this->remote_version();

			if ( $remote && version_compare( $remote, $installed, '>' ) ) {
				$transient->response[ $this->slug ] = array(
					'theme'       => $this->slug,
					'new_version' => $remote,
					'url'         => 'https://github.com/' . $this->repo,
					'package'     => sprintf( 'https://api.github.com/repos/%s/zipball/%s', $this->repo, rawurlencode( $this->branch ) ),
				);
			} else {
				unset( $transient->response[ $this->slug ] );
				$transient->no_update[ $this->slug ] = array(
					'theme'       => $this->slug,
					'new_version' => $installed,
					'url'         => 'https://github.com/' . $this->repo,
					'package'     => '',
				);
			}

			return $transient;
		}

		/** Send the token when WordPress downloads the zip from our private repo. */
		public function authorize_download( $args, $url ) {
			if ( defined( 'MAGE_GITHUB_TOKEN' ) && MAGE_GITHUB_TOKEN
				&& false !== strpos( $url, 'api.github.com/repos/' . $this->repo ) ) {
				$args['headers']['Authorization'] = 'Bearer ' . MAGE_GITHUB_TOKEN;
			}
			return $args;
		}

		/**
		 * GitHub zipballs extract to "owner-repo-<sha>/". Rename that folder to
		 * the theme slug so WordPress installs it in the right place.
		 */
		public function fix_source_dir( $source, $remote_source, $upgrader, $hook_extra = array() ) {
			if ( empty( $hook_extra['theme'] ) || $hook_extra['theme'] !== $this->slug ) {
				return $source;
			}

			global $wp_filesystem;
			if ( ! $wp_filesystem ) {
				return $source;
			}

			$desired = trailingslashit( $remote_source ) . $this->slug;
			if ( untrailingslashit( $source ) === untrailingslashit( $desired ) ) {
				return $source;
			}

			if ( $wp_filesystem->move( $source, $desired, true ) ) {
				return trailingslashit( $desired );
			}

			return $source;
		}

		/** Clear the version cache after an update so the dashboard reflects it. */
		public function flush_cache() {
			delete_transient( $this->cache_key );
		}
	}

	new Mage_Theme_Updater();

endif;
