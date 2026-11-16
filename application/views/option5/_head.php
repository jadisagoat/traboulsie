<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php
// the bilingual design: the English page and the Arabic page (yoursite/ar) are both indexed,
// each one pointing to the other (hreflang) in views/include/_seo.php
$lang_urls = array('en' => base_url(), 'ar' => base_url().'ar');
$this->load->view('include/_seo', array('lang_urls' => $lang_urls, 'page_lang' => (isset($lang) && $lang == 'ar') ? 'ar' : 'en'));
?>
<script>
(function(h){
	h.className=h.className.replace('no-js','js');
	// things that animate in are only hidden when the script can show them again (option5.js sets O5_READY)
	if(!(window.matchMedia&&matchMedia('(prefers-reduced-motion: reduce)').matches)){
		h.className+=' motion';
		// the intro loader is shown once per visit
		try{ if(sessionStorage.getItem('o5-intro')) h.className+=' skip-loader'; }catch(e){ h.className+=' skip-loader'; }
		setTimeout(function(){ if(!window.O5_READY) h.className+=' motion-off'; },3500);
	}
})(document.documentElement);
</script>
<meta name="theme-color" content="#0e1317">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@300;400;500;600;700&family=Mulish:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="<?=base_url()?>css_site/option5.css?v=<?=@filemtime('css_site/option5.css')?>" rel="stylesheet">
<?php if(!empty($hero_images)){ ?>
<link rel="preload" as="image" href="<?=base_url().$hero_images[0]['src']?>">
<?php } ?>
<script src="<?=base_url()?>js_site/vendor/gsap.min.js" defer></script>
<script src="<?=base_url()?>js_site/vendor/ScrollTrigger.min.js" defer></script>
<script src="<?=base_url()?>js_site/vendor/lenis.min.js" defer></script>
<script src="<?=base_url()?>js_site/option5.js?v=<?=@filemtime('js_site/option5.js')?>" defer></script>
