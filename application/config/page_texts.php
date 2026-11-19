<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| The built-in texts of the pages edited in the control panel under "Pages & texts" > "Texts of the pages":
| the pages of the menu (yoursite/about, yoursite/faq ...), the block "Why Traboulsi Est." and the page yoursite/export.
| They are stored in the home_page_text table; a field left empty in the control panel shows the text written here.
| The list of fields is built from these texts in models/site_content.php (page_text_sections).
*/

// 'address' => 'ar' / 'en' => name in the menu, title of the page, line under the title, Google title
$config['section_defaults'] = array(
	'products'=>array('ar'=>array('المنتجات','منتجاتنا: معدات المطابخ والتبريد التجاري','برادات، خطوط طبخ، ماكينات تحضير، تجهيزات سوبرماركت وأعمال ستانلس ستيل: كل ما نصنّعه ونورّده، مرتّباً حسب الفئة.','المنتجات: معدات مطابخ وبرادات تجارية وستانلس ستيل | مؤسسة طرابلسي - لبنان'),
		'en'=>array('Products','Our products: commercial kitchen and refrigeration equipment','Refrigerators, cooking lines, preparation machines, supermarket equipment and stainless-steel work: everything we manufacture and supply, sorted by category.','Products: Commercial Kitchen Equipment, Refrigeration & Stainless Steel | Traboulsi Est. Lebanon')),
	'services'=>array('ar'=>array('خدماتنا','خدماتنا: تصنيع وتوريد وتركيب وصيانة','من دراسة المشروع إلى التصنيع والتركيب والصيانة: فريق واحد يجهّز مطعمك أو محلّك أو مطبخ مؤسستك.','خدماتنا: تجهيز مطاعم وفنادق وسوبرماركت، تصنيع وتركيب وصيانة | مؤسسة طرابلسي'),
		'en'=>array('Services','Our services: manufacturing, supply, installation and maintenance','From the study of the project to manufacturing, installation and maintenance: one team equips your restaurant, your shop or the kitchen of your institution.','Services: Kitchen Fit-out, Manufacturing, Installation & Maintenance | Traboulsi Est. Lebanon')),
	'about'=>array('ar'=>array('من نحن','من نحن: مؤسسة طرابلسي للتجارة والصناعة','نصنّع البرادات والشوايات وأعمال الستانلس ستيل في دير الزهراني منذ 1982، ونجهّز المشاريع الغذائية في كل لبنان.','من نحن | مؤسسة طرابلسي للتجارة والصناعة - دير الزهراني، لبنان منذ 1982'),
		'en'=>array('About','About Traboulsi Est. for Trading & Industry','Manufacturing refrigeration, grills and stainless-steel equipment in Deir El Zahrani since 1982, and equipping food businesses all over Lebanon.','About Us | Traboulsi Est. for Trading & Industry, Lebanon, since 1982')),
	'clients'=>array('ar'=>array('عملاؤنا','عملاؤنا: مستشفيات ومطاعم وسوبرماركت جهّزناها','مجموعة من المؤسسات والمحلات التي وثقت بنا في مختلف المناطق اللبنانية.','عملاؤنا: مستشفيات، مطاعم وسوبرماركت جهّزتها مؤسسة طرابلسي في لبنان'),
		'en'=>array('Our clients','Our clients: hospitals, restaurants and supermarkets we equipped','A selection of the institutions and businesses that trusted us across Lebanon.','Our Clients: Hospitals, Restaurants & Supermarkets Equipped by Traboulsi Est. in Lebanon')),
	'projects'=>array('ar'=>array('مشاريعنا','مشاريعنا المنفّذة','مطابخ وأفران وملاحم وسوبرماركت جهّزناها في كل لبنان، من أول رسمة حتى التسليم.','مشاريعنا المنفّذة: مطابخ ومحلات جهّزتها مؤسسة طرابلسي في لبنان'),
		'en'=>array('Projects','Completed projects','Kitchens, bakeries, butcher shops and supermarkets we equipped across Lebanon, from the first drawing to delivery.','Completed Projects: Kitchens & Shops Equipped by Traboulsi Est. in Lebanon')),
	'faq'=>array('ar'=>array('أسئلة شائعة','أسئلة شائعة عن تجهيز المطابخ والمحلات','إجابات عمّا يسأله عملاؤنا كثيراً عن التصنيع والتسليم والصيانة وعروض الأسعار.','أسئلة شائعة: تجهيز المطاعم، التصنيع حسب الطلب، الصيانة | مؤسسة طرابلسي'),
		'en'=>array('FAQ','Frequently asked questions','Answers to what our customers ask most about manufacturing, delivery, maintenance and quotations.','FAQ: Kitchen Equipment, Custom Manufacturing & Maintenance | Traboulsi Est. Lebanon')),
	'contact'=>array('ar'=>array('اتصل بنا','اتصل بنا: اطلب عرض سعر','أرسل لنا رسالة أو زر أحد فرعينا في دير الزهراني وخلدة. فريقنا يتواصل معك قريباً.','اتصل بنا واطلب عرض سعر | مؤسسة طرابلسي - دير الزهراني وخلدة، لبنان'),
		'en'=>array('Contact','Contact us: request a quote','Send us a message or visit one of our two branches, in Deir El Zahrani and Khalde. Our team will get back to you shortly.','Contact Us & Request a Quote | Traboulsi Est., Deir El Zahrani & Khalde, Lebanon')),
);

// the block "Why Traboulsi Est.": {year} = the year founded, {years} = the years since
$config['why_defaults'] = array(
	'en' => array(
		'eyebrow' => 'What sets us apart',
		'title' => 'Why Traboulsi Est.',
		'more' => 'Watch our factory at work',
		'items' => array(
			array('Made in Lebanon', 'Refrigeration, grills and stainless-steel work are built in our own factory in Deir El Zahrani, to the sizes of your project.'),
			array('Since {year}', '{years} years equipping kitchens, shops and hospitals all over Lebanon.'),
			array('One team', 'The same company manufactures, supplies, installs and maintains your equipment, and keeps the spare parts.'),
			array('Two branches', 'Factory and main showroom in Deir El Zahrani, and the Khalde branch for Beirut and Mount Lebanon.'),
		),
	),
	'ar' => array(
		'eyebrow' => 'ما يميّزنا',
		'title' => 'لماذا مؤسسة طرابلسي',
		'more' => 'شاهد مصنعنا أثناء العمل',
		'items' => array(
			array('صناعة لبنانية', 'البرادات والشوايات وأعمال الستانلس ستيل تُصنّع في مصنعنا في دير الزهراني، بمقاسات مشروعك.'),
			array('منذ {year}', '{years} سنة في تجهيز المطابخ والمحلات والمستشفيات في كل لبنان.'),
			array('فريق واحد', 'الشركة نفسها تصنّع وتورّد وتركّب وتصون معداتك، وتؤمّن قطع الغيار.'),
			array('فرعان', 'المصنع والمعرض الرئيسي في دير الزهراني، وفرع خلدة لخدمة بيروت وجبل لبنان.'),
		),
	),
);
