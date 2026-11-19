<?php
/* The designed background of the banner (home page > "Banner background" = designed): no photo, a technical drawing of a
   kitchen line that draws itself — upright fridge, counter fridge, range under its hood, fryer and display fridge — over the
   dark colours of the site. Each shape has pathLength="1" so css_site/option5.css can draw it line by line (--i = its turn). */
?>
<div class="hero-art" aria-hidden="true">
	<span class="art-glow art-glow-a"></span>
	<span class="art-glow art-glow-b"></span>
	<svg viewBox="0 0 1600 520" preserveAspectRatio="xMidYMax meet" fill="none">
		<!-- floor -->
		<path class="d" style="--i:0" pathLength="1" d="M40 470H1560"/>
		<!-- upright fridge -->
		<g style="--i:1">
			<rect class="d" pathLength="1" x="80" y="110" width="250" height="360" rx="4"/>
			<path class="d" pathLength="1" d="M80 152H330M205 152V470"/>
			<path class="d" pathLength="1" d="M104 124H306M104 134H306M104 144H306"/>
			<path class="d a" pathLength="1" d="M193 270V340M217 270V340"/>
		</g>
		<!-- counter fridge with its worktop -->
		<g style="--i:2">
			<path class="d" pathLength="1" d="M350 300H770V312H350Z"/>
			<rect class="d" pathLength="1" x="360" y="312" width="400" height="148"/>
			<path class="d" pathLength="1" d="M493 312V460M627 312V460M370 460V470M750 460V470"/>
			<path class="d a" pathLength="1" d="M478 360V410M612 360V410M746 360V410"/>
			<path class="d" pathLength="1" d="M360 300V262H770V300"/>
		</g>
		<!-- hood -->
		<g style="--i:3">
			<path class="d" pathLength="1" d="M770 120H1070L1100 192H740Z"/>
			<rect class="d" pathLength="1" x="880" y="36" width="80" height="84"/>
			<path class="d" pathLength="1" d="M760 204H1080M800 192V204M860 192V204M920 192V204M980 192V204M1040 192V204"/>
		</g>
		<!-- range: burners, knobs, oven -->
		<g style="--i:4">
			<rect class="d" pathLength="1" x="790" y="300" width="260" height="170"/>
			<path class="d a" pathLength="1" d="M812 300V288H868V300M888 300V288H944V300M964 300V288H1020V300"/>
			<circle class="d" pathLength="1" cx="822" cy="328" r="8"/>
			<circle class="d" pathLength="1" cx="870" cy="328" r="8"/>
			<circle class="d" pathLength="1" cx="920" cy="328" r="8"/>
			<circle class="d" pathLength="1" cx="970" cy="328" r="8"/>
			<circle class="d" pathLength="1" cx="1018" cy="328" r="8"/>
			<rect class="d" pathLength="1" x="810" y="352" width="220" height="100" rx="3"/>
			<rect class="d" pathLength="1" x="842" y="376" width="156" height="50" rx="3"/>
			<path class="d a" pathLength="1" d="M836 364H1004"/>
		</g>
		<!-- fryer -->
		<g style="--i:5">
			<rect class="d" pathLength="1" x="1076" y="300" width="130" height="170"/>
			<path class="d" pathLength="1" d="M1096 300V258H1132V300M1150 300V258H1186V300M1076 340H1206"/>
			<circle class="d a" pathLength="1" cx="1141" cy="372" r="9"/>
			<path class="d" pathLength="1" d="M1100 420H1182M1100 434H1182"/>
		</g>
		<!-- display fridge with curved glass -->
		<g style="--i:6">
			<rect class="d" pathLength="1" x="1240" y="362" width="296" height="108" rx="3"/>
			<path class="d" pathLength="1" d="M1240 362Q1246 252 1330 240H1536V362"/>
			<path class="d" pathLength="1" d="M1272 304H1536M1262 334H1536"/>
			<path class="d a" pathLength="1" d="M1262 430H1514M1262 442H1514"/>
		</g>
		<!-- dimension lines, like on the plan of a kitchen -->
		<g class="art-dim" style="--i:7">
			<path class="d a" pathLength="1" d="M350 498H1206M350 490V506M1206 490V506M770 492V504"/>
			<path class="d a" pathLength="1" d="M52 110V470M44 110H60M44 470H60"/>
			<text x="778" y="516">4200</text>
			<text x="30" y="296" transform="rotate(-90 30 296)">2000</text>
		</g>
	</svg>
</div>
