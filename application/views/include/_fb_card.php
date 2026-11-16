<?php
/*
 * Facebook card shown under Facebook's page plugin until the plugin has loaded (or when it is blocked / blank).
 * It always shows: logo, page name, a grid of real photos, and a link to the Facebook page.
 * Expects: $fb_page (address), $photos (array of array('src' => path, 'title' => text)), $labels (array of texts).
 */
if (!function_exists('fbc_e')) {
	function fbc_e($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
}
$base = base_url();
$photos = array_slice(array_values($photos), 0, 7); // 1 cover + a grid of 3 x 2
$cover = count($photos) ? $photos[0]['src'] : '';
$grid = count($photos) > 1 ? array_slice($photos, 1) : $photos;
$page_path = trim((string)parse_url($fb_page, PHP_URL_PATH), '/');
?>
<a class="fb-card" href="<?=fbc_e($fb_page)?>" target="_blank" rel="noopener" aria-label="<?=fbc_e($labels['aria'])?>">
	<span class="fb-cover"<?php if($cover){ ?> style="background-image:url('<?=fbc_e($base.$cover)?>')"<?php } ?>></span>
	<span class="fb-head">
		<span class="fb-logo"><img src="<?=$base?>images/logo.png" width="300" height="54" alt="Traboulsi Est. logo" loading="lazy"></span>
		<span class="fb-name">
			<strong><?=fbc_e($labels['name'])?></strong>
			<small dir="ltr"><svg class="ic" aria-hidden="true"><use href="#i-facebook"/></svg> facebook.com/<?=fbc_e($page_path)?></small>
		</span>
		<span class="fb-follow"><svg class="ic" aria-hidden="true"><use href="#i-facebook"/></svg><?=fbc_e($labels['follow'])?></span>
	</span>
	<span class="fb-grid">
		<?php foreach($grid as $photo){ ?>
		<span class="fb-photo"><img src="<?=fbc_e($base.$photo['src'])?>" alt="<?=fbc_e($photo['title'])?>" loading="lazy"></span>
		<?php } ?>
	</span>
	<span class="fb-foot"><?=fbc_e($labels['all'])?><svg class="ic" aria-hidden="true"><use href="#i-arrow"/></svg></span>
</a>
