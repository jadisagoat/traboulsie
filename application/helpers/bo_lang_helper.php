<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| Language of the control panel (back_office): English by default, Arabic when chosen
| with the switch in the top bar (back_office/lang/set/ar). The choice is kept in a cookie.
|
| The pages are written in English. When Arabic is on, hooks/Security_guard.php passes
| every control panel page through bo_translate_html(), which swaps the English texts
| listed in language/arabic/back_office_lang.php and leaves everything else as it is.
*/

// 'ar' or 'en'
function bo_lang()
{
	return (isset($_COOKIE['bo_lang']) && $_COOKIE['bo_lang'] === 'ar') ? 'ar' : 'en';
}

function bo_is_ar()
{
	return bo_lang() === 'ar';
}

function bo_dictionary()
{
	static $dict = NULL;
	if ($dict === NULL)
	{
		$dict = include(APPPATH.'language/arabic/back_office_lang.php');
		$dict['lower'] = array();
		foreach ($dict['words'] as $en => $ar)
		{
			$key = strtolower($en);
			if ( ! isset($dict['lower'][$key])) $dict['lower'][$key] = $ar;
		}
	}
	return $dict;
}

// the Arabic of an English text (plain text in, plain text out), or the text itself
function bo_word($text)
{
	$dict = bo_dictionary();
	if (isset($dict['words'][$text])) return $dict['words'][$text];
	$key = strtolower($text);
	if (isset($dict['lower'][$key])) return $dict['lower'][$key];
	return NULL;
}

// for the views: bo_t('Export') gives "Export" in English and "تصدير" in Arabic.
// A key may hold HTML (like 'videos_help'); $english is then what shows in English.
function bo_t($text, $english = NULL)
{
	if (bo_is_ar())
	{
		$ar = bo_word($text);
		if ($ar !== NULL) return $ar;
	}
	return $english === NULL ? $text : $english;
}

// one text of the page (already decoded) -> its Arabic, or NULL when unknown
function bo_translate_text($text)
{
	$ar = bo_word($text);
	if ($ar !== NULL) return $ar;

	// "Phone:" / "Title *" -> translate "Phone" / "Title" and keep the end
	if (preg_match('/^(.*?)([\s:*]+)$/su', $text, $m) && $m[1] !== '')
	{
		$ar = bo_word($m[1]);
		if ($ar !== NULL) return $ar.$m[2];
	}

	$dict = bo_dictionary();
	foreach ($dict['patterns'] as $regex => $arabic)
	{
		if (preg_match($regex, $text, $m))
		{
			for ($i = 1; $i < count($m); $i++)
			{
				$part = bo_word($m[$i]);
				$arabic = str_replace('{'.$i.'}', $part !== NULL ? $part : $m[$i], $arabic);
			}
			return $arabic;
		}
	}
	return NULL;
}

// a whole control panel page: texts between tags and title / placeholder attributes.
// Scripts, styles and the content of text boxes are not touched (that is the website's content).
function bo_translate_html($html)
{
	$dict = bo_dictionary();
	$html = strtr($html, $dict['js']);

	$parts = preg_split('#(<script\b.*?</script>|<style\b.*?</style>|<textarea\b.*?</textarea>)#is', $html, -1, PREG_SPLIT_DELIM_CAPTURE);
	foreach ($parts as $i => $part)
	{
		if ($i % 2) continue; // a script, a style or a text box

		// texts between tags
		$part = preg_replace_callback('#>([^<>]*[A-Za-z][^<>]*)<#', function ($m) {
			$raw = $m[1];
			$text = str_replace("\xC2\xA0", ' ', html_entity_decode($raw, ENT_QUOTES, 'UTF-8'));
			$core = trim($text);
			if ($core === '') return $m[0];
			$ar = bo_translate_text(preg_replace('/\s+/u', ' ', $core));
			if ($ar === NULL) return $m[0];
			// keep the spaces around the text
			preg_match('/^(\s*)/u', $text, $lead);
			preg_match('/(\s*)$/u', $text, $trail);
			return '>'.$lead[1].htmlspecialchars($ar, ENT_QUOTES, 'UTF-8').$trail[1].'<';
		}, $part);

		// tooltips and placeholders
		$part = preg_replace_callback('#\b(title|placeholder|aria-label)="([^"]*[A-Za-z][^"]*)"#', function ($m) {
			$ar = bo_translate_text(trim(html_entity_decode($m[2], ENT_QUOTES, 'UTF-8')));
			return $ar === NULL ? $m[0] : $m[1].'="'.htmlspecialchars($ar, ENT_QUOTES, 'UTF-8').'"';
		}, $part);

		$parts[$i] = $part;
	}
	return implode('', $parts);
}
