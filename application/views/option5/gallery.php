<?php
/*
| The photo page (yoursite/photos, yoursite/ar/photos) and the video page (yoursite/videos, yoursite/ar/videos).
| Included by home.php inside <main>: it shares the header, the footer, the photo viewer and every variable of the home page.
| Photos shown: the showroom, every photo of the completed projects, and the products. Videos shown: all of back office > Videos.
*/
$g_photos = array();
$g_count = array('showroom' => 0, 'projects' => 0, 'products' => 0);
$g_names = array('showroom' => 'Showroom', 'projects' => 'Projects', 'products' => 'Products');
if($page === 'photos'){
	foreach($showroom as $photo){
		$small = 'uploads/about_us/530x400/'.basename($photo['src']);
		$g_photos[] = array('group' => 'showroom', 'src' => $photo['src'], 'thumb' => (strpos($photo['src'], 'uploads/about_us/') === 0 && is_file($small)) ? $small : $photo['src'], 'title' => o5_t($photo['title']), 'cat' => o5_t('Showroom'));
	}
	foreach($projects as $pr){
		$key = isset($project_sectors[$pr['sector']]) ? $pr['sector'] : 'other';
		$p_cat = o5_t($project_sectors[$key]);
		$p_title = trim((string)($is_ar ? $pr['title_ar'] : $pr['title']));
		$p_place = trim((string)($is_ar ? $pr['place_ar'] : $pr['place']));
		if($p_title === '') $p_title = $p_cat.($p_place !== '' ? ' · '.$p_place : '');
		foreach($pr['photo_list'] as $ph){
			$g_photos[] = array('group' => 'projects', 'src' => 'uploads/projects/'.$ph, 'thumb' => is_file('uploads/projects/thumb/'.$ph) ? 'uploads/projects/thumb/'.$ph : 'uploads/projects/'.$ph, 'title' => $p_title, 'cat' => $p_cat);
		}
	}
	foreach($products as $product){
		$big = is_file('uploads/products/600x455/'.$product['image']) ? 'uploads/products/600x455/' : 'uploads/products/290x220/';
		$cat = isset($cat_names[$product['id_category']]) ? $cat_names[$product['id_category']] : o5_t('Products');
		$g_photos[] = array('group' => 'products', 'src' => $big.$product['image'], 'thumb' => $big.$product['image'], 'title' => o5_pname(o5_name($product['title'])), 'cat' => $cat);
	}
	foreach($g_photos as $photo) $g_count[$photo['group']]++;
}
$g_total = $page === 'photos' ? count($g_photos) : count($videos);
$fb_more = $fb_page === '' ? '' : rtrim($fb_page, '/').'/'.$page;
?>

<!-- ============ Photo / video page: title and the switch between the two ============ -->
<section id="top" class="g-hero is-dark">
	<div class="glow glow-a" aria-hidden="true"></div>
	<div class="container">
		<nav class="crumbs" aria-label="<?=o5_te('You are here')?>" data-reveal="up">
			<a href="<?=o5_e($home_url)?>"><?=o5_te('Home')?></a><span aria-hidden="true">/</span><b><?=o5_te($page === 'photos' ? 'Photos' : 'Videos')?></b>
		</nav>
		<p class="eyebrow" data-reveal="up" style="--d:60ms"><?=o5_te($page === 'photos' ? 'Photo gallery' : 'Video gallery')?></p>
		<h1 class="g-title" data-reveal="up" style="--d:120ms"><?=o5_te($page === 'photos' ? 'All our photos' : 'All our videos')?></h1>
		<p class="lead" data-reveal="up" style="--d:200ms"><?=o5_te($page === 'photos' ? 'Our showroom, the projects we delivered and every product we make and supply, in one place.' : 'Installations, new arrivals and our workshop at work: every video we published, in one place.')?></p>
		<div class="g-switch" data-reveal="up" style="--d:280ms">
			<a href="<?=o5_e($photos_url)?>"<?=$page === 'photos' ? ' class="is-active" aria-current="page"' : ''?>><svg class="ic"><use href="#i-image"/></svg><?=o5_te('Photos')?><?php if($page === 'photos'){ ?> <small><?=$g_total?></small><?php } ?></a>
			<a href="<?=o5_e($videos_url)?>"<?=$page === 'videos' ? ' class="is-active" aria-current="page"' : ''?>><svg class="ic"><use href="#i-play"/></svg><?=o5_te('Videos')?> <small><?=count($videos)?></small></a>
		</div>
	</div>
</section>

