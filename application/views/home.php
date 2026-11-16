<?php
if(!function_exists('tb_e')){
	function tb_e($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8', false); }
	function tb_title($s){ return ucwords(strtolower(preg_replace('/\s+/', ' ', trim(html_entity_decode($s, ENT_QUOTES, 'UTF-8'))))); }
	// pick an icon from the words in a category / client group title
	function tb_icon($title, $map, $default){
		$t = strtolower($title);
		foreach($map as $needle => $icon){ if(preg_match('/'.$needle.'/', $t)) return $icon; }
		return $default;
	}
	// hero title from the back office: [words] get the accent colour, each line breaks on large screens
	function tb_hero_title($s){
		$s = preg_replace('/\[(.+?)\]/s', '<span class="accent">$1</span>', tb_e(trim($s)));
		return preg_replace('/\s*\R\s*/u', ' <br class="br-lg">', $s);
	}
	// a back office text inside its tag, or nothing when the text was left empty
	function tb_tag($open, $text, $close){ return trim($text) === '' ? '' : $open.tb_e($text).$close; }
	// a key figure typed in the back office, or the automatic count when left empty
	function tb_num($typed, $auto){ $n = preg_replace('/\D/', '', $typed); return $n === '' ? $auto : (int)$n; }
}
$hp = $home_page; // texts from back_office/home_page
$cat_icons = array('kitchen' => 'chef', 'pastr|baker' => 'cake', 'juice|coffee' => 'coffee', 'ice' => 'icecream', 'supermarket' => 'cart', 'stain|stanil|steel' => 'layers', 'fridge|refrig' => 'fridge');
$client_icons = array('hospital' => 'medical', 'restaurant' => 'utensils', 'pastr|baker|sweet' => 'cake', 'supermarket' => 'cart');

$cat_names = array();
foreach($categories as $category) $cat_names[$category['id_category']] = tb_title($category['title']);

$founded = (int)$hp['founded_year'];
$years = $founded ? date('Y') - $founded : 0;
$product_total = count($products);
$unique_clients = array();
foreach($client_groups as $groups){
	foreach($groups as $group){
		foreach($group['names'] as $client_name) $unique_clients[strtolower($client_name)] = $client_name;
	}
}
$stat_products = tb_num($hp['stat_products_number'], $product_total);
$stat_sectors = tb_num($hp['stat_sectors_number'], count($categories));
$stat_clients = tb_num($hp['stat_clients_number'], count($unique_clients));
$features = array(1 => 'factory', 2 => 'layers', 3 => 'package', 4 => 'wrench');

$hero_main = count($hero_images) ? $hero_images[count($hero_images) - 1]['src'] : '';
$storefront = is_file('uploads/about_us/530x400/tra.jpg') ? 'uploads/about_us/530x400/tra.jpg' : '';

$map_src = 'https://www.google.com/maps?q=Deir+El+Zahrani,+Nabatieh,+Lebanon&z=14&output=embed';
if(!empty($generalinfo['google_map'])){
	$gm = trim($generalinfo['google_map']);
	if(preg_match('#src="([^"]+)"#', $gm, $m)) $map_src = html_entity_decode($m[1]);
	elseif(preg_match('#^https?://#', $gm)) $map_src = $gm;
}

$form = array();
foreach(array('name', 'email', 'telephone', 'company', 'subject', 'message') as $field){
	$form[$field] = ($valid == 'true') ? '' : set_value($field);
}
?>
<svg xmlns="http://www.w3.org/2000/svg" style="display:none" aria-hidden="true">
	<symbol id="i-phone" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></symbol>
	<symbol id="i-mail" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></symbol>
	<symbol id="i-pin" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></symbol>
	<symbol id="i-fax" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></symbol>
	<symbol id="i-facebook" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></symbol>
	<symbol id="i-instagram" viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="20" rx="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></symbol>
	<symbol id="i-arrow" viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></symbol>
	<symbol id="i-up" viewBox="0 0 24 24"><line x1="12" y1="19" x2="12" y2="5"/><polyline points="5 12 12 5 19 12"/></symbol>
	<symbol id="i-left" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></symbol>
	<symbol id="i-right" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></symbol>
	<symbol id="i-x" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></symbol>
	<symbol id="i-search" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></symbol>
	<symbol id="i-expand" viewBox="0 0 24 24"><polyline points="15 3 21 3 21 9"/><polyline points="9 21 3 21 3 15"/><line x1="21" y1="3" x2="14" y2="10"/><line x1="3" y1="21" x2="10" y2="14"/></symbol>
	<symbol id="i-send" viewBox="0 0 24 24"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></symbol>
	<symbol id="i-check" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></symbol>
	<symbol id="i-award" viewBox="0 0 24 24"><circle cx="12" cy="8" r="7"/><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"/></symbol>
	<symbol id="i-grid" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/></symbol>
	<symbol id="i-users" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></symbol>
	<symbol id="i-fridge" viewBox="0 0 24 24"><rect x="5" y="2" width="14" height="20" rx="2"/><line x1="5" y1="10" x2="19" y2="10"/><line x1="8.5" y1="5" x2="8.5" y2="7"/><line x1="8.5" y1="13" x2="8.5" y2="16"/></symbol>
	<symbol id="i-chef" viewBox="0 0 24 24"><path d="M6 13.87A4 4 0 0 1 7.41 6a5.11 5.11 0 0 1 1.05-1.54 5 5 0 0 1 7.08 0A5.11 5.11 0 0 1 16.59 6 4 4 0 0 1 18 13.87V21H6Z"/><line x1="6" y1="17" x2="18" y2="17"/></symbol>
	<symbol id="i-cake" viewBox="0 0 24 24"><path d="M20 21v-8a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v8"/><path d="M4 16s.5-1 2-1 2.5 2 4 2 2.5-2 4-2 2.5 2 4 2 2-1 2-1"/><path d="M2 21h20"/><path d="M7 8v3"/><path d="M12 8v3"/><path d="M17 8v3"/><path d="M7 4h.01"/><path d="M12 4h.01"/><path d="M17 4h.01"/></symbol>
	<symbol id="i-coffee" viewBox="0 0 24 24"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/></symbol>
	<symbol id="i-icecream" viewBox="0 0 24 24"><path d="m7 11 4.08 10.35a1 1 0 0 0 1.84 0L17 11"/><path d="M17 7A5 5 0 0 0 7 7"/><path d="M17 7a2 2 0 0 1 0 4H7a2 2 0 0 1 0-4"/></symbol>
	<symbol id="i-cart" viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></symbol>
	<symbol id="i-layers" viewBox="0 0 24 24"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/></symbol>
	<symbol id="i-medical" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="3"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></symbol>
	<symbol id="i-utensils" viewBox="0 0 24 24"><path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"/><path d="M7 2v20"/><path d="M21 15V2a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"/></symbol>
	<symbol id="i-building" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></symbol>
	<symbol id="i-factory" viewBox="0 0 24 24"><path d="M2 20a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8l-7 5V8l-7 5V4a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"/><path d="M17 18h1"/><path d="M12 18h1"/><path d="M7 18h1"/></symbol>
	<symbol id="i-package" viewBox="0 0 24 24"><line x1="16.5" y1="9.4" x2="7.5" y2="4.21"/><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></symbol>
	<symbol id="i-wrench" viewBox="0 0 24 24"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></symbol>
	<symbol id="i-whatsapp" viewBox="0 0 24 24"><path fill="currentColor" stroke="none" d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z"/></symbol>
</svg>

<a class="skip-link" href="#main">Skip to content</a>

<div class="topbar">
	<div class="container topbar-inner">
		<div class="topbar-info">
			<?php if(count($locations)){ ?><span><svg class="ic"><use href="#i-pin"/></svg><?=tb_e(implode(' · ', $locations))?></span><?php } ?>
			<?php if($contact['phone']){ ?><a href="<?=tb_e($contact['phone_href'])?>"><svg class="ic"><use href="#i-phone"/></svg><span class="tel"><?=tb_e($contact['phone'])?></span></a><?php } ?>
			<?php if($contact['email']){ ?><a href="mailto:<?=tb_e($contact['email'])?>"><svg class="ic"><use href="#i-mail"/></svg><?=tb_e($contact['email'])?></a><?php } ?>
		</div>
		<div class="topbar-side">
			<?=tb_tag('<span class="ar" lang="ar" dir="rtl">', $hp['topbar_name_ar'], '</span>')?>
			<?php if(!empty($contact_us['facebook'])){ ?><a href="<?=tb_e($contact_us['facebook'])?>" target="_blank" rel="noopener" aria-label="Facebook"><svg class="ic"><use href="#i-facebook"/></svg></a><?php } ?>
			<?php if(!empty($contact_us['instagram'])){ ?><a href="<?=tb_e($contact_us['instagram'])?>" target="_blank" rel="noopener" aria-label="Instagram"><svg class="ic"><use href="#i-instagram"/></svg></a><?php } ?>
		</div>
	</div>
</div>

<header class="site-header" id="siteHeader">
	<div class="container header-inner">
		<a href="#home" class="brand" aria-label="Traboulsi Est. — home">
			<img src="<?=base_url()?>images/logo.png" width="300" height="54" alt="Traboulsi Est. for Trading &amp; Industry — Since 1982">
		</a>
		<nav class="main-nav" id="mainNav" aria-label="Main">
			<a href="#home" class="is-active">Home</a>
			<a href="#about">About</a>
			<a href="#solutions">Solutions</a>
			<a href="#portfolio">Products</a>
			<a href="#team">Clients</a>
			<a href="#contact">Contact</a>
			<a href="#contact" class="btn btn-primary nav-cta-mobile">Request a quote</a>
		</nav>
		<a href="#contact" class="btn btn-primary header-cta">Request a quote</a>
		<button class="nav-toggle" id="navToggle" type="button" aria-controls="mainNav" aria-expanded="false" aria-label="Open menu"><span></span></button>
	</div>
</header>

<main id="main">

<section id="home" class="hero">
	<div class="hero-media">
		<?php foreach($hero_images as $i => $image){ ?>
		<img class="hero-slide<?=$i == 0 ? ' is-active' : ''?>" src="<?=base_url().tb_e($image['src'])?>" alt="<?=tb_e($image['title'])?> — showroom" <?=$i == 0 ? 'fetchpriority="high"' : 'loading="lazy"'?>>
		<?php } ?>
	</div>
	<div class="hero-overlay"></div>
	<div class="container hero-content">
		<?=tb_tag('<p class="eyebrow eyebrow-light"><span class="pulse-dot"></span>', $hp['hero_eyebrow'], '</p>')?>
		<?php if(trim($hp['hero_title']) !== ''){ ?><h1><?=tb_hero_title($hp['hero_title'])?></h1><?php } ?>
		<?=tb_tag('<p class="hero-lead">', $hp['hero_text'], '</p>')?>
		<?=tb_tag('<p class="hero-ar" lang="ar" dir="rtl">', $hp['hero_text_ar'], '</p>')?>
		<div class="hero-actions">
			<?=tb_tag('<a href="#portfolio" class="btn btn-primary btn-lg">', $hp['hero_button_1'], ' <svg class="ic"><use href="#i-arrow"/></svg></a>')?>
			<?=tb_tag('<a href="#contact" class="btn btn-ghost btn-lg">', $hp['hero_button_2'], '</a>')?>
		</div>
	</div>
	<?php if(count($hero_images) > 1){ ?>
	<div class="container hero-dots" aria-label="Choose slide">
		<?php foreach($hero_images as $i => $image){ ?>
		<button type="button" class="hero-dot<?=$i == 0 ? ' is-active' : ''?>" aria-label="Show slide <?=$i + 1?>"><span></span></button>
		<?php } ?>
	</div>
	<?php } ?>
</section>

<section class="stats" aria-label="Key figures">
	<div class="container">
		<div class="stats-grid">
			<?php if($years){ ?>
			<div class="stat">
				<span class="stat-icon"><svg class="ic"><use href="#i-award"/></svg></span>
				<div><strong><span data-count="<?=$years?>"><?=$years?></span><em>+</em></strong><?=tb_tag('<p>', $hp['stat_years_label'], '</p>')?></div>
			</div>
			<?php } ?>
			<div class="stat">
				<span class="stat-icon"><svg class="ic"><use href="#i-fridge"/></svg></span>
				<div><strong><span data-count="<?=$stat_products?>"><?=$stat_products?></span><em>+</em></strong><?=tb_tag('<p>', $hp['stat_products_label'], '</p>')?></div>
			</div>
			<div class="stat">
				<span class="stat-icon"><svg class="ic"><use href="#i-grid"/></svg></span>
				<div><strong><span data-count="<?=$stat_sectors?>"><?=$stat_sectors?></span></strong><?=tb_tag('<p>', $hp['stat_sectors_label'], '</p>')?></div>
			</div>
			<div class="stat">
				<span class="stat-icon"><svg class="ic"><use href="#i-users"/></svg></span>
				<div><strong><span data-count="<?=$stat_clients?>"><?=$stat_clients?></span><em>+</em></strong><?=tb_tag('<p>', $hp['stat_clients_label'], '</p>')?></div>
			</div>
		</div>
	</div>
</section>

<section id="solutions" class="section">
	<div class="container">
		<div class="section-head" data-reveal>
			<?=tb_tag('<p class="eyebrow">', $hp['solutions_eyebrow'], '</p>')?>
			<?=tb_tag('<h2>', $hp['solutions_title'], '</h2>')?>
			<?=tb_tag('<p class="section-lead">', $hp['solutions_text'], '</p>')?>
		</div>
		<div class="sector-grid">
			<?php foreach($categories as $i => $category){
				$count = $cat_counts[$category['id_category']]; ?>
			<a class="sector-card" data-reveal style="--d:<?=($i % 3) * 80?>ms"
				<?php if($count){ ?>href="#portfolio" data-filter-link="prod<?=$category['id_category']?>"<?php }else{ ?>href="#contact"<?php } ?>>
				<span class="sector-num"><?=str_pad($i + 1, 2, '0', STR_PAD_LEFT)?></span>
				<span class="sector-icon"><svg class="ic"><use href="#i-<?=tb_icon($category['title'], $cat_icons, 'package')?>"/></svg></span>
				<h3><?=tb_e($cat_names[$category['id_category']])?></h3>
				<p><?=$count ? $count.' products in the catalogue' : 'Made to order — ask us for details'?></p>
				<span class="sector-link"><?=$count ? 'View products' : 'Ask for a quote'?> <svg class="ic"><use href="#i-arrow"/></svg></span>
			</a>
			<?php } ?>
		</div>
	</div>
</section>

<section id="about" class="section section-alt">
	<div class="container about-grid">
		<div class="about-media" data-reveal>
			<?php if($hero_main){ ?>
			<figure class="about-img-main"><img src="<?=base_url().tb_e($hero_main)?>" alt="Traboulsi Est. showroom with commercial kitchen equipment" loading="lazy"></figure>
			<?php } ?>
			<?php if($storefront){ ?>
			<figure class="about-img-sub"><img src="<?=base_url().$storefront?>" width="530" height="400" alt="Traboulsi Est. building in Deir El Zahrani" loading="lazy"></figure>
			<?php } ?>
			<?php if($founded){ ?><div class="about-badge"><strong><?=$founded?></strong><?=tb_tag('<span>', $hp['about_badge_label'], '</span>')?></div><?php } ?>
		</div>
		<div class="about-body" data-reveal style="--d:120ms">
			<p class="eyebrow"><?=tb_e($about_us['title'])?></p>
			<?=tb_tag('<h2>', $hp['about_title'], '</h2>')?>
			<div class="about-text is-clamped" id="aboutText"><?=$about_us['description']?></div>
			<button type="button" class="link-btn" data-toggle-about aria-expanded="false" aria-controls="aboutText"><span>Read more</span> <svg class="ic"><use href="#i-arrow"/></svg></button>
			<ul class="feature-list">
				<?php foreach($features as $n => $icon){
					if(trim($hp['feature_'.$n.'_title']) === '') continue; ?>
				<li><span class="feature-icon"><svg class="ic"><use href="#i-<?=$icon?>"/></svg></span><div><strong><?=tb_e($hp['feature_'.$n.'_title'])?></strong><?=tb_tag('<span>', $hp['feature_'.$n.'_text'], '</span>')?></div></li>
				<?php } ?>
			</ul>
		</div>
	</div>
</section>

<section id="portfolio" class="section">
	<div class="container">
		<div class="section-head section-head-split" data-reveal>
			<div>
				<?=tb_tag('<p class="eyebrow">', $hp['products_eyebrow'], '</p>')?>
				<?=tb_tag('<h2>', $hp['products_title'], '</h2>')?>
			</div>
			<label class="search">
				<svg class="ic"><use href="#i-search"/></svg>
				<input type="search" id="productSearch" placeholder="Search products…" aria-label="Search products" autocomplete="off">
			</label>
		</div>

		<div class="filters" role="toolbar" aria-label="Filter products by category">
			<button type="button" class="chip is-active" data-filter="*" aria-pressed="true">All products <span class="chip-count"><?=$product_total?></span></button>
			<?php foreach($categories as $category){
				if(!$cat_counts[$category['id_category']]) continue; ?>
			<button type="button" class="chip" data-filter="prod<?=$category['id_category']?>" aria-pressed="false"><?=tb_e($cat_names[$category['id_category']])?> <span class="chip-count"><?=$cat_counts[$category['id_category']]?></span></button>
			<?php } ?>
		</div>

		<div class="product-grid" id="productGrid">
			<?php foreach($products as $product){
				$cat = isset($cat_names[$product['id_category']]) ? $cat_names[$product['id_category']] : ''; ?>
			<article class="product-card" data-cat="prod<?=$product['id_category']?>" data-name="<?=tb_e($product['title'])?>" data-search="<?=tb_e(strtolower($product['title'].' '.$cat))?>">
				<a href="<?=base_url()?>uploads/products/600x455/<?=tb_e($product['image'])?>" class="product-media" aria-label="View <?=tb_e($product['title'])?>">
					<img src="<?=base_url()?>uploads/products/290x220/<?=tb_e($product['image'])?>" width="290" height="220" loading="lazy" alt="<?=tb_e($product['alt'])?>">
					<span class="product-zoom"><svg class="ic"><use href="#i-expand"/></svg></span>
				</a>
				<div class="product-body">
					<span class="product-cat"><?=tb_e($cat)?></span>
					<h3 class="product-title"><?=tb_e($product['title'])?></h3>
				</div>
			</article>
			<?php } ?>
		</div>

		<p class="empty-state" id="productsEmpty" hidden>No products match your search. <a href="#contact">Ask us directly</a> — we may have it in stock or make it to order.</p>

		<div class="load-more" id="loadMoreWrap">
			<p class="load-more-text">Showing <span id="shownCount"><?=$product_total?></span> of <span id="totalCount"><?=$product_total?></span> products</p>
			<div class="progress"><span id="productsBar"></span></div>
			<button type="button" class="btn btn-outline" id="loadMore" hidden>Show more products</button>
		</div>
	</div>
</section>

<section id="team" class="section section-dark">
	<div class="container">
		<div class="section-head" data-reveal>
			<?=tb_tag('<p class="eyebrow eyebrow-light">', $hp['clients_eyebrow'], '</p>')?>
			<?=tb_tag('<h2>', $hp['clients_title'], '</h2>')?>
			<?=tb_tag('<p class="section-lead">', $hp['clients_text'], '</p>')?>
		</div>
	</div>

	<?php if(count($unique_clients)){ ?>
	<div class="marquee" aria-hidden="true">
		<div class="marquee-track">
			<?php for($copy = 0; $copy < 2; $copy++){ foreach($unique_clients as $client_name){ ?>
			<span><?=tb_e($client_name)?></span>
			<?php } } ?>
		</div>
	</div>
	<?php } ?>

	<div class="container">
		<?php if(!count($client_groups)){ ?>
		<p class="muted-light center">Coming soon.</p>
		<?php } ?>
		<?php foreach($client_groups as $country => $groups){ ?>
		<div class="clients" data-tabs data-reveal>
			<p class="country-tag"><svg class="ic"><use href="#i-pin"/></svg><?=tb_e($country)?></p>
			<div class="clients-layout">
				<div class="client-tabs" role="tablist" aria-label="Client sectors in <?=tb_e($country)?>">
					<?php foreach($groups as $i => $group){
						$tab_id = 'ct-'.$group['id_our_clients']; ?>
					<button type="button" role="tab" id="<?=$tab_id?>" aria-controls="<?=$tab_id?>-panel" aria-selected="<?=$i == 0 ? 'true' : 'false'?>" tabindex="<?=$i == 0 ? '0' : '-1'?>" class="client-tab<?=$i == 0 ? ' is-active' : ''?>">
						<span class="client-icon"><svg class="ic"><use href="#i-<?=tb_icon($group['title'], $client_icons, 'building')?>"/></svg></span>
						<span class="client-tab-text"><strong><?=tb_e($group['title'])?></strong><?php if(count($group['names'])){ ?><small><?=count($group['names'])?> clients</small><?php } ?></span>
						<svg class="ic client-tab-arrow"><use href="#i-right"/></svg>
					</button>
					<?php } ?>
				</div>
				<?php foreach($groups as $i => $group){
					$tab_id = 'ct-'.$group['id_our_clients']; ?>
				<div class="client-panel<?=$i == 0 ? ' is-active' : ''?>" role="tabpanel" id="<?=$tab_id?>-panel" aria-labelledby="<?=$tab_id?>">
					<h3><?=tb_e($group['title'])?></h3>
					<?php if(count($group['names'])){ ?>
					<ul class="client-names">
						<?php foreach($group['names'] as $client_name){ ?>
						<li><svg class="ic"><use href="#i-check"/></svg><?=tb_e($client_name)?></li>
						<?php } ?>
					</ul>
					<?php }else{ ?>
					<div class="client-raw"><?=$group['description']?></div>
					<?php } ?>
				</div>
				<?php } ?>
			</div>
		</div>
		<?php } ?>
	</div>
</section>

<section class="cta-band">
	<div class="container">
		<div class="cta-inner" data-reveal>
			<div>
				<?=tb_tag('<h2>', $hp['cta_title'], '</h2>')?>
				<?=tb_tag('<p>', $hp['cta_text'], '</p>')?>
			</div>
			<div class="cta-actions">
				<?=tb_tag('<a href="#contact" class="btn btn-light btn-lg">', $hp['cta_button'], ' <svg class="ic"><use href="#i-arrow"/></svg></a>')?>
				<?php if($contact['mobile'] || $contact['phone']){
					$cta_href = $contact['mobile'] ? $contact['mobile_href'] : $contact['phone_href'];
					$cta_num = $contact['mobile'] ? $contact['mobile'] : $contact['phone']; ?>
				<a href="<?=tb_e($cta_href)?>" class="btn btn-ghost btn-lg"><svg class="ic"><use href="#i-phone"/></svg><span class="tel"><?=tb_e($cta_num)?></span></a>
				<?php } ?>
			</div>
		</div>
	</div>
</section>

<?php $this->load->view('include/_seo_section', array('wrap' => 'container')); // Arabic services + FAQ (Google) ?>

<section id="contact" class="section section-alt">
	<div class="container">
		<div class="section-head" data-reveal>
			<?=tb_tag('<p class="eyebrow">', $hp['contact_eyebrow'], '</p>')?>
			<?=tb_tag('<h2>', $hp['contact_title'], '</h2>')?>
			<?=tb_tag('<p class="section-lead">', $hp['contact_text'], '</p>')?>
		</div>

		<div class="contact-grid">
			<aside class="contact-card" data-reveal>
				<h3><?=tb_e($contact_us['title'])?></h3>
				<?=tb_tag('<p class="contact-company">', $hp['contact_company'], '</p>')?>
				<?php if(count($contact_items)){ ?>
				<ul class="contact-list">
					<?php foreach($contact_items as $item){
						$label = strtolower($item['label']);
						$icon = $item['type'] == 'mailto' ? 'mail' : (strpos($label, 'fax') !== false ? 'fax' : 'phone'); ?>
					<li>
						<span class="contact-icon"><svg class="ic"><use href="#i-<?=$icon?>"/></svg></span>
						<div><span class="contact-label"><?=tb_e($item['label'])?></span><a href="<?=tb_e($item['href'])?>"<?=$item['type'] == 'tel' ? ' class="tel"' : ''?>><?=tb_e($item['value'])?></a></div>
					</li>
					<?php } ?>
					<?php foreach($locations as $location){ ?>
					<li>
						<span class="contact-icon"><svg class="ic"><use href="#i-pin"/></svg></span>
						<div><span class="contact-label">Branch</span><span><?=tb_e($location)?></span><?php $role = branch_role($location); if($role !== ''){ ?><span class="branch-role"><?=tb_e($role)?></span><?php } ?></div>
					</li>
					<?php } ?>
				</ul>
				<?php }else{ ?>
				<div class="contact-raw"><?=$contact_us['description']?></div>
				<?php } ?>
				<div class="contact-social">
					<?php if(!empty($contact_us['facebook'])){ ?><a href="<?=tb_e($contact_us['facebook'])?>" target="_blank" rel="noopener" class="social-btn"><svg class="ic"><use href="#i-facebook"/></svg>Facebook</a><?php } ?>
					<?php if(!empty($contact_us['instagram'])){ ?><a href="<?=tb_e($contact_us['instagram'])?>" target="_blank" rel="noopener" class="social-btn social-ig"><svg class="ic"><use href="#i-instagram"/></svg>Instagram</a><?php } ?>
					<?php if($contact['whatsapp']){ ?><a href="https://wa.me/<?=$contact['whatsapp']?>" target="_blank" rel="noopener" class="social-btn social-wa"><svg class="ic"><use href="#i-whatsapp"/></svg>WhatsApp</a><?php } ?>
				</div>
				<div class="contact-map">
					<iframe src="<?=tb_e($map_src)?>" title="Map — Deir El Zahrani, Lebanon" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
				</div>
			</aside>

			<form id="main-contact-form" class="contact-form" name="contact-form" method="post" action="<?=base_url()?>#contact" data-reveal style="--d:120ms">
				<div class="form-head">
					<?=tb_tag('<h3>', $hp['contact_form_title'], '</h3>')?>
					<p>Fields marked * are required.</p>
				</div>
				<?php if($valid == 'not' && validation_errors()){ ?>
				<div class="alert alert-error" role="alert"><?=validation_errors()?></div>
				<?php } ?>
				<?php if($valid == 'true'){ ?>
				<div class="alert alert-success" role="status"><svg class="ic"><use href="#i-check"/></svg>Thank you! Your message has been sent. We'll get back to you shortly.</div>
				<?php } ?>
				<div class="form-row">
					<label class="field"><span>Full name *</span><input type="text" name="name" value="<?=$form['name']?>" autocomplete="name" required></label>
					<label class="field"><span>Email *</span><input type="email" name="email" value="<?=$form['email']?>" autocomplete="email" required></label>
				</div>
				<div class="form-row">
					<label class="field"><span>Phone *</span><input type="tel" name="telephone" value="<?=$form['telephone']?>" autocomplete="tel" required></label>
					<label class="field"><span>Company name *</span><input type="text" name="company" value="<?=$form['company']?>" autocomplete="organization" required></label>
				</div>
				<label class="field"><span>Subject *</span><input type="text" name="subject" id="subjectField" value="<?=$form['subject']?>" required></label>
				<label class="field"><span>Message *</span><textarea name="message" id="message" rows="6" required><?=$form['message']?></textarea></label>
				<button type="submit" name="submit" class="btn btn-primary btn-lg btn-block">Send message <svg class="ic"><use href="#i-send"/></svg></button>
			</form>
		</div>
	</div>
</section>

</main>

<footer class="site-footer">
	<div class="container footer-grid">
		<div class="footer-brand">
			<img src="<?=base_url()?>images/logo.png" width="222" height="40" alt="Traboulsi Est." loading="lazy">
			<?=tb_tag('<p>', $hp['footer_text'], '</p>')?>
			<?=tb_tag('<p class="ar" lang="ar" dir="rtl">', $hp['footer_text_ar'], '</p>')?>
		</div>
		<div>
			<h4>Explore</h4>
			<ul>
				<li><a href="#about">About us</a></li>
				<li><a href="#solutions">Solutions</a></li>
				<li><a href="#portfolio">Products</a></li>
				<li><a href="#team">Our clients</a></li>
				<li><a href="#contact">Contact</a></li>
			</ul>
		</div>
		<div>
			<h4>Products</h4>
			<ul>
				<?php foreach($categories as $category){
					if(!$cat_counts[$category['id_category']]) continue; ?>
				<li><a href="#portfolio" data-filter-link="prod<?=$category['id_category']?>"><?=tb_e($cat_names[$category['id_category']])?></a></li>
				<?php } ?>
			</ul>
		</div>
		<div>
			<h4>Get in touch</h4>
			<ul class="footer-contact">
				<?php if($contact['phone']){ ?><li><svg class="ic"><use href="#i-phone"/></svg><a href="<?=tb_e($contact['phone_href'])?>" class="tel"><?=tb_e($contact['phone'])?></a></li><?php } ?>
				<?php if($contact['mobile']){ ?><li><svg class="ic"><use href="#i-whatsapp"/></svg><a href="<?=tb_e($contact['mobile_href'])?>" class="tel"><?=tb_e($contact['mobile'])?></a></li><?php } ?>
				<?php if($contact['email']){ ?><li><svg class="ic"><use href="#i-mail"/></svg><a href="mailto:<?=tb_e($contact['email'])?>"><?=tb_e($contact['email'])?></a></li><?php } ?>
				<?php foreach($locations as $location){ ?><li><svg class="ic"><use href="#i-pin"/></svg><span><?=tb_e($location)?></span></li><?php } ?>
			</ul>
		</div>
	</div>
	<div class="container">
		<div class="footer-bottom">
			<p>&copy; <?=date('Y')?> <?=tb_e($hp['footer_copyright'])?>. All rights reserved.</p>
			<p>Powered by <a href="https://creativelebanon.com/" target="_blank" rel="noopener">CreativeLebanon</a></p>
		</div>
	</div>
</footer>

<?php if($contact['whatsapp']){ ?>
<a class="whatsapp-float" href="https://wa.me/<?=$contact['whatsapp']?>" target="_blank" rel="noopener" aria-label="Chat with us on WhatsApp"><svg class="ic"><use href="#i-whatsapp"/></svg></a>
<?php } ?>
<a class="to-top" id="toTop" href="#home" aria-label="Back to top"><svg class="ic"><use href="#i-up"/></svg></a>

<div class="lightbox" id="lightbox" role="dialog" aria-modal="true" aria-labelledby="lbTitle" hidden>
	<div class="lb-stage">
		<button type="button" class="lb-btn lb-close" aria-label="Close"><svg class="ic"><use href="#i-x"/></svg></button>
		<button type="button" class="lb-btn lb-prev" aria-label="Previous product"><svg class="ic"><use href="#i-left"/></svg></button>
		<figure class="lb-figure">
			<div class="lb-img"><img id="lbImg" alt=""></div>
			<figcaption class="lb-caption">
				<div>
					<span class="lb-cat" id="lbCat"></span>
					<h3 id="lbTitle"></h3>
					<span class="lb-count" id="lbCount"></span>
				</div>
				<button type="button" class="btn btn-primary" id="lbAsk">Ask about this product <svg class="ic"><use href="#i-arrow"/></svg></button>
			</figcaption>
		</figure>
		<button type="button" class="lb-btn lb-next" aria-label="Next product"><svg class="ic"><use href="#i-right"/></svg></button>
	</div>
</div>
