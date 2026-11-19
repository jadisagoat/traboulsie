<?php
/* "Why Traboulsi Est.": what sets the company apart, shown on the home page and on every page of views/option5/pages.php.
   Local manufacturing, the year it was founded, one team for installation and service, and the factory videos as proof. */
$why_year = $founded ? $founded : 1982;
$why = $is_ar ? array(
	array('layers', 'صناعة لبنانية', 'البرادات والشوايات وأعمال الستانلس ستيل تُصنّع في مصنعنا في دير الزهراني، بمقاسات مشروعك.'),
	array('wrench', 'منذ '.$why_year, ($years ? $years.' سنة' : 'عقود').' في تجهيز المطابخ والمحلات والمستشفيات في كل لبنان.'),
	array('truck', 'فريق واحد', 'الشركة نفسها تصنّع وتورّد وتركّب وتصون معداتك، وتؤمّن قطع الغيار.'),
	array('building', 'فرعان', 'المصنع والمعرض الرئيسي في دير الزهراني، وفرع خلدة لخدمة بيروت وجبل لبنان.'),
) : array(
	array('layers', 'Made in Lebanon', 'Refrigeration, grills and stainless-steel work are built in our own factory in Deir El Zahrani, to the sizes of your project.'),
	array('wrench', 'Since '.$why_year, ($years ? $years.' years' : 'Decades').' equipping kitchens, shops and hospitals all over Lebanon.'),
	array('truck', 'One team', 'The same company manufactures, supplies, installs and maintains your equipment, and keeps the spare parts.'),
	array('building', 'Two branches', 'Factory and main showroom in Deir El Zahrani, and the Khalde branch for Beirut and Mount Lebanon.'),
);
?>
<section class="sp-sec why is-dark">
	<div class="glow glow-b" aria-hidden="true"></div>
	<div class="container">
		<div class="section-head" data-reveal="up">
			<p class="eyebrow"><?=$is_ar ? 'ما يميّزنا' : 'What sets us apart'?></p>
			<h2 class="h2"><?=$is_ar ? 'لماذا مؤسسة طرابلسي' : 'Why Traboulsi Est.'?></h2>
		</div>
		<ul class="why-grid">
			<?php foreach($why as $i => $w){ ?>
			<li data-reveal="up" style="--d:<?=$i * 100?>ms"><svg class="ic"><use href="#i-<?=$w[0]?>"/></svg><h3><?=o5_e($w[1])?></h3><p><?=o5_e($w[2])?></p></li>
			<?php } ?>
		</ul>
		<?php if(count($videos)){ ?>
		<p class="why-more" data-reveal="up"><a class="btn-link btn-link-light" href="<?=o5_e($videos_url)?>"><svg class="ic"><use href="#i-play"/></svg><?=$is_ar ? 'شاهد مصنعنا أثناء العمل' : 'Watch our factory at work'?><svg class="ic"><use href="#i-arrow"/></svg></a></p>
		<?php } ?>
	</div>
</section>