<?php if($page === 'photos'){ ?>
<!-- ============ All photos ============ -->
<section class="g-body">
	<div class="container">
		<?php if(count(array_filter($g_count)) > 1){ ?>
		<div class="chips" role="group" aria-label="<?=o5_te('Filter the photos')?>" data-reveal="up">
			<button type="button" class="chip is-active" data-g-filter="all" aria-pressed="true"><?=o5_te('All')?> <small><?=$g_total?></small></button>
			<?php foreach($g_count as $group => $n){ if(!$n) continue; ?>
			<button type="button" class="chip" data-g-filter="<?=$group?>" aria-pressed="false"><?=o5_te($g_names[$group])?> <small><?=$n?></small></button>
			<?php } ?>
		</div>
		<?php } ?>
		<div class="g-grid">
			<?php foreach($g_photos as $i => $photo){ ?>
			<a href="<?=$base.o5_e($photo['src'])?>" class="g-item is-<?=$photo['group']?><?=$i % 11 == 0 ? ' is-big' : ''?>" data-gallery data-group="<?=$photo['group']?>" data-cat="<?=o5_e($photo['cat'])?>" data-reveal="zoom" style="--d:<?=($i % 5) * 70?>ms" data-cursor="<?=o5_te('View')?>">
				<img src="<?=$base.o5_e($photo['thumb'])?>" alt="<?=o5_e($photo['title'])?>" loading="lazy">
				<span class="g-cap"><small><?=o5_e($photo['cat'])?></small><b><?=o5_e($photo['title'])?></b></span>
				<i class="g-zoom" aria-hidden="true"><svg class="ic"><use href="#i-plus"/></svg></i>
			</a>
			<?php } ?>
		</div>
<?php }else{ ?>
<!-- ============ All videos ============ -->
<section class="g-body is-dark">
	<div class="glow glow-b" aria-hidden="true"></div>
	<div class="container">
		<?php if(!count($videos)){ ?><p class="lead g-empty"><?=o5_te('Our videos will be here soon.')?></p><?php } ?>
		<div class="v-grid">
			<?php foreach($videos as $i => $video){
				// one language per page: the Arabic title and text on /ar, the English ones on the English page
				$v_title = $is_ar ? trim((string)@$video['title_ar']) : trim((string)$video['title']);
				$v_text = $is_ar ? trim((string)@$video['description_ar']) : trim((string)$video['description']);
				if($is_ar && $v_title === '' && trim((string)$video['title']) !== '' && o5_t($video['title']) !== $video['title']) $v_title = o5_t($video['title']);
			?>
			<figure class="video-card<?=$video['vertical'] ? ' is-vertical' : ''?>" data-reveal="up" style="--d:<?=($i % 3) * 120?>ms">
				<div class="video-frame" data-cursor="<?=o5_te('Play')?>">
					<iframe src="<?=o5_e($video['src'])?>" title="<?=o5_e($v_title !== '' ? $v_title : o5_t('Video'))?>" loading="lazy" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share" allowfullscreen></iframe>
				</div>
				<?php if($v_title !== '' || $v_text !== ''){ ?>
				<figcaption>
					<?php if($v_title !== ''){ ?><h3><?=o5_e($v_title)?></h3><?php } ?>
					<?php if($v_text !== ''){ ?><p><?=nl2br(o5_e($v_text), false)?></p><?php } ?>
				</figcaption>
				<?php } ?>
			</figure>
			<?php } ?>
		</div>
<?php } ?>
		<div class="g-more" data-reveal="up">
			<?php if($fb_more !== ''){ ?><a href="<?=o5_e($fb_more)?>" target="_blank" rel="noopener" class="btn-solid btn-glow" data-magnetic><svg class="ic"><use href="#i-facebook"/></svg><span><?=o5_te($page === 'photos' ? 'More photos on Facebook' : 'More videos on Facebook')?></span></a><?php } ?>
			<a href="<?=o5_e($page === 'photos' ? $videos_url : $photos_url)?>" class="btn-link<?=$page === 'videos' ? ' btn-link-light' : ''?>"><?=o5_te($page === 'photos' ? 'Watch our videos' : 'See our photos')?><svg class="ic"><use href="#i-arrow"/></svg></a>
			<a href="<?=o5_e($home_url)?>#contact" class="btn-link<?=$page === 'videos' ? ' btn-link-light' : ''?>"><?=o5_te('Contact us')?><svg class="ic"><use href="#i-arrow"/></svg></a>
		</div>
	</div>
</section>
