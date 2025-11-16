<?php
declare(strict_types=1);

use Beeralex\Core\Config\Module\Schema\Schema;
use Beeralex\Core\Config\Module\Schema\SchemaTab;

return Schema::make()
    ->tab(
        'edit1',
        'Общие настройки',
        'Интеграция с API GigaChat',
        function (SchemaTab $tab) {
            $tab->input(
                'authorization_key',
                'Ключ авторизации',
                'Обязательные'
            );

            $tab->input(
                'scope',
                'Scope'
            );
            
            $tab->input(
                'base_oauth_url',
                'Базовый адрес запроса для получения токена',
                null,
                null,
                false,
                'https://ngw.devices.sberbank.ru:9443'
            );

            $tab->input(
                'base_gigachat_url',
                'Базовый адрес запроса к GigaChat API',
                null,
                null,
                false,
                'https://gigachat.devices.sberbank.ru'
            );

            $tab->select(
                'gigachat_model',
                'Модель формирующая ответ на сообщение',
                ['' => 'Выберите модель'],
                'Прочие',
                false,
                ''
            );

            $tab->checkbox(
                'logs_enable',
                'Включить логирование'
            );

            $tab->checkbox(
                'cert_enable',
                'Сертификат НУЦ Минцифры установлен на уровне системы'
            );
        }
    );