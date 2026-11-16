<?php
/* Traboulsi Est. — option 3 (motion design). Same data as options 1 and 2, see controllers/home.php */
if(!function_exists('o3_e')){
	function o3_e($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8', false); }
	function o3_title($s){ return ucwords(strtolower(preg_replace('/\s+/', ' ', trim(html_entity_decode($s, ENT_QUOTES, 'UTF-8'))))); }
	function o3_icon($title, $map, $default){
		$t = strtolower($title);
		foreach($map as $needle => $icon){ if(preg_match('/'.$needle.'/', $t)) return $icon; }
		return $default;
	}
	// hero title: one line per line typed in the back office, [words] in red italic
	function o3_hero_title($s){
		$out = '';
		foreach(preg_split('/\s*\R\s*/u', trim($s)) as $line){
			$line = preg_replace('/\[(.+?)\]/s', '<em>$1</em>', o3_e($line));
			$out .= '<span class="line"><span class="line-in" data-split>'.$line.'</span></span> ';
		}
		return $out;
	}
	// a back office text inside its tag, or nothing when it was left empty
	function o3_tag($open, $text, $close){ return trim($text) === '' ? '' : $open.o3_e($text).$close; }
	// a back office text, or the default when it was left empty
	function o3_or($text, $default){ return trim($text) === '' ? $default : $text; }
	function o3_num($typed, $auto){ $n = preg_replace('/\D/', '', $typed); return $n === '' ? $auto : (int)$n; }
	// "showcase fridge 108" -> array('Showcase Fridge', '108')
	function o3_model($title){
		$title = trim($title);
		if(preg_match('/^(.*\D)\s*(\d{2,4})$/', $title, $m)) return array(ucfirst(trim($m[1])), $m[2]);
		return array(ucfirst($title), '');
	}
	function o3_pad($n){ return str_pad($n, 2, '0', STR_PAD_LEFT); }
}
$hp = $home_page;
$cat_icons = array('refrig|fridge|cold' => 'fridge', 'heat|oven|grill' => 'flame', 'stain|steel|process' => 'layers');
$client_icons = array('hospital' => 'medical', 'restaurant' => 'utensils', 'pastr|baker|sweet' => 'cake', 'supermarket' => 'cart');

$cat_names = array();
$cat_products = array();
foreach($categories as $category) $cat_names[$category['id_category']] = o3_title($category['title']);
foreach($products as $product) $cat_products[$product['id_category']][] = $product;

$founded = (int)$hp['founded_year'];
$years = $founded ? date('Y') - $founded : 0;
$product_total = count($products);
$unique_clients = array();
foreach($client_groups as $groups){
	foreach($groups as $group){
		foreach($group['names'] as $client_name) $unique_clients[strtolower($client_name)] = $client_name;
	}
}
$stat_products = o3_num($hp['stat_products_number'], $product_total);
$stat_sectors = o3_num($hp['stat_sectors_number'], count($categories));
$stat_clients = o3_num($hp['stat_clients_number'], count($unique_clients));
$features = array(1 => 'factory', 2 => 'layers', 3 => 'package', 4 => 'wrench');
$services = array();
foreach($features as $n => $icon){
	if(trim($hp['feature_'.$n.'_title']) !== '') $services[] = array('icon' => $icon, 'title' => $hp['feature_'.$n.'_title'], 'text' => $hp['feature_'.$n.'_text']);
}

$hero_main = count($hero_images) ? $hero_images[count($hero_images) - 1]['src'] : '';
$storefront = is_file('uploads/about_us/530x400/tra.jpg') ? 'uploads/about_us/530x400/tra.jpg' : '';

// showroom photos: the banners and the About Us images, without duplicates
$showroom = array();
$seen = array();
foreach($hero_images as $image){
	$key = md5_file($image['src']);
	if(isset($seen[$key])) continue;
	$seen[$key] = 1;
	$showroom[] = array('src' => $image['src'], 'title' => $image['title']);
}
foreach((array)glob('uploads/about_us/530x400/*.{jpg,jpeg,png,webp}', GLOB_BRACE) as $file){
	$key = md5_file($file);
	if(isset($seen[$key])) continue;
	$seen[$key] = 1;
	$big = 'uploads/about_us/'.basename($file);
	$showroom[] = array('src' => is_file($big) ? $big : $file, 'title' => 'Traboulsi Est. building');
}

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
$call_href = $contact['mobile'] ? $contact['mobile_href'] : $contact['phone_href'];
$call_num = $contact['mobile'] ? $contact['mobile'] : $contact['phone'];
$badge_text = strtoupper(($founded ? 'Since '.$founded.' • ' : '').'Traboulsi Est. • Trading & Industry • ');
$cta_ring = strtoupper(o3_or($hp['cta_button'], 'Get a quote')).' • '.strtoupper(o3_or($hp['cta_button'], 'Get a quote')).' • ';
$nav = array('home' => 'Home', 'about' => 'About', 'solutions' => 'Solutions', 'portfolio' => 'Products', 'clients' => 'Clients', 'showroom' => 'Showroom', 'contact' => 'Contact');
if(!count($showroom)) unset($nav['showroom']);
$base = base_url();
?>
<svg xmlns="http://www.w3.org/2000/svg" style="display:none" aria-hidden="true">
	<symbol id="i-phone" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></symbol>
	<symbol id="i-mail" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></symbol>
	<symbol id="i-pin" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></symbol>
	<symbol id="i-fax" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></symbol>
	<symbol id="i-facebook" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></symbol>
	<symbol id="i-instagram" viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="20" rx="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></symbol>
	<symbol id="i-arrow" viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></symbol>
	<symbol id="i-arrow-ur" viewBox="0 0 24 24"><line x1="7" y1="17" x2="17" y2="7"/><polyline points="7 7 17 7 17 17"/></symbol>
	<symbol id="i-down" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><polyline points="19 12 12 19 5 12"/></symbol>
	<symbol id="i-up" viewBox="0 0 24 24"><line x1="12" y1="19" x2="12" y2="5"/><polyline points="5 12 12 5 19 12"/></symbol>
	<symbol id="i-left" viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></symbol>
	<symbol id="i-right" viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></symbol>
	<symbol id="i-x" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></symbol>
	<symbol id="i-search" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></symbol>
	<symbol id="i-plus" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></symbol>
	<symbol id="i-send" viewBox="0 0 24 24"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></symbol>
	<symbol id="i-check" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></symbol>
	<symbol id="i-fridge" viewBox="0 0 24 24"><rect x="5" y="2" width="14" height="20" rx="2"/><line x1="5" y1="10" x2="19" y2="10"/><line x1="8.5" y1="5" x2="8.5" y2="7"/><line x1="8.5" y1="13" x2="8.5" y2="16"/></symbol>
	<symbol id="i-flame" viewBox="0 0 24 24"><path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.07-2.14-.22-4.05 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.15.43-2.29 1-3a2.5 2.5 0 0 0 2.5 2.5z"/></symbol>
	<symbol id="i-cake" viewBox="0 0 24 24"><path d="M20 21v-8a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v8"/><path d="M4 16s.5-1 2-1 2.5 2 4 2 2.5-2 4-2 2.5 2 4 2 2-1 2-1"/><path d="M2 21h20"/><path d="M7 8v3"/><path d="M12 8v3"/><path d="M17 8v3"/></symbol>
	<symbol id="i-cart" viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></symbol>
	<symbol id="i-layers" viewBox="0 0 24 24"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/></symbol>
	<symbol id="i-medical" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="3"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></symbol>
	<symbol id="i-utensils" viewBox="0 0 24 24"><path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"/><path d="M7 2v20"/><path d="M21 15V2a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"/></symbol>
	<symbol id="i-building" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></symbol>
	<symbol id="i-factory" viewBox="0 0 24 24"><path d="M2 20a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8l-7 5V8l-7 5V4a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"/><path d="M17 18h1"/><path d="M12 18h1"/><path d="M7 18h1"/></symbol>
	<symbol id="i-package" viewBox="0 0 24 24"><line x1="16.5" y1="9.4" x2="7.5" y2="4.21"/><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></symbol>
	<symbol id="i-wrench" viewBox="0 0 24 24"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></symbol>
	<symbol id="i-star" viewBox="0 0 24 24"><path d="M12 2l2.4 7.4H22l-6.2 4.5 2.4 7.4L12 16.8l-6.2 4.5 2.4-7.4L2 9.4h7.6z"/></symbol>
	<symbol id="i-whatsapp" viewBox="0 0 24 24"><path fill="currentColor" stroke="none" d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z"/></symbol>
</svg>

<a class="skip-link" href="#main">Skip to content</a>

<!-- ============ Intro screen ============ -->
<div class="loader" id="loader" aria-hidden="true">
	<div class="loader-panels"><span></span><span></span><span></span><span></span><span></span></div>
	<div class="loader-inner">
		<img src="<?=$base?>images/logo.png" width="300" height="54" alt="<?=o3_e(o3_or($hp['contact_company'], 'Traboulsi Est. for Trading & Industry'))?>">
		<div class="loader-row">
			<span class="loader-word"><?=o3_e(o3_or($hp['hero_eyebrow'], 'Traboulsi Est.'))?></span>
			<span class="loader-num"><span id="loaderNum">0</span>%</span>
		</div>
		<div class="loader-bar"><span id="loaderBar"></span></div>
	</div>
</div>

<div class="cursor" id="cursor" aria-hidden="true"><span class="cursor-ring"></span><span class="cursor-label" id="cursorLabel"></span></div>
<div class="grain" aria-hidden="true"></div>

<!-- ============ Header ============ -->
<header class="header" id="siteHeader">
	<div class="wrap header-inner">
		<a href="#home" class="brand" aria-label="Traboulsi Est. — home">
			<img src="<?=$base?>images/logo.png" width="300" height="54" alt="Traboulsi Est. — Since 1982">
		</a>
		<nav class="nav" aria-label="Main">
			<?php foreach($nav as $id => $label){ ?>
			<a href="#<?=$id?>"<?=$id == 'home' ? ' class="is-active"' : ''?>><span class="roll" data-text="<?=$label?>"><?=$label?></span></a>
			<?php } ?>
		</nav>
		<div class="header-side">
			<?php if($call_num){ ?><a class="header-call tel" href="<?=o3_e($call_href)?>"><span class="pulse-dot"></span><?=o3_e($call_num)?></a><?php } ?>
			<a href="#contact" class="pill pill-red" data-magnetic><span class="roll" data-text="Let's talk">Let's talk</span><svg class="ic"><use href="#i-arrow-ur"/></svg></a>
			<button class="burger" id="menuToggle" type="button" aria-controls="menu" aria-expanded="false" aria-label="Open menu"><span></span><span></span></button>
		</div>
	</div>
</header>

<!-- ============ Full screen menu ============ -->
<div class="menu" id="menu" hidden data-lenis-prevent>
	<div class="menu-bg"></div>
	<div class="wrap menu-inner">
		<nav class="menu-links" aria-label="Menu">
			<?php $n = 0; foreach($nav as $id => $label){ $n++; ?>
			<a href="#<?=$id?>" style="--i:<?=$n?>"><small><?=o3_pad($n)?></small><span><?=$label?></span></a>
			<?php } ?>
		</nav>
		<div class="menu-info">
			<p class="menu-label">Get in touch</p>
			<?php if($call_num){ ?><a href="<?=o3_e($call_href)?>" class="tel"><?=o3_e($call_num)?></a><?php } ?>
			<?php if($contact['email']){ ?><a href="mailto:<?=o3_e($contact['email'])?>"><?=o3_e($contact['email'])?></a><?php } ?>
			<?php if(count($locations)){ ?><p><?=o3_e(implode(' · ', $locations))?></p><?php } ?>
			<?=o3_tag('<p class="ar" lang="ar" dir="rtl">', $hp['topbar_name_ar'], '</p>')?>
		</div>
	</div>
</div>

<main id="main">

<!-- ============ Hero ============ -->
<section id="home" class="hero">
	<div class="hero-frame" id="heroFrame">
		<div class="hero-media" aria-hidden="true">
			<?php foreach($hero_images as $i => $image){ ?>
			<img class="hero-slide<?=$i == 0 ? ' is-active' : ''?>" src="<?=$base.o3_e($image['src'])?>" alt="<?=o3_e(o3_or($image['title'], 'Traboulsi Est. showroom'))?>" <?=$i == 0 ? 'fetchpriority="high"' : 'loading="lazy"'?>>
			<?php } ?>
		</div>
		<div class="hero-shade"></div>
		<div class="hero-glow" aria-hidden="true"></div>
	</div>

	<div class="wrap hero-inner">
		<div class="hero-content">
			<?=o3_tag('<p class="eyebrow eyebrow-light hero-eyebrow"><span class="pulse-dot"></span>', $hp['hero_eyebrow'], '</p>')?>
			<?php if(trim($hp['hero_title']) !== ''){ ?><h1 class="hero-title"><?=o3_hero_title($hp['hero_title'])?></h1><?php } ?>
			<div class="hero-bottom">
				<div class="hero-copy">
					<?=o3_tag('<p class="hero-lead">', $hp['hero_text'], '</p>')?>
					<?=o3_tag('<p class="hero-ar" lang="ar" dir="rtl">', $hp['hero_text_ar'], '</p>')?>
				</div>
				<div class="hero-actions">
					<?=o3_tag('<a href="#portfolio" class="pill pill-red pill-lg" data-magnetic><span class="roll" data-text="'.o3_e($hp['hero_button_1']).'">', $hp['hero_button_1'], '</span><svg class="ic"><use href="#i-arrow-ur"/></svg></a>')?>
					<?=o3_tag('<a href="#contact" class="pill pill-glass pill-lg" data-magnetic><span class="roll" data-text="'.o3_e($hp['hero_button_2']).'">', $hp['hero_button_2'], '</span></a>')?>
				</div>
			</div>
		</div>
	</div>

	<div class="hero-foot">
		<div class="wrap hero-foot-inner">
			<a href="#about" class="hero-scroll" aria-label="Scroll to About">
				<span class="hero-scroll-line"><span></span></span>
				<span>Scroll to explore</span>
			</a>
			<div class="hero-stats">
				<?php if($years){ ?><div class="glass"><strong><span data-count="<?=$years?>"><?=$years?></span>+</strong><?=o3_tag('<span>', $hp['stat_years_label'], '</span>')?></div><?php } ?>
				<div class="glass"><strong><span data-count="<?=$stat_products?>"><?=$stat_products?></span>+</strong><?=o3_tag('<span>', $hp['stat_products_label'], '</span>')?></div>
				<div class="glass"><strong><span data-count="<?=$stat_clients?>"><?=$stat_clients?></span>+</strong><?=o3_tag('<span>', $hp['stat_clients_label'], '</span>')?></div>
			</div>
			<?php if(count($hero_images) > 1){ ?>
			<div class="hero-pager" aria-label="Choose slide">
				<span class="hero-count"><b id="heroNow">01</b><i>/</i><?=o3_pad(count($hero_images))?></span>
				<div class="hero-dots">
					<?php foreach($hero_images as $i => $image){ ?>
					<button type="button" class="hero-dot<?=$i == 0 ? ' is-active' : ''?>" aria-label="Show slide <?=$i + 1?>"><span></span></button>
					<?php } ?>
				</div>
			</div>
			<?php } ?>
		</div>
	</div>
</section>

<!-- ============ Marquee ============ -->
<div class="marquee" aria-hidden="true">
	<div class="marquee-track" data-marquee>
		<?php
		$ticker = array();
		foreach($categories as $category) $ticker[] = $cat_names[$category['id_category']];
		foreach($services as $service) $ticker[] = $service['title'];
		for($copy = 0; $copy < 2; $copy++){ ?>
		<div class="marquee-group">
			<?php foreach($ticker as $t => $word){ ?>
			<span class="<?=$t % 2 ? 'is-outline' : ''?>"><?=o3_e($word)?></span><svg class="ic star"><use href="#i-star"/></svg>
			<?php } ?>
		</div>
		<?php } ?>
	</div>
</div>

<!-- ============ About ============ -->
<section id="about" class="section about">
	<div class="wrap">
		<div class="about-head">
			<p class="eyebrow"><span class="eyebrow-num">01</span><?=o3_e($about_us['title'])?></p>
			<?=o3_tag('<h2 class="statement" data-words="scrub">', $hp['about_title'], '</h2>')?>
		</div>

		<div class="about-grid">
			<div class="about-media">
				<?php if($hero_main){ ?>
				<figure class="about-img-main" data-clip><img src="<?=$base.o3_e($hero_main)?>" alt="Traboulsi Est. showroom with commercial kitchen equipment" loading="lazy" data-parallax></figure>
				<?php } ?>
				<?php if($storefront){ ?>
				<figure class="about-img-sub" data-clip data-speed="-0.12"><img src="<?=$base.$storefront?>" width="530" height="400" alt="Traboulsi Est. building in Deir El Zahrani" loading="lazy"></figure>
				<?php } ?>
				<a href="#solutions" class="spin-badge about-badge" aria-label="See our solutions">
					<svg viewBox="0 0 200 200" class="spin-text" aria-hidden="true">
						<defs><path id="badgeCircle" d="M100,100 m-78,0 a78,78 0 1,1 156,0 a78,78 0 1,1 -156,0"/></defs>
						<text><textPath href="#badgeCircle" textLength="485"><?=o3_e($badge_text)?></textPath></text>
					</svg>
					<span class="spin-core"><svg class="ic"><use href="#i-arrow-ur"/></svg></span>
				</a>
			</div>

			<div class="about-body">
				<?php if($years){ ?>
				<div class="about-years" data-reveal>
					<strong><span data-count="<?=$years?>"><?=$years?></span><sup>+</sup></strong>
					<span><?=o3_e(o3_or($hp['stat_years_label'], 'Years of experience'))?><?php if($founded){ ?><br><em><?=o3_e(o3_or($hp['about_badge_label'], 'Since'))?> <?=$founded?></em><?php } ?></span>
				</div>
				<?php } ?>
				<div class="about-text is-clamped" id="aboutText" data-reveal><?=$about_us['description']?></div>
				<button type="button" class="text-btn" data-toggle-about aria-expanded="false" aria-controls="aboutText"><span>Read more</span><svg class="ic"><use href="#i-plus"/></svg></button>

				<ul class="about-points">
					<?php foreach($services as $i => $service){ ?>
					<li data-reveal style="--d:<?=$i * 80?>ms"><span><?=o3_pad($i + 1)?></span><?=o3_e($service['title'])?><svg class="ic"><use href="#i-arrow-ur"/></svg></li>
					<?php } ?>
				</ul>
			</div>
		</div>
	</div>
</section>

<!-- ============ Solutions (categories, sideways on large screens) ============ -->
<section id="solutions" class="hs" data-hscroll>
	<div class="hs-pin">
		<div class="hs-track" data-hs-track>
			<div class="hs-panel hs-intro">
				<p class="eyebrow eyebrow-light"><span class="eyebrow-num">02</span><?=o3_e(o3_or($hp['solutions_eyebrow'], 'What we equip'))?></p>
				<?=o3_tag('<h2 class="h2">', $hp['solutions_title'], '</h2>')?>
				<?=o3_tag('<p class="lead">', $hp['solutions_text'], '</p>')?>
				<p class="hs-hint"><svg class="ic"><use href="#i-arrow"/></svg>Keep scrolling</p>
			</div>
			<?php foreach($categories as $i => $category){
				$id = $category['id_category'];
				$count = $cat_counts[$id];
				$thumbs = isset($cat_products[$id]) ? array_slice($cat_products[$id], 0, 3) : array(); ?>
			<a class="hs-panel hs-card" data-cursor="<?=$count ? 'Browse' : 'Ask'?>"
				<?php if($count){ ?>href="#portfolio" data-filter-link="prod<?=$id?>"<?php }else{ ?>href="#contact"<?php } ?>>
				<span class="hs-num" aria-hidden="true"><?=o3_pad($i + 1)?></span>
				<div class="hs-stack" aria-hidden="true">
					<?php foreach($thumbs as $t => $thumb){ ?>
					<img src="<?=$base?>uploads/products/290x220/<?=o3_e($thumb['image'])?>" alt="<?=o3_e($thumb['alt'])?>" loading="lazy" style="--t:<?=$t?>">
					<?php } ?>
				</div>
				<div class="hs-body">
					<span class="hs-icon"><svg class="ic"><use href="#i-<?=o3_icon($category['title'], $cat_icons, 'package')?>"/></svg></span>
					<h3><?=o3_e($cat_names[$id])?></h3>
					<p><?=$count ? $count.' products in the catalogue' : 'Made to order — ask us for details'?></p>
					<span class="hs-link"><?=$count ? 'View products' : 'Ask for a quote'?><svg class="ic"><use href="#i-arrow-ur"/></svg></span>
				</div>
			</a>
			<?php } ?>
			<div class="hs-panel hs-end">
				<p class="hs-end-big">Can't find it?<br><em>We make it.</em></p>
				<p>Custom stainless-steel work and made-to-measure equipment from our own factory.</p>
				<a href="#contact" class="pill pill-red pill-lg" data-magnetic><span class="roll" data-text="Tell us what you need">Tell us what you need</span><svg class="ic"><use href="#i-arrow-ur"/></svg></a>
			</div>
		</div>
		<div class="hs-progress" aria-hidden="true"><span data-hs-bar></span></div>
	</div>
</section>

<!-- ============ Services: stacking cards ============ -->
<?php if(count($services)){ ?>
<section id="services" class="section services">
	<div class="wrap services-grid">
		<div class="services-head">
			<p class="eyebrow"><span class="eyebrow-num">03</span><?=o3_e(o3_or($hp['services_eyebrow'], 'What we do'))?></p>
			<h2 class="h2" data-words><?=o3_e(o3_or($hp['services_title'], 'Our services & solutions'))?></h2>
			<p class="lead"><?=o3_e(o3_or($hp['services_text'], 'From manufacturing to installation and after-sales care, one team follows your project from the first sketch to the last screw.'))?></p>
			<a href="#contact" class="pill pill-dark" data-magnetic><span class="roll" data-text="Start a project">Start a project</span><svg class="ic"><use href="#i-arrow-ur"/></svg></a>
		</div>
		<ol class="stack">
			<?php foreach($services as $i => $service){ ?>
			<li class="stack-card stack-<?=$i % 4 + 1?>" style="--i:<?=$i?>">
				<div class="stack-top">
					<span class="stack-icon"><svg class="ic"><use href="#i-<?=$service['icon']?>"/></svg></span>
					<span class="stack-num"><?=o3_pad($i + 1)?> / <?=o3_pad(count($services))?></span>
				</div>
				<h3><?=o3_e($service['title'])?></h3>
				<?=o3_tag('<p>', $service['text'], '</p>')?>
				<span class="stack-bg" aria-hidden="true"><?=o3_pad($i + 1)?></span>
			</li>
			<?php } ?>
		</ol>
	</div>
</section>
<?php } ?>

<!-- ============ Products ============ -->
<section id="portfolio" class="section products">
	<div class="wrap">
		<div class="head">
			<div>
				<p class="eyebrow"><span class="eyebrow-num">04</span><?=o3_e(o3_or($hp['products_eyebrow'], 'Catalogue'))?></p>
				<?=o3_tag('<h2 class="h2" data-words>', $hp['products_title'], '</h2>')?>
			</div>
			<label class="search">
				<svg class="ic"><use href="#i-search"/></svg>
				<input type="search" id="productSearch" placeholder="Search a product…" aria-label="Search products" autocomplete="off">
			</label>
		</div>

		<div class="tabs" id="tabs" role="toolbar" aria-label="Filter products by category">
			<span class="tabs-ink" id="tabsInk" aria-hidden="true"></span>
			<button type="button" class="tab is-active" data-filter="*" aria-pressed="true">All <sup><?=$product_total?></sup></button>
			<?php foreach($categories as $category){
				if(!$cat_counts[$category['id_category']]) continue; ?>
			<button type="button" class="tab" data-filter="prod<?=$category['id_category']?>" aria-pressed="false"><?=o3_e($cat_names[$category['id_category']])?> <sup><?=$cat_counts[$category['id_category']]?></sup></button>
			<?php } ?>
		</div>

		<div class="p-grid" id="productGrid">
			<?php foreach($products as $product){
				$cat = isset($cat_names[$product['id_category']]) ? $cat_names[$product['id_category']] : '';
				list($p_name, $p_ref) = o3_model($product['title']); ?>
			<article class="p-card" data-cat="prod<?=$product['id_category']?>" data-name="<?=o3_e($p_name.($p_ref ? ' '.$p_ref : ''))?>" data-search="<?=o3_e(strtolower($product['title'].' '.$cat))?>">
				<a href="<?=$base?>uploads/products/600x455/<?=o3_e($product['image'])?>" class="p-media" data-cursor="View" aria-label="View <?=o3_e($product['title'])?>">
					<img src="<?=$base?>uploads/products/290x220/<?=o3_e($product['image'])?>" width="290" height="220" loading="lazy" alt="<?=o3_e($product['alt'])?>">
					<?php if($p_ref){ ?><span class="p-ref">Ref. <?=o3_e($p_ref)?></span><?php } ?>
				</a>
				<div class="p-body">
					<div>
						<span class="p-cat"><?=o3_e($cat)?></span>
						<h3 class="p-title"><?=o3_e($p_name)?></h3>
					</div>
					<button type="button" class="p-ask" data-ask aria-label="Ask about <?=o3_e($product['title'])?>"><svg class="ic"><use href="#i-arrow-ur"/></svg></button>
				</div>
			</article>
			<?php } ?>
		</div>

		<p class="empty" id="productsEmpty" hidden>No products match your search. <a href="#contact">Ask us directly</a> — we may have it in stock or make it to order.</p>

		<div class="more" id="loadMoreWrap">
			<p>Showing <b id="shownCount"><?=$product_total?></b> of <b id="totalCount"><?=$product_total?></b> products</p>
			<div class="bar"><span id="productsBar"></span></div>
			<button type="button" class="pill pill-dark" id="loadMore" hidden><span>Load more products</span><svg class="ic"><use href="#i-plus"/></svg></button>
		</div>
	</div>
</section>

<!-- ============ Clients ============ -->
<section id="clients" class="section clients">
	<div class="wrap">
		<div class="head">
			<div>
				<p class="eyebrow eyebrow-light"><span class="eyebrow-num">05</span><?=o3_e(o3_or($hp['clients_eyebrow'], 'Our clients'))?></p>
				<?=o3_tag('<h2 class="h2" data-words>', $hp['clients_title'], '</h2>')?>
			</div>
			<?=o3_tag('<p class="lead">', $hp['clients_text'], '</p>')?>
		</div>
	</div>

	<?php if(count($unique_clients)){
		$names = array_values($unique_clients);
		$half = (int)ceil(count($names) / 2);
		$rows = count($names) > 8 ? array(array_slice($names, 0, $half), array_slice($names, $half)) : array($names, array_reverse($names)); ?>
	<div class="names" aria-hidden="true">
		<?php foreach($rows as $r => $row){ ?>
		<div class="names-row<?=$r ? ' is-reverse' : ''?>">
			<div class="names-track" data-marquee data-dir="<?=$r ? '-1' : '1'?>">
				<?php for($copy = 0; $copy < 2; $copy++){ ?>
				<div class="marquee-group">
					<?php foreach($row as $k => $client_name){ ?>
					<span class="<?=$k % 2 ? 'is-serif' : ''?>"><?=o3_e($client_name)?></span><i></i>
					<?php } ?>
				</div>
				<?php } ?>
			</div>
		</div>
		<?php } ?>
	</div>
	<?php } ?>

	<div class="wrap">
		<?php foreach($client_groups as $country => $groups){ ?>
		<div class="acc" data-acc>
			<p class="acc-country"><svg class="ic"><use href="#i-pin"/></svg><?=o3_e($country)?></p>
			<?php foreach($groups as $i => $group){
				$acc_id = 'o3acc-'.$group['id_our_clients']; ?>
			<div class="acc-item<?=$i == 0 ? ' is-open' : ''?>" data-reveal>
				<h3>
					<button type="button" class="acc-btn" aria-expanded="<?=$i == 0 ? 'true' : 'false'?>" aria-controls="<?=$acc_id?>">
						<span class="acc-num"><?=o3_pad($i + 1)?></span>
						<span class="acc-icon"><svg class="ic"><use href="#i-<?=o3_icon($group['title'], $client_icons, 'building')?>"/></svg></span>
						<span class="acc-name"><?=o3_e($group['title'])?></span>
						<?php if(count($group['names'])){ ?><span class="acc-count"><?=count($group['names'])?> clients</span><?php } ?>
						<span class="acc-plus" aria-hidden="true"></span>
					</button>
				</h3>
				<div class="acc-panel" id="<?=$acc_id?>">
					<div class="acc-panel-in">
						<?php if(count($group['names'])){ ?>
						<ul class="acc-names">
							<?php foreach($group['names'] as $client_name){ ?>
							<li><?=o3_e($client_name)?></li>
							<?php } ?>
						</ul>
						<?php }else{ ?>
						<div class="acc-raw"><?=$group['description']?></div>
						<?php } ?>
					</div>
				</div>
			</div>
			<?php } ?>
		</div>
		<?php } ?>
	</div>
</section>

<!-- ============ Showroom ============ -->
<?php if(count($showroom)){ ?>
<section id="showroom" class="section showroom">
	<div class="wrap">
		<div class="head">
			<div>
				<p class="eyebrow"><span class="eyebrow-num">06</span><?=o3_e(o3_or($hp['showroom_eyebrow'], 'Our showroom'))?></p>
				<h2 class="h2" data-words><?=o3_e(o3_or($hp['showroom_title'], 'See the equipment before you buy'))?></h2>
			</div>
			<p class="lead"><?=o3_e(o3_or($hp['showroom_text'], 'Visit our factory in Deir El Zahrani or our branch in Khalde: refrigerators, cooking lines and stainless-steel work, on display and ready to deliver.'))?></p>
		</div>
	</div>
	<div class="show-track" id="showTrack">
		<?php foreach($showroom as $i => $photo){ ?>
		<a href="<?=$base.o3_e($photo['src'])?>" class="show-item show-<?=$i % 4 + 1?>" data-gallery data-cursor="Open" aria-label="Open photo <?=$i + 1?>">
			<img src="<?=$base.o3_e($photo['src'])?>" alt="<?=o3_e($photo['title'])?> — showroom photo <?=$i + 1?>" loading="lazy">
			<span class="show-cap"><b><?=o3_pad($i + 1)?></b><?=o3_e($photo['title'])?></span>
		</a>
		<?php } ?>
	</div>
</section>
<?php } ?>

<!-- ============ Call to action ============ -->
<section class="cta">
	<div class="cta-blob" aria-hidden="true"></div>
	<div class="wrap cta-inner">
		<div class="cta-text">
			<?=o3_tag('<h2 data-words>', $hp['cta_title'], '</h2>')?>
			<?=o3_tag('<p>', $hp['cta_text'], '</p>')?>
			<?php if($call_num){ ?>
			<a href="<?=o3_e($call_href)?>" class="cta-call"><svg class="ic"><use href="#i-phone"/></svg><span class="tel"><?=o3_e($call_num)?></span></a>
			<?php } ?>
		</div>
		<a href="#contact" class="cta-circle" data-magnetic data-cursor-hide aria-label="<?=o3_e(o3_or($hp['cta_button'], 'Get a quote'))?>">
			<svg viewBox="0 0 200 200" class="spin-text" aria-hidden="true">
				<defs><path id="ctaCircle" d="M100,100 m-80,0 a80,80 0 1,1 160,0 a80,80 0 1,1 -160,0"/></defs>
				<text><textPath href="#ctaCircle" textLength="498"><?=o3_e($cta_ring)?></textPath></text>
			</svg>
			<span class="cta-core"><svg class="ic"><use href="#i-arrow-ur"/></svg></span>
		</a>
	</div>
</section>

<!-- ============ Contact ============ -->
<?php $this->load->view('include/_seo_section', array('wrap' => 'wrap')); // Arabic services + FAQ (Google) ?>

<section id="contact" class="section contact">
	<div class="wrap">
		<div class="head">
			<div>
				<p class="eyebrow"><span class="eyebrow-num">07</span><?=o3_e(o3_or($hp['contact_eyebrow'], 'Contact'))?></p>
				<?=o3_tag('<h2 class="h2" data-words>', $hp['contact_title'], '</h2>')?>
			</div>
			<?=o3_tag('<p class="lead">', $hp['contact_text'], '</p>')?>
		</div>

		<div class="contact-grid">
			<aside class="info-card" data-reveal>
				<h3><?=o3_e(o3_or($hp['contact_company'], $contact_us['title']))?></h3>
				<?php if(count($contact_items)){ ?>
				<ul class="info-list">
					<?php foreach($contact_items as $item){
						$label = strtolower($item['label']);
						$icon = $item['type'] == 'mailto' ? 'mail' : (strpos($label, 'fax') !== false ? 'fax' : 'phone'); ?>
					<li>
						<span class="info-icon"><svg class="ic"><use href="#i-<?=$icon?>"/></svg></span>
						<div><small><?=o3_e($item['label'])?></small><a href="<?=o3_e($item['href'])?>"<?=$item['type'] == 'tel' ? ' class="tel"' : ''?>><?=o3_e($item['value'])?></a></div>
					</li>
					<?php } ?>
					<?php foreach($locations as $location){ ?>
					<li>
						<span class="info-icon"><svg class="ic"><use href="#i-pin"/></svg></span>
						<div><small>Branch</small><span><?=o3_e($location)?></span><?php $role = branch_role($location); if($role !== ''){ ?><span class="branch-role"><?=o3_e($role)?></span><?php } ?></div>
					</li>
					<?php } ?>
				</ul>
				<?php }else{ ?>
				<div class="info-raw"><?=$contact_us['description']?></div>
				<?php } ?>
				<div class="info-social">
					<?php if(!empty($contact_us['facebook'])){ ?><a href="<?=o3_e($contact_us['facebook'])?>" target="_blank" rel="noopener" aria-label="Facebook" data-magnetic><svg class="ic"><use href="#i-facebook"/></svg></a><?php } ?>
					<?php if(!empty($contact_us['instagram'])){ ?><a href="<?=o3_e($contact_us['instagram'])?>" target="_blank" rel="noopener" aria-label="Instagram" data-magnetic><svg class="ic"><use href="#i-instagram"/></svg></a><?php } ?>
					<?php if($contact['whatsapp']){ ?><a href="https://wa.me/<?=$contact['whatsapp']?>" target="_blank" rel="noopener" aria-label="WhatsApp" data-magnetic><svg class="ic"><use href="#i-whatsapp"/></svg></a><?php } ?>
				</div>
				<div class="info-map">
					<iframe src="<?=o3_e($map_src)?>" title="Map — Deir El Zahrani, Lebanon" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
				</div>
			</aside>

			<form id="main-contact-form" class="form" name="contact-form" method="post" action="<?=o3_e($page_url)?>#contact" data-reveal style="--d:120ms">
				<div class="form-head">
					<?=o3_tag('<h3>', $hp['contact_form_title'], '</h3>')?>
					<p>Fields marked * are required.</p>
				</div>
				<?php if($valid == 'not' && validation_errors()){ ?>
				<div class="alert alert-error" role="alert"><?=validation_errors()?></div>
				<?php } ?>
				<?php if($valid == 'true'){ ?>
				<div class="alert alert-success" role="status"><svg class="ic"><use href="#i-check"/></svg>Thank you! Your message has been sent. We'll get back to you shortly.</div>
				<?php } ?>
				<div class="form-row">
					<label class="field"><input type="text" name="name" value="<?=$form['name']?>" autocomplete="name" placeholder=" " required><span>Full name *</span><i></i></label>
					<label class="field"><input type="email" name="email" value="<?=$form['email']?>" autocomplete="email" placeholder=" " required><span>Email *</span><i></i></label>
				</div>
				<div class="form-row">
					<label class="field"><input type="tel" name="telephone" value="<?=$form['telephone']?>" autocomplete="tel" placeholder=" " required><span>Phone *</span><i></i></label>
					<label class="field"><input type="text" name="company" value="<?=$form['company']?>" autocomplete="organization" placeholder=" " required><span>Company name *</span><i></i></label>
				</div>
				<label class="field"><input type="text" name="subject" id="subjectField" value="<?=$form['subject']?>" placeholder=" " required><span>Subject *</span><i></i></label>
				<label class="field"><textarea name="message" id="message" rows="5" placeholder=" " required><?=$form['message']?></textarea><span>Message *</span><i></i></label>
				<button type="submit" name="submit" class="pill pill-red pill-lg pill-block"><span class="roll" data-text="Send message">Send message</span><svg class="ic"><use href="#i-send"/></svg></button>
			</form>
		</div>
	</div>
</section>

</main>

<!-- ============ Footer ============ -->
<footer class="footer">
	<div class="wrap">
		<div class="footer-top">
			<p class="footer-claim" data-words="scrub">Let's equip your <em>next project.</em></p>
			<?php if($contact['email']){ ?><a class="footer-mail" href="mailto:<?=o3_e($contact['email'])?>"><span><?=o3_e($contact['email'])?></span><svg class="ic"><use href="#i-arrow-ur"/></svg></a><?php } ?>
		</div>
		<div class="footer-grid">
			<div class="footer-brand">
				<img src="<?=$base?>images/logo.png" width="222" height="40" alt="Traboulsi Est." loading="lazy">
				<?=o3_tag('<p>', $hp['footer_text'], '</p>')?>
				<?=o3_tag('<p class="ar" lang="ar" dir="rtl">', $hp['footer_text_ar'], '</p>')?>
			</div>
			<div>
				<h4>Explore</h4>
				<ul>
					<?php foreach($nav as $id => $label){ if($id == 'home') continue; ?>
					<li><a href="#<?=$id?>"><span class="roll" data-text="<?=$label?>"><?=$label?></span></a></li>
					<?php } ?>
				</ul>
			</div>
			<div>
				<h4>Products</h4>
				<ul>
					<?php foreach($categories as $category){
						if(!$cat_counts[$category['id_category']]) continue;
						$label = o3_e($cat_names[$category['id_category']]); ?>
					<li><a href="#portfolio" data-filter-link="prod<?=$category['id_category']?>"><span class="roll" data-text="<?=$label?>"><?=$label?></span></a></li>
					<?php } ?>
				</ul>
			</div>
			<div>
				<h4>Get in touch</h4>
				<ul class="footer-contact">
					<?php if($contact['phone']){ ?><li><svg class="ic"><use href="#i-phone"/></svg><a href="<?=o3_e($contact['phone_href'])?>" class="tel"><?=o3_e($contact['phone'])?></a></li><?php } ?>
					<?php if($contact['mobile']){ ?><li><svg class="ic"><use href="#i-whatsapp"/></svg><a href="<?=o3_e($contact['mobile_href'])?>" class="tel"><?=o3_e($contact['mobile'])?></a></li><?php } ?>
					<?php foreach($locations as $location){ ?><li><svg class="ic"><use href="#i-pin"/></svg><span><?=o3_e($location)?></span></li><?php } ?>
				</ul>
				<div class="footer-social">
					<?php if(!empty($contact_us['facebook'])){ ?><a href="<?=o3_e($contact_us['facebook'])?>" target="_blank" rel="noopener" aria-label="Facebook" data-magnetic><svg class="ic"><use href="#i-facebook"/></svg></a><?php } ?>
					<?php if(!empty($contact_us['instagram'])){ ?><a href="<?=o3_e($contact_us['instagram'])?>" target="_blank" rel="noopener" aria-label="Instagram" data-magnetic><svg class="ic"><use href="#i-instagram"/></svg></a><?php } ?>
					<?php if($contact['whatsapp']){ ?><a href="https://wa.me/<?=$contact['whatsapp']?>" target="_blank" rel="noopener" aria-label="WhatsApp" data-magnetic><svg class="ic"><use href="#i-whatsapp"/></svg></a><?php } ?>
				</div>
			</div>
		</div>
	</div>
	<div class="footer-giant" aria-hidden="true" data-split-giant>TRABOULSI</div>
	<div class="wrap">
		<div class="footer-bottom">
			<p>&copy; <?=date('Y')?> <?=o3_e($hp['footer_copyright'])?>. All rights reserved.</p>
			<p>Powered by <a href="https://creativelebanon.com/" target="_blank" rel="noopener">CreativeLebanon</a></p>
		</div>
	</div>
</footer>

<?php if($contact['whatsapp']){ ?>
<a class="wa-float" href="https://wa.me/<?=$contact['whatsapp']?>" target="_blank" rel="noopener" aria-label="Chat with us on WhatsApp"><svg class="ic"><use href="#i-whatsapp"/></svg></a>
<?php } ?>
<a class="to-top" id="toTop" href="#home" aria-label="Back to top">
	<svg class="to-top-ring" viewBox="0 0 48 48" aria-hidden="true"><circle cx="24" cy="24" r="22"/><circle cx="24" cy="24" r="22" id="topRing"/></svg>
	<svg class="ic"><use href="#i-up"/></svg>
</a>

<div class="lightbox" id="lightbox" role="dialog" aria-modal="true" aria-labelledby="lbTitle" hidden data-lenis-prevent>
	<button type="button" class="lb-btn lb-close" aria-label="Close"><svg class="ic"><use href="#i-x"/></svg></button>
	<div class="lb-stage">
		<button type="button" class="lb-btn lb-prev" aria-label="Previous"><svg class="ic"><use href="#i-left"/></svg></button>
		<figure class="lb-figure">
			<div class="lb-img"><img id="lbImg" alt=""></div>
			<figcaption class="lb-caption">
				<div>
					<span class="lb-cat" id="lbCat"></span>
					<h3 id="lbTitle"></h3>
				</div>
				<span class="lb-count" id="lbCount"></span>
				<button type="button" class="pill pill-red" id="lbAsk"><span>Ask about this product</span><svg class="ic"><use href="#i-arrow-ur"/></svg></button>
			</figcaption>
		</figure>
		<button type="button" class="lb-btn lb-next" aria-label="Next"><svg class="ic"><use href="#i-right"/></svg></button>
	</div>
</div>
