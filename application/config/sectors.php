<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| The sector pages (yoursite/restaurant-equipment, yoursite/ar/restaurant-equipment, ...) and the
| texts of the category pages (yoursite/products/...). One page per sector, in English and Arabic.
|
|	'address' => array(            the page is yoursite/address-equipment
|		'project'  => the sector of back office > Projects shown on the page
|		'clients'  => words found in the client groups shown on the page (Our clients)
|		'products' => words found in the names of the products shown on the page
|		'icon'     => the icon of the page (see the <symbol> list of views/option5/home.php)
|		'en' / 'ar' => name, title + description (Google), h1, lead, text (paragraphs),
|		               supply (what we supply, one line each), faq (question, answer)
|	)
*/
$config['sector_pages'] = array(

	'restaurant' => array(
		'project' => 'restaurants',
		'clients' => 'restaurant',
		'icon' => 'utensils',
		'products' => 'cooking line|gas stove|gas cooker|grill|fryer|griddle|pizza|shawarma|rotisserie|hood|dishwasher|work table|sink|salad bar|counter fridge|upright fridge|hummus|vegetable cutter|potato peeler|plate warmer|hot food|kitchen set|cold room|ice maker',
		'en' => array(
			'name' => 'Restaurants',
			'title' => 'Restaurant Kitchen Equipment in Lebanon | Traboulsi Est.',
			'description' => 'Complete restaurant kitchens in Lebanon: cooking lines, grills, fryers, refrigeration, stainless-steel tables, sinks and hoods, manufactured, supplied, installed and serviced by Traboulsi Est. since 1982.',
			'h1' => 'Restaurant kitchen equipment in Lebanon',
			'lead' => 'From a snack counter to a full restaurant kitchen: we plan the line, build the stainless steel and the refrigeration, install everything and stay for the maintenance.',
			'text' => array(
				'A restaurant kitchen works when every station is in the right place: receiving and cold storage, preparation, cooking, plating and washing. Traboulsi Est. has been equipping restaurants across Lebanon since 1982, and we handle the whole kitchen as one project rather than a list of separate machines.',
				'Refrigerators, grills and all the stainless-steel work (tables, sinks, hoods, shelving) are produced in our own factory in Deir El Zahrani, so they are made to the exact sizes of your kitchen. Cooking lines, fryers, ovens, dishwashers and preparation machines are supplied from the brands we import, then installed and serviced by our own technicians.',
			),
			'supply' => array(
				'Cooking lines: gas stoves, griddles, fryers and lava-rock or charcoal grills',
				'Shawarma grills, chicken rotisseries, pizza ovens and salamanders',
				'Upright and counter fridges, cold rooms and ice makers',
				'Stainless-steel work tables, sinks, shelving and cupboards made to measure',
				'Exhaust hoods and ventilation over the cooking line',
				'Dishwashers and food preparation machines (cutters, peelers, mixers)',
			),
			'faq' => array(
				array('Can you equip a restaurant from an empty space?', 'Yes. Send us the plan or the dimensions of the kitchen and the menu you will serve. We propose the layout and the list of equipment, then manufacture, supply and install it.'),
				array('Do you make stainless-steel tables and hoods to my sizes?', 'Yes. Tables, sinks, hoods, shelving and cupboards are fabricated in our factory in Deir El Zahrani to the measurements of your kitchen.'),
				array('Do you maintain the equipment after installation?', 'Yes. Our technicians carry out the maintenance and supply spare parts for the equipment we install, in every Lebanese region.'),
			),
		),
		'ar' => array(
			'name' => 'المطاعم',
			'title' => 'تجهيز مطاعم في لبنان | معدات مطابخ المطاعم - مؤسسة طرابلسي',
			'description' => 'تجهيز مطاعم كامل في لبنان: خطوط طبخ، شوايات، قلايات، برادات، طاولات ومغاسل وشفاطات ستانلس ستيل، تصنيع وتوريد وتركيب وصيانة من مؤسسة طرابلسي منذ 1982.',
			'h1' => 'تجهيز المطاعم ومطابخها في لبنان',
			'lead' => 'من محل سناك صغير إلى مطبخ مطعم كامل: نخطّط خط العمل، نصنّع الستانلس ستيل والبرادات، نركّب كل شيء ونبقى معك للصيانة.',
			'text' => array(
				'مطبخ المطعم ينجح عندما يكون كل قسم في مكانه الصحيح: الاستلام والتبريد، التحضير، الطبخ، التقديم والغسيل. مؤسسة طرابلسي تجهّز المطاعم في كل لبنان منذ عام 1982، ونتعامل مع المطبخ كمشروع واحد متكامل لا كلائحة ماكينات منفصلة.',
				'البرادات والشوايات وجميع أعمال الستانلس ستيل (طاولات، مغاسل، شفاطات، رفوف) تُصنّع في مصنعنا في دير الزهراني، لذلك تأتي بالمقاسات الدقيقة لمطبخك. أما خطوط الطبخ والقلايات والأفران وغسالات الصحون وماكينات التحضير فنورّدها من العلامات التي نستوردها، ويتولى فنّيونا تركيبها وصيانتها.',
			),
			'supply' => array(
				'خطوط طبخ: غازات، صاجات، قلايات وشوايات على الفحم أو الحجر البركاني',
				'شوايات شاورما، شوايات دجاج، أفران بيتزا وسالاماندر',
				'برادات عمودية وبرادات طاولة، غرف تبريد وآلات ثلج',
				'طاولات عمل ومغاسل ورفوف وخزائن ستانلس ستيل حسب المقاس',
				'شفاطات وتهوية فوق خط الطبخ',
				'غسالات صحون وماكينات تحضير الطعام (تقطيع، تقشير، عجن)',
			),
			'faq' => array(
				array('هل تجهّزون مطعماً من مساحة فارغة؟', 'نعم. أرسل لنا مخطط المطبخ أو مقاساته ونوع الطعام الذي ستقدّمه، فنقترح توزيع الأقسام ولائحة المعدات، ثم نصنّع ونورّد ونركّب.'),
				array('هل تصنّعون طاولات وشفاطات ستانلس ستيل بمقاساتي؟', 'نعم. الطاولات والمغاسل والشفاطات والرفوف والخزائن تُصنّع في مصنعنا في دير الزهراني بحسب مقاسات مطبخك.'),
				array('هل تقدّمون الصيانة بعد التركيب؟', 'نعم. فنّيونا يقومون بالصيانة ويؤمّنون قطع الغيار للمعدات التي نركّبها، في جميع المناطق اللبنانية.'),
			),
		),
	),

	'hotel' => array(
		'project' => 'hotels',
		'clients' => 'hotel|resort|rest house',
		'icon' => 'building',
		'products' => 'cooking line|convection oven|dishwasher|salad bar|hot food|plate warmer|service trolley|cold room|ice maker|beverage bar|kitchen set|espresso|upright fridge|cupboard|gastronorm|hood',
		'en' => array(
			'name' => 'Hotels',
			'title' => 'Hotel & Resort Kitchen Equipment in Lebanon | Traboulsi Est.',
			'description' => 'Hotel and resort kitchens in Lebanon: main kitchen, buffet, bar and room-service equipment, cold rooms and stainless-steel work, supplied, installed and maintained by Traboulsi Est.',
			'h1' => 'Hotel and resort kitchen equipment',
			'lead' => 'Main kitchen, breakfast buffet, bar and banquet service: one supplier for the equipment, the stainless steel and the maintenance contract.',
			'text' => array(
				'A hotel kitchen runs from early breakfast to late room service, so the equipment has to hold up to long hours and large volumes. We equip hotels, resorts and rest houses with complete kitchens: cooking lines, convection ovens, cold rooms, dishwashing areas and the buffet and bar equipment the guests see.',
				'The stainless-steel work and the refrigeration are built in our factory to the sizes of each room, and our team installs the whole project and follows it with maintenance. Tell us the number of rooms and covers you serve and we size the kitchen with you.',
			),
			'supply' => array(
				'Complete cooking lines and convection ovens for the main kitchen',
				'Cold rooms, upright fridges and ice makers',
				'Buffet equipment: salad bars, hot food showcases and plate warmers',
				'Bar equipment: beverage fridges and espresso machines',
				'Dishwashing area with sinks, tables and commercial dishwashers',
				'Service trolleys, gastronorm pans and stainless-steel storage',
			),
			'faq' => array(
				array('Do you handle large hotel projects?', 'Yes. We work from the plans of the kitchen, the buffet and the bars, and deliver the equipment, the stainless-steel fabrication and the installation as one project.'),
				array('Can the equipment be delivered in stages?', 'Yes. We schedule manufacturing, delivery and installation with the progress of your site.'),
				array('Do you offer maintenance for hotels?', 'Yes. Our technicians provide periodic maintenance and spare parts for the equipment we supply.'),
			),
		),
		'ar' => array(
			'name' => 'الفنادق',
			'title' => 'تجهيز فنادق ومنتجعات في لبنان | معدات مطابخ الفنادق - مؤسسة طرابلسي',
			'description' => 'تجهيز مطابخ الفنادق والمنتجعات في لبنان: المطبخ الرئيسي، البوفيه، البار، غرف التبريد وأعمال الستانلس ستيل، توريد وتركيب وصيانة من مؤسسة طرابلسي.',
			'h1' => 'تجهيز مطابخ الفنادق والمنتجعات',
			'lead' => 'المطبخ الرئيسي، بوفيه الفطور، البار وخدمة الحفلات: مورّد واحد للمعدات والستانلس ستيل والصيانة.',
			'text' => array(
				'مطبخ الفندق يعمل من الفطور الباكر حتى خدمة الغرف ليلاً، لذلك يجب أن تتحمّل معداته ساعات العمل الطويلة والكميات الكبيرة. نجهّز الفنادق والمنتجعات والاستراحات بمطابخ متكاملة: خطوط طبخ، أفران حرارية، غرف تبريد، أقسام غسيل الصحون ومعدات البوفيه والبار التي يراها النزلاء.',
				'أعمال الستانلس ستيل والتبريد تُصنّع في مصنعنا بمقاسات كل قسم، وفريقنا يركّب المشروع كاملاً ويتابعه بالصيانة. أخبرنا بعدد الغرف وعدد الوجبات التي تقدّمها فنحدّد معك حجم المطبخ المناسب.',
			),
			'supply' => array(
				'خطوط طبخ متكاملة وأفران حرارية للمطبخ الرئيسي',
				'غرف تبريد، برادات عمودية وآلات ثلج',
				'معدات البوفيه: بار سلطات، واجهات طعام ساخن وسخانات صحون',
				'معدات البار: برادات مشروبات وماكينات إسبريسو',
				'قسم غسيل الصحون: مغاسل وطاولات وغسالات صحون تجارية',
				'عربات خدمة، أوعية غاسترونورم وتخزين ستانلس ستيل',
			),
			'faq' => array(
				array('هل تنفّذون مشاريع فنادق كبيرة؟', 'نعم. نعمل على مخططات المطبخ والبوفيه والبار، ونسلّم المعدات وأعمال الستانلس ستيل والتركيب كمشروع واحد.'),
				array('هل يمكن تسليم المعدات على مراحل؟', 'نعم. نبرمج التصنيع والتسليم والتركيب بحسب تقدّم ورشتك.'),
				array('هل تقدّمون صيانة للفنادق؟', 'نعم. فنّيونا يقومون بالصيانة الدورية ويؤمّنون قطع الغيار للمعدات التي نورّدها.'),
			),
		),
	),

	'supermarket' => array(
		'project' => 'supermarkets',
		'clients' => 'supermarket',
		'icon' => 'cart',
		'products' => 'showcase fridge|open chiller|sliding doors|cheese display|meat display|cold room|shopping trolley|storage shelving|scale|bag sealer|vacuum|upright fridge|meat slicer',
		'en' => array(
			'name' => 'Supermarkets',
			'title' => 'Supermarket & Mini-Market Equipment in Lebanon | Traboulsi Est.',
			'description' => 'Supermarket and mini-market equipment in Lebanon: display fridges, open chillers, cold rooms, shelving, trolleys and scales, manufactured, supplied and installed by Traboulsi Est.',
			'h1' => 'Supermarket and mini-market equipment',
			'lead' => 'Display fridges that sell, cold rooms that hold the stock and shelving that fits your floor plan, from one manufacturer.',
			'text' => array(
				'In a supermarket the refrigeration is both the storage and the shop window. We manufacture showcase fridges, open chillers, meat and cheese displays and cold rooms, and we size them to the length of your aisles and counters.',
				'Alongside the refrigeration we supply the rest of the shop: storage shelving, shopping trolleys, scales, slicers and packing machines for the deli and butchery counters. Our team installs the equipment and services it after the opening.',
			),
			'supply' => array(
				'Showcase fridges for dairy, cheese, deli and meat counters',
				'Open chillers and sliding-door fridges for drinks and dairy',
				'Cold rooms and freezer rooms for the stock',
				'Storage shelving and shopping trolleys',
				'Scales, meat slicers, bag sealers and vacuum packing machines',
				'Installation, start-up and after-sales maintenance',
			),
			'faq' => array(
				array('Do you build display fridges to the length of my counter?', 'Yes. Showcase fridges and cold rooms are made to order, so tell us the available length and what you will display.'),
				array('Can you equip a small mini-market?', 'Yes. We equip shops of every size, from a single display fridge to a full supermarket.'),
				array('Do you install cold rooms?', 'Yes. We supply and install cold and freezer rooms and maintain them afterwards.'),
			),
		),
		'ar' => array(
			'name' => 'السوبرماركت',
			'title' => 'تجهيز سوبرماركت وميني ماركت في لبنان | مؤسسة طرابلسي',
			'description' => 'تجهيز سوبرماركت وميني ماركت في لبنان: برادات عرض، برادات مفتوحة، غرف تبريد، رفوف، عربات تسوّق وموازين، تصنيع وتوريد وتركيب من مؤسسة طرابلسي.',
			'h1' => 'تجهيز السوبرماركت والميني ماركت',
			'lead' => 'برادات عرض تبيع، غرف تبريد تحفظ البضاعة ورفوف تناسب مساحة محلّك، من مصنّع واحد.',
			'text' => array(
				'في السوبرماركت، التبريد هو المخزن وواجهة البيع معاً. نصنّع برادات العرض والبرادات المفتوحة وبرادات اللحوم والأجبان وغرف التبريد، ونحدّد مقاساتها بحسب طول ممرّاتك وكونتواراتك.',
				'إلى جانب التبريد نورّد باقي تجهيزات المحل: رفوف التخزين، عربات التسوّق، الموازين، آلات التقطيع وآلات التغليف لأقسام الأجبان واللحوم. فريقنا يركّب المعدات ويصونها بعد الافتتاح.',
			),
			'supply' => array(
				'برادات عرض لأقسام الألبان والأجبان واللحوم',
				'برادات مفتوحة وبرادات بأبواب منزلقة للمشروبات والألبان',
				'غرف تبريد وتجميد للمخزون',
				'رفوف تخزين وعربات تسوّق',
				'موازين، آلات تقطيع لحوم، آلات تلحيم أكياس وتغليف بالتفريغ',
				'التركيب والتشغيل وصيانة ما بعد البيع',
			),
			'faq' => array(
				array('هل تصنّعون برادات العرض بطول الكونتوار عندي؟', 'نعم. برادات العرض وغرف التبريد تُصنّع حسب الطلب، فأخبرنا بالطول المتوفر وبما ستعرضه.'),
				array('هل تجهّزون ميني ماركت صغيراً؟', 'نعم. نجهّز المحلات بكل الأحجام، من برّاد عرض واحد إلى سوبرماركت كامل.'),
				array('هل تركّبون غرف التبريد؟', 'نعم. نورّد ونركّب غرف التبريد والتجميد ونصونها بعد ذلك.'),
			),
		),
	),

	'butchery' => array(
		'project' => 'butchers',
		'clients' => 'butcher|charcut|meat|chicken',
		'icon' => 'knife',
		'products' => 'meat|bone saw|sausage|kibbeh|hamburger|vacuum|scale|knives|cold room|cutter mixer|insect killer',
		'en' => array(
			'name' => 'Butcher shops',
			'title' => 'Butchery Equipment in Lebanon: Meat Fridges, Mincers & Saws | Traboulsi Est.',
			'description' => 'Butcher shop equipment in Lebanon: meat display fridges, cold rooms, mincers, bone saws, slicers, kibbeh and sausage machines, supplied, installed and serviced by Traboulsi Est.',
			'h1' => 'Butcher shop equipment',
			'lead' => 'Meat display fridges built in our factory, and every machine behind the counter: mincers, bone saws, slicers and vacuum packing.',
			'text' => array(
				'A butcher shop depends on steady cold and on machines that are easy to clean. We manufacture meat display fridges and cold rooms in stainless steel, and we supply the processing machines butchers use every day.',
				'We equip the whole shop: the display counter, the cold room behind it, the cutting and mincing line, the scales and the packing. Everything is installed by our team, with maintenance and spare parts afterwards.',
			),
			'supply' => array(
				'Meat display fridges in stainless steel and tempered glass',
				'Cold rooms for carcasses and stock',
				'Meat mincers, bone saws, slicers and tenderizers',
				'Kibbeh machines, sausage fillers and hamburger presses',
				'Vacuum packing machines and electronic scales',
				'Stainless-steel cutting tables, sinks and professional knives',
			),
			'faq' => array(
				array('Do you manufacture the meat display fridge yourselves?', 'Yes. Meat display fridges are made to order, in the length and finish your shop needs.'),
				array('Can you equip a butchery section inside a supermarket?', 'Yes. We equip stand-alone butcher shops and the meat sections of supermarkets.'),
				array('Do you supply spare parts for mincers and saws?', 'Yes. We supply blades, plates and spare parts for the machines we sell and service them.'),
			),
		),
		'ar' => array(
			'name' => 'الملاحم',
			'title' => 'تجهيز ملاحم في لبنان: برادات لحوم، فرّامات ومناشير عظم | مؤسسة طرابلسي',
			'description' => 'تجهيز ملاحم في لبنان: برادات عرض لحوم، غرف تبريد، فرّامات، مناشير عظم، آلات تقطيع، آلات كبّة ونقانق، توريد وتركيب وصيانة من مؤسسة طرابلسي.',
			'h1' => 'تجهيز الملاحم',
			'lead' => 'برادات عرض لحوم مصنّعة في مصنعنا، وكل ماكينة خلف الكونتوار: فرّامات، مناشير عظم، آلات تقطيع وتغليف بالتفريغ.',
			'text' => array(
				'الملحمة تعتمد على تبريد ثابت وعلى ماكينات سهلة التنظيف. نصنّع برادات عرض اللحوم وغرف التبريد من الستانلس ستيل، ونورّد ماكينات التحضير التي يستعملها اللحّام كل يوم.',
				'نجهّز المحل كاملاً: كونتوار العرض، غرفة التبريد خلفه، خط التقطيع والفرم، الموازين والتغليف. كل شيء يركّبه فريقنا، مع الصيانة وقطع الغيار بعد ذلك.',
			),
			'supply' => array(
				'برادات عرض لحوم من الستانلس ستيل والزجاج المقوّى',
				'غرف تبريد للذبائح والمخزون',
				'فرّامات لحمة، مناشير عظم، آلات تقطيع وتطرية',
				'آلات كبّة، آلات حشو نقانق ومكابس همبرغر',
				'آلات تغليف بالتفريغ وموازين إلكترونية',
				'طاولات تقطيع ومغاسل ستانلس ستيل وسكاكين احترافية',
			),
			'faq' => array(
				array('هل تصنّعون برّاد عرض اللحوم بأنفسكم؟', 'نعم. برادات عرض اللحوم تُصنّع حسب الطلب، بالطول والشكل الذي يحتاجه محلّك.'),
				array('هل تجهّزون قسم ملحمة داخل سوبرماركت؟', 'نعم. نجهّز الملاحم المستقلة وأقسام اللحوم في السوبرماركت.'),
				array('هل تؤمّنون قطع غيار للفرّامات والمناشير؟', 'نعم. نؤمّن السكاكين والأقراص وقطع الغيار للماكينات التي نبيعها ونقوم بصيانتها.'),
			),
		),
	),

	'bakery' => array(
		'project' => 'bakeries',
		'clients' => 'pastr|baker|sweet',
		'icon' => 'bread',
		'products' => 'pizza oven|convection oven|ajeen oven|dough|planetary|bread|croissant|cake display|work table',
		'en' => array(
			'name' => 'Bakeries & pastry shops',
			'title' => 'Bakery & Pastry Equipment in Lebanon: Ovens, Mixers, Display Fridges | Traboulsi Est.',
			'description' => 'Bakery and pastry shop equipment in Lebanon: ovens, Arabic bread lines, dough mixers and sheeters, cake display fridges and stainless-steel tables, from Traboulsi Est. since 1982.',
			'h1' => 'Bakery and pastry shop equipment',
			'lead' => 'Ovens and dough machines for the back, cake and sweets displays for the front, and stainless-steel tables made to your sizes.',
			'text' => array(
				'We equip bakeries, manakish and lahm bi ajeen ovens, pastry kitchens and Arabic sweets shops. For production we supply ovens, automatic Arabic bread lines, spiral and planetary mixers, dough sheeters and cutters.',
				'For the shop we manufacture cake and sweets display fridges and the stainless-steel work tables and shelving of the kitchen. Our team installs the line, starts it up and looks after the maintenance.',
			),
			'supply' => array(
				'Pizza, convection and lahm bi ajeen ovens',
				'Automatic Arabic bread lines and rotary ovens (on order)',
				'Spiral dough mixers and planetary mixers',
				'Dough sheeters, pizza dough rollers and croissant cutters',
				'Cake and sweets display fridges',
				'Stainless-steel work tables, shelving and bread slicers',
			),
			'faq' => array(
				array('Do you supply automatic Arabic bread lines?', 'Yes. We import and install automatic Arabic bread lines and rotary ovens on order. Contact us with the capacity you need.'),
				array('Can you make a display fridge for Arabic sweets or cakes?', 'Yes. Cake and sweets display fridges are made to order in the length your shop needs.'),
				array('Do you install and start up the ovens?', 'Yes. Our technicians install the equipment, start it up and provide the maintenance afterwards.'),
			),
		),
		'ar' => array(
			'name' => 'الأفران والحلويات',
			'title' => 'تجهيز أفران ومخابز وباتيسري في لبنان | مؤسسة طرابلسي',
			'description' => 'تجهيز الأفران والمخابز ومحلات الحلويات في لبنان: أفران، خطوط خبز عربي آلية، عجّانات وفرّادات، برادات عرض حلويات وطاولات ستانلس ستيل، من مؤسسة طرابلسي منذ 1982.',
			'h1' => 'تجهيز الأفران والمخابز ومحلات الحلويات',
			'lead' => 'أفران وماكينات عجين للمشغل، واجهات عرض للحلويات في الصالة، وطاولات ستانلس ستيل بمقاساتك.',
			'text' => array(
				'نجهّز المخابز وأفران المناقيش واللحم بعجين ومطابخ الباتيسري ومحلات الحلويات العربية. للإنتاج نورّد الأفران، خطوط الخبز العربي الآلية، العجّانات الحلزونية والكوكبية، فرّادات العجين والقطّاعات.',
				'وللصالة نصنّع برادات عرض الكاتو والحلويات، وطاولات العمل والرفوف الستانلس ستيل للمشغل. فريقنا يركّب الخط ويشغّله ويتولّى صيانته.',
			),
			'supply' => array(
				'أفران بيتزا، أفران حرارية وأفران لحم بعجين',
				'خطوط خبز عربي آلية وأفران دوّارة (حسب الطلب)',
				'عجّانات حلزونية وعجّانات كوكبية',
				'فرّادات عجين، فرّادات بيتزا وقطّاعات كرواسان',
				'برادات عرض كاتو وحلويات',
				'طاولات عمل ورفوف ستانلس ستيل وآلات تقطيع خبز',
			),
			'faq' => array(
				array('هل تورّدون خطوط خبز عربي آلية؟', 'نعم. نستورد ونركّب خطوط الخبز العربي الآلية والأفران الدوّارة حسب الطلب. تواصل معنا وأخبرنا بالطاقة الإنتاجية التي تحتاجها.'),
				array('هل تصنّعون برّاد عرض للحلويات العربية أو الكاتو؟', 'نعم. برادات عرض الحلويات تُصنّع حسب الطلب بالطول الذي يحتاجه محلّك.'),
				array('هل تركّبون الأفران وتشغّلونها؟', 'نعم. فنّيونا يركّبون المعدات ويشغّلونها ويقومون بصيانتها بعد ذلك.'),
			),
		),
	),

	'coffee-shop' => array(
		'project' => 'cafes',
		'clients' => 'caf|coffee|juice',
		'icon' => 'coffee',
		'products' => 'espresso|juicer|slush|drink dispenser|milkshake|ice maker|beverage bar|cake display|waffle|crepe|ice cream|popcorn|cotton candy|contact grill|hot dog',
		'en' => array(
			'name' => 'Cafés & juice bars',
			'title' => 'Coffee Shop, Juice Bar & Snack Equipment in Lebanon | Traboulsi Est.',
			'description' => 'Coffee shop, juice bar and snack equipment in Lebanon: espresso machines, juicers, slush and ice cream machines, crepe and waffle makers, bar fridges and counters, from Traboulsi Est.',
			'h1' => 'Coffee shop, juice bar and snack equipment',
			'lead' => 'Espresso, fresh juice, ice cream, crepes and waffles: the machines on the counter and the stainless-steel bar under them.',
			'text' => array(
				'A café or juice bar needs compact, reliable machines and a counter designed around the way the staff work. We supply espresso machines, juicers, slush and cold-drink dispensers, soft ice cream machines, crepe and waffle makers and contact grills.',
				'We also build the counter itself: stainless-steel bars, under-counter fridges, cake displays and sinks made to the size of your shop. One team delivers, installs and services the whole setup.',
			),
			'supply' => array(
				'Espresso coffee machines',
				'Orange and carrot juicers, slush machines and cold-drink dispensers',
				'Soft ice cream machines and ice cream display freezers',
				'Crepe machines, waffle machines and contact grills',
				'Popcorn, cotton candy and hot dog machines',
				'Bar fridges, ice makers, cake displays and stainless-steel counters',
			),
			'faq' => array(
				array('Can you build the bar counter to the size of my shop?', 'Yes. Stainless-steel counters, sinks and under-counter fridges are made to measure in our factory.'),
				array('Do you equip kiosks and small snack shops?', 'Yes. We equip small kiosks as well as full cafés, and help you choose machines that fit the space.'),
				array('Do you service espresso and ice cream machines?', 'Yes. We maintain the machines we supply and provide their spare parts.'),
			),
		),
		'ar' => array(
			'name' => 'المقاهي ومحلات العصير',
			'title' => 'تجهيز مقاهي ومحلات عصير وسناك في لبنان | مؤسسة طرابلسي',
			'description' => 'تجهيز المقاهي ومحلات العصير والسناك في لبنان: ماكينات إسبريسو، عصّارات، آلات سلاش وبوظة، آلات كريب ووافل، برادات بار وكونتوارات، من مؤسسة طرابلسي.',
			'h1' => 'تجهيز المقاهي ومحلات العصير والسناك',
			'lead' => 'إسبريسو، عصير طازج، بوظة، كريب ووافل: الماكينات فوق الكونتوار والبار الستانلس ستيل تحتها.',
			'text' => array(
				'المقهى أو محل العصير يحتاج إلى ماكينات صغيرة الحجم وموثوقة وإلى كونتوار مصمّم بحسب طريقة عمل الموظفين. نورّد ماكينات الإسبريسو، العصّارات، آلات السلاش وموزّعات المشروبات الباردة، آلات البوظة السوفت، آلات الكريب والوافل وشوايات الضغط.',
				'ونصنّع الكونتوار نفسه: بارات ستانلس ستيل، برادات تحت الكونتوار، واجهات عرض حلويات ومغاسل بمقاس محلّك. فريق واحد يسلّم ويركّب ويصون التجهيز كاملاً.',
			),
			'supply' => array(
				'ماكينات قهوة إسبريسو',
				'عصّارات برتقال وجزر، آلات سلاش وموزّعات مشروبات باردة',
				'آلات بوظة سوفت وفريزرات عرض بوظة',
				'آلات كريب، آلات وافل وشوايات ضغط',
				'آلات بوشار وغزل بنات وهوت دوغ',
				'برادات بار، آلات ثلج، واجهات حلويات وكونتوارات ستانلس ستيل',
			),
			'faq' => array(
				array('هل تصنّعون كونتوار البار بمقاس محلّي؟', 'نعم. الكونتوارات والمغاسل الستانلس ستيل والبرادات تحت الكونتوار تُصنّع حسب المقاس في مصنعنا.'),
				array('هل تجهّزون الأكشاك ومحلات السناك الصغيرة؟', 'نعم. نجهّز الأكشاك الصغيرة كما المقاهي الكاملة، ونساعدك في اختيار الماكينات المناسبة للمساحة.'),
				array('هل تصونون ماكينات الإسبريسو والبوظة؟', 'نعم. نصون الماكينات التي نورّدها ونؤمّن قطع غيارها.'),
			),
		),
	),

	'hospital-kitchen' => array(
		'project' => 'hospitals',
		'clients' => 'hospital',
		'icon' => 'medical',
		'products' => 'cooking line|convection oven|dishwasher|cold room|morgue|service trolley|kitchen set|work table|sink|cupboard|stainless steel shelving|upright fridge|hood|plate warmer|gastronorm',
		'en' => array(
			'name' => 'Hospitals & institutions',
			'title' => 'Hospital, School & Institutional Kitchen Equipment in Lebanon | Traboulsi Est.',
			'description' => 'Central kitchens for hospitals, schools and institutions in Lebanon: cooking lines, cold rooms, stainless-steel work, dishwashing and morgue refrigerators, supplied and installed by Traboulsi Est.',
			'h1' => 'Hospital and institutional kitchen equipment',
			'lead' => 'Central kitchens that serve hundreds of meals a day, built in stainless steel for hygiene and installed by the team that maintains them.',
			'text' => array(
				'Hospitals, schools and institutions need kitchens that are easy to clean, sized for large volumes and reliable every day of the year. Traboulsi Est. has equipped the kitchens of public and private hospitals in the South, Nabatieh and North Lebanon.',
				'We supply the complete kitchen: cooking lines, cold rooms, preparation and dishwashing areas, and the trolleys that carry the meals to the wards. Our factory also manufactures morgue refrigerators for hospitals.',
			),
			'supply' => array(
				'High-capacity cooking lines and convection ovens',
				'Cold rooms and upright fridges for food storage',
				'Stainless-steel tables, sinks, cupboards and shelving',
				'Dishwashing area with commercial dishwashers',
				'Meal distribution: service trolleys and plate warmers',
				'Morgue refrigerators for one or two bodies',
			),
			'faq' => array(
				array('Have you equipped hospitals before?', 'Yes. Our clients include public and private hospitals in several Lebanese regions; see the list on this page.'),
				array('Do you manufacture morgue refrigerators?', 'Yes. We manufacture morgue refrigerators for one or two bodies in stainless steel.'),
				array('Can you work to the specifications of a tender?', 'Yes. Send us the bill of quantities or the specifications and we will prepare our offer accordingly.'),
			),
		),
		'ar' => array(
			'name' => 'المستشفيات والمؤسسات',
			'title' => 'تجهيز مطابخ المستشفيات والمدارس والمؤسسات في لبنان | مؤسسة طرابلسي',
			'description' => 'مطابخ مركزية للمستشفيات والمدارس والمؤسسات في لبنان: خطوط طبخ، غرف تبريد، أعمال ستانلس ستيل، غسيل صحون وبرادات موتى، توريد وتركيب من مؤسسة طرابلسي.',
			'h1' => 'تجهيز مطابخ المستشفيات والمؤسسات',
			'lead' => 'مطابخ مركزية تقدّم مئات الوجبات يومياً، مصنوعة من الستانلس ستيل لسهولة التعقيم، ويركّبها الفريق نفسه الذي يصونها.',
			'text' => array(
				'المستشفيات والمدارس والمؤسسات تحتاج إلى مطابخ سهلة التنظيف، بحجم يناسب الكميات الكبيرة، وتعمل بثبات كل أيام السنة. مؤسسة طرابلسي جهّزت مطابخ مستشفيات حكومية وخاصة في الجنوب والنبطية والشمال.',
				'نورّد المطبخ كاملاً: خطوط الطبخ، غرف التبريد، أقسام التحضير وغسيل الصحون، والعربات التي تنقل الوجبات إلى الأقسام. ويصنّع مصنعنا أيضاً برادات الموتى للمستشفيات.',
			),
			'supply' => array(
				'خطوط طبخ بطاقة عالية وأفران حرارية',
				'غرف تبريد وبرادات عمودية لحفظ الطعام',
				'طاولات ومغاسل وخزائن ورفوف ستانلس ستيل',
				'قسم غسيل الصحون مع غسالات صحون تجارية',
				'توزيع الوجبات: عربات خدمة وسخانات صحون',
				'برادات موتى لجثة واحدة أو جثتين',
			),
			'faq' => array(
				array('هل جهّزتم مستشفيات من قبل؟', 'نعم. من عملائنا مستشفيات حكومية وخاصة في عدة مناطق لبنانية؛ تجد اللائحة في هذه الصفحة.'),
				array('هل تصنّعون برادات الموتى؟', 'نعم. نصنّع برادات الموتى لجثة واحدة أو جثتين من الستانلس ستيل.'),
				array('هل تعملون وفق دفتر شروط مناقصة؟', 'نعم. أرسل لنا جدول الكميات أو المواصفات ونحضّر عرضنا على أساسها.'),
			),
		),
	),
);

