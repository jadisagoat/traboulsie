<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| Home page texts
|--------------------------------------------------------------------------
|
| Every text of the home page that is edited in back_office/home_page and
| stored in the home_page_text table (one row per name).
| The back office form is built from this list, in this order.
|
|	'Section legend' => array(
|		'name' => array('Label', type, 'Hint (optional)'),
|	)
|
| Types: text, textarea, ar (right-to-left), artext (right-to-left textarea), title (each line breaks on large
| screens and [words] show in orange), choice (a list: the 4th item gives the
| options as value => label).
| A new field only needs a line here: it is saved on the next Save Changes.
|
*/
$config['home_page_sections'] = array(
	'Google (browser tab & search results)' => array(
		'seo_title' => array('Page title', 'textarea'),
		'seo_description' => array('Description', 'textarea', 'Shown under the title in Google and when the site is shared on Facebook / WhatsApp'),
		'seo_keywords' => array('Keywords', 'textarea'),
		'ga4_id' => array('Google Analytics 4 ID', 'text', 'The measurement ID from analytics.google.com, like G-XXXXXXXXXX. Leave empty to turn statistics off'),
		'google_verification' => array('Google Search Console code', 'text', 'The content of the verification meta tag given by search.google.com/search-console (HTML tag method). Leave empty if the site is verified another way'),
	),
	'Google: Arabic text on the page (SEO)' => array(
		'seo_section_title' => array('Title of the Arabic block', 'ar', 'This block is shown before Contact on the English page and is what Google reads for Arabic searches. Leave the fields empty to use the built-in texts'),
		'seo_text_ar' => array('Company description in Arabic', 'artext', 'A few paragraphs: what the company manufactures, imports and equips, and where. An empty line starts a new paragraph'),
		'seo_services_ar' => array('What we equip (Arabic)', 'artext', 'One service per line'),
		'seo_faq' => array('Frequently asked questions', 'artext', 'One question per line, the answer after a | sign: question | answer'),
		'seo_services_en' => array('What we equip (English, option 5 design)', 'textarea', 'One service per line, shown in the Services section of the English page'),
		'seo_faq_en' => array('Frequently asked questions (English, option 5 design)', 'textarea', 'One question per line, the answer after a | sign: question | answer'),
	),
	'Top bar' => array(
		'topbar_name_ar' => array('Company name in Arabic', 'ar', 'The address, phones and Facebook / Instagram come from Contact Us'),
	),
	'Hero (top of the page, over the banner images)' => array(
		'hero_eyebrow' => array('Small line above the title', 'text', 'Shown in small capital letters'),
		'hero_title' => array('Title', 'title'),
		'hero_text' => array('Text under the title', 'textarea'),
		'hero_text_ar' => array('Arabic line', 'ar'),
		'hero_title_ar' => array('Arabic title (option 5 design)', 'ar', 'Shown on the Arabic page instead of the English title. [words] show in red'),
		'hero_button_1' => array('First button (opens the products)', 'text'),
		'hero_button_2' => array('Second button (opens the contact form)', 'text'),
	),
	'Key figures (under the hero)' => array(
		'founded_year' => array('Year founded', 'text', 'Used for the years of experience and the badge in About'),
		'stat_years_label' => array('Years of experience: label', 'text'),
		'stat_products_number' => array('Products: number', 'text', 'Leave empty to count the products automatically'),
		'stat_products_label' => array('Products: label', 'text'),
		'stat_sectors_number' => array('Sectors: number', 'text', 'Leave empty to count the categories automatically'),
		'stat_sectors_label' => array('Sectors: label', 'text'),
		'stat_clients_number' => array('Clients: number', 'text', 'Leave empty to count the clients automatically'),
		'stat_clients_label' => array('Clients: label', 'text'),
	),
	'What we equip (the categories)' => array(
		'solutions_eyebrow' => array('Small line above the title', 'text'),
		'solutions_title' => array('Title', 'text'),
		'solutions_text' => array('Text', 'textarea'),
	),
	'About' => array(
		'about_title' => array('Title', 'text', 'The small line above it and the long text come from About Us'),
		'about_badge_label' => array('Label under the year on the photo', 'text'),
		'feature_1_title' => array('Point 1: title', 'text'),
		'feature_1_text' => array('Point 1: text', 'text'),
		'feature_2_title' => array('Point 2: title', 'text'),
		'feature_2_text' => array('Point 2: text', 'text'),
		'feature_3_title' => array('Point 3: title', 'text'),
		'feature_3_text' => array('Point 3: text', 'text'),
		'feature_4_title' => array('Point 4: title', 'text'),
		'feature_4_text' => array('Point 4: text', 'text'),
	),
	'Products' => array(
		'products_eyebrow' => array('Small line above the title', 'text'),
		'products_title' => array('Title', 'text'),
	),
	'Clients' => array(
		'clients_eyebrow' => array('Small line above the title', 'text'),
		'clients_title' => array('Title', 'text'),
		'clients_text' => array('Text', 'textarea'),
	),
	'Request a quote band' => array(
		'cta_title' => array('Title', 'text'),
		'cta_text' => array('Text', 'textarea'),
		'cta_button' => array('Button', 'text'),
	),
	'Contact' => array(
		'contact_eyebrow' => array('Small line above the title', 'text'),
		'contact_title' => array('Title', 'text'),
		'contact_text' => array('Text', 'textarea'),
		'contact_company' => array('Company name in the contact card', 'text', 'Also used as the company name for Google'),
		'contact_form_title' => array('Title of the form', 'text'),
	),
	'More texts of the home page' => array(
		'services_eyebrow' => array('Services: small line above the title', 'text', 'The 4 numbered services are the 4 points of About. Leave these empty to use the default texts'),
		'services_title' => array('Services: title', 'text'),
		'services_text' => array('Services: text', 'textarea'),
		'showroom_eyebrow' => array('Showroom: small line above the title', 'text', 'The photos are the banner images and the About Us images'),
		'showroom_title' => array('Showroom: title', 'text'),
		'showroom_text' => array('Showroom: text', 'textarea'),
	),
	'Videos & Facebook (option 4 design)' => array(
		'videos_eyebrow' => array('Small line above the title', 'text', 'The videos themselves are added in Videos'),
		'videos_title' => array('Title', 'text'),
		'videos_text' => array('Text', 'textarea'),
		'facebook_page' => array('Facebook page shown on the site', 'text', 'Its latest posts are shown under the videos. Leave empty to use the Facebook link of Contact Us'),
	),
	'Footer' => array(
		'footer_text' => array('Text under the logo', 'textarea', 'Also used as the company description for Google'),
		'footer_text_ar' => array('Arabic line under the logo', 'ar'),
		'footer_copyright' => array('Name after ©', 'text'),
	),
);
