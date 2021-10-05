<?php
return [
    'schedule'    => 'синхронизацию',
    'schedules'   => 'Синхронизации с 1С',
    'icon'        => 'refresh',
    //
    'status'      => 'Статус',
    'action_id'   => 'Тип данных',
    'url'         => 'Ссылка с JSON данными',
    'message'     => 'Текст ошибки',
    //
    'synchronize' => 'Синхронизировать с 1С',
    'created'     => 'Синхронизация успешно создана',
    'updated'     => 'Синхронизация успешно обновлена',
    'deleted'     => 'Синхронизация успешно удалена',
    //
    'error'       => 'Синхронизация невозможна...',
    //
    'statuses'    => [
        '0' => [
            'text'  => 'В обработке',
            'icon'  => 'circle-o',
            'color' => 'primary',
        ],
        '1' => [
            'text'  => 'Успех',
            'icon'  => 'check',
            'color' => 'success',
        ],
        '2' => [
            'text'  => 'Ошибка',
            'icon'  => 'close',
            'color' => 'danger',
        ],
    ],

];