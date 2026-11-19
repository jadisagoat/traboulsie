<?php
/* "Why Traboulsi Est.": what sets the company apart, shown on the pages of the menu and on every page of views/option5/pages.php.
   Its texts are edited in control panel > Pages & texts > Texts of the pages ({year} = year founded, {years} = years since). */
$why_l = $is_ar ? 'ar' : 'en';
$why_fill = function($text) use ($founded, $years){ return str_replace(array('{year}', '{years}'), array($founded ? $founded : 1982, $years ? $years : date('Y') - 1982), (string)$text); };
$why_icons = array('layers', 'wrench', 'truck', 'building');
$why = array();
for($n = 1; $n <= 4; $n++){
	if(isset($pt['why_'.$n.'_title_'.$why_l]) && trim($pt['why_'.$n.'_title_'.$why_l]) !== '') $why[] = array($why_icons[$n - 1], $why_fill($pt['why_'.$n.'_title_'.$why_l]), $why_fill($pt['why_'.$n.'_text_'.$why_l]));
}
if(count($why)){ ?>
<section class="sp-sec why is-dark">
	<div class="glow glow-b" aria-hidden="true"></div>
	<div class="container">
		<div class="section-head" data-reveal="up">
			<p class="eyebrow"><?=o5_e($pt['why_eyebrow_'.$why_l])?></p>
			<h2 class="h2"><?=o5_e($pt['why_title_'.$why_l])?></h2>
		</div>
		<ul class="why-grid">
			<?php foreach($why as $i => $w){ ?>
			<li data-reveal="up" style="--d:<?=$i * 100?>ms"><svg class="ic"><use href="#i-<?=$w[0]?>"/></svg><h3><?=o5_e($w[1])?></h3><p><?=o5_e($w[2])?></p></li>
			<?php } ?>
		</ul>
		<?php if(count($videos)){ ?>
		<p class="why-more" data-reveal="up"><a class="btn-link btn-link-light" href="<?=o5_e($videos_url)?>"><svg class="ic"><use href="#i-play"/></svg><?=o5_e($pt['why_more_'.$why_l])?><svg class="ic"><use href="#i-arrow"/></svg></a></p>
		<?php } ?>
	</div>
</section>
<?php } ?>
