<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php $this->load->view('include/_seo'); // title, description, Open Graph, schema.org ?>
<script>document.documentElement.className=document.documentElement.className.replace('no-js','js');</script>
<meta name="theme-color" content="#111214">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@400;600&family=Manrope:wght@400;500;600;700;800&family=Unbounded:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="<?=base_url()?>css_site/option2.css?v=<?=@filemtime('css_site/option2.css')?>" rel="stylesheet">
<?php if(!empty($hero_images)){ ?>
<link rel="preload" as="image" href="<?=base_url().$hero_images[0]['src']?>">
<?php } ?>

<script src="<?=base_url()?>js_site/option2.js?v=<?=@filemtime('js_site/option2.js')?>" defer></script>
