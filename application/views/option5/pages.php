<?php
/*
| The pages built from the catalogue and from config/sectors.php + config/guides.php:
|   sector    yoursite/restaurant-equipment            category  yoursite/products/refrigeration-cold-display
|   product   yoursite/product/showcase-fridge-108     export    yoursite/export
|   guides    yoursite/guides                          guide     yoursite/guides/restaurant-kitchen-checklist
| (and the same addresses under yoursite/ar/). Included by home.php inside <main>: it shares the header, the footer,
| the contact form and every variable of the home page. The data of each page is prepared in controllers/home.php.
*/
$L = $is_ar ? 'ar' : 'en';
$wa_link = function($message) use ($contact){ return 'https://wa.me/'.$contact['whatsapp'].'?text='.rawurlencode($message); };
// a product card that opens the page of the product
$pc = function($product, $i = 0) use ($base, $cat_names){
	$name = o5_pname(o5_name($product['title']));
	$cat = isset($cat_names[$product['id_category']]) ? $cat_names[$product['id_category']] : '';
	$dir = is_file('uploads/products/600x455/'.$product['image']) ? 'uploads/products/600x455/' : 'uploads/products/290x220/'; ?>
	<a class="pc" href="<?=o5_e(o5_url('product/'.$product['slug']))?>" data-reveal="up" style="--d:<?=($i % 4) * 80?>ms">
		<span class="pc-img"><img src="<?=$base.$dir.o5_e($product['image'])?>" alt="<?=o5_e($name.($cat !== '' ? ' - '.$cat : ''))?>" loading="lazy"></span>
		<span class="pc-body"><small><?=o5_e($cat)?></small><b><?=o5_e($name)?></b><i class="pc-go" aria-hidden="true"><svg class="ic"><use href="#i-arrow"/></svg></i></span>
	</a>
<?php };
// the links to the sector pages (all of them, or all but the one being read)
$sector_links = function($skip = '') use ($sectors, $L){ ?>
	<div class="sec-grid">
		<?php $i = 0; foreach($sectors as $slug => $s){ if($slug === $skip) continue; ?>
		<a class="sec-card" href="<?=o5_e(o5_url($s['path']))?>" data-reveal="up" style="--d:<?=($i++ % 4) * 80?>ms">
			<svg class="ic"><use href="#i-<?=$s['icon']?>"/></svg>
			<b><?=o5_e($s[$L]['name'])?></b>
			<i aria-hidden="true"><svg class="ic"><use href="#i-arrow"/></svg></i>
		</a>
		<?php } ?>
	</div>
<?php };
$crumbs = array(); // name => address ('' for the page itself)
$actions_message = '';
?>

