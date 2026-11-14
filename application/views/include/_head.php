<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<script>document.documentElement.className=document.documentElement.className.replace('no-js','js');</script>
<?php
// page title, Google texts and company info come from back_office/home_page
$seo = isset($home_page) ? $home_page : array();
$seo_e = function($name) use ($seo){ return isset($seo[$name]) ? htmlspecialchars($seo[$name], ENT_QUOTES, 'UTF-8') : ''; };
?>
<title><?=$seo_e('seo_title')?></title>
<base href="<?=base_url()?>" />
<meta name="keywords" content="<?=$seo_e('seo_keywords')?>" />
<meta name="description" content="<?=$seo_e('seo_description')?>" />
<meta name="theme-color" content="#12151A">
<?php $og_image = is_file('uploads/about_us/530x400/tra.jpg') ? base_url().'uploads/about_us/530x400/tra.jpg' : base_url().'images/logo.png'; ?>
<meta property="og:type" content="website" />
<meta property="og:url" content="<?=base_url()?>" />
<meta property="og:image" content="<?=$og_image?>" />
<meta property="og:title" content="<?=$seo_e('seo_title')?>"/>
<meta property="og:description" content="<?=$seo_e('seo_description')?>"/>
<link rel="shortcut icon" href="<?=base_url()?>favicon.ico" />

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@400;600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="<?=base_url()?>css_site/theme2026.css?v=<?=@filemtime('css_site/theme2026.css')?>" rel="stylesheet">
<?php if(!empty($hero_images)){ ?>
<link rel="preload" as="image" href="<?=base_url().$hero_images[0]['src']?>">
<?php } ?>

<?php
// LocalBusiness structured data
$schema = array(
	'@context' => 'https://schema.org',
	'@type' => 'LocalBusiness',
	'url' => base_url(),
	'logo' => base_url().'images/logo.png',
	'image' => $og_image,
	'address' => array(
		'@type' => 'PostalAddress',
		'addressLocality' => 'Deir El Zahrani',
		'addressRegion' => 'Nabatieh',
		'addressCountry' => 'LB'
	)
);
foreach(array('name' => 'contact_company', 'alternateName' => 'topbar_name_ar', 'description' => 'footer_text', 'foundingDate' => 'founded_year') as $key => $name){
	if(!empty($seo[$name])) $schema[$key] = $seo[$name];
}
if(!empty($contact['phone'])) $schema['telephone'] = $contact['phone'];
if(!empty($contact['email'])) $schema['email'] = $contact['email'];
$schema['sameAs'] = array();
foreach(array('facebook', 'instagram') as $social){
	if(!empty($contact_us[$social])) $schema['sameAs'][] = $contact_us[$social];
}
if(!count($schema['sameAs'])) unset($schema['sameAs']);
?>
<script type="application/ld+json"><?=json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)?></script>
