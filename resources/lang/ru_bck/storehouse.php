<?php
return [
	'storehouse' => 'склад дистрибьютора',
	'storehouses' => 'Склады дистрибьюторов',
	'icon' => 'inbox',
	'linked_addresses' => 'Связанные адреса',
	//
	'name' => 'Наименование',
	'enabled' => 'Включить склад',
	'everyday' => 'Обновлять ежедневно',
	'user_id' => 'Владелец',
	'uploaded_at' => 'Дата обновления остатков',
	'url' => 'Ссылка автоматической загрузки',
	//
	'created' => 'Склад дистрибьютора успешно добавлен',
	'updated' => 'Склад дистрибьютора успешно обновлен',
	'deleted' => 'Склад дистрибьютора успешно удален',
	//
	'error' => [
		'deleted' => 'Ошибка удаления склада дистрибьютора',
		'url' => 'Поле Ссылка автоматической загрузки должно быть ссылкой на действительный URL адрес',
		'upload' => [
			'title' => 'Ошибка обновления остатков',
			'greeting' => 'Здравствуйте, :user.',
			'message' => ':date при попытке автоматического обновления остатков запчастей на складе :link обнаружены следующие ошибки:',
			'url_not_exist' => 'Не доступен указаный URL адрес <a target="_blank" href=":url">:url</a>',
			'xml_load_failed' => 'Не удалось загрузить корректный YML файл',
			'data_is_empty' => 'Не найдены запчасти в YML файле',
			'sku_not_exist' => 'Номенклатура :offer_id - не указан артикул',
			'sku_not_found' => 'Артикул :sku - не найден',
			'offer_duplicate_found' => 'Номенклатура :offer_id - найден дубликат',
			'sku_duplicate_found' => 'Артикул :sku - найден дубликат',
			'quantity_not_exist' => 'Артикул :sku - не указано количество',
			'quantity_not_integer' => 'Артикул :sku - количество должно быть целым числом',
			'quantity_not_positive' => 'Артикул :sku - количество должно быть больше нуля',
			'quantity_max' => 'Артикул :sku - максимальное количество должно быть <= :max',
		],
	],
	'placeholder' => [
		'name' => 'Например, Центральный склад, Орехово',
		'url' => 'Например, http://site.ru/products.xml',
		'search' => 'Поиск',
		'search_user' => 'Поиск владельцев',
	],
	'help' => [
		'back' => 'Вернуться назад',
		'products' => 'Запчасти',
		'addresses' => 'Привязать к адресам',
		'url' => 'Ссылка на файл в формате YML, который используется для выгрузки на Яндекс Маркет',
		'search_user' => 'По имени пользователя',
		'url_not_defined' => 'Ссылка не указана',
	],
	'header' => [
		'upload' => 'Обновить остатки запчастей на складе',
		'download' => 'Выгрузить остатки в Excel',
		'quantity' => 'Остатки запчастей на складах',
	],
	'excel' => [
		'index' => [
			"A" => "#",
			'B' => 'Пользователь',
			'C' => 'Склад',
			'D' => 'Регион',
			'E' => 'Артикул',
			'F' => 'Наименование',
			'G' => 'Количество',
			'H' => 'Дата обновления остатков',
		],
		'show' => [
			"A" => "#",
			'B' => 'Артикул',
			'C' => 'Наименование',
			'D' => 'Количество',
			'E' => 'Дата обновления остатков',
		],
		'user' => [
			"A" => "#",
			'B' => 'Артикул',
			'C' => 'Наименование',
			'D' => 'Количество',
			'E' => 'Дата обновления остатков',
			'F' => 'Цена с НДС',
			'G' => 'Склад',
			'H' => 'Адрес',
		],
	],
];