<?php /* ===================================================================== Sector ===================================================================== */
if($page === 'sector'){
	$t = $sector[$L];
	$ask_subject = ($is_ar ? 'طلب عرض سعر: ' : 'Quote request: ').$t['h1'];
	$actions_message = ($is_ar ? 'مرحباً، أريد عرض سعر لـ' : 'Hello, I would like a quote for: ').$t['h1'];
?>
<section id="top" class="g-hero is-dark">
	<div class="glow glow-a" aria-hidden="true"></div>
	<div class="container">
		<nav class="crumbs" aria-label="<?=o5_te('You are here')?>" data-reveal="up">
			<a href="<?=o5_e($home_url)?>"><?=o5_te('Home')?></a><span aria-hidden="true">/</span><a href="<?=o5_e($home_url)?>#services"><?=o5_te('What we equip')?></a><span aria-hidden="true">/</span><b><?=o5_e($t['name'])?></b>
		</nav>
		<p class="eyebrow" data-reveal="up" style="--d:60ms"><svg class="ic"><use href="#i-<?=$sector['icon']?>"/></svg> <?=o5_te('What we equip')?></p>
		<h1 class="g-title" data-reveal="up" style="--d:120ms"><?=o5_e($t['h1'])?></h1>
		<p class="lead" data-reveal="up" style="--d:200ms"><?=o5_e($t['lead'])?></p>
		<div class="g-actions" data-reveal="up" style="--d:280ms">
			<a href="#contact" class="btn-solid btn-glow" data-magnetic><span><?=o5_te('Request a quote')?></span><svg class="ic"><use href="#i-arrow"/></svg></a>
			<?php if($contact['whatsapp']){ ?><a href="<?=o5_e($wa_link($actions_message))?>" target="_blank" rel="noopener" class="btn-link btn-link-light"><svg class="ic"><use href="#i-whatsapp"/></svg><?=o5_te('WhatsApp')?></a><?php } ?>
			<?php if($call_num){ ?><a href="<?=o5_e($call_href)?>" class="btn-link btn-link-light"><svg class="ic"><use href="#i-phone"/></svg><span class="tel"><?=o5_e($call_num)?></span></a><?php } ?>
		</div>
	</div>
</section>

<section class="sp-sec">
	<div class="container sp-two">
		<div class="sp-text" data-reveal="left">
			<?php foreach($t['text'] as $p){ ?><p><?=o5_e($p)?></p><?php } ?>
		</div>
		<aside class="sp-card" data-reveal="right" style="--d:120ms">
			<h2 class="h3"><?=o5_te('What we supply')?></h2>
			<ul class="sp-check">
				<?php foreach($t['supply'] as $line){ ?><li><svg class="ic"><use href="#i-check"/></svg><span><?=o5_e($line)?></span></li><?php } ?>
			</ul>
		</aside>
	</div>
</section>

<?php if(count($page_products)){ ?>
<section class="sp-sec is-mist">
	<div class="container">
		<div class="section-head" data-reveal="up">
			<p class="eyebrow"><?=o5_te('Catalogue')?></p>
			<h2 class="h2"><?=$is_ar ? 'معدات نورّدها لـ'.o5_e($t['name']) : 'Equipment we supply for '.o5_e(mb_strtolower($t['name'], 'UTF-8'))?></h2>
		</div>
		<div class="pc-grid">
			<?php foreach($page_products as $i => $product) $pc($product, $i); ?>
		</div>
		<div class="chips sp-chips" data-reveal="up">
			<?php foreach($categories as $category){ if(!$cat_counts[$category['id_category']]) continue; ?>
			<a class="chip" href="<?=o5_e(o5_url('products/'.$category['slug']))?>"><?=o5_e($cat_names[$category['id_category']])?> <small><?=$cat_counts[$category['id_category']]?></small></a>
			<?php } ?>
		</div>
	</div>
</section>
<?php } ?>

<?php if(count($sector_projects)){ ?>
<section class="sp-sec projects">
	<div class="container">
		<div class="section-head" data-reveal="up">
			<p class="eyebrow"><?=o5_te('Our work')?></p>
			<h2 class="h2"><?=o5_te('Projects in this sector')?></h2>
		</div>
		<div class="proj-grid">
			<?php foreach($sector_projects as $i => $pr){
				$p_title = trim((string)($is_ar ? $pr['title_ar'] : $pr['title']));
				$p_place = trim((string)($is_ar ? $pr['place_ar'] : $pr['place']));
				$p_text = trim((string)($is_ar ? $pr['description_ar'] : $pr['description']));
				if($p_title === '') $p_title = $t['name'].($p_place !== '' ? ' · '.$p_place : '');
				$p_photos = array();
				foreach($pr['photo_list'] as $ph) $p_photos[] = $base.'uploads/projects/'.$ph;
				$cover = $pr['photo_list'][0];
				$cover_src = is_file('uploads/projects/thumb/'.$cover) ? 'uploads/projects/thumb/'.$cover : 'uploads/projects/'.$cover; ?>
			<a href="<?=o5_e($p_photos[0])?>" class="proj-card" data-project data-photos="<?=o5_e(json_encode($p_photos))?>" data-title="<?=o5_e($p_title)?>" data-cat="<?=o5_e($t['name'].($p_place !== '' ? ' · '.$p_place : ''))?>" data-text="<?=o5_e($p_text)?>" data-reveal="up" style="--d:<?=($i % 3) * 110?>ms">
				<span class="proj-img"><img src="<?=$base.o5_e($cover_src)?>" alt="<?=o5_e($p_title)?> — <?=o5_e($t['name'])?>" loading="lazy"></span>
				<span class="proj-body">
					<span class="proj-title"><?=o5_e($p_title)?></span>
					<?php if($p_place !== ''){ ?><span class="proj-place"><svg class="ic"><use href="#i-pin"/></svg><?=o5_e($p_place)?></span><?php } ?>
				</span>
			</a>
			<?php } ?>
		</div>
	</div>
</section>
<?php } ?>

<?php if(count($sector_clients)){ ?>
<section class="sp-sec">
	<div class="container">
		<div class="section-head" data-reveal="up">
			<p class="eyebrow"><?=o5_te('Our clients')?></p>
			<h2 class="h2"><?=o5_te('Some of our clients in this sector')?></h2>
		</div>
		<ul class="sp-clients" data-reveal="up">
			<?php foreach($sector_clients as $client_name){ ?><li><svg class="ic"><use href="#i-check"/></svg><?=o5_te($client_name)?></li><?php } ?>
		</ul>
	</div>
</section>
<?php } ?>

<?php include APPPATH.'views/option5/_why.php'; ?>

<section class="sp-sec faq">
	<div class="container sp-narrow">
		<div class="section-head" data-reveal="up">
			<p class="eyebrow"><?=o5_te('FAQ')?></p>
			<h2 class="h2"><?=o5_te('Frequently asked questions')?></h2>
		</div>
		<div class="faq-list" data-reveal="up">
			<?php foreach($t['faq'] as $i => $qa){ ?>
			<details class="faq-item" name="faq"<?=$i == 0 ? ' open' : ''?>>
				<summary><span class="faq-num"><?=sprintf('%02d', $i + 1)?></span><span class="faq-q"><?=o5_e($qa[0])?></span><span class="faq-toggle" aria-hidden="true"><svg class="ic"><use href="#i-plus"/></svg></span></summary>
				<div class="faq-a"><p><?=o5_e($qa[1])?></p></div>
			</details>
			<?php } ?>
		</div>
	</div>
</section>

<section class="sp-sec is-mist">
	<div class="container">
		<div class="section-head" data-reveal="up">
			<h2 class="h2"><?=o5_te('Other sectors we equip')?></h2>
		</div>
		<?php $sector_links(o5_slug(preg_replace('/-equipment$/', '', $sector['path']))); ?>
	</div>
</section>

<?php /* ===================================================================== Category ===================================================================== */
}elseif($page === 'category'){
	$ask_subject = ($is_ar ? 'استفسار عن: ' : 'Inquiry about: ').$category_page['name'];
?>
<section id="top" class="g-hero is-dark">
	<div class="glow glow-a" aria-hidden="true"></div>
	<div class="container">
		<nav class="crumbs" aria-label="<?=o5_te('You are here')?>" data-reveal="up">
			<a href="<?=o5_e($home_url)?>"><?=o5_te('Home')?></a><span aria-hidden="true">/</span><a href="<?=o5_e($home_url)?>#products"><?=o5_te('Products')?></a><span aria-hidden="true">/</span><b><?=o5_e($category_page['name'])?></b>
		</nav>
		<p class="eyebrow" data-reveal="up" style="--d:60ms"><?=count($page_products)?> <?=o5_te('products')?></p>
		<h1 class="g-title" data-reveal="up" style="--d:120ms"><?=o5_e($category_page['h1'])?></h1>
		<p class="lead" data-reveal="up" style="--d:200ms"><?=o5_e($category_page['text'])?></p>
		<div class="g-actions" data-reveal="up" style="--d:280ms">
			<a href="#contact" class="btn-solid btn-glow" data-magnetic><span><?=o5_te('Request a quote')?></span><svg class="ic"><use href="#i-arrow"/></svg></a>
			<?php if($call_num){ ?><a href="<?=o5_e($call_href)?>" class="btn-link btn-link-light"><svg class="ic"><use href="#i-phone"/></svg><span class="tel"><?=o5_e($call_num)?></span></a><?php } ?>
		</div>
	</div>
</section>

<section class="sp-sec">
	<div class="container">
		<div class="chips sp-chips is-top" data-reveal="up">
			<?php foreach($categories as $category){ if(!$cat_counts[$category['id_category']]) continue; ?>
			<a class="chip<?=$category['id_category'] == $category_page['id_category'] ? ' is-active' : ''?>" href="<?=o5_e(o5_url('products/'.$category['slug']))?>"<?=$category['id_category'] == $category_page['id_category'] ? ' aria-current="page"' : ''?>><?=o5_e($cat_names[$category['id_category']])?> <small><?=$cat_counts[$category['id_category']]?></small></a>
			<?php } ?>
		</div>
		<div class="pc-grid">
			<?php foreach($page_products as $i => $product) $pc($product, $i); ?>
		</div>
	</div>
</section>

<?php include APPPATH.'views/option5/_why.php'; ?>

<section class="sp-sec is-mist">
	<div class="container">
		<div class="section-head" data-reveal="up">
			<p class="eyebrow"><?=o5_te('What we equip')?></p>
			<h2 class="h2"><?=o5_te('Solutions by sector')?></h2>
		</div>
		<?php $sector_links(); ?>
	</div>
</section>

<?php /* ===================================================================== Product ===================================================================== */
}elseif($page === 'product'){
	$p = $product_page;
	$ask_subject = ($is_ar ? 'استفسار عن: ' : 'Inquiry about: ').$p['name'];
	$actions_message = ($is_ar ? 'مرحباً، أريد عرض سعر لـ: ' : 'Hello, I would like a quote for: ').$p['name'].' — '.$page_url;
	$points = array('Supplied, installed and serviced by our own team', 'Delivery and installation in every Lebanese region', 'Spare parts and after-sales maintenance');
	if(preg_match('/stainless|fridge|chiller|cold room|freezer|hood/i', $p['title'])) $points[] = 'Custom sizes made to order in our factory';
	$size = @getimagesize($p['src']);
?>
<section id="top" class="g-hero is-dark is-slim">
	<div class="glow glow-a" aria-hidden="true"></div>
	<div class="container">
		<nav class="crumbs" aria-label="<?=o5_te('You are here')?>">
			<a href="<?=o5_e($home_url)?>"><?=o5_te('Home')?></a><span aria-hidden="true">/</span><a href="<?=o5_e(o5_url('products/'.$p['cat_slug']))?>"><?=o5_e($p['cat_name'])?></a><span aria-hidden="true">/</span><b><?=o5_e($p['name'])?></b>
		</nav>
	</div>
</section>

<section class="sp-sec">
	<div class="container pp">
		<a class="pp-img" href="<?=$base.o5_e($p['src'])?>" data-gallery data-cat="<?=o5_e($p['cat_name'])?>" data-reveal="left" data-cursor="<?=o5_te('View')?>">
			<img src="<?=$base.o5_e($p['src'])?>" alt="<?=o5_e($p['name'].' - '.$p['cat_name'].($is_ar ? ' | مؤسسة طرابلسي - لبنان' : ' | Traboulsi Est. Lebanon'))?>" fetchpriority="high"<?php if($size){ ?> width="<?=$size[0]?>" height="<?=$size[1]?>"<?php } ?>>
		</a>
		<div class="pp-text" data-reveal="right" style="--d:120ms">
			<a class="p-cat" href="<?=o5_e(o5_url('products/'.$p['cat_slug']))?>"><?=o5_e($p['cat_name'])?></a>
			<h1 class="pp-name"><?=o5_e($p['name'])?></h1>
			<?php if($p['text'] !== ''){ ?><p class="lead"><?=o5_e($p['text'])?></p><?php } ?>
			<ul class="sp-check">
				<?php foreach($points as $point){ ?><li><svg class="ic"><use href="#i-check"/></svg><span><?=o5_te($point)?></span></li><?php } ?>
			</ul>
			<div class="pp-actions">
				<a href="#contact" class="btn-solid btn-glow" data-magnetic><span><?=o5_te('Ask for a quote')?></span><svg class="ic"><use href="#i-arrow"/></svg></a>
				<?php if($contact['whatsapp']){ ?><a href="<?=o5_e($wa_link($actions_message))?>" target="_blank" rel="noopener" class="btn-more pp-wa"><span><?=o5_te('WhatsApp')?></span><svg class="ic"><use href="#i-whatsapp"/></svg></a><?php } ?>
			</div>
			<?php if(count($product_sectors)){ ?>
			<p class="pp-fits"><span><?=o5_te('Suitable for')?></span>
				<?php foreach($product_sectors as $s){ ?><a href="<?=o5_e(o5_url($s['path']))?>"><svg class="ic"><use href="#i-<?=$s['icon']?>"/></svg><?=o5_e($s[$L]['name'])?></a><?php } ?>
			</p>
			<?php } ?>
		</div>
	</div>
</section>

<?php if(count($page_products)){ ?>
<section class="sp-sec is-mist">
	<div class="container">
		<div class="section-head" data-reveal="up">
			<p class="eyebrow"><?=o5_e($p['cat_name'])?></p>
			<h2 class="h2"><?=o5_te('Similar products')?></h2>
		</div>
		<div class="pc-grid">
			<?php foreach($page_products as $i => $product) $pc($product, $i); ?>
		</div>
		<p class="sp-more" data-reveal="up"><a class="btn-more" href="<?=o5_e(o5_url('products/'.$p['cat_slug']))?>"><span><?=o5_e($p['cat_name'])?></span><svg class="ic"><use href="#i-plus"/></svg></a></p>
	</div>
</section>
<?php } ?>

<?php include APPPATH.'views/option5/_why.php'; ?>

<?php /* ===================================================================== Delivery & export ===================================================================== */
}elseif($page === 'export'){
	$t = $export_page;
	$ask_subject = $t['name'];
?>
<section id="top" class="g-hero is-dark">
	<div class="glow glow-a" aria-hidden="true"></div>
	<div class="container">
		<nav class="crumbs" aria-label="<?=o5_te('You are here')?>" data-reveal="up">
			<a href="<?=o5_e($home_url)?>"><?=o5_te('Home')?></a><span aria-hidden="true">/</span><b><?=o5_e($t['name'])?></b>
		</nav>
		<p class="eyebrow" data-reveal="up" style="--d:60ms"><svg class="ic"><use href="#i-truck"/></svg> <?=o5_e($t['name'])?></p>
		<h1 class="g-title" data-reveal="up" style="--d:120ms"><?=o5_e($t['h1'])?></h1>
		<p class="lead" data-reveal="up" style="--d:200ms"><?=o5_e($t['lead'])?></p>
		<div class="g-actions" data-reveal="up" style="--d:280ms">
			<a href="#contact" class="btn-solid btn-glow" data-magnetic><span><?=o5_te('Request a quote')?></span><svg class="ic"><use href="#i-arrow"/></svg></a>
			<?php if($contact['whatsapp']){ ?><a href="https://wa.me/<?=$contact['whatsapp']?>" target="_blank" rel="noopener" class="btn-link btn-link-light"><svg class="ic"><use href="#i-whatsapp"/></svg><?=o5_te('WhatsApp')?></a><?php } ?>
		</div>
	</div>
</section>

<section class="sp-sec">
	<div class="container sp-two">
		<div class="sp-text" data-reveal="left">
			<?php foreach($t['text'] as $p){ ?><p><?=o5_e($p)?></p><?php } ?>
		</div>
		<aside class="sp-card" data-reveal="right" style="--d:120ms">
			<h2 class="h3"><?=o5_e($t['regions_title'])?></h2>
			<ul class="sp-check">
				<?php foreach($t['regions'] as $line){ ?><li><svg class="ic"><use href="#i-pin"/></svg><span><?=o5_e($line)?></span></li><?php } ?>
			</ul>
		</aside>
	</div>
</section>

<section class="sp-sec is-mist">
	<div class="container">
		<div class="section-head" data-reveal="up"><h2 class="h2"><?=o5_e($t['steps_title'])?></h2></div>
		<ol class="sp-steps">
			<?php foreach($t['steps'] as $i => $step){ ?>
			<li data-reveal="up" style="--d:<?=$i * 100?>ms"><b><?=sprintf('%02d', $i + 1)?></b><h3><?=o5_e($step[0])?></h3><p><?=o5_e($step[1])?></p></li>
			<?php } ?>
		</ol>
	</div>
</section>

<?php include APPPATH.'views/option5/_why.php'; ?>

<section class="sp-sec faq">
	<div class="container sp-narrow">
		<div class="section-head" data-reveal="up"><p class="eyebrow"><?=o5_te('FAQ')?></p><h2 class="h2"><?=o5_te('Frequently asked questions')?></h2></div>
		<div class="faq-list" data-reveal="up">
			<?php foreach($t['faq'] as $i => $qa){ ?>
			<details class="faq-item" name="faq"<?=$i == 0 ? ' open' : ''?>>
				<summary><span class="faq-num"><?=sprintf('%02d', $i + 1)?></span><span class="faq-q"><?=o5_e($qa[0])?></span><span class="faq-toggle" aria-hidden="true"><svg class="ic"><use href="#i-plus"/></svg></span></summary>
				<div class="faq-a"><p><?=o5_e($qa[1])?></p></div>
			</details>
			<?php } ?>
		</div>
	</div>
</section>

<?php /* ===================================================================== Guides: the list ===================================================================== */
}elseif($page === 'guides'){
	$ask_subject = '';
?>
<section id="top" class="g-hero is-dark">
	<div class="glow glow-a" aria-hidden="true"></div>
	<div class="container">
		<nav class="crumbs" aria-label="<?=o5_te('You are here')?>" data-reveal="up">
			<a href="<?=o5_e($home_url)?>"><?=o5_te('Home')?></a><span aria-hidden="true">/</span><b><?=o5_te('Guides')?></b>
		</nav>
		<p class="eyebrow" data-reveal="up" style="--d:60ms"><?=o5_te('Guides')?></p>
		<h1 class="g-title" data-reveal="up" style="--d:120ms"><?=o5_te('Before you buy: our guides')?></h1>
		<p class="lead" data-reveal="up" style="--d:200ms"><?=o5_te('What four decades of equipping kitchens and shops taught us, written for people who are about to open theirs.')?></p>
	</div>
</section>

<section class="sp-sec">
	<div class="container">
		<div class="guide-list">
			<?php $i = 0; foreach($guides as $slug => $g){ ?>
			<a class="guide-card" href="<?=o5_e(o5_url('guides/'.$slug))?>" data-reveal="up" style="--d:<?=($i++ % 2) * 110?>ms">
				<span class="guide-num" aria-hidden="true"><?=sprintf('%02d', $i)?></span>
				<h2><?=o5_e($g[$L]['h1'])?></h2>
				<p><?=o5_e($g[$L]['description'])?></p>
				<span class="btn-link"><?=o5_te('Read the guide')?><svg class="ic"><use href="#i-arrow"/></svg></span>
			</a>
			<?php } ?>
		</div>
	</div>
</section>

<section class="sp-sec is-mist">
	<div class="container">
		<div class="section-head" data-reveal="up"><p class="eyebrow"><?=o5_te('What we equip')?></p><h2 class="h2"><?=o5_te('Solutions by sector')?></h2></div>
		<?php $sector_links(); ?>
	</div>
</section>

<?php /* ===================================================================== Guide ===================================================================== */
}elseif($page === 'guide'){
	$t = $guide[$L];
	$ask_subject = ($is_ar ? 'سؤال بعد قراءة: ' : 'Question after reading: ').$t['h1'];
	$guide_sector = isset($sectors[$guide['sector']]) ? $sectors[$guide['sector']] : NULL;
?>
<section id="top" class="g-hero is-dark">
	<div class="glow glow-a" aria-hidden="true"></div>
	<div class="container sp-narrow">
		<nav class="crumbs" aria-label="<?=o5_te('You are here')?>" data-reveal="up">
			<a href="<?=o5_e($home_url)?>"><?=o5_te('Home')?></a><span aria-hidden="true">/</span><a href="<?=o5_e(o5_url('guides'))?>"><?=o5_te('Guides')?></a>
		</nav>
		<h1 class="g-title is-article" data-reveal="up" style="--d:100ms"><?=o5_e($t['h1'])?></h1>
		<p class="lead" data-reveal="up" style="--d:180ms"><?=o5_e($t['lead'])?></p>
	</div>
</section>

<article class="sp-sec">
	<div class="container sp-narrow guide">
		<?php foreach($t['sections'] as $section){ ?>
		<h2><?=o5_e($section[0])?></h2>
		<?php foreach($section[1] as $p){ ?><p><?=o5_e($p)?></p><?php } ?>
		<?php if(count($section[2])){ ?><ul class="sp-check"><?php foreach($section[2] as $line){ ?><li><svg class="ic"><use href="#i-check"/></svg><span><?=o5_e($line)?></span></li><?php } ?></ul><?php } ?>
		<?php } ?>
		<div class="guide-end">
			<a href="#contact" class="btn-solid btn-glow" data-magnetic><span><?=o5_te('Request a quote')?></span><svg class="ic"><use href="#i-arrow"/></svg></a>
			<?php if($guide_sector){ ?><a class="btn-link" href="<?=o5_e(o5_url($guide_sector['path']))?>"><?=o5_e($guide_sector[$L]['h1'])?><svg class="ic"><use href="#i-arrow"/></svg></a><?php } ?>
		</div>
	</div>
</article>

<?php if(count($guides) > 1){ ?>
<section class="sp-sec is-mist">
	<div class="container">
		<div class="section-head" data-reveal="up"><h2 class="h2"><?=o5_te('More guides')?></h2></div>
		<div class="guide-list">
			<?php foreach($guides as $slug => $g){ if($slug === $guide_slug) continue; ?>
			<a class="guide-card" href="<?=o5_e(o5_url('guides/'.$slug))?>" data-reveal="up">
				<h2><?=o5_e($g[$L]['h1'])?></h2>
				<p><?=o5_e($g[$L]['description'])?></p>
				<span class="btn-link"><?=o5_te('Read the guide')?><svg class="ic"><use href="#i-arrow"/></svg></span>
			</a>
			<?php } ?>
		</div>
	</div>
</section>
<?php } ?>

<?php include APPPATH.'views/option5/_why.php'; ?>
<?php } ?>
