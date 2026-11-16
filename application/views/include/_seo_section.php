<?php
/*
| The Arabic services block shown on the page before Contact (all 4 designs): the
| company description, the list of what we equip and the frequently asked questions.
| Texts: back_office/home_page, "Google: Arabic text on the page". Empty = config/seo_defaults.php.
| $wrap = the container class of the design.
*/
$this->config->load('seo_defaults', TRUE);
$sd = $this->config->item('seo_defaults', 'seo_defaults');
$s = isset($home_page) ? $home_page : array();
foreach (array('seo_section_title', 'seo_text_ar', 'seo_services_ar', 'seo_faq') as $name) {
	if (!isset($s[$name]) || trim($s[$name]) === '') $s[$name] = $sd[$name];
}
$e = function($t){ return htmlspecialchars(trim((string)$t), ENT_QUOTES, 'UTF-8'); };
$lines = function($text){ return preg_split('/\s*\R\s*/u', trim((string)$text), -1, PREG_SPLIT_NO_EMPTY); };
$paragraphs = preg_split('/\s*\R\s*\R\s*/u', trim($s['seo_text_ar']), -1, PREG_SPLIT_NO_EMPTY);
$services = $lines($s['seo_services_ar']);
$faq = array();
foreach ($lines($s['seo_faq']) as $line) {
	if (strpos($line, '|') === FALSE) continue;
	list($q, $a) = array_map('trim', explode('|', $line, 2));
	if ($q !== '' && $a !== '') $faq[] = array($q, $a);
}
$wrap = isset($wrap) ? $wrap : 'container';
?>
<section id="services-ar" class="seo-ar" dir="rtl" lang="ar" aria-labelledby="seo-ar-title">
	<div class="<?=$e($wrap)?>">
		<div class="seo-ar-grid">
			<div class="seo-ar-text">
				<h2 id="seo-ar-title" class="seo-ar-h2"><?=$e($s['seo_section_title'])?></h2>
				<?php foreach ($paragraphs as $p) { ?>
				<p><?=nl2br($e($p))?></p>
				<?php } ?>
			</div>
			<?php if (count($services)) { ?>
			<div class="seo-ar-services">
				<h3 class="seo-ar-h3">ماذا نجهّز</h3>
				<ul class="seo-ar-list">
					<?php foreach ($services as $service) { ?>
					<li><?=$e($service)?></li>
					<?php } ?>
				</ul>
			</div>
			<?php } ?>
		</div>
		<?php if (count($faq)) { ?>
		<div class="seo-ar-faq">
			<h3 class="seo-ar-h3">أسئلة شائعة</h3>
			<?php foreach ($faq as $qa) { ?>
			<details class="seo-ar-qa">
				<summary><?=$e($qa[0])?></summary>
				<p><?=$e($qa[1])?></p>
			</details>
			<?php } ?>
		</div>
		<?php } ?>
	</div>
</section>
