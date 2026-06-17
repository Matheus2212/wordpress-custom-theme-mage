<?php
/**
 * Mage emblem — animated SVG "aura" effects wrapped around the (static) mage logo.
 *
 * The surrounding effects (rotating ring, rising beams, central light pillar,
 * ground portal ripples and sparkles) are an animated SVG. The logo in the
 * middle is rendered separately and is never animated.
 *
 * @package mage
 */

?>
<div class="mage-emblem" role="img" aria-label="<?php esc_attr_e( 'Logo da Mage Systems com efeitos mágicos animados', 'mage' ); ?>">

	<?php /* ── Surrounding effects: animated SVG ─────────────────────────── */ ?>
	<svg class="mage-aura" viewBox="0 0 600 820" preserveAspectRatio="xMidYMid meet" aria-hidden="true" focusable="false">
		<defs>
			<radialGradient id="mageAuraGlow" cx="50%" cy="50%" r="50%">
				<stop offset="0%"  stop-color="#b96cff" stop-opacity=".55" />
				<stop offset="45%" stop-color="#810AD2" stop-opacity=".25" />
				<stop offset="100%" stop-color="#810AD2" stop-opacity="0" />
			</radialGradient>
			<linearGradient id="magePillar" x1="0" y1="0" x2="0" y2="1">
				<stop offset="0%"   stop-color="#d9b3ff" stop-opacity="0" />
				<stop offset="35%"  stop-color="#c58bff" stop-opacity=".85" />
				<stop offset="100%" stop-color="#810AD2" stop-opacity="0" />
			</linearGradient>
			<linearGradient id="mageBeam" x1="0" y1="1" x2="0" y2="0">
				<stop offset="0%"   stop-color="#c58bff" stop-opacity="0" />
				<stop offset="60%"  stop-color="#c58bff" stop-opacity=".9" />
				<stop offset="100%" stop-color="#e9d4ff" stop-opacity="0" />
			</linearGradient>
		</defs>

		<?php /* Soft aura behind the logo */ ?>
		<circle class="aura-glow" cx="300" cy="300" r="190" fill="url(#mageAuraGlow)" />

		<?php /* Halo rings — counter-rotating */ ?>
		<g class="aura-rings" fill="none">
			<circle class="ring ring-1" cx="300" cy="300" r="150" stroke="#b96cff" stroke-width="1.5" stroke-opacity=".55" stroke-dasharray="2 14" stroke-linecap="round" />
			<circle class="ring ring-2" cx="300" cy="300" r="168" stroke="#810AD2" stroke-width="1" stroke-opacity=".4" stroke-dasharray="60 28 14 28" />
			<circle class="ring ring-3" cx="300" cy="300" r="132" stroke="#d9b3ff" stroke-width="1" stroke-opacity=".5" stroke-dasharray="180 520" stroke-linecap="round" />
		</g>

		<?php /* Central light pillar dropping from the logo to the portal */ ?>
		<rect class="pillar" x="296" y="320" width="8" height="330" rx="4" fill="url(#magePillar)" />

		<?php /* Rising light beams */ ?>
		<g class="beams" stroke="url(#mageBeam)" stroke-width="2" stroke-linecap="round">
			<line class="beam beam-1" x1="262" y1="640" x2="262" y2="560" />
			<line class="beam beam-2" x1="300" y1="660" x2="300" y2="540" />
			<line class="beam beam-3" x1="338" y1="640" x2="338" y2="560" />
			<line class="beam beam-4" x1="282" y1="650" x2="282" y2="570" />
			<line class="beam beam-5" x1="320" y1="650" x2="320" y2="570" />
		</g>

		<?php /* Ground portal — concentric ripples in perspective */ ?>
		<g class="portal" fill="none" stroke="#b96cff">
			<ellipse class="ripple ripple-1" cx="300" cy="660" rx="60"  ry="14" stroke-width="2" />
			<ellipse class="ripple ripple-2" cx="300" cy="660" rx="110" ry="24" stroke-width="1.5" />
			<ellipse class="ripple ripple-3" cx="300" cy="660" rx="170" ry="36" stroke-width="1.2" />
			<ellipse class="ripple ripple-4" cx="300" cy="660" rx="235" ry="50" stroke-width="1" />
		</g>
		<circle class="portal-core" cx="300" cy="660" r="6" fill="#f0e0ff" />

		<?php /* Sparkles */ ?>
		<g class="sparkles" fill="#e9d4ff">
			<path class="spark spark-1" d="M150 230 l3 9 9 3 -9 3 -3 9 -3 -9 -9 -3 9 -3 z" />
			<path class="spark spark-2" d="M455 200 l2.5 7 7 2.5 -7 2.5 -2.5 7 -2.5 -7 -7 -2.5 7 -2.5 z" />
			<path class="spark spark-3" d="M420 360 l2 6 6 2 -6 2 -2 6 -2 -6 -6 -2 6 -2 z" />
			<path class="spark spark-4" d="M175 380 l2 6 6 2 -6 2 -2 6 -2 -6 -6 -2 6 -2 z" />
			<path class="spark spark-5" d="M300 120 l3 9 9 3 -9 3 -3 9 -3 -9 -9 -3 9 -3 z" />
		</g>
	</svg>

	<?php /* ── The mage hat mark in the middle — static, never animated ───── */ ?>
	<div class="mage-mark">
		<svg class="mage-mark-svg" viewBox="0 0 512 512" aria-hidden="true" focusable="false">
			<defs>
				<linearGradient id="mageHatFill" x1="0" y1="0" x2="1" y2="1">
					<stop offset="0%" stop-color="#bd7bff" />
					<stop offset="100%" stop-color="#8a23e0" />
				</linearGradient>
				<linearGradient id="mageBrimFill" x1="0" y1="0" x2="0" y2="1">
					<stop offset="0%" stop-color="#7d22cf" />
					<stop offset="100%" stop-color="#5a118f" />
				</linearGradient>
			</defs>
			<?php /* brim */ ?>
			<ellipse cx="256" cy="258" rx="176" ry="30" fill="url(#mageBrimFill)" />
			<?php /* pointed hat with a folded-over tip */ ?>
			<path d="M188 18 C 178 50 168 78 152 96 L 214 126 L 200 232 L 340 232 L 330 110 Z" fill="url(#mageHatFill)" />
			<?php /* hat-band sparkle */ ?>
			<path d="M256 136 L265 167 L296 176 L265 185 L256 216 L247 185 L216 176 L247 167 Z" fill="#ffffff" />
		</svg>
	</div>
</div>
