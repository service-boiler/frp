<?php
return [

    'tenders'    =>'Тендеры',
    'tender'    =>'Тендер',
    'created'    =>'Тендерная заявка создана',
    'products'  => 'Оборудование',
    'manager_id'    =>'Менеджер',
    'head_id'       =>'Руководитель',
    'region_id'     => 'Регион',
    'source_id'     => 'Источник заявки',
    'city'          => 'Город', 
    'address'       => 'Адрес',
    'address_addon' => 'Дополнение к адресу',
    'address_name' => 'Название объекта',
    'tender_category_id' =>'Категория тендера',
    'investor_name' => 'Инвестор', 
    'customer'      => 'Заказчик',
    'gen_contractor' => 'Генподрядчик',
    'gen_designer'  => 'Генпроектировщик',
    'contractors'     => 'Подрядчики',
    'designers'     => 'Проектировщики',
    'picker'        => 'Комплектовщик', 
    'distributor_id' =>'Дистрибьютор',
    'distr_contact_phone' => 'Телефон контактного лица дистибьютора',
    'distr_contact' => 'Контакты дистрибьютора',
    'planned_purchase_date'=>'Планируемая дата закупки',
    'planned_purchase_year'=>'Планируемый год закупки',
    'planned_purchase_month'=>'Планируемый месяц закупки',
    'date_price' => 'Цена действ. до: ',
    'rates' => 'Курс для дистрибьютора',
    'rates_min' => 'Коридор курса для дистрибьютора от',
    'rates_max' => 'Коридор курса для дистрибьютора до',
    'rates_object_min' => 'Коридор курса для объекта от',
    'rates_object_max' => 'Коридор курса для объекта до',
    'rates_object' => 'Курс для объекта',
    'cb_rate' => 'Цены в евро по курсу ЦБ на день оплаты',
    'comment ' => 'Примечания',
    'result' => 'Результаты тендера',
    'status_id' =>'Статус тендера',
    'cost_object' =>'Цена для объекта',
    'cost' =>'Цена для дистрибьютора',
    'head_approved_status_id' =>'Статус согласования с руководителем',
    'head_approved_date' =>'Дата согласования с руководителем',
    'director_approved_status_id' =>'Статус согласования с директором',
    'director_approved_date' =>'Дата согласования с директором',
    'director_approved_date_short' =>'Cогласован с дир.',



    'updated' => 'Тендерная заявка обновлена',
    'discount' => 'Скидка от РРЦ для дистрибьютора',
    'discount_object' => 'Скидка от РРЦ для объекта',
    'count' => ' количество ',
	'tender_price_status' => [
        '0' => 'еще не согласована',
        '1' => 'согласована',
        '2' => 'отказано'
    ],
	'tender_price_color' => [
        '0' => '#FFFF33',
        '1' => '#CCFF99',
        '2' => '#FFCCCC'
    ],
	'tender_source' => [
        'source' => 'Откуда поступила заявка на тендерную поставку',
        '0' => '- не задано -',
        '1' => 'от менеджера',
        '2' => 'от дистрибьютора'
        
    ],
    
	'message' => [
		'add' => 'Добавен товар :product',
		'delete' => 'Удален товар :product',
		'product' => "У товара :product были изменены поля:",
		'item' => ":column с :original на :change",
		'columns' => [
			'price' => ' (€) ',
			'cost_object' => ' (€) ',
			'cost' => ' (€) ',
			'count' => ' (шт.) ',
			'discount' => ' % ',
			'discount_object' => ' % ',
		],
	],
];