/*
| Texts of the category pages (yoursite/products/refrigeration-cold-display, ...), by the address of the category.
| A category added later in the control panel gets a general text until it has a line here.
*/
$config['category_pages'] = array(
	'refrigeration-cold-display' => array(
		'en' => array('Commercial refrigerators, showcase fridges, open chillers, meat, cheese and cake displays, cold rooms and ice makers. Display fridges and cold rooms are built in our factory in Deir El Zahrani and can be made to the length your shop needs.', 'Commercial fridges, display fridges and cold rooms in Lebanon'),
		'ar' => array('برادات تجارية، برادات عرض، برادات مفتوحة، واجهات لحوم وأجبان وحلويات، غرف تبريد وآلات ثلج. برادات العرض وغرف التبريد تُصنّع في مصنعنا في دير الزهراني ويمكن تصنيعها بالطول الذي يحتاجه محلّك.', 'برادات تجارية، برادات عرض وغرف تبريد في لبنان'),
	),
	'cooking-ovens-bakery' => array(
		'en' => array('Gas stoves, cooking lines, griddles, fryers, charcoal and lava-rock grills, shawarma grills, rotisseries, pizza and convection ovens: the cooking equipment of restaurants, snack shops and bakeries, installed and serviced by our own technicians.', 'Commercial cooking equipment, grills and ovens in Lebanon'),
		'ar' => array('غازات طبخ، خطوط طبخ، صاجات، قلايات، شوايات فحم وحجر بركاني، شوايات شاورما ودجاج، أفران بيتزا وأفران حرارية: معدات الطبخ للمطاعم ومحلات السناك والأفران، يركّبها ويصونها فنّيونا.', 'معدات طبخ تجارية، شوايات وأفران في لبنان'),
	),
	'juice-coffee-snack-bars' => array(
		'en' => array('Espresso machines, juicers, slush and cold-drink dispensers, soft ice cream, milkshake, popcorn and cotton candy machines for cafés, juice bars, kiosks and fun food corners.', 'Coffee, juice and snack bar machines in Lebanon'),
		'ar' => array('ماكينات إسبريسو، عصّارات، آلات سلاش وموزّعات مشروبات باردة، آلات بوظة سوفت وميلك شيك وبوشار وغزل بنات للمقاهي ومحلات العصير والأكشاك.', 'ماكينات القهوة والعصير والسناك في لبنان'),
	),
	'food-preparation-butchery' => array(
		'en' => array('Meat mincers, bone saws, slicers, kibbeh and sausage machines, dough mixers and sheeters, vegetable cutters, hummus machines, vacuum packing and dishwashers: the preparation machines of butcher shops, bakeries and professional kitchens.', 'Food preparation and butchery machines in Lebanon'),
		'ar' => array('فرّامات لحمة، مناشير عظم، آلات تقطيع، آلات كبّة ونقانق، عجّانات وفرّادات، آلات تقطيع خضار، آلات حمّص، تغليف بالتفريغ وغسالات صحون: ماكينات التحضير للملاحم والأفران والمطابخ الاحترافية.', 'ماكينات تحضير الطعام ومعدات الملاحم في لبنان'),
	),
	'supermarket-storage' => array(
		'en' => array('Storage shelving, shopping trolleys, mechanical and electronic scales, gastronorm pans, storage containers and professional knives for supermarkets, mini-markets and kitchen storerooms.', 'Supermarket shelving, trolleys, scales and storage in Lebanon'),
		'ar' => array('رفوف تخزين، عربات تسوّق، موازين ميكانيكية وإلكترونية، أوعية غاسترونورم، أوعية تخزين وسكاكين احترافية للسوبرماركت والميني ماركت ومخازن المطابخ.', 'رفوف وعربات وموازين وتخزين للسوبرماركت في لبنان'),
	),
	'stainless-steel-fabrication' => array(
		'en' => array('Stainless-steel work tables, sinks, cupboards, shelving, service trolleys, exhaust hoods and complete kitchen sets, fabricated in our factory in Deir El Zahrani. Every piece can be made to the measurements of your kitchen.', 'Stainless steel tables, sinks, hoods and custom fabrication in Lebanon'),
		'ar' => array('طاولات عمل، مغاسل، خزائن، رفوف، عربات خدمة، شفاطات ومطابخ ستانلس ستيل متكاملة، تُصنّع في مصنعنا في دير الزهراني. كل قطعة يمكن تصنيعها بمقاسات مطبخك.', 'طاولات ومغاسل وشفاطات ستانلس ستيل وتصنيع حسب الطلب في لبنان'),
	),
);

