<?php
/*
| Everything Google, Facebook and WhatsApp read from the page: title, description,
| canonical address, Open Graph and the structured data (schema.org) of the company,
| its catalogue and its FAQ. Loaded by the <head> of the 4 designs.
| The texts come from back_office/home_page; an empty field falls back to config/seo_defaults.php.
*/
$this->config->load('seo_defaults', TRUE);
$seo_defaults = $this->config->item('seo_defaults', 'seo_defaults');
$seo = isset($home_page) ? $home_page : array();
foreach ($seo_defaults as $name => $default) {
	if (!isset($seo[$name]) || trim($seo[$name]) === '') $seo[$name] = $default;
}
$seo_v = function($name) use ($seo){ return isset($seo[$name]) ? trim(preg_replace('/\s+/', ' ', (string)$seo[$name])) : ''; };
$seo_e = function($name) use ($seo_v){ return htmlspecialchars($seo_v($name), ENT_QUOTES, 'UTF-8'); };
$seo_lines = function($text){ return preg_split('/\s*\R\s*/u', trim((string)$text), -1, PREG_SPLIT_NO_EMPTY); };

$site_url = base_url();
$page_lang = isset($page_lang) ? $page_lang : 'en';
$canonical = ($page_lang == 'ar' && isset($lang_urls['ar'])) ? $lang_urls['ar'] : $site_url;
$is_preview = isset($page_url) && rtrim($page_url, '/') !== rtrim($canonical, '/');
$og_image = is_file('uploads/about_us/530x400/tra.jpg') ? $site_url.'uploads/about_us/530x400/tra.jpg' : $site_url.'images/logo.png';
$company = $seo_v('contact_company') !== '' ? $seo_v('contact_company') : 'Traboulsi Est. for Trading & Industry';
$company_ar = $seo_v('topbar_name_ar') !== '' ? $seo_v('topbar_name_ar') : 'مؤسسة طرابلسي للتجارة والصناعة';
?>
<title><?=$seo_e('seo_title')?></title>
<base href="<?=$site_url?>" />
<meta name="description" content="<?=$seo_e('seo_description')?>" />
<meta name="keywords" content="<?=$seo_e('seo_keywords')?>" />
<meta name="robots" content="<?=$is_preview ? 'noindex, follow' : 'index, follow, max-image-preview:large, max-snippet:-1'?>" />
<link rel="canonical" href="<?=$canonical?>" />
<?php if(isset($lang_urls['ar'])){ ?>
<link rel="alternate" hreflang="en" href="<?=$lang_urls['en']?>" />
<link rel="alternate" hreflang="ar" href="<?=$lang_urls['ar']?>" />
<link rel="alternate" hreflang="ar-LB" href="<?=$lang_urls['ar']?>" />
<link rel="alternate" hreflang="x-default" href="<?=$lang_urls['en']?>" />
<?php }else{ ?>
<link rel="alternate" hreflang="ar-LB" href="<?=$site_url?>" />
<link rel="alternate" hreflang="en" href="<?=$site_url?>" />
<link rel="alternate" hreflang="x-default" href="<?=$site_url?>" />
<?php } ?>
<meta name="geo.region" content="LB" />
<meta name="geo.placename" content="Deir El Zahrani, Nabatieh, Lebanon" />
<meta name="author" content="<?=htmlspecialchars($company, ENT_QUOTES, 'UTF-8')?>" />
<?php if ($seo_v('google_verification') !== '') { ?>
<meta name="google-site-verification" content="<?=$seo_e('google_verification')?>" />
<?php } ?>
<meta property="og:type" content="website" />
<meta property="og:site_name" content="<?=htmlspecialchars($company, ENT_QUOTES, 'UTF-8')?>" />
<meta property="og:locale" content="<?=$page_lang == 'ar' ? 'ar_LB' : 'en_US'?>" />
<meta property="og:locale:alternate" content="<?=$page_lang == 'ar' ? 'en_US' : 'ar_LB'?>" />
<meta property="og:url" content="<?=$canonical?>" />
<meta property="og:image" content="<?=$og_image?>" />
<meta property="og:title" content="<?=$seo_e('seo_title')?>" />
<meta property="og:description" content="<?=$seo_e('seo_description')?>" />
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:title" content="<?=$seo_e('seo_title')?>" />
<meta name="twitter:description" content="<?=$seo_e('seo_description')?>" />
<meta name="twitter:image" content="<?=$og_image?>" />
<link rel="shortcut icon" href="<?=$site_url?>favicon.ico" />
<link href="<?=$site_url?>css_site/seo-section.css?v=<?=@filemtime('css_site/seo-section.css')?>" rel="stylesheet">
<?php
// ---- structured data -------------------------------------------------------
$phones = array();
foreach (array('phone', 'mobile') as $k) {
	if (!empty($contact[$k])) $phones[] = $contact[$k];
}
$addresses = array();
foreach ((isset($locations) ? $locations : array()) as $line) {
	$parts = array_map('trim', preg_split('/\s[-–]\s/u', $line, 2));
	$addresses[] = array(
		'@type' => 'PostalAddress',
		'addressLocality' => isset($parts[1]) ? $parts[1] : $parts[0],
		'addressRegion' => $parts[0],
		'addressCountry' => 'LB'
	);
}
if (!count($addresses)) {
	$addresses[] = array('@type' => 'PostalAddress', 'addressLocality' => 'Deir El Zahrani', 'addressRegion' => 'Nabatieh', 'addressCountry' => 'LB');
}
$same_as = array();
foreach (array('facebook', 'instagram') as $social) {
	if (!empty($contact_us[$social])) $same_as[] = $contact_us[$social];
}
$services_ar = $seo_lines($seo['seo_services_ar']);
$services_en = $seo_lines($seo['seo_services_en']);

