<?php
return [

        /*
        |--------------------------------------------------------------------------
        | Поле для проверки отображения на сайте и
        | Бренд по умолчанию для проверки
        |--------------------------------------------------------------------------
        */
	'check_field' => 'show_ferroli',
    'check_field_second' => 'show_market_ru',
	'brand_default' => 1,

	'catalog_price_pdf' => 'https://yadi.sk/d/lRlXWUVlIEs0ug',
	'youtube_channel'   => 'https://www.youtube.com/channel/UC9q1SNmiWzsFUysN-LPXHnw',
    'MIRROR_CONFIG'=>'srvkk',

    'kotelkotel_ru'=>[
      'MIRROR_CONFIG'=>'kk'
    ],
    'service_kotelkotel_ru'=>[
      'MIRROR_CONFIG'=>'srvkk'
    ],
    'zip_kotelkotel_ru'=>[
      'MIRROR_CONFIG'=>'zipkk'
    ],

        /*
        |--------------------------------------------------------------------------
        | Требовать наличие сертификата у инженера
        |       Длина № сертфиката
        |--------------------------------------------------------------------------
        */
	'engineer_certificate_required' => true,
	'certificate_length' => 20,

	/*
	|--------------------------------------------------------------------------
	| Минимально необходимые баллы
	| для получения сертификата на монтаж
	|--------------------------------------------------------------------------
	*/
	'certificate_scores' => [
		8, 9, 10,
	],
	'certificate_first_letter' => 'R',
	'certificate_srv_first_letter' => 'S',

    /*      Типы цен по-умолчанию для неавторизованных, админа и назначаемая по-умолчанию для пользователей         */
	'defaults' => [
		'currency' => 643,
		'image' => 'http://placehold.it/500x500',
		'guest' => [
			'price_type_id' => '7fb003f2-aca8-11e8-80cc-85ebbdeccdc7',
			'price_rub_type_id' => 'fb01f652-6417-11ea-80d6-8439fa8d819d',
			'price_eur_type_id' => '7fb003f2-aca8-11e8-80cc-85ebbdeccdc7',
			'price_promo_rub_type_id' => '468f0fd3-7e23-11ea-80d6-8439fa8d819d',
			'price_promo_eur_type_id' => '468f0fd2-7e23-11ea-80d6-8439fa8d819d',
			'price_old_eur_type_id' => '0fadd64e-ac8c-11ea-80d6-8439fa8d819d',
		],
		'admin' => [
			'price_type_id' => '7fb003f2-aca8-11e8-80cc-85ebbdeccdc7',
			'price_type_id_report_asc' => '7fb003f2-aca8-11e8-80cc-85ebbdeccdc7',
			'role_id' => 1,
		],
		'user' => [
			'warehouse_id' => '19c8a7e7-8b9a-11e8-80c9-c659bc5ae479',
			'organization_id' => '728fcfa4-8b85-11e8-80c8-cd0a94fa06dd',
			'price_type_id' => '7fb003f2-aca8-11e8-80cc-85ebbdeccdc7',
			'currency_id' => 643,
			'role_id' => 2,
		],
        /*      Тип цены для отчетов по гарантийному ремонту*/
		'part' => [
			'price_type_id' => '7fb003f6-aca8-11e8-80cc-85ebbdeccdc7',
		],
        /*      Тип цены для тендеров*/
		'tender' => [
			'price_type_id' => '7fb003f5-aca8-11e8-80cc-85ebbdeccdc7',
		],
        /*      Тип цены для заказов на витрину*/
		'stand_item' => [
			'price_type_id' => 'fb01f652-6417-11ea-80d6-8439fa8d819d',
		],

	],
	/*
	|--------------------------------------------------------------------------
	| Время, через которое бонус будет отправлен в Дигифт
	|--------------------------------------------------------------------------
	*/
	'digift_send_day_delay' => 3, // 3 дня

	/*
	|--------------------------------------------------------------------------
	| Время, которое должно пройти с последней проверки баланса
	| для его анализа в крон задании
	|--------------------------------------------------------------------------
	*/
	'digift_check_balance_delay' => 24, // 24 часа

	/*
	|--------------------------------------------------------------------------
	| Время, которое должно пройти с последней попытки отправки бонуса
	|--------------------------------------------------------------------------
	*/
	'digift_change_balance_delay' => 1, // 1 час

	/*
	|--------------------------------------------------------------------------
	| Таймаут, в течение которого сервер будет ожидать ответ от Дигифт
	|--------------------------------------------------------------------------
	*/
	'digift_timeout' => 5.0, // 5 секунд

	'receiver_id' => 1,

	'round' => 0,
	'round_step' => 1,

	'round_up' => false,

	'decimals' => 0,

	'decimalPoint' => '.',

	'thousandSeparator' => ' ',

	'nds' => 20,
	/*
	|--------------------------------------------------------------------------
	| Код основной валюты
	| Для основной валюты устанавливается обменный курс = 1.0000
	*/
	'main' => 643,

	'country' => 643,
	'country_phone' => '7',

	'mounting_min_cost' => 3000,



	/*
	|--------------------------------------------------------------------------
	| Коды обновляемых валют
	|--------------------------------------------------------------------------
	|
	| Для основной валюты устанавливается обменный курс = 1.0000
	|
	*/
	'update' => [
		978,
	],

	/*
	| Курсы валют с сайта ЦБ
	| Site/SiteServiceProvider.php  здесь тоже заменить при заливе зеркала BY
	*/
	'exchange' => 'QuadStudio\Service\Site\Exchanges\Cbr',

	'admin_ip' => [
		1 => '127.0.0.1',
	],

	'variables' => [
        'academy' => ['academy_learn_count_current','academy_learn_heads_current','academy_learn_year_current','academy_learn_count_last','academy_learn_heads_last','academy_learn_year_last'],
    ],
    
    
    'name_limit' => 30,

	/*
	|--------------------------------------------------------------------------
	| Лимит товаров для загрузки на склад дистрибьютора
	|--------------------------------------------------------------------------
	*/
	'storehouse_product_limit' => 30000,
	'storehouse_product_max_quantity' => 200000,

	'max_storehouse_products' => 10000,

    'serial_pattern' => [
			'pattern' => '/[^0-z]/',
			'replacement' => "",
		],
	/*
	|--------------------------------------------------------------------------
	| Телефоныый номер
	|--------------------------------------------------------------------------
	*/
	'phone' => [

		// Правило для валидации формы
		'pattern' => '^(\(([1-6,8,9]{1})([0-9]{2})\)\s([0-9]{3})-([0-9]{2})-([0-9]{2}))$',
		'pattern_mobile' => '^(\(([9]{1})([0-9]{2})\)\s([0-9]{3})-([0-9]{2})-([0-9]{2}))$',

		// Геттер (преобразование телефона из базы для отображения в форме)
		'get' => [
			'pattern' => '/^([0-9]{3})([0-9]{3})([0-9]{2})([0-9]{2})$/',
			'replacement' => '($1) $2-$3-$4',
		],

		// Сеттер (преобразование телефона из формы для сохранения в базе)
		'set' => [
			'pattern' => '/[^0-9]/',
			'replacement' => "",
		],

		// Маска ввода для формы
		'mask' => '(000) 000-00-00',

		// Формат, отображаемый при ошибке валидации в форме
		'format' => '(9XX) XXX-XX-XX',

		// Длина номера телефона
		'maxlength' => 15,
	],

    // Роли, которым доступна автоматическая загрузка остатков
	'warehouse_check' => [
		'gendistr',
		'csc',
		'distr',
	],
    // видят все склады
	'warehouse_all_roles' => [
		'ferroli_user',
		'admin_site',
		'supervisor',
		'gendistr',
		'csc',
		'distr',
	],
    
	'ferroli_shouse_roles' => [
		'gendistr',
		'distr',
		'dealer',
		'csc',
	],
	'distr_warehouse_check' => [
		'gendistr',
		'distr',
	],
    
	'roles_distr' => [
		'distr',
		'gendistr',
		'printdistr',
	],
	'roles_sale' => [
		'distr',
		'gendistr',
		'printdistr',
		'dealer',
	],
	'roles_ferroli' => [
		'ferroli_user',
		'service_super',
		'supervisor',
	],
    
	'roles_fl' => [
		'14',
		'15',
		'16',
	],

	'roles_for_mission' => [
		'asc',
		'csc',
		'distr',
		'gendistr',
		'dealer',
	],

    'roles_engineer'=>['service_fl','montage_fl'],
    'roles_client'=>['end_user'],
    'supervisor_roles'=>['supervisor','admin_site'],
    //Пользователи - юр.лица
    'types_company'=>['1','5'],
    'types_client'=>['4','5'],

	'distr_stand_order_status' => [
		'2','3','4','5','6','12','13',
	],

    //Грппы файлов для документов и заявок
    
    'stand_order_bill_file_type' => 22,

    
    'stand_order_group_file' => 6,
    
    'partner_plus_request_group_file' => 15,

	'routes' => [
		'rbac',
		'cart',
	],

	'cache' => [
		'use' => false,
		'ttl' => 60 * 60 * 24,
	],

	'sort_order' => [
		'equipment' => 'sort_order',
		'catalog' => 'sort_order',
	],

	'datasheet' => [
		'products' => [
			'count' => 3,
		],
	],

	'delimiter' => ':',

	'weeks_delivery' => [
		'min' => 0,
		'max' => 15,
	],

	'geocode' => true,

	'images' => [
		'mime' => 'jpg,jpeg,png',
		'size' => [
			'image' => [
				'width' => 500,
				'height' => 500,
			],
			'canvas' => [
				'width' => 500,
				'height' => 500,
			],
		],

	],

	'schemes' => [
		'process' => true,
		'mode' => 'update',
		'mime' => 'jpg,jpeg',
		'accept' => 'image/jpeg',
		'name' => 'scheme[image_id]',
		'dot_name' => 'scheme.image_id',
		'size' => 15000000, // 15мб
		'preview' => [
			'width' => 150,
			'height' => 150,
		],
		'image' => [
			'width' => 740,
			'height' => 740,
		],
		'canvas' => [
			'width' => 740,
			'height' => 740,
		],

	],

	'templates' => [
		'process' => true,
		'mode' => 'update',
		'mime' => 'docx',
		'accept' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
		'name' => 'contract_type[file_id]',
		'dot_name' => 'contract_type.file_id',
		'size' => 15000000, // 15мб
	],

	'template_files' => [
		'process' => true,
		'mode' => 'update',
		'mime' => 'docx',
		'accept' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
		'name' => 'template_files[file_id]',
		'dot_name' => 'template_files.file_id',
		'size' => 15000000, // 15мб
	],

	'esb_contract_templates' => [
		'process' => true,
		'mode' => 'update',
		'mime' => 'docx',
		'accept' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
		'name' => 'esb_contract_template[file_id]',
		'dot_name' => 'esb_contract_template.file_id',
		'size' => 15000000, // 15мб
	],

	'catalogs' => [
		'process' => true,
		'mode' => 'update',
		'mime' => 'jpg,jpeg',
		'accept' => 'image/jpeg',
		'name' => 'catalog[image_id]',
		'dot_name' => 'catalog.image_id',
		'size' => 15000000, // 15мб
		'preview' => [
			'width' => 150,
			'height' => 150,
		],
		'image' => [
			'width' => 500,
			'height' => 500,
		],
		'canvas' => [
			'width' => 500,
			'height' => 500,
		],
	],


	'presentations' => [
		'process' => false,
		'mode' => 'update',
		'mime' => 'jpg,jpeg',
		'accept' => 'image/jpeg',
		'name' => 'presentationSlide[image_id]',
		'dot_name' => 'presentation_slides.image_id',
		'size' => 15000000, // 15мб
		'preview' => [
			'width' => 150,
			'height' => 150,
		],
		
	],

	'sounds' => [
		'process' => false,
		'mode' => 'update',
		'mime' => 'mp3,m4a',
		'accept' => 'audio/*',
		'name' => 'presentationSlide[file_id]',
		'dot_name' => 'presentation_slides.file_id',
		'size' => 15000000, // 150мб
		
		
	],
    
	'banners' => [
		'process' => false,
		'mode' => 'update',
		'mime' => 'jpg,jpeg',
		'accept' => 'image/jpeg',
		'name' => 'headBannerBlock[image_id]',
		'dot_name' => 'head_banner_blocks.image_id',
		'size' => 15000000, // 15мб
		'preview' => [
			'width' => 150,
			'height' => 150,
		],
		
	],
	'index_cards' => [
		'process' => false,
		'mode' => 'update',
		'mime' => 'jpg,jpeg,png',
		'accept' => 'image/jpeg,image/png',
		'name' => 'indexCardsBlock[image_id]',
		'dot_name' => 'index_cards_blocks.image_id',
		'size' => 15000000, // 15мб
		'preview' => [
			'width' => 150,
			'height' => 150,
		],
		
	],
	'index_quadro' => [
		'process' => false,
		'mode' => 'update',
		'mime' => 'jpg,jpeg,png',
		'accept' => 'image/jpeg,image/png',
		'name' => 'indexQuadroBlock[image_id]',
		'dot_name' => 'index_quadro_blocks.image_id',
		'size' => 15000000, // 15мб
		'preview' => [
			'width' => 150,
			'height' => 150,
		],
		
	],

	'datasheets' => [
		'process' => true,
		'mode' => 'update',
		'mime' => 'pdf',
		'accept' => 'application/pdf',
		'name' => 'datasheet[file_id]',
		'dot_name' => 'datasheet.file_id',
		'size' => 15000000, // 15мб
	],

	'payments' => [
		'process' => false,
		'mode' => 'single',
		'mime' => 'pdf,jpg,jpeg,png,tiff,xls,xlsx,doc,docx',
		'accept' => 'application/pdf,image/jpeg,image/png,image/tiff,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document',
		'name' => 'payment[file_id]',
		'dot_name' => 'payment.file_id',
		'size' => 15000000, // 15мб
	],


	'events' => [
		'process' => true,
		'mode' => 'update',
		'mime' => 'jpg,jpeg,png',
		'accept' => 'image/jpeg,image/png',
		'name' => 'event[image_id]',
		'dot_name' => 'event.image_id',
		'size' => 15000000, // 15мб
		'preview' => [
			'width' => 130,
			'height' => 70,
		],
		'image' => [
			'width' => 370,
			'height' => 200,
		],
		'canvas' => [
			'width' => 370,
			'height' => 200,
		],
	],

	'event_types' => [
		'process' => true,
		'mode' => 'update',
		'mime' => 'jpg,jpeg,png',
		'accept' => 'image/jpeg,image/png',
		'name' => 'event_type[image_id]',
		'dot_name' => 'event_type.image_id',
		'size' => 15000000, // 15мб
		'preview' => [
			'width' => 130,
			'height' => 70,
		],
		'image' => [
			'width' => 370,
			'height' => 200,
		],
		'canvas' => [
			'width' => 370,
			'height' => 200,
		],
	],

	'products' => [
		'process' => true,
		'mode' => 'append',
		'mime' => 'jpg,jpeg',
		'accept' => 'image/jpeg',
		'name' => 'images[]',
		'dot_name' => 'images',
		'size' => 15000000, //
		'preview' => [
			'width' => 150,
			'height' => 150,
		],
		'image' => [
			'width' => 500,
			'height' => 500,
		],
		'canvas' => [
			'width' => 500,
			'height' => 500,
		],
	],

	'equipments' => [
		'process' => true,
		'mode' => 'append',
		'mime' => 'jpg,jpeg',
		'accept' => 'image/jpeg',
		'name' => 'images[]',
		'dot_name' => 'images',
		'size' => 15000000, //
		'preview' => [
			'width' => 150,
			'height' => 150,
		],
		'image' => [
			'width' => 500,
			'height' => 500,
		],
		'canvas' => [
			'width' => 500,
			'height' => 500,
		],
	],

	'addresses' => [
		'process' => true,
		'mode' => 'append',
		'mime' => 'jpg,jpeg',
		'accept' => 'image/jpeg',
		'name' => 'images[]',
		'dot_name' => 'images',
		'size' => 300000, //
		'preview' => [
			'width' => 150,
			'height' => 150,
		],
		'image' => [
			'width' => 500,
			'height' => 500,
		],
		'canvas' => [
			'width' => 500,
			'height' => 500,
		],
	],

	'announcements' => [
		'process' => true,
		'mode' => 'update',
		'mime' => 'jpg,jpeg',
		'accept' => 'image/jpeg',
		'name' => 'announcement[image_id]',
		'dot_name' => 'announcement.image_id',
		'size' => 15000000, // 5мб
		'preview' => [
			'width' => 130,
			'height' => 70,
		],
		'image' => [
			'width' => 370,
			'height' => 200,
		],
		'canvas' => [
			'width' => 370,
			'height' => 200,
		],
	],

//    'repairs' => [
//        'process'  => true,
//        'mode'     => 'append',
//        'mime'     => 'jpg,jpeg,png,pdf',
//        'accept'   => 'image/jpeg,image/png,application/pdf',
//        'name'     => 'repair[file_id][]',
//        'dot_name' => 'repair.file_id',
//        'size'     => 5000000, // 5мб
//    ],

    'orders' => [
        'process'  => true,
        'mode'     => 'append',
        'mime'     => 'jpg,jpeg,png,pdf',
        'accept'   => 'image/jpeg,image/png,application/pdf',
        'name'     => 'orders[file_id][]',
        'dot_name' => 'orders.file_id',
        'size'     => 5000000, // 5мб
    ],

	'webinars' => [
		'process' => true,
		'mode' => 'update',
		'mime' => 'jpg,jpeg,png',
		'accept' => 'image/jpeg,image/png',
		'name' => 'webinar[image_id]',
		'dot_name' => 'webinar.image_id',
		'size' => 15000000, // 15мб
		'preview' => [
			'width' => 130,
			'height' => 70,
		],
		'image' => [
			'width' => 370,
			'height' => 200,
		],
		'canvas' => [
			'width' => 370,
			'height' => 200,
		],
	],

	'files' => [
		'mime' => 'jpg,jpeg,png,pdf',
		'size' => 128092,
		'path' => '',
		//'path' => date('Ym'),
	],

	'logo' => [
		'mime' => 'jpg,jpeg',
		'size' => 1000000,
		'image' => [
			'width' => 200,
			'height' => 200,
		],
		'canvas' => [
			'width' => 200,
			'height' => 200,
		],
		

	],
	'esb_visit_status_transition' => [
		'esb_user' => [
			1 => [7,4], //Новый
			2 => [3,7,4],
			3 => [7,4],
			7 => [],
		],
		'service' => [
			1 => [2,4,7], //Новый
			2 => [6,4,7],
			3 => [4,7],
			5 => [3,4,7],
			7 => [2],
		],

	],
	'esb_request_status_transition' => [
		'esb_user' => [
			1 => [7,4], //Новый
			2 => [7,4],
			3 => [7,4],
			7 => [],
		],
		'service' => [
			1 => [2,4,7], //Новый
			2 => [4,7],
			3 => [4,7],
			7 => [2],
		],

	],
	'retail_order_status_transition' => [
		'esb_user' => [
			1 => [4,7], //Новый
			2 => [4,7],
			3 => [4,7],
			4 => [7],
			7 => [1],
		],
		'dealer' => [
			1 => [2,4,7], //Новый
			2 => [4,7],
			3 => [2,4,7],
			4 => [7],
			7 => [1],
			
		],

	],
	'repair_status_transition' => [
		'admin' => [
			1 => [3, 4, 5, 6],
			2 => [3, 4, 5],
			3 => [],
			4 => [],
			5 => [],
			6 => [1],
		],
		'user' => [
			1 => [],
			2 => [],
			3 => [2],
			4 => [],
			5 => [],
			6 => [],
		],

	],
	'stand_order_status_transition' => [
		'admin' => [
            1   => [6], // Новый
			2	=> [6], //Согласован менеджером, отправлен дистрибьютору
            3	=> [6], //	Ожидание оплаты дистрибьютору
            4	=> [6], //	Оплачен
            5	=> [6], //	Отгружен
            6	=> [6], //	Отменен
            7	=> [6], //	На согласовании у клиента
            8	=> [6], //	Согласован у клиента
            9	=> [6], //	Цены согласованы
            10	=> [6], //	Согласован, отгрузка от Ферроли
            11	=> [6], //	Ожидание оплаты, отгрузка от Ферроли
            12	=> [6], //	Получен клиентом
            13	=> [6], //	Проверен и закрыт
            14	=> [6], //	На согласовании у руководителя отдела
            15	=> [6], //	На согласовании у директора 
            16	=> [6], //	На исправлении
		],
		'manager' => [
			1   => [6,10,2], //  Новый
			2	=> [6,1], //  Согласован менеджером, отправлен дистрибьютору
            3	=> [6,1], //	Ожидание оплаты дистрибьютору
            4	=> [], //	Оплачен
            5	=> [], //	Отгружен
            6	=> [1], //	Отменен
            7	=> [6,1], //	На согласовании у клиента
            8	=> [6,1], //	Согласован у клиента
            9	=> [6], //	Цены согласованы
            10	=> [6], //	Согласован, отгрузка от Ферроли
            11	=> [6], //	Ожидание оплаты, отгрузка от Ферроли
            12	=> [14], //	Получен клиентом
            13	=> [], //	Проверен и закрыт
            14	=> [], //	На согласовании у руководителя отдела
            15	=> [], //	На согласовании у директора 
            16	=> [6,14,1], //	На исправлении
		],
		'user' => [
			1   => [6], //  Новый
			2	=> [12], //  Согласован менеджером, отправлен дистрибьютору
            3	=> [], //	Ожидание оплаты дистрибьютору
            4	=> [12], //	Оплачен
            5	=> [12], //	Отгружен
            6	=> [], //	Отменен
            7	=> [6], //	На согласовании у клиента
            8	=> [], //	Согласован у клиента
            9	=> [], //	Цены согласованы
            10	=> [12], //	Согласован, отгрузка от Ферроли
            11	=> [], //	Ожидание оплаты, отгрузка от Ферроли
            12	=> [], //	Получен клиентом
            13	=> [], //	Проверен и закрыт
            14	=> [], //	На согласовании у руководителя отдела
            15	=> [], //	На согласовании у директора 
            16	=> [6,14], //	На исправлении
		],
		'distributor' => [
			1   => [], //  Новый
			2	=> [6,4,5], //  Согласован менеджером, отправлен дистрибьютору
            3	=> [6,4], //	Ожидание оплаты дистрибьютору
            4	=> [5], //	Оплачен
            5	=> [], //	Отгружен
            6	=> [], //	Отменен
            7	=> [6], //	На согласовании у клиента
            8	=> [6], //	Согласован у клиента
            9	=> [6], //	Цены согласованы
            10	=> [], //	Согласован, отгрузка от Ферроли
            11	=> [], //	Ожидание оплаты, отгрузка от Ферроли
            12	=> [], //	Получен клиентом
            13	=> [], //	Проверен и закрыт
            14	=> [], //	На согласовании у руководителя отдела
            15	=> [], //	На согласовании у директора 
            16	=> [6,14], //	На исправлении
		],
		'head' => [
			1   => [6,14], //  Новый
			2	=> [6], //  Согласован менеджером, отправлен дистрибьютору
            3	=> [6], //	Ожидание оплаты дистрибьютору
            4	=> [6], //	Оплачен
            5	=> [6], //	Отгружен
            6	=> [6], //	Отменен
            7	=> [6], //	На согласовании у клиента
            8	=> [6], //	Согласован у клиента
            9	=> [6], //	Цены согласованы
            10	=> [6], //	Согласован, отгрузка от Ферроли
            11	=> [6], //	Ожидание оплаты, отгрузка от Ферроли
            12	=> [6], //	Получен клиентом
            13	=> [6], //	Проверен и закрыт
            14	=> [6,13,16], //	На согласовании у руководителя отдела
            15	=> [6], //	На согласовании у директора 
            16	=> [6], //	На исправлении
		],
		'director' => [
			1   => [6,14], //  Новый
			2	=> [6], //  Согласован менеджером, отправлен дистрибьютору
            3	=> [6], //	Ожидание оплаты дистрибьютору
            4	=> [6], //	Оплачен
            5	=> [6], //	Отгружен
            6	=> [6], //	Отменен
            7	=> [6], //	На согласовании у клиента
            8	=> [6], //	Согласован у клиента
            9	=> [6], //	Цены согласованы
            10	=> [6], //	Согласован, отгрузка от Ферроли
            11	=> [6], //	Ожидание оплаты, отгрузка от Ферроли
            12	=> [6], //	Получен клиентом
            13	=> [6], //	Проверен и закрыт
            14	=> [6], //	На согласовании у руководителя отдела
            15	=> [6,16,10,2], //	На согласовании у директора 
            16	=> [6], //	На исправлении
		],
		'worker' => [
			1 => [],
			2 => [],
			3 => [],
			4 => [5,6],
			5 => [6],
			6 => [],
			7 => [],
			8 => [],
			9 => [],
			10 => [],
		],
		'viewer' => [
			1 => [],
			2 => [],
			3 => [],
			4 => [],
			5 => [],
			6 => [],
			7 => [],
			8 => [],
			9 => [],
			10 => [],
		],

	],
    
	'mission_status_transition' => [
		
		'user' => [
			1 => [2,5],  // Черновик
			2 => [5],      // На согласовании у руководителя
			3 => [4,5],      // Согласован у руководителя
			4 => [5],    // Завершен успешно
			5 => [1],     // Отменен
		],
		'head' => [
			1 => [2,6],  // Черновик
			2 => [6,5],      // На согласовании у руководителя
			3 => [4,5,1],      // Согласован у руководителя
			4 => [5],    // Завершен успешно
			5 => [1],     // Отменен
			6 => [5,1],     // На согласовании у директора
		],
		'director' => [
			1 => [2,3],  // Черновик
			2 => [3,4,5],      // На согласовании у руководителя
			3 => [4,5,1],      // Согласован у руководителя
			4 => [5],    // Завершен успешно
			5 => [1],     // Отменен
            6 => [3,5,1],     // На согласовании у директора
		],
		'viewer' => [
			1 => [],
			2 => [],
			3 => [],
			4 => [],
			5 => [],
			6 => [],
			7 => [],
			8 => [],
			9 => [],
			10 => [],
		],

	],
    
	'partner_plus_request_status_transition' => [
		'admin' => [
			1 => [10],  // Новый -> Руководителю, Удалить, Отменить
			2 => [10],      // На согласовании у руководителя
			3 => [10],      // На согласовании у директора
			4 => [10],    // Согласован у директора -> Выполняю, Отменить
			7 => [1],    // Отменен после согласования
            8 => [1],    // Удален
            9 => [1,10],    // На исправлении
            10 => [1],      // Отменен
		],
		'ferroli_user' => [
			1 => [2,8,10],  // Новый -> Руководителю, Удалить, Отменить
			2 => [10],      // На согласовании у руководителя
			3 => [10],      // На согласовании у директора
			4 => [10],    // Согласован у директора -> Выполняю, Отменить
			7 => [1,2],    // Отменен после согласования
            8 => [],    // Удален
            9 => [2,10],    // На исправлении
            10 => [1],      // Отменен
		],
		'supervisor' => [
			1 => [2,8,10],  // Новый -> Руководителю, Удалить, Отменить
			2 => [3,9,10],      // На согласовании у руководителя
			3 => [9,10],      // На согласовании у директора
			4 => [10],    // Согласован у директора -> Выполняю, Отменить
			7 => [1,2],    // Отменен после согласования
            8 => [],    // Удален
            9 => [2,10],    // На исправлении
            10 => [1],      // Отменен
		],
		'director' => [
			1 => [2,8,10],  // Новый -> Руководителю, Удалить, Отменить
			2 => [10],      // На согласовании у руководителя
			3 => [10],      // На согласовании у директора
			4 => [10],    // Согласован у директора -> Выполняю, Отменить
			7 => [1,2],    // Отменен после согласования
            8 => [],    // Удален
            9 => [2,10],    // На исправлении
            10 => [1],      // Отменен
		],
		'partner' => [
			1 => [2],  // Новый -> Руководителю, Удалить, Отменить
			2 => [10],      // На согласовании у руководителя
			3 => [10],      // На согласовании у директора
			4 => [10],    // Согласован у директора -> Выполняю, Отменить
			7 => [1,2],    // Отменен после согласования
            8 => [],    // Удален
            9 => [2,10],    // На исправлении
            10 => [1],      // Отменен
		],
		'distributor' => [
			1 => [2,8,10],  // Новый -> Руководителю, Удалить, Отменить
			2 => [10],      // На согласовании у руководителя
			3 => [10],      // На согласовании у директора
			4 => [10],    // Согласован у директора -> Выполняю, Отменить
			7 => [1,2],    // Отменен после согласования
            8 => [],    // Удален
            9 => [2,10],    // На исправлении
            10 => [1],      // Отменен
		],
		'viewer' => [
			1 => [],  // Новый -> Руководителю, Удалить, Отменить
			2 => [],      // На согласовании у руководителя
			3 => [],      // На согласовании у директора
			4 => [],    // Согласован у директора -> Выполняю, Отменить
			7 => [],    // Отменен после согласования
            8 => [],    // Удален
            9 => [],    // На исправлении
            10 => [],      // Отменен
		],

	],
    'tender_sub_head_statuses' => [12],
    
	'tender_status_transition' => [
		'admin' => [
			1 => [10],
			2 => [10],
			3 => [10],
			4 => [10],
			5 => [10],
			6 => [10],
            9 => [3,10],
            11 => [7,6],
		],
		'user' => [
			1 => [2,10,8],  // Новый
			2 => [10],      // На согласовании у руководителя
			12 => [10],      // На согласовании у руководителя
			3 => [10],      // На согласовании у директора
			4 => [10,5],    // Согласован у директора -> Выполняю, Отменить
			5 => [7,6,11],     // Согласован, выполняется
			6 => [],        // Завершен успешно
            7 => [1,2],    // Отменен после согласования
            8 => [],    // Удален
            9 => [2,10],    // На исправлении
            10 => [1],      // Отменен
            11 => [7,6],       //Оплачен
            13 => [2,8,9],       //На согласовании у менеджера
		],
		'distr_user' => [
			1 => [13,10,8],  // Новый -> Руководителю, Удалить, Отменить
			2 => [10],      // На согласовании у руководителя
			12 => [10],      // На согласовании у руководителя
			3 => [10],      // На согласовании у директора
			4 => [10],    // Согласован у директора -> Выполняю, Отменить
			5 => [7],     // Согласован, выполняется
			6 => [],        // Завершен успешно
            7 => [1,2],    // Отменен после согласования
            8 => [],    // Удален
            9 => [13,10],    // На исправлении
            10 => [1],      // Отменен
            11 => [7,6],       //Оплачен
            12 => [10],       //На согласовании у
            13 => [10],       //На согласовании у менеджера
		],
		'sub_head' => [
			/**1 => [2,8,10],  // Новый -> Руководителю, Удалить, Отменить
			2 => [10],      // На согласовании у руководителя
			3 => [10],      // На согласовании у директора
			4 => [10,5],    // Согласован у директора -> Выполняю, Отменить
			5 => [7,6,11],     // Согласован, выполняется
			6 => [],        // Завершен успешно
            7 => [1,2],    // Отменен после согласования
            8 => [],    // Удален
            9 => [2,10],    // На исправлении
            10 => [1],      // Отменен
            11 => [7,6],       //Оплачен
            12 => [2,8,10],       //На согласовании у **/
		],
		'head' => [
			1 => [10],
			12 => [9,10],
			2 => [3,9,10],
			3 => [9,10],
			4 => [9,7,11],
			5 => [7,11],
			6 => [],
			7 => [],
			8 => [],
            9 => [10,3],
            10 => [],
            11 => [7,6],       //Оплачен
		],
		'director' => [
			1 => [10],
			2 => [4,9,10],
			3 => [4,9,10],
			4 => [9,10,11],
			5 => [9,10,11],
			6 => [],
			7 => [],
			8 => [1],
			9 => [10,4],
			10 => [1],
            11 => [7,6],       //Оплачен
		],
		'worker' => [
			1 => [],
			2 => [],
			3 => [],
			4 => [5,6,11],
			5 => [6,11],
			6 => [],
			7 => [],
			8 => [],
			9 => [],
			10 => [],
            11 => [7,6],       //Оплачен
		],
		'viewer' => [
			1 => [],
			2 => [],
			3 => [],
			4 => [],
			5 => [],
			6 => [],
			7 => [],
			8 => [],
			9 => [],
			10 => [],
		],

	],
    'tender_status_approved' => [4,5,6],

    'director_email' => [
    'leonid.evlentiev@ferroli.com','evlleon83@yandex.ru'
    ],
    
	'ticket_status_transition' => [
		'admin' => [
			1 => [3,4,6],
			2 => [3,4,6],
			3 => [4],
			4 => [1],
			5 => [1,4,6],
		],'user' => [
			1 => [3,4,6],
			2 => [3,4,6],
			3 => [4],
			4 => [1],
			5 => [1,4],
		],
		
		

	],
    
	'mailing' => [
		'mimes' => 'jpg,jpeg,png,pdf,xls,xlsx,doc,docx',
		'message_max_size' => 25000000,  // 25мб
		'attachment_max_size' => 5000000,   // 5мб
	],


	'per_page' => [
		'user' => 50,
		'block' => 250,
		'catalog' => 25,
		'repair' => 250,
		'mounting' => 100,
		'member' => 100,
		'trade' => 100,
		'launch' => 100,
		'engineer' => 100,
		'act' => 100,
		'serial' => 100,
		'period' => 100,
		'order' => 100,
		'product' => 16,
		'product_admin' => 100,
		'archive' => 25,
		'product_list' => 100,
		'product_type' => 25,
		'message' => 250,
		'announcement' => 12,
		'storehouse_product' => 100,
		'storehouse' => 100,
	],

	'per_page_range' => [
		'r16' => [16 => 16, 32 => 32, 64 => 64, 128 => 128, 256 => 256, 512 => 512, 1024 => 1024, 9999999 => '∞...'],
		'r10' => [10 => 10, 25 => 25, 50 => 50, 100 => 100, 250 => 250, 500 => 500, 1000 => 1000, 9999999 => '∞...'],
	],

	'front_routes' => [
		'index',
		'academy',
		'login',
		'register',
		'register_fl',
		'register_fls',
		'feedback',
		'feedback-client',
		'feedback_success',
		'service-centers',
		'where-to-buy',
		'online-stores',
		'black-list',
		'mounter-requests',
		'announcements.index',
		'announcements.show',
		'events.index',
		'events.webinars',
		'events.webinars.index',
		'events.webinars.show',
		'events.show',
		'event_types.show',
		'members.index',
		'members.create',
		'datasheets.index',
		'datasheets.show',
		'catalogs.index',
		'catalogs.show',
		'catalogs.list',
		'equipments.show',
		'ferroliplus',
		'managerplus',
		'masterplus',
		'products.index',
		'schemes.show',
		'products.schemes',
		'products.scheme',
		'products.list',
		'products.show',
		'whereToBuy',
		'cart',
		'password.reset',
		'password.request',
		'unsubscribe',
        'public-user-card',
	],
    'spec_menu_routes' => [
       // 'products'=>['show'=>'front_lp_marketru'],
    	'registerd'=>'register',
    	'products-lp'=>'front_lp_marketru',
    	'e-warranty'=>'front_lp_ew_marketru',
        //'equipments'=>['show'=>'front_marketru_products'],
    ],

	'seeders' => [
		'countries',
		'regions',
		'contragent_types',
		'address_types',
		'contact_types',
		'product_types',
		'currencies',
		'users',
	],

	'run' => [
		['site:resource', []],
		['rbac:resource', []],
		['migrate', []],
		['db:seed', ['--class' => 'SiteSeeder']],
		['db:seed', ['--class' => 'RbacSeeder']],
	],

];
