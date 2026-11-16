<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php $this->load->view('include/_seo'); // title, description, Open Graph, schema.org ?>
<script>
(function(d){
	var h=d.documentElement;h.className=h.className.replace('no-js','js');
	// motion states are only hidden when JS can show them again; option3.js sets O3_READY
	if(!(window.matchMedia&&matchMedia('(prefers-reduced-motion: reduce)').matches)){
		h.className+=' js-motion';
		try{ if(sessionStorage.getItem('o3-intro')) h.className+=' skip-loader'; }catch(e){}
		setTimeout(function(){ if(!window.O3_READY) h.className+=' motion-fallback'; },4000);
	}
})(document);
</script>
<meta name="theme-color" content="#0E0E0E">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=IBM+Plex+Sans+Arabic:wght@400;600&family=Syne:wght@500;600;700;800&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
<link href="<?=base_url()?>css_site/option3.css?v=<?=@filemtime('css_site/option3.css')?>" rel="stylesheet">
<?php if(!empty($hero_images)){ ?>
<link rel="preload" as="image" href="<?=base_url().$hero_images[0]['src']?>">
<?php } ?>

<script src="<?=base_url()?>js_site/vendor/gsap.min.js" defer></script>
<script src="<?=base_url()?>js_site/vendor/ScrollTrigger.min.js" defer></script>
<script src="<?=base_url()?>js_site/vendor/lenis.min.js" defer></script>
<script src="<?=base_url()?>js_site/option3.js?v=<?=@filemtime('js_site/option3.js')?>" defer></script>
