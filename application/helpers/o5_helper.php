<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Small helpers of the site design (views/option5): escaping, the Arabic version of a label, product names, addresses of the pages. */
if(!function_exists('o5_e')){
	function o5_e($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8', false); }
	// the Arabic version of a label on the Arabic page, the label itself otherwise
	function o5_t($s){
		$s = (string)$s;
		if(empty($GLOBALS['o5_lang']) || $GLOBALS['o5_lang'] != 'ar') return $s;
		$k = trim(preg_replace('/\s+/u', ' ', html_entity_decode($s, ENT_QUOTES, 'UTF-8')));
		return isset($GLOBALS['o5_dict'][$k]) ? $GLOBALS['o5_dict'][$k] : $s;
	}
	// translated + escaped
	function o5_te($s){ return o5_e(o5_t($s)); }
	// product name on the Arabic page: "Showcase Fridge 108" -> "برّاد عرض 108"
	function o5_pname($s){
		if(empty($GLOBALS['o5_lang']) || $GLOBALS['o5_lang'] != 'ar') return $s;
		// the Arabic name typed in the control panel, then the built-in name of the type of product
		$key = strtolower(preg_replace('/\s+/', ' ', trim((string)$s)));
		if(!empty($GLOBALS['o5_ptitles'][$key])) return $GLOBALS['o5_ptitles'][$key];
		$num = preg_match('/\s(\d{1,4})\s*$/', $s, $m) ? $m[1] : '';
		$type = trim($num === '' ? $s : substr($s, 0, -strlen($m[0])));
		foreach($GLOBALS['o5_pdict'] as $en => $ar){ if(strcasecmp($en, $type) == 0) return trim($ar.' '.$num); }
		return $s;
	}
	function o5_title($s){
		$s = ucwords(strtolower(preg_replace('/\s+/', ' ', trim(html_entity_decode($s, ENT_QUOTES, 'UTF-8')))));
		return preg_replace_callback('/(?<=\s)(And|Or|Of|For|The)(?=\s)/', function($m){ return strtolower($m[1]); }, $s);
	}
	function o5_name($s){ return ucfirst(preg_replace('/\s+/', ' ', trim(html_entity_decode($s, ENT_QUOTES, 'UTF-8')))); }
	function o5_icon($title, $map, $default){
		$t = strtolower($title);
		foreach($map as $needle => $icon){ if(preg_match('/'.$needle.'/', $t)) return $icon; }
		return $default;
	}
	// a back office text (translated on the Arabic page) inside its tag, or nothing when empty
	function o5_tag($open, $text, $close){ return trim($text) === '' ? '' : $open.o5_te($text).$close; }
	function o5_or($text, $default){ return trim($text) === '' ? $default : $text; }
	function o5_num($typed, $auto){ $n = preg_replace('/\D/', '', $typed); return $n === '' ? $auto : (int)$n; }
	// hero title: one line per line typed in the back office, each rising from behind a mask, [words] in red
	function o5_lines($s){
		$out = '';
		foreach(preg_split('/\s*\R\s*/u', trim($s)) as $i => $line){
			$line = preg_replace('/\[(.+?)\]/su', '<em>$1</em>', o5_te($line));
			$out .= '<span class="line"><span style="--i:'.$i.'">'.$line.'</span></span> ';
		}
		return $out;
	}
	function o5_lines_split($text){ return preg_split('/\s*\R\s*/u', trim((string)$text), -1, PREG_SPLIT_NO_EMPTY); }
	function o5_faq($text){
		$out = array();
		foreach(o5_lines_split($text) as $line){
			if(strpos($line, '|') === FALSE) continue;
			list($q, $a) = array_map('trim', explode('|', $line, 2));
			if($q !== '' && $a !== '') $out[] = array($q, $a);
		}
		return $out;
	}
	// "Showcase Fridge 108" -> "showcase-fridge-108": the address of a product, a category or a page
	function o5_slug($s){
		$s = strtolower(trim(html_entity_decode(strip_tags((string)$s), ENT_QUOTES, 'UTF-8')));
		return trim(preg_replace('/[^a-z0-9]+/', '-', $s), '-');
	}
	// the address of a product, never the same twice: a second "Gas Stove" becomes gas-stove-84 (84 = its number in the database)
	function o5_product_slug($title, $id, &$used){
		$slug = o5_slug($title);
		if($slug === '' || isset($used[$slug])) $slug = trim($slug.'-'.$id, '-');
		$used[$slug] = 1;
		return $slug;
	}
	// the address of a page in the language being shown: o5_url('product/gas-fryer-177') is yoursite/ar/product/gas-fryer-177 on the Arabic page
	function o5_url($path = ''){
		$ar = !empty($GLOBALS['o5_lang']) && $GLOBALS['o5_lang'] == 'ar';
		return base_url().($ar ? 'ar'.($path === '' ? '' : '/') : '').$path;
	}
}
