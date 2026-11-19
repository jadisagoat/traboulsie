<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| The guides (yoursite/guides, yoursite/guides/address and the same under yoursite/ar/).
| Articles that answer what customers search for before they buy.
|
|	'address' => array(
|		'date'   => day it was written (YYYY-MM-DD)
|		'sector' => the sector page linked at the end (an address of config/sectors.php)
|		'en' / 'ar' => title + description (Google), h1, lead, sections: array(heading, paragraphs, list)
|	)
| A new guide only needs a block here: it appears in the list, in the footer and in sitemap.xml.
*/
$config['guides'] = array(

	'restaurant-kitchen-checklist' => array(
		'date' => '2026-11-19',
		'sector' => 'restaurant',
		'en' => array(
			'title' => 'How to Equip a Restaurant Kitchen in Lebanon: Complete Checklist | Traboulsi Est.',
			'description' => 'A practical checklist for opening a restaurant in Lebanon: the kitchen zones, the equipment each one needs, gas and electricity, ventilation and the mistakes to avoid.',
			'h1' => 'How to equip a restaurant kitchen in Lebanon: the complete checklist',
			'lead' => 'Before you buy a single machine, decide the menu, the number of meals and the flow of the kitchen. This checklist follows the order we use when we plan a kitchen for a customer.',
			'sections' => array(
				array('1. Start from the menu and the volume', array('The equipment follows the menu, not the other way round. A grill restaurant, a pizzeria and a snack shop need three different kitchens. Write down what you will serve, how many meals at the busiest hour, and how many people will work in the kitchen. These three numbers decide the size of the cooking line and of the refrigeration.'), array()),
				array('2. Divide the kitchen into zones', array('A kitchen that works is one where food moves in one direction and clean never crosses dirty.'), array(
					'Receiving and storage: cold room or upright fridges, freezer, dry shelving',
					'Preparation: stainless-steel tables, sinks, vegetable cutter, meat mincer, mixer',
					'Cooking: stoves, griddle, fryer, grill, oven, under the exhaust hood',
					'Service: pass table, hot food showcase, plate warmer, salad bar',
					'Washing: sink, dishwasher, racks and a table on each side',
				)),
				array('3. Size the refrigeration first', array('Refrigeration is what protects your stock and what works twenty-four hours a day. In the Lebanese climate, and with power cuts, choose well-insulated fridges and count on a generator or a backup for the cold room. As a rule, plan separate cold storage for meat, for vegetables and dairy, and for prepared food.'), array()),
				array('4. Gas, electricity and ventilation', array('Check what your premises can supply before choosing the machines: the available electrical power, whether three-phase power is available, where the gas line will run and where the hood can exhaust. A hood that is too small makes the kitchen hot and smoky, and it is expensive to change after the opening.'), array()),
				array('5. Stainless steel made to measure', array('Tables, sinks, shelves and hoods are worth making to the exact sizes of the room: no lost corners, no gaps where dirt collects. Ask for food-grade stainless steel and for rounded, easy-to-clean edges.'), array()),
				array('6. Mistakes we see most often', array(), array(
					'Buying the cooking line before fixing the menu',
					'Too little cold storage for the weekend volume',
					'No space left around machines for cleaning and maintenance',
					'An undersized hood or no fresh-air intake',
					'Choosing equipment with no local spare parts or service',
				)),
				array('7. Ask for one plan and one offer', array('The easiest way to stay on budget is to give one supplier the plan of the room and the menu, and to receive one layout and one priced list. Traboulsi Est. prepares this for you, manufactures the stainless steel and the refrigeration, supplies the cooking equipment and installs everything.'), array()),
			),
		),
		'ar' => array(
			'title' => 'كيف تجهّز مطبخ مطعم في لبنان: لائحة كاملة بالمعدات | مؤسسة طرابلسي',
			'description' => 'لائحة عملية لمن يفتح مطعماً في لبنان: أقسام المطبخ، المعدات التي يحتاجها كل قسم، الغاز والكهرباء، التهوية والأخطاء التي يجب تجنّبها.',
			'h1' => 'كيف تجهّز مطبخ مطعم في لبنان: اللائحة الكاملة',
			'lead' => 'قبل أن تشتري أي ماكينة، حدّد لائحة الطعام وعدد الوجبات وحركة العمل داخل المطبخ. هذه اللائحة تتبع الترتيب الذي نعتمده عندما نخطّط مطبخاً لأحد عملائنا.',
			'sections' => array(
				array('1. ابدأ من لائحة الطعام وحجم العمل', array('المعدات تتبع لائحة الطعام وليس العكس. مطعم المشاوي والبيتزا ومحل السناك تحتاج إلى ثلاثة مطابخ مختلفة. اكتب ما ستقدّمه، عدد الوجبات في ساعة الذروة، وعدد العاملين في المطبخ. هذه الأرقام الثلاثة تحدّد حجم خط الطبخ وحجم التبريد.'), array()),
				array('2. قسّم المطبخ إلى أقسام', array('المطبخ الناجح هو الذي يتحرّك فيه الطعام باتجاه واحد، ولا يلتقي فيه النظيف بالمتّسخ.'), array(
					'الاستلام والتخزين: غرفة تبريد أو برادات عمودية، فريزر ورفوف للمواد الجافة',
					'التحضير: طاولات ومغاسل ستانلس ستيل، آلة تقطيع خضار، فرّامة لحمة، عجّانة',
					'الطبخ: غازات، صاج، قلاية، شواية وفرن، كلها تحت الشفاط',
					'التقديم: طاولة تسليم، واجهة طعام ساخن، سخان صحون وبار سلطات',
					'الغسيل: مغسلة، غسالة صحون، رفوف وطاولة من كل جهة',
				)),
				array('3. حدّد حجم التبريد أولاً', array('التبريد هو الذي يحمي بضاعتك ويعمل أربعاً وعشرين ساعة في اليوم. في مناخ لبنان ومع انقطاع الكهرباء، اختر برادات جيدة العزل واحسب حساب مولّد أو بديل لغرفة التبريد. والقاعدة أن تخصّص تبريداً منفصلاً للحوم، وللخضار والألبان، وللطعام المحضّر.'), array()),
				array('4. الغاز والكهرباء والتهوية', array('تأكّد ممّا يمكن أن يؤمّنه محلّك قبل اختيار الماكينات: القدرة الكهربائية المتوفرة، وجود كهرباء ثلاثية الأطوار، مسار خط الغاز ومكان خروج هواء الشفاط. الشفاط الصغير يجعل المطبخ حاراً ومليئاً بالدخان، وتغييره بعد الافتتاح مكلف.'), array()),
				array('5. ستانلس ستيل حسب المقاس', array('الطاولات والمغاسل والرفوف والشفاطات تستحق أن تُصنّع بمقاسات الغرفة الدقيقة: لا زوايا ضائعة ولا فراغات تتجمّع فيها الأوساخ. اطلب ستانلس ستيل غذائياً وحوافّ مدوّرة سهلة التنظيف.'), array()),
				array('6. أكثر الأخطاء التي نراها', array(), array(
					'شراء خط الطبخ قبل تثبيت لائحة الطعام',
					'تبريد أقل من حاجة نهاية الأسبوع',
					'عدم ترك مساحة حول الماكينات للتنظيف والصيانة',
					'شفاط أصغر من اللازم أو بدون إدخال هواء نقي',
					'اختيار معدات لا تتوفر لها قطع غيار أو صيانة محلية',
				)),
				array('7. اطلب مخططاً واحداً وعرضاً واحداً', array('أسهل طريقة للبقاء ضمن الميزانية هي أن تعطي مورّداً واحداً مخطط المحل ولائحة الطعام، وتستلم توزيعاً واحداً ولائحة أسعار واحدة. مؤسسة طرابلسي تحضّر لك ذلك، تصنّع الستانلس ستيل والبرادات، تورّد معدات الطبخ وتركّب كل شيء.'), array()),
			),
		),
	),

	'choosing-display-fridge-cold-room' => array(
		'date' => '2026-11-19',
		'sector' => 'supermarket',
		'en' => array(
			'title' => 'Display Fridge or Cold Room? How to Choose Commercial Refrigeration in Lebanon | Traboulsi Est.',
			'description' => 'How to choose commercial refrigeration for a supermarket, butchery or pastry shop in Lebanon: types of display fridges, when you need a cold room, sizes, temperatures and running costs.',
			'h1' => 'Display fridge or cold room? How to choose commercial refrigeration',
			'lead' => 'The right refrigeration sells your products and protects your stock. The wrong one wastes electricity every hour of the day. Here is how to choose.',
			'sections' => array(
				array('Display or storage: two different jobs', array('A display fridge is built to show the product and to be opened all day; a cold room or an upright fridge is built to hold stock at a steady temperature. Most shops need both: the display at the front and the storage behind it.'), array()),
				array('Which display fridge for which product', array(), array(
					'Meat: a meat display fridge with a stainless-steel deck, easy to wash, around 0 to 2 °C',
					'Cheese, deli and dairy: a showcase fridge with tempered glass, around 2 to 6 °C',
					'Cakes and Arabic sweets: a cake display fridge with shelves and gentle ventilation so the product does not dry',
					'Drinks and packed dairy: an open chiller or a sliding-door fridge that customers serve themselves from',
					'Ice cream: a display freezer, around -18 °C',
				)),
				array('When you need a cold room', array('If your weekly stock does not fit in two or three upright fridges, a cold room costs less to run and keeps a steadier temperature. Butcher shops, supermarkets, restaurants with high volume and central kitchens almost always need one. Decide whether you need chilling (above zero), freezing (below zero) or one room of each.'), array()),
				array('Sizes: measure before you order', array('Measure the length available for the counter, the width of the door the fridge must pass through, and the height of the ceiling for a cold room. Equipment made to order can use the full length of your wall, which is often worth more than a standard size that leaves a gap.'), array()),
				array('Electricity and power cuts', array('Refrigeration runs day and night, so insulation thickness, door seals and the quality of the compressor decide your bill. Ask how long the fridge holds its temperature without power, and plan the generator load for the start-up of the compressors.'), array()),
				array('Service matters more than the price tag', array('A fridge that stops for two days costs more than the difference between two offers. Buy from a supplier who installs it, keeps the spare parts and can send a technician to your region. Traboulsi Est. manufactures display fridges and cold rooms in Deir El Zahrani and maintains them all over Lebanon.'), array()),
			),
		),
		'ar' => array(
			'title' => 'برّاد عرض أم غرفة تبريد؟ كيف تختار التبريد التجاري في لبنان | مؤسسة طرابلسي',
			'description' => 'كيف تختار التبريد التجاري لسوبرماركت أو ملحمة أو محل حلويات في لبنان: أنواع برادات العرض، متى تحتاج غرفة تبريد، المقاسات، درجات الحرارة وكلفة التشغيل.',
			'h1' => 'برّاد عرض أم غرفة تبريد؟ كيف تختار التبريد التجاري',
			'lead' => 'التبريد الصحيح يبيع بضاعتك ويحمي مخزونك، والتبريد الخاطئ يهدر الكهرباء كل ساعة. إليك كيف تختار.',
			'sections' => array(
				array('العرض والتخزين: وظيفتان مختلفتان', array('برّاد العرض مصنوع ليُظهر البضاعة ويُفتح طوال النهار، أما غرفة التبريد أو البرّاد العمودي فمصنوع ليحفظ المخزون على حرارة ثابتة. معظم المحلات تحتاج إلى الاثنين: العرض في الواجهة والتخزين خلفها.'), array()),
				array('أي برّاد عرض لأي بضاعة', array(), array(
					'اللحوم: برّاد عرض لحوم بأرضية ستانلس ستيل سهلة الغسل، بحرارة بين 0 و2 درجة مئوية تقريباً',
					'الأجبان والألبان: برّاد عرض بزجاج مقوّى، بحرارة بين 2 و6 درجات تقريباً',
					'الكاتو والحلويات العربية: برّاد عرض حلويات برفوف وتهوية خفيفة كي لا تجفّ البضاعة',
					'المشروبات والألبان المعلّبة: برّاد مفتوح أو برّاد بأبواب منزلقة يخدم الزبون نفسه منه',
					'البوظة: فريزر عرض بحرارة 18 تحت الصفر تقريباً',
				)),
				array('متى تحتاج غرفة تبريد', array('إذا كان مخزونك الأسبوعي لا يتّسع في برّادين أو ثلاثة برادات عمودية، فغرفة التبريد أقل كلفة في التشغيل وأثبت في الحرارة. الملاحم والسوبرماركت والمطاعم ذات الحجم الكبير والمطابخ المركزية تحتاج إليها في أغلب الأحيان. حدّد إن كنت تحتاج تبريداً (فوق الصفر) أو تجميداً (تحت الصفر) أو غرفة من كل نوع.'), array()),
				array('المقاسات: قِس قبل أن تطلب', array('قِس الطول المتوفر للكونتوار، وعرض الباب الذي سيدخل منه البرّاد، وارتفاع السقف لغرفة التبريد. المعدات المصنّعة حسب الطلب تستعمل طول الحائط كاملاً، وهذا في الغالب أفضل من مقاس جاهز يترك فراغاً.'), array()),
				array('الكهرباء وانقطاعها', array('التبريد يعمل ليلاً ونهاراً، لذلك سماكة العزل وإحكام الأبواب وجودة الكمبريسور هي التي تحدّد فاتورتك. اسأل كم يحافظ البرّاد على حرارته بدون كهرباء، واحسب حمل المولّد عند إقلاع الكمبريسورات.'), array()),
				array('الصيانة أهم من السعر', array('البرّاد الذي يتوقف يومين يكلّف أكثر من الفرق بين عرضَي سعر. اشترِ من مورّد يركّب البرّاد ويحتفظ بقطع غياره ويستطيع إرسال فنّي إلى منطقتك. مؤسسة طرابلسي تصنّع برادات العرض وغرف التبريد في دير الزهراني وتصونها في كل لبنان.'), array()),
			),
		),
	),
);