$org = array(
	'@type' => array('LocalBusiness', 'Organization'),
	'@id' => $site_url.'#organization',
	'name' => $company,
	'alternateName' => $company_ar,
	'legalName' => $company,
	'description' => $seo_v('seo_description'),
	'url' => $site_url,
	'logo' => $site_url.'images/logo.png',
	'image' => $og_image,
	'address' => count($addresses) == 1 ? $addresses[0] : $addresses,
	'areaServed' => array('@type' => 'Country', 'name' => 'Lebanon'),
	'knowsLanguage' => array('ar', 'en'),
	'knowsAbout' => array_merge($services_ar, $services_en),
	'priceRange' => '$$',
);
if ($seo_v('founded_year') !== '') $org['foundingDate'] = $seo_v('founded_year');
if (count($phones)) $org['telephone'] = count($phones) == 1 ? $phones[0] : $phones;
if (!empty($contact['email'])) $org['email'] = $contact['email'];
if (count($same_as)) $org['sameAs'] = $same_as;
if (count($phones)) {
	$org['contactPoint'] = array(
		'@type' => 'ContactPoint',
		'telephone' => $phones[0],
		'contactType' => 'sales',
		'areaServed' => 'LB',
		'availableLanguage' => array('Arabic', 'English')
	);
}
// the catalogue: one OfferCatalog per category with its products
$catalog = array();
if (!empty($categories)) {
	$by_cat = array();
	foreach ((isset($products) ? $products : array()) as $product) $by_cat[$product['id_category']][] = $product;
	foreach ($categories as $category) {
		$items = array();
		foreach ((isset($by_cat[$category['id_category']]) ? $by_cat[$category['id_category']] : array()) as $product) {
			$items[] = array('@type' => 'Offer', 'itemOffered' => array(
				'@type' => 'Product',
				'name' => trim(html_entity_decode(strip_tags($product['title']), ENT_QUOTES, 'UTF-8')),
				'image' => $site_url.'uploads/products/290x220/'.$product['image'],
				'description' => isset($product['text_en']) && $product['text_en'] != '' ? $product['text_en'] : $product['alt'],
				'category' => isset($product['category']) ? $product['category'] : '',
				'brand' => array('@type' => 'Brand', 'name' => $company)
			));
		}
		$entry = array('@type' => 'OfferCatalog', 'name' => trim(html_entity_decode(strip_tags($category['title']), ENT_QUOTES, 'UTF-8')));
		if (count($items)) $entry['itemListElement'] = $items;
		$catalog[] = $entry;
	}
}
if (count($catalog)) {
	$org['hasOfferCatalog'] = array('@type' => 'OfferCatalog', 'name' => 'Products', 'itemListElement' => $catalog);
}
$graph = array($org);
$graph[] = array(
	'@type' => 'WebSite',
	'@id' => $site_url.'#website',
	'url' => $site_url,
	'name' => $company,
	'alternateName' => $company_ar,
	'inLanguage' => array('ar', 'en'),
	'publisher' => array('@id' => $site_url.'#organization')
);
$graph[] = array(
	'@type' => 'WebPage',
	'@id' => $canonical.'#webpage',
	'url' => $canonical,
	'name' => $seo_v('seo_title'),
	'description' => $seo_v('seo_description'),
	'inLanguage' => $page_lang == 'ar' ? 'ar' : array('ar', 'en'),
	'isPartOf' => array('@id' => $site_url.'#website'),
	'about' => array('@id' => $site_url.'#organization'),
	'primaryImageOfPage' => $og_image
);
// the services, as a list Google can show
$service_list = array();
foreach ($services_ar as $i => $name) {
	$service_list[] = array('@type' => 'ListItem', 'position' => $i + 1, 'item' => array(
		'@type' => 'Service', 'name' => $name, 'provider' => array('@id' => $site_url.'#organization'), 'areaServed' => array('@type' => 'Country', 'name' => 'Lebanon')
	));
}
if (count($service_list)) $graph[] = array('@type' => 'ItemList', 'name' => $seo_v('seo_section_title'), 'itemListElement' => $service_list);
// FAQ: "question | answer" per line
$faq = array();
foreach ($seo_lines($seo['seo_faq']) as $line) {
	if (strpos($line, '|') === FALSE) continue;
	list($q, $a) = array_map('trim', explode('|', $line, 2));
	if ($q === '' || $a === '') continue;
	$faq[] = array('@type' => 'Question', 'name' => $q, 'acceptedAnswer' => array('@type' => 'Answer', 'text' => $a));
}
if (count($faq)) $graph[] = array('@type' => 'FAQPage', 'mainEntity' => $faq);
// texts typed years ago in the control panel are not always valid UTF-8: json_encode would give nothing
$graph = array('@context' => 'https://schema.org', '@graph' => $graph);
array_walk_recursive($graph, function(&$v){ if (is_string($v)) $v = (string) @iconv('UTF-8', 'UTF-8//IGNORE', $v); });
$ld = json_encode($graph, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | (defined('JSON_PARTIAL_OUTPUT_ON_ERROR') ? JSON_PARTIAL_OUTPUT_ON_ERROR : 0));
?>
<script type="application/ld+json"><?=$ld?></script>