/*
| The page yoursite/export (yoursite/ar/export): delivery in every Lebanese region and export on request.
*/
$config['export_page'] = array(
	'en' => array(
		'name' => 'Delivery & export',
		'title' => 'Delivery across Lebanon & Export of Kitchen Equipment | Traboulsi Est.',
		'description' => 'Traboulsi Est. delivers and installs commercial kitchen equipment in every Lebanese region and prepares refrigeration and stainless-steel equipment made in Lebanon for export to the Middle East on request.',
		'h1' => 'Delivery across Lebanon and export',
		'lead' => 'Made in Deir El Zahrani, delivered and installed in every Lebanese region, and prepared for export when your project is outside Lebanon.',
		'text' => array(
			'Our factory and main showroom are in Deir El Zahrani (Nabatieh) and our Khalde branch serves Beirut and Mount Lebanon. From these two points our own team delivers, installs and maintains equipment in all Lebanese regions.',
			'For customers outside Lebanon, we manufacture refrigeration, grills and stainless-steel work to order and prepare it for shipping. Send us the list of equipment or the plan of your project, and the country of destination, and we will answer with an offer and the delivery terms.',
		),
		'regions_title' => 'Where we deliver and install',
		'regions' => array('Beirut', 'Mount Lebanon', 'South Lebanon: Saida, Tyre', 'Nabatieh, Marjeyoun, Bint Jbeil', 'Bekaa: Zahle, Baalbek', 'North Lebanon: Tripoli, Akkar'),
		'steps_title' => 'How an export order works',
		'steps' => array(
			array('Your request', 'You send the plan or the list of equipment, the quantities and the destination.'),
			array('Our offer', 'We confirm the sizes and specifications and send a detailed quotation.'),
			array('Manufacturing', 'The refrigeration and stainless-steel work are built to order in our factory.'),
			array('Packing & shipping', 'The equipment is packed for transport and handed over according to the agreed terms.'),
		),
		'faq' => array(
			array('Do you deliver outside the South and Beirut?', 'Yes. We deliver and install in every Lebanese region, including the Bekaa and the North.'),
			array('Do you export equipment outside Lebanon?', 'Yes, on request. Contact us with the equipment you need and the country of destination and we will send you an offer with the delivery terms.'),
			array('Can the equipment be made to my country\'s electrical standard or to special sizes?', 'Equipment manufactured in our factory is made to order, so sizes and specifications are agreed with you before production.'),
		),
	),
	'ar' => array(
		'name' => 'التسليم والتصدير',
		'title' => 'تسليم في كل لبنان وتصدير معدات المطابخ | مؤسسة طرابلسي',
		'description' => 'مؤسسة طرابلسي تسلّم وتركّب معدات المطابخ التجارية في جميع المناطق اللبنانية، وتجهّز البرادات وأعمال الستانلس ستيل المصنّعة في لبنان للتصدير إلى دول الشرق الأوسط عند الطلب.',
		'h1' => 'التسليم في كل لبنان والتصدير',
		'lead' => 'صُنع في دير الزهراني، يُسلَّم ويُركَّب في كل المناطق اللبنانية، ويُجهَّز للتصدير عندما يكون مشروعك خارج لبنان.',
		'text' => array(
			'مصنعنا ومعرضنا الرئيسي في دير الزهراني (النبطية)، وفرعنا في خلدة يخدم بيروت وجبل لبنان. من هاتين النقطتين يسلّم فريقنا المعدات ويركّبها ويصونها في جميع المناطق اللبنانية.',
			'وللعملاء خارج لبنان، نصنّع البرادات والشوايات وأعمال الستانلس ستيل حسب الطلب ونجهّزها للشحن. أرسل لنا لائحة المعدات أو مخطط مشروعك وبلد الوجهة، فنردّ عليك بعرض سعر وشروط التسليم.',
		),
		'regions_title' => 'أين نسلّم ونركّب',
		'regions' => array('بيروت', 'جبل لبنان', 'الجنوب: صيدا، صور', 'النبطية، مرجعيون، بنت جبيل', 'البقاع: زحلة، بعلبك', 'الشمال: طرابلس، عكار'),
		'steps_title' => 'كيف يتم طلب التصدير',
		'steps' => array(
			array('طلبك', 'ترسل المخطط أو لائحة المعدات والكميات وبلد الوجهة.'),
			array('عرضنا', 'نثبّت المقاسات والمواصفات ونرسل عرض سعر مفصّلاً.'),
			array('التصنيع', 'تُصنّع البرادات وأعمال الستانلس ستيل حسب الطلب في مصنعنا.'),
			array('التوضيب والشحن', 'تُوضَّب المعدات للنقل وتُسلَّم وفق الشروط المتفق عليها.'),
		),
		'faq' => array(
			array('هل تسلّمون خارج الجنوب وبيروت؟', 'نعم. نسلّم ونركّب في جميع المناطق اللبنانية، ومنها البقاع والشمال.'),
			array('هل تصدّرون المعدات إلى خارج لبنان؟', 'نعم، عند الطلب. تواصل معنا وأخبرنا بالمعدات التي تحتاجها وبلد الوجهة، فنرسل لك عرضاً مع شروط التسليم.'),
			array('هل يمكن تصنيع المعدات بمقاسات خاصة أو بمواصفات كهرباء بلدي؟', 'المعدات التي نصنّعها في مصنعنا تُصنّع حسب الطلب، فنتّفق معك على المقاسات والمواصفات قبل الإنتاج.'),
		),
	),
);
