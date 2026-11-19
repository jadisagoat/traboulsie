<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| The content of the website that is edited in the control panel under "Pages & texts":
|   - lists kept in their own table (config/site_records.php): sector pages, guides, banner cards, Arabic translations
|   - single texts kept in home_page_text (config/page_texts.php): pages of the menu, "Why Traboulsi Est.", export page
|   - Arabic name of a product and the texts of a category page: columns added to products and category
| Each table is created and filled with the texts of the config files the first time it is used. When a table cannot be
| created (a database user without that right), rows() gives NULL and the website shows the texts of the config files.
*/
class Site_content extends CI_Model {

	var $ready = array();

	function defs()
	{
		$this->config->load('site_records', TRUE);
		return $this->config->item('site_records', 'site_records');
	}

	function def($type)
	{
		$defs = $this->defs();
		return isset($defs[$type]) ? $defs[$type] : NULL;
	}

	function column_type($field)
	{
		return in_array($field[1], array('slug', 'date', 'choice')) ? 'varchar(190) DEFAULT NULL' : 'text';
	}

	// the table of a list: created with its columns, and filled, the first time; new fields of the config get their column
	function table($type)
	{
		if (isset($this->ready[$type])) return $this->ready[$type];
		$def = $this->def($type);
		if (!$def) return $this->ready[$type] = FALSE;
		$table = $def['table'];
		$debug = $this->db->db_debug;
		$this->db->db_debug = FALSE; // a refused CREATE / ALTER must not stop the page
		if (!$this->db->table_exists($table)) {
			$cols = '';
			foreach ($def['fields'] as $name => $field) $cols .= "`".$name."` ".$this->column_type($field).",\n";
			$this->db->query("CREATE TABLE IF NOT EXISTS `".$table."` (
				`id` int NOT NULL AUTO_INCREMENT,
				`deleted` int NOT NULL DEFAULT 0,
				`created_date` datetime DEFAULT NULL,
				`updated_date` datetime DEFAULT NULL,
				`sort_order` int NOT NULL DEFAULT 0,
				".$cols."PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8");
			$this->db->data_cache = array(); // the list of tables was read before the table existed
			$ok = $this->db->table_exists($table);
			if ($ok) $this->seed($type);
		} else {
			$ok = TRUE;
			$have = $this->db->list_fields($table);
			foreach ($def['fields'] as $name => $field) {
				if (!in_array($name, $have)) $this->db->query("ALTER TABLE `".$table."` ADD `".$name."` ".$this->column_type($field));
			}
		}
		$this->db->db_debug = $debug;
		return $this->ready[$type] = $ok;
	}

	// the lines of a list in their order, or NULL when its table is not there
	function rows($type)
	{
		if (!$this->table($type)) return NULL;
		$def = $this->def($type);
		return $this->db->where('deleted', 0)->order_by('sort_order, id')->get($def['table'])->result_array();
	}

	// ---------- first content of each list: what the website showed before the lists were in the database ----------
	function seed($type)
	{
		$def = $this->def($type);
		$rows = array();
		if ($type == 'sectors') {
			$this->config->load('sectors', TRUE);
			foreach ((array)$this->config->item('sector_pages', 'sectors') as $slug => $s) {
				$row = array('slug' => $slug, 'icon' => $s['icon'], 'products_words' => $s['products'], 'clients_words' => $s['clients'], 'project_key' => $s['project']);
				foreach (array('en', 'ar') as $l) {
					$t = $s[$l];
					$row['name_'.$l] = $t['name'];
					$row['title_'.$l] = $t['title'];
					$row['description_'.$l] = $t['description'];
					$row['h1_'.$l] = $t['h1'];
					$row['lead_'.$l] = $t['lead'];
					$row['text_'.$l] = implode("\n\n", $t['text']);
					$row['supply_'.$l] = implode("\n", $t['supply']);
					$faq = array();
					foreach ($t['faq'] as $qa) $faq[] = $qa[0].' | '.$qa[1];
					$row['faq_'.$l] = implode("\n", $faq);
				}
				$rows[] = $row;
			}
		} elseif ($type == 'guides') {
			$this->config->load('guides', TRUE);
			foreach ((array)$this->config->item('guides', 'guides') as $slug => $g) {
				$row = array('slug' => $slug, 'date' => $g['date'], 'sector' => $g['sector']);
				foreach (array('en', 'ar') as $l) {
					$t = $g[$l];
					$row['title_'.$l] = $t['title'];
					$row['description_'.$l] = $t['description'];
					$row['h1_'.$l] = $t['h1'];
					$row['lead_'.$l] = $t['lead'];
					$body = array();
					foreach ($t['sections'] as $section) {
						$body[] = '## '.$section[0];
						foreach ($section[1] as $p) $body[] = $p;
						foreach ($section[2] as $item) $body[] = '- '.$item;
						$body[] = '';
					}
					$row['body_'.$l] = trim(implode("\n", $body));
				}
				$rows[] = $row;
			}
		} elseif ($type == 'hero_cards') {
			$reel = function($file, $en, $ar){ return array('type' => 'video', 'file' => 'images/hero-reels/'.$file.'.mp4', 'poster' => 'images/hero-reels/'.$file.'.jpg', 'label_en' => $en, 'label_ar' => $ar, 'link' => '#videos'); };
			if (is_file('images/hero-reels/laser.mp4')) $rows[] = $reel('laser', 'Laser cutting', 'القصّ بالليزر');
			if (is_file('uploads/products/600x455/complete-set-179.jpg')) $rows[] = array('type' => 'photo', 'file' => 'uploads/products/600x455/complete-set-179.jpg', 'poster' => '', 'label_en' => 'Cooking lines', 'label_ar' => 'خطوط الطبخ', 'link' => 'product/complete-cooking-line-179');
			if (is_file('images/hero-reels/team.jpg')) $rows[] = array('type' => 'portrait', 'file' => 'images/hero-reels/team.jpg', 'poster' => '', 'label_en' => 'Our team', 'label_ar' => 'فريقنا', 'link' => 'about');
			if (is_file('images/hero-reels/grills.mp4')) $rows[] = $reel('grills', 'Grills & hoods', 'الشوايات والهود');
			if (is_file('images/hero-reels/finishing.mp4')) $rows[] = $reel('finishing', 'Finishing', 'التشطيب');
		} elseif ($type == 'translations') {
			$this->config->load('site_lang', TRUE);
			foreach ((array)$this->config->item('site_lang_ar', 'site_lang') as $en => $ar) $rows[] = array('en' => $en, 'ar' => $ar);
		}
		$now = date('Y-m-d H:i:s');
		foreach ($rows as $i => $row) {
			$row['sort_order'] = $i;
			$row['created_date'] = $now;
			$row['updated_date'] = $now;
			$this->db->insert($def['table'], $row);
		}
	}

	// ---------- Arabic name of a product, texts of a category page: columns of the old tables ----------
	function upgrade()
	{
		if (isset($this->ready['#columns'])) return;
		$this->ready['#columns'] = TRUE;
		$debug = $this->db->db_debug;
		$this->db->db_debug = FALSE;
		if (!$this->db->field_exists('title_ar', 'products')) {
			$this->db->query("ALTER TABLE `products` ADD `title_ar` varchar(250) DEFAULT NULL");
			$this->db->data_cache = array();
			if ($this->db->field_exists('title_ar', 'products')) {
				// today's Arabic names: the type of the product from config/site_lang.php and its number
				$this->config->load('site_lang', TRUE);
				$types = (array)$this->config->item('site_products_ar', 'site_lang');
				foreach ($this->db->select('id_products, title')->get('products')->result_array() as $p) {
					$title = preg_replace('/\s+/', ' ', trim(html_entity_decode((string)$p['title'], ENT_QUOTES, 'UTF-8')));
					$num = preg_match('/\s(\d{1,4})\s*$/', $title, $m) ? $m[1] : '';
					$type = trim($num === '' ? $title : substr($title, 0, -strlen($m[0])));
					foreach ($types as $en => $ar) {
						if (strcasecmp($en, $type) == 0) { $this->db->where('id_products', $p['id_products'])->update('products', array('title_ar' => trim($ar.' '.$num))); break; }
					}
				}
			}
		}
		if (!$this->db->field_exists('h1_en', 'category')) {
			$this->db->query("ALTER TABLE `category` ADD `h1_en` varchar(250) DEFAULT NULL, ADD `h1_ar` varchar(250) DEFAULT NULL, ADD `text_en` text, ADD `text_ar` text");
			$this->db->data_cache = array();
			if ($this->db->field_exists('h1_en', 'category')) {
				$this->config->load('sectors', TRUE);
				$texts = (array)$this->config->item('category_pages', 'sectors');
				foreach ($this->db->select('id_category, title')->get('category')->result_array() as $c) {
					$slug = o5_slug($c['title']);
					if (isset($texts[$slug])) $this->db->where('id_category', $c['id_category'])->update('category', array('h1_en' => $texts[$slug]['en'][1], 'text_en' => $texts[$slug]['en'][0], 'h1_ar' => $texts[$slug]['ar'][1], 'text_ar' => $texts[$slug]['ar'][0]));
				}
			}
		}
		$this->db->db_debug = $debug;
	}

	// ---------- single texts (home_page_text): the fields of the form and the text of each ----------
	// 'Legend' => 'name' => array('Label', type, 'Hint', 'built-in text')
	function page_text_sections()
	{
		$this->config->load('page_texts', TRUE);
		$this->config->load('sectors', TRUE);
		$sections = array();
		$langs = array('en' => 'English', 'ar' => 'Arabic');
		$names = array('products' => 'Products', 'services' => 'Services', 'about' => 'About', 'clients' => 'Our clients', 'projects' => 'Projects', 'faq' => 'FAQ', 'contact' => 'Contact');
		$pages = (array)$this->config->item('section_defaults', 'page_texts');
		foreach ($names as $key => $name) {
			$fields = array();
			foreach ($langs as $l => $lang) {
				$t = $pages[$key][$l];
				$rtl = $l == 'ar';
				$fields['sec_'.$key.'_name_'.$l] = array('Name in the menu ('.$lang.')', $rtl ? 'ar' : 'text', '', $t[0]);
				$fields['sec_'.$key.'_h1_'.$l] = array('Title of the page ('.$lang.')', $rtl ? 'ar' : 'text', '', $t[1]);
				$fields['sec_'.$key.'_lead_'.$l] = array('Line under the title ('.$lang.')', $rtl ? 'artext' : 'textarea', 'Also the description shown in Google', $t[2]);
				$fields['sec_'.$key.'_title_'.$l] = array('Google title ('.$lang.')', $rtl ? 'ar' : 'text', '', $t[3]);
			}
			$sections['Page: '.$name.' (yoursite/'.$key.')'] = $fields;
		}
		$why = (array)$this->config->item('why_defaults', 'page_texts');
		$fields = array();
		foreach ($langs as $l => $lang) {
			$rtl = $l == 'ar';
			$fields['why_eyebrow_'.$l] = array('Small line above the title ('.$lang.')', $rtl ? 'ar' : 'text', '', $why[$l]['eyebrow']);
			$fields['why_title_'.$l] = array('Title ('.$lang.')', $rtl ? 'ar' : 'text', '', $why[$l]['title']);
			foreach ($why[$l]['items'] as $i => $item) {
				$fields['why_'.($i + 1).'_title_'.$l] = array('Point '.($i + 1).': title ('.$lang.')', $rtl ? 'ar' : 'text', '{year} is replaced by the year founded', $item[0]);
				$fields['why_'.($i + 1).'_text_'.$l] = array('Point '.($i + 1).': text ('.$lang.')', $rtl ? 'artext' : 'textarea', '{years} is replaced by the years of experience', $item[1]);
			}
			$fields['why_more_'.$l] = array('Link to the videos ('.$lang.')', $rtl ? 'ar' : 'text', '', $why[$l]['more']);
		}
		$sections['Block "Why Traboulsi Est." (shown on the sector, product, category and menu pages)'] = $fields;
		$export = (array)$this->config->item('export_page', 'sectors');
		$fields = array();
		foreach ($langs as $l => $lang) {
			$t = $export[$l];
			$rtl = $l == 'ar';
			$one = $rtl ? 'ar' : 'text';
			$many = $rtl ? 'artext' : 'textarea';
			$steps = array();
			foreach ($t['steps'] as $step) $steps[] = $step[0].' | '.$step[1];
			$faq = array();
			foreach ($t['faq'] as $qa) $faq[] = $qa[0].' | '.$qa[1];
			$fields['export_name_'.$l] = array('Name in the menu ('.$lang.')', $one, '', $t['name']);
			$fields['export_h1_'.$l] = array('Title of the page ('.$lang.')', $one, '', $t['h1']);
			$fields['export_lead_'.$l] = array('Line under the title ('.$lang.')', $many, '', $t['lead']);
			$fields['export_text_'.$l] = array('Text ('.$lang.')', $many, 'An empty line starts a new paragraph', implode("\n\n", $t['text']));
			$fields['export_regions_title_'.$l] = array('Title of the regions ('.$lang.')', $one, '', $t['regions_title']);
			$fields['export_regions_'.$l] = array('Regions ('.$lang.')', $many, 'One per line', implode("\n", $t['regions']));
			$fields['export_steps_title_'.$l] = array('Title of the steps ('.$lang.')', $one, '', $t['steps_title']);
			$fields['export_steps_'.$l] = array('Steps ('.$lang.')', $many, 'One step per line, its text after a | sign: step | text', implode("\n", $steps));
			$fields['export_faq_'.$l] = array('Questions ('.$lang.')', $many, 'One question per line, the answer after a | sign: question | answer', implode("\n", $faq));
			$fields['export_title_'.$l] = array('Google title ('.$lang.')', $one, '', $t['title']);
			$fields['export_description_'.$l] = array('Google description ('.$lang.')', $many, '', $t['description']);
		}
		$sections['Page: Delivery & export (yoursite/export)'] = $fields;
		return $sections;
	}

	// every single text as name => text: the one typed in the control panel, the built-in one when it is empty
	function page_texts()
	{
		if (isset($this->ready['#texts'])) return $this->ready['#texts'];
		$texts = array();
		foreach ($this->page_text_sections() as $fields) {
			foreach ($fields as $name => $field) $texts[$name] = $field[3];
		}
		$debug = $this->db->db_debug;
		$this->db->db_debug = FALSE;
		$query = $this->db->like('name', 'sec_', 'after')->or_like('name', 'why_', 'after')->or_like('name', 'export_', 'after')->get('home_page_text');
		$this->db->db_debug = $debug;
		if ($query) {
			foreach ($query->result_array() as $row) {
				if (isset($texts[$row['name']]) && trim((string)$row['value']) !== '') $texts[$row['name']] = (string)$row['value'];
			}
		}
		return $this->ready['#texts'] = $texts;
	}

	// ---------- the lists as the website reads them ----------

	// "fryer|grill, pizza oven" typed in the control panel as a safe pattern for the names of the products
	function words($text)
	{
		$words = array();
		foreach (preg_split('/[|,\n]+/u', (string)$text) as $word) {
			$word = trim($word);
			if ($word !== '') $words[] = preg_quote($word, '/');
		}
		return count($words) ? implode('|', $words) : '(?!)';
	}

	function lines($text)
	{
		return preg_split('/\s*\R\s*/u', trim((string)$text), -1, PREG_SPLIT_NO_EMPTY);
	}

	function pairs($text)
	{
		$out = array();
		foreach ($this->lines($text) as $line) {
			if (strpos($line, '|') === FALSE) continue;
			list($a, $b) = array_map('trim', explode('|', $line, 2));
			if ($a !== '' && $b !== '') $out[] = array($a, $b);
		}
		return $out;
	}

	// the Arabic text of a line, its English text when the Arabic one is empty
	function pick($row, $name, $l)
	{
		$value = isset($row[$name.'_'.$l]) ? trim((string)$row[$name.'_'.$l]) : '';
		return $value !== '' || $l == 'en' ? $value : trim((string)$row[$name.'_en']);
	}

	// the sector pages: address => page, in the shape of config/sectors.php
	function sectors()
	{
		$rows = $this->rows('sectors');
		if ($rows === NULL) {
			$this->config->load('sectors', TRUE);
			$sectors = (array)$this->config->item('sector_pages', 'sectors');
			foreach ($sectors as $slug => $sector) $sectors[$slug]['path'] = $slug.'-equipment';
			return $sectors;
		}
		$sectors = array();
		foreach ($rows as $row) {
			$slug = o5_slug($row['slug']);
			if ($slug === '') continue;
			$sector = array('path' => $slug.'-equipment', 'icon' => $row['icon'] != '' ? $row['icon'] : 'layers', 'project' => (string)$row['project_key'], 'products' => $this->words($row['products_words']), 'clients' => $this->words($row['clients_words']));
			foreach (array('en', 'ar') as $l) {
				$name = $this->pick($row, 'name', $l);
				$h1 = $this->pick($row, 'h1', $l);
				$lead = $this->pick($row, 'lead', $l);
				$title = $this->pick($row, 'title', $l);
				$description = $this->pick($row, 'description', $l);
				$sector[$l] = array(
					'name' => $name,
					'h1' => $h1 !== '' ? $h1 : $name,
					'lead' => $lead,
					'title' => $title !== '' ? $title : $h1.($l == 'ar' ? ' | مؤسسة طرابلسي' : ' | Traboulsi Est.'),
					'description' => $description !== '' ? $description : $lead,
					'text' => preg_split('/\s*\R\s*\R\s*/u', $this->pick($row, 'text', $l), -1, PREG_SPLIT_NO_EMPTY),
					'supply' => $this->lines($this->pick($row, 'supply', $l)),
					'faq' => $this->pairs($this->pick($row, 'faq', $l)),
				);
			}
			$sectors[$slug] = $sector;
		}
		return $sectors;
	}

	// the guides: address => guide, in the shape of config/guides.php
	function guides()
	{
		$rows = $this->rows('guides');
		if ($rows === NULL) {
			$this->config->load('guides', TRUE);
			return (array)$this->config->item('guides', 'guides');
		}
		$guides = array();
		foreach ($rows as $row) {
			$slug = o5_slug($row['slug']);
			if ($slug === '') continue;
			$guide = array('date' => preg_match('/^\d{4}-\d{2}-\d{2}$/', (string)$row['date']) ? $row['date'] : substr((string)$row['created_date'], 0, 10), 'sector' => o5_slug($row['sector']));
			foreach (array('en', 'ar') as $l) {
				$h1 = $this->pick($row, 'h1', $l);
				$lead = $this->pick($row, 'lead', $l);
				$title = $this->pick($row, 'title', $l);
				$description = $this->pick($row, 'description', $l);
				// "## heading", "- point", paragraph
				$sections = array();
				foreach ($this->lines($this->pick($row, 'body', $l)) as $line) {
					if (strpos($line, '##') === 0) { $sections[] = array(trim(ltrim($line, '#')), array(), array()); continue; }
					if (!count($sections)) $sections[] = array('', array(), array());
					$last = count($sections) - 1;
					if (preg_match('/^[-•]\s*(.+)$/u', $line, $m)) $sections[$last][2][] = $m[1]; else $sections[$last][1][] = $line;
				}
				$guide[$l] = array('h1' => $h1, 'lead' => $lead, 'title' => $title !== '' ? $title : $h1.($l == 'ar' ? ' | مؤسسة طرابلسي' : ' | Traboulsi Est.'), 'description' => $description !== '' ? $description : $lead, 'sections' => $sections);
			}
			$guides[$slug] = $guide;
		}
		return $guides;
	}

	// English text => Arabic text: the built-in dictionary with the lines of the control panel on top of it
	function dictionary()
	{
		$this->config->load('site_lang', TRUE);
		$dict = (array)$this->config->item('site_lang_ar', 'site_lang');
		$rows = $this->rows('translations');
		foreach ((array)$rows as $row) {
			$en = trim(preg_replace('/\s+/u', ' ', (string)$row['en']));
			if ($en !== '' && trim((string)$row['ar']) !== '') $dict[$en] = trim((string)$row['ar']);
		}
		return $dict;
	}

	// the page yoursite/export in the shape of config/sectors.php (export_page)
	function export_page($l)
	{
		$t = $this->page_texts();
		$page = array();
		foreach (array('name', 'h1', 'lead', 'regions_title', 'steps_title', 'title', 'description') as $name) $page[$name] = $t['export_'.$name.'_'.$l];
		$page['text'] = preg_split('/\s*\R\s*\R\s*/u', trim($t['export_text_'.$l]), -1, PREG_SPLIT_NO_EMPTY);
		$page['regions'] = $this->lines($t['export_regions_'.$l]);
		$page['steps'] = $this->pairs($t['export_steps_'.$l]);
		$page['faq'] = $this->pairs($t['export_faq_'.$l]);
		return $page;
	}
}
