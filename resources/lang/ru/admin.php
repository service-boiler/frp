<?php
return [
    'bonus_val'         => 'баллов',
    'mirror_sfru'       => 'Править на service.ferroli.ru',
    'video_blocks_index'       => 'Видео Youtube',
    'video_block'       => 'видео',
	 'video_block_deleted' => 'Запись удалена',
	 'video_block_url'	=> 'Ссылка на видео. Обязательно код для встраивания (поделиться -  встроить https://www.youtube.com/embed/.......)',
	 'video_block_title'	=> 'Заголовок видео',
     'head_banner_blocks_index' => 'Заглавные баннеры',
     'head_banner_block' => 'Баннер',
     'head_banner_block_path' => 'URL, где отображается',
     'head_banner_block_url' => 'URL, куда ссылается',
     'head_banner_block_title' => 'Название только для админки',
     'index_cards_blocks' => 'Карточки разделов на главной',
     'index_cards_blocks_index' => 'Карточки разделов на главной',
     'index_cards_block' => 'Карточку',
     'index_cards_block_url' => 'URL, куда ссылается',
     'index_cards_block_title' => 'Название (только для админки)',
     'index_cards_block_text' => 'Текст карточки',
     'index_cards_block_image_size' => ' (Размер 365х350 px)',
     'index_quadro_blocks_index' => 'Квадратный баннер на главной',
     'index_quadro_block' => 'блок',
     'index_quadro_block_url' => 'URL, куда ссылается',
     'index_quadro_block_title' => 'Название (только для админки)',
     'index_quadro_block_text' => 'Текст на блоке',
     'index_quadro_block_text_hover' => 'Всплывающий при наведении текст',
     'index_quadro_block_sort_order' => 'Номер блока (изменить можно в списке перетаскиванием вверх-вниз)',
    'created'     => 'Узел оборудования успешно создан',
    'updated'     => 'Узел оборудования успешно обновлен',
    'sms_limit_subject'     => 'Превышен лимит отправки СМС',
    'variables'     => 'Переменные',
    'variable_value'     => 'Значение переменной',
    'variable_comment'     => 'Комментарии к переменной',
    'variable_updated'     => 'Значение переменной обновлено',
    'serial_upload_help'     => 'Выберите Excel файл и нажмите кнопку "Загрузить. Лимит 60.000 строк.  <a href="/up/serial-example.xlsx">Пример xlsx</a>',
    //
    'retail_order' => [
        'retail_order' => 'Заказ с маркета',
        'retail_orders' => 'Заказы с маркета',
        'user' => 'Дилер',
        'client' => 'Клиент',
        'products' => 'Товары',
    ],
    'crm_sales_plan' => [
        'index' => 'Продажи по региональным дистрибьюторам. План-факт',
        'index_sm' => 'Продажи план-факт',
    ],
    'esb_catalog_service' => [
        'price_index'=>'Прайс-лист на услуги',
        'price_index_sm'=>'Прайс-лист на услуги',
        'prices'=>'Цены на услуги АСЦ',
        'index_sm'=>'Каталог услуг сервиса',
        'services'=>'Каталог услуг АСЦ',
        'price_add'=>'Добавить цену в прейскурант',
        'service_add'=>'Добавить услугу в справочник',
        'shared'=>'Для всех сервисов',
        'brand'=>'Бренд оборудования',
        'name'=>'Наименование',
        'placeholder_name'=>'Например, Ремонт напольного котла от 30 до 60 кВт, или Транспортные расходы до 10 км от города',
        'esb_catalog_service_type'=>'Тип услуги',
        'esb_catalog_service_id'=>'Наименование услуги из справочника',
        'price'=>'Цена, руб.',
        'cost_std'=>'Рекомендованная стоимость, руб.',
        'created'=>'Услуга создана',
        'price_created'=>'Запись в прайсе создана',
        'updated'=>'Услуга обновлена',
        'price_updated'=>'Запись в прайсе обновлена',
        'asc_info'=>'Важная информация об АСЦ (например, радиус обслуживания или районы обслуживания)',
        'asc_info_address_upd'=>'Информация будет добавлена для адреса',
        'public_card'=>'Публичная карточка сервисного центра',

    ],
    'user_stars_help' => [
      '1' => 'Сервис авторизован, сотрудники прошли обучение и аттестацию.',
      '2' => "Сервис авторизован, сотрудники прошли обучение и аттестацию. &nbsp; &nbsp; &nbsp;
                Есть склад запчастей ",
      '3' => 'Сервис авторизован, сотрудники прошли обучение и аттестацию. &nbsp;&nbsp;&nbsp;
                Участвует в программе электронной гарантии'
    ],
    'user_tooltip'=>[
        'e_warranty'=>'Участвует в программе электронной гарантии',
    ],
    //
    'customer' => [
        'index' => 'Клиенты 2-го уровня',
        'add' => 'Добавить клиента',
        'edit' => 'Изменить клиента',
        'name' => 'Название/Имя',
        'roles' => 'Роли клиента',
        'role' => 'Роль клиента',
        'name_show' => 'Название/Имя',
        'comment' => 'Примечание',
        'contacts' => 'Контактные лица',
        'phone' => 'Общий телефон',
        'email' => 'Общий email',
        'contacts_common' => 'Общая контактная информация',
        'any_contacts' => 'Другая общая контактная информация',
        'lpr' => 'Лицо принимающее решения',
        'phone_contact' => 'Телефон',
        'email_contact' => 'Email',
        'contact_add' => 'Создать контактное лицо',
        'contact_edit' => 'Изменить контактное лицо',
        'any_contacts_contact' => 'Другая контактная информация',
        'created' => 'Клиент создан',
        'updated' => 'Клиент обновлен',
        'region_locality' => 'Город, регион',
        '' => '',
    ],
    'contact' => [
        'phone' => 'Телефон',
        'email' => 'email',
        'any_contacts' => 'Другая контактная информация',
        'lpr' => 'Лицо принимающее решения',
        'add' => 'Создать контактное лицо',
        'edit' => 'Изменить контактное лицо',
        'created' => 'Клиент создан',
        'updated' => 'Клиент обновлен',
        'region_locality' => 'Город, регион',
        'position' => 'Должность',
    ],
    'revision_part' => [
        'index' => 'Ревизии оборудования. Замены запчастей.',
        'add' => 'Добавить замену запчасти',
        'created' => 'Успешно создано',
        'deleted' => 'Успешно удалено',
        'notice_sended' => 'Отправлено в ЛК',
        'error_deleted' => 'Ошибка удаления',
        'revision_part' => 'Замена запчасти',
        'part_new_name' => 'Новое наименование',
        'part_old_name' => 'Старое наименование',
        'part_old_sku' => 'Старый артикул',
        'part_new_sku' => 'Новый артикул',
        'enabled' => 'Включено',
        'enabled_1' => 'Включено',
        'enabled_0' => 'Не включено',
        'public' => 'Публичный доступ для АСЦ',
        'public_0' => 'Нет. Только внутренняя информация',
        'public_1' => 'Да',
        'interchange' => 'Взаимозаменяемы',
        'interchange_0' => 'Нет',
        'interchange_1' => 'Да',
        'date_change' => 'Дата внесения изменений',
        'date_notice' => 'Дата публикации или оповещения об изменениях',
        'date_notice_short' => 'Дата публикации об изменениях',
        'text_object' => 'Предмет изменения',
        'description' => 'Описание изменения',
        'comments' => 'Комментарии',
        'start_serial' => 'Начиная с серийного номера',
        'products' => 'Оборудование в котором внесены изменения',
        'product_id' => 'Модель оборудования',
        
    ],
    'mission' => [
        'index' => 'Командировки',
        'mission' => 'Командировка',
        'add' => 'Добавить командировку',
        'created' => 'Успешно создано',
        'edit' => 'Изменить командировку',
        'updated' => 'Успешно обновлена',
        'deleted' => 'Успешно удалено',
        'error_deleted' => 'Ошибка удаления',
        'date_from' => 'Дата c',
        'date_to' => 'Дата по',
        'comments' => 'Комментарии',
        'users' => 'Командируемые',
        'budget' => 'Бюджет',
        'budget_currency' => 'Валюта',
        'main' => 'Главный ответственный',
        'division' => 'Подразделение компании',
        'target' => 'Цель',
        'result' => 'Результат / отчет менеджера',
        'project' => 'Проект для CRM',
        'status' => 'Статус командировки',
        'created_by' => 'Создатель',
        'event' => 'Мероприятие',
        'clients' => 'Клиенты в регионе',
        'report_download' => 'Скачать служебное задание и отчет для бухгалтерии',

    ],
    'template_file' => [
        'template_file'=>'Файл шаблона',
        'template_files'=>'Файлы шаблонов',
        'name'=>'Имя шаблона',
        'file_id'=>'id шаблона / id файла',
        'created'=>'Файл загружен',

    ],
    //
    'review'      => [
    'review' => 'Отзыв',
    'reviews' => 'Отзывы',
    'reviewable' => 'Товар',
    'user_id' => 'Пользователь',
    'user_id_help' => '(если пользователь зарегистрирован на сайте)',
    'status_id' => 'Статус',
    'no_ident' => 'не определен',
    'ip' => 'ip-адрес',
    'message' => 'Текст отзыва',
    'name' => 'Имя',
    'email' => 'email',
    'phone' => 'Телефон',
    'rate' => 'Оценка',
    'created_at' => 'Дата отзыва',
    'updated' => 'Отзыв обновлен',
    'email_h1' => 'Отзыв на сайте Ferroli',
    'email_title_admin' => 'Отзыв на сайте Ferroli',
    'email_title' => 'Отзыв на сайте Ferroli',
    ],
    
    'black_list' => [
    'black_list' => 'Черный список',
    'address' => 'адрес',
    'web' => 'Сайт',
    'name' => 'Название',
    'full' => 'Адрес',
    'comment' => 'Комментарий',
    'placeholder' => [
    'full' => 'Например, Минск'
    ],
    ],
    'placeholder' => [
        'url' => 'https://www.youtube.com/embed/aWglBq_ErPE',
        'title' => 'Название только для админки',
        'path' => 'Например, academy или /',
        'index_cards_block_text' => 'Текст карточки',
        'name' => 'Название',
    ],
	 'video_icon'            => 'youtube-play',
     
     'promocodes' => [
     'promocode' => 'промокод',
     'created' => 'Промокод создан',
     'expiry_at' => 'срок действия по ',
     'name' => 'Название промокода',
     'index' => 'Промокоды',
     'bonuses' => 'Количество начисляемых бонусных баллов',
     'short_token' => 'Короткий код для ввода на сайте',
     'expiry_placeholder' => 'Оставить поле пустым, если без срока действия',
     ],
     
     'webinar' => [
     'index' => 'Вебинары',
     'created' => 'Вебинар добавлен',
     'deleted' => 'Вебинар удален',
     'enter' => 'Войти в вебинар и начать просмотр',
     'register' => 'Я приму участие',
     'unregister' => 'Я не буду участвовать',
     'you_registered' => 'Ваша заявка на участие принята',
     'you_registered_earlier' => 'Вы зарегистрировались ранее',
     'you_unregistered' => 'Ваша заявка на участие удалена. Но может птребоваться дополнительно отказаться от участия перейдя по ссылке в email письме от Zoom',
     'you_unregistered_earlier' => 'Вы не регистрировались ранее',
     'you_denied' => 'Ваш email заблокирован в Zoom ранее, пожалуйста, обратитесь к организатору конференции info@ferroli.ru',
     'no_id_service' => 'Вход в вебинар невозможен. Не указан идентификатор вебинара на сервисе вебинаров.',
     'webinar_add' => 'вебинар',
     'create_zoom_webinar' => 'Создать в Zoom',
     'zoom_webinar_created' => 'Вебинар успешно создан в Zoom',
     'zoom_webinar_exists' => 'Уже создан в Zoom. Перейти',
     'get_zoom_webinar_stat' => 'Запросить статистику',
     'zoom_webinar_stat_updated' => 'Cтстистика обновлена.',
     'zoom_webinar_stat_empty' => 'Нет стстистики. Возможно, вебинар еще не состоялся.',
     'webinar' => 'вебинар',
     'name' => 'Название вебинара',
     'link_service' => 'Полная ссылка на вебинар на вебинарном сервисе',
     'link_service_placeholder' => 'Например, https://ferroli.webinar.ru/asdasd',
     'id_service' => 'Идентификатор вебинара на вебинарном сервисе',
     'zoom_id' => 'Идентификатор вебинара на Zoom',
     'id_service_placeholder' => 'Например, 98011d5cfce44d03',
     'annotation' => 'Краткое описание вебинара',
     'description' => 'Подробное описание вебинара',
     'theme' => 'Тема вебинара',
     'theme_help' => 'Вебинары должны быть привязаны к темам. Для двух вебинаров с одной темой баллы за участие будут начислены один раз.',
     'type_id' => 'Тип вебинара',
     'members' => 'Участники',
     'unauth_participants' => 'Незарегистрированные участники',
     'image_id' => 'Изображение для карточки вебинара',
     'duration' => 'Продолжительность вебинара',
     'duration_fact' => 'Фактическая продолжительность вебинара',
     'datetime' => 'Дата и время начала вебинара',
     'datetime_ph' => 'Например, 13.01.2020 23:55',
     'promocode' => 'Промокод',
     'promocode_help' => 'Выбрать <b>!! только !!</b>, если сумма баллов за участие отличается от указанного в теме, либо баллы нужно начислить вне зависимости от просмотров других вебинаров с одинаковой темой.',
     'updated' => 'Вебинар обновлен',
     'delete_cannot' => 'Нельзя удалять, если уже есть участники',
     'user_visited' => 'Посетил или нет вебинар',
     ],
     'webinar_theme' => [
     'index' => 'Темы вебинаров',
     'name' => 'Название темы',
     'promocode' => 'Промокод',
     'created' => 'Тема вебинара создана',
     'updated' => 'Тема вебинара обновлена',
     'save_and_new_webinar' => 'Сохранить и создать новый вебинар',
     '' => '',
     'webinar_theme_add' => 'тему вебинара',
     ],
     'dashboard' => [
     'asc_csc' => 'Сервисы и региональные склады',
     'ferroli-plus' => 'Регистрации Ferroli+',
     'ferroli-plus-reports' => 'Отчеты Ferroli+',
     'events' => 'Участники мероприятий',
     
     ],
     
     
     
];