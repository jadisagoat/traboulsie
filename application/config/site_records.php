<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| Content kept in its own table and edited in the control panel under "Pages & texts"
| (controllers/back_office/records.php, models/site_content.php).
|
|	'address in the control panel' => array(
|		'table'    => its table (created and filled with today's content the first time it is used)
|		'title'    => name of the list, 'one' => name of one line
|		'help'     => text shown above the list
|		'list'     => the two fields shown in the list
|		'required' => fields that cannot be empty
|		'fields'   => 'name' => array('Label', type, 'Hint', options of a choice)
|	)
| Types: text, ar (right-to-left), textarea, artext, slug (an address: small letters and -), date, choice, file.
| A new field only needs a line here: its column is added to the table by itself.
*/
$icons = array('utensils' => 'Fork and knife', 'building' => 'Building', 'cart' => 'Shopping cart', 'knife' => 'Knife', 'bread' => 'Bread', 'coffee' => 'Cup', 'medical' => 'Medical cross', 'fridge' => 'Fridge', 'flame' => 'Flame', 'layers' => 'Layers', 'cake' => 'Cake', 'icecream' => 'Ice cream', 'wrench' => 'Wrench', 'truck' => 'Truck', 'globe' => 'Globe');
$project_keys = array('' => '—', 'restaurants' => 'Restaurants', 'bakeries' => 'Bakeries & pastry shops', 'butchers' => 'Butcher shops', 'supermarkets' => 'Supermarkets', 'cafes' => 'Cafés & coffee shops', 'hotels' => 'Hotels', 'hospitals' => 'Hospitals', 'other' => 'Other projects');
$lines = 'One per line';
$faq = 'One question per line, the answer after a | sign: question | answer';

$config['site_records'] = array(

	'sectors' => array(
		'table' => 'site_sectors',
		'title' => 'Sector pages',
		'one' => 'sector page',
		'help' => 'One page of the website per sector: yoursite/<b>address</b>-equipment (and yoursite/ar/…). They are listed in the Services menu, on the home page and in the footer, in this order. Drag the rows to change the order.',
		'list' => array('name_en', 'slug'),
		'required' => array('slug', 'name_en', 'h1_en'),
		'fields' => array(
			'slug' => array('Address of the page', 'slug', 'Small English letters and - only. "restaurant" gives yoursite/restaurant-equipment. Changing it changes the address Google knows'),
			'icon' => array('Icon', 'choice', '', $icons),
			'products_words' => array('Products shown on the page', 'textarea', 'Words found in the names of the products, separated by | . Example: fryer|grill|pizza oven. At most two products of each type are shown, sixteen in all'),
			'clients_words' => array('Clients shown on the page', 'text', 'Words found in the titles of the groups of Our clients, separated by | . Example: restaurant'),
			'project_key' => array('Projects shown on the page', 'choice', 'The completed projects of this sector', $project_keys),
			'name_en' => array('Name (English)', 'text', 'Short, used in the menus: Restaurants'),
			'name_ar' => array('Name (Arabic)', 'ar'),
			'h1_en' => array('Title of the page (English)', 'text'),
			'h1_ar' => array('Title of the page (Arabic)', 'ar'),
			'lead_en' => array('Line under the title (English)', 'textarea'),
			'lead_ar' => array('Line under the title (Arabic)', 'artext'),
			'text_en' => array('Text (English)', 'textarea', 'An empty line starts a new paragraph'),
			'text_ar' => array('Text (Arabic)', 'artext', 'An empty line starts a new paragraph'),
			'supply_en' => array('What we supply (English)', 'textarea', $lines),
			'supply_ar' => array('What we supply (Arabic)', 'artext', $lines),
			'faq_en' => array('Questions (English)', 'textarea', $faq),
			'faq_ar' => array('Questions (Arabic)', 'artext', $faq),
			'title_en' => array('Google title (English)', 'text', 'Shown in the browser tab and in the search results'),
			'title_ar' => array('Google title (Arabic)', 'ar'),
			'description_en' => array('Google description (English)', 'textarea'),
			'description_ar' => array('Google description (Arabic)', 'artext'),
		),
	),

	'guides' => array(
		'table' => 'site_guides',
		'title' => 'Guides (articles)',
		'one' => 'guide',
		'help' => 'The articles of yoursite/guides. Each one has its page: yoursite/guides/<b>address</b>.',
		'list' => array('h1_en', 'slug'),
		'required' => array('slug', 'h1_en', 'body_en'),
		'fields' => array(
			'slug' => array('Address of the page', 'slug', 'Small English letters and - only: restaurant-kitchen-checklist'),
			'date' => array('Date', 'date', 'The day it was written, like 2026-11-19'),
			'sector' => array('Sector page linked at the end', 'text', 'The address of a sector page (restaurant, supermarket …). Leave empty for none'),
			'h1_en' => array('Title (English)', 'text'),
			'h1_ar' => array('Title (Arabic)', 'ar'),
			'lead_en' => array('Introduction (English)', 'textarea'),
			'lead_ar' => array('Introduction (Arabic)', 'artext'),
			'body_en' => array('Article (English)', 'textarea', 'A line starting with ## is a heading, a line starting with - is a point of a list, any other line is a paragraph'),
			'body_ar' => array('Article (Arabic)', 'artext', 'A line starting with ## is a heading, a line starting with - is a point of a list, any other line is a paragraph'),
			'title_en' => array('Google title (English)', 'text'),
			'title_ar' => array('Google title (Arabic)', 'ar'),
			'description_en' => array('Google description (English)', 'textarea', 'Also shown under the title in the list of guides'),
			'description_ar' => array('Google description (Arabic)', 'artext'),
		),
	),

	'hero_cards' => array(
		'table' => 'site_hero_cards',
		'title' => 'Banner cards',
		'one' => 'card',
		'help' => 'The cards that turn in the banner of the home page, in this order (drag the rows to change it). The card in the middle of the list is the one in front when the page opens. Use an odd number of cards: 3, 5 or 7.',
		'list' => array('label_en', 'type'),
		'required' => array('label_en'),
		'fields' => array(
			'type' => array('Kind of card', 'choice', 'Video: a short silent loop. Photo of a product: shown whole on white. Photo that fills the card: a person, the factory, a project', array('video' => 'Video', 'photo' => 'Photo of a product (white background)', 'portrait' => 'Photo that fills the card')),
			'file' => array('Video or photo', 'file', 'mp4 video (short, without sound, about 320 × 392, under 2 MB) or jpg / png / webp photo. Tall format, like a phone screen'),
			'poster' => array('Still picture of the video', 'file', 'Only for a video: a jpg of its first second, shown while it loads and blended in the background'),
			'label_en' => array('Label (English)', 'text'),
			'label_ar' => array('Label (Arabic)', 'ar'),
			'link' => array('Opens', 'text', 'Where the card in front leads: #videos or #about for a part of the home page, or the address of a page such as product/gas-fryer-177, restaurant-equipment, about'),
		),
	),

	'translations' => array(
		'table' => 'site_translations',
		'title' => 'Arabic translations',
		'one' => 'translation',
		'help' => 'Every English word or sentence of the website and its Arabic version: buttons, menus, names of clients and categories. A text written in English in the control panel (a new client, a new category) is shown in Arabic on the Arabic pages once it has a line here, written exactly like the English text.',
		'list' => array('en', 'ar'),
		'required' => array('en', 'ar'),
		'fields' => array(
			'en' => array('English text', 'textarea', 'Exactly as it is written on the English page'),
			'ar' => array('Arabic text', 'artext'),
		),
	),
);
