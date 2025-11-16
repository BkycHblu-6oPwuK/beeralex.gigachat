# beeralex.gigachat

Модуль интеграции с API Sber GigaChat для работы с AI-моделями от Сбера в Bitrix.

## Требования

- PHP 8.1+
- Bitrix Framework
- `beeralex.core` модуль
- Сертификаты НУЦ Минцифры (опционально, но рекомендуется)

## Установка

Добавьте в `composer.json` настройку для установки в `local/modules`:

```json
{
  "extra": {
    "installer-paths": {
      "local/modules/{$name}/": ["type:bitrix-module"]
    }
  }
}
```

Установите пакеты:

```bash
composer require beeralex/beeralex.core
composer require beeralex/beeralex.gigachat
```

### Регистрация в GigaChat

1. Зарегистрируйтесь в [личном кабинете GigaChat](https://developers.sber.ru/studio)
2. Создайте проект и получите **Authorization Key** и **Scope**
3. Следуйте [инструкции по регистрации](https://developers.sber.ru/docs/ru/gigachat/quickstart/ind-create-project)

### Настройка модуля

1. Установите модуль через админку Bitrix
2. Заполните обязательные настройки:
   - **Authorization Key** - ключ авторизации из ЛК
   - **Scope** - область доступа
   - **Base OAuth URL** - URL для получения токена
   - **Base GigaChat URL** - URL API GigaChat
3. После сохранения выберите **модель по умолчанию** для генерации ответов

### Сертификаты НУЦ Минцифры

Для работы в продакшене рекомендуется установить сертификаты НУЦ Минцифры:

1. Следуйте [инструкции по установке сертификатов](https://developers.sber.ru/docs/ru/gigachat/certificates)
2. После установки включите настройку **"Сертификат НУЦ Минцифры установлен"**

⚠️ **Важно:** Если сертификаты не установлены, проверка SSL будет отключена для всех запросов к API.

---

## Основные возможности

### 💬 Чат с AI
- Диалоги с моделями GigaChat
- Системные промпты для настройки роли
- Управление параметрами генерации (temperature, top_p, max_tokens)
- Автоматическое обновление токена при истечении

### 🧠 Embeddings
- Генерация векторных представлений текста
- Поддержка batch обработки

### 📊 Управление моделями
- Получение списка доступных моделей
- Выбор модели по умолчанию
- Проверка баланса моделей

### 🔐 Авторизация
- Автоматическое получение токена
- Кэширование токена (30 минут)
- Автоматическое обновление при истечении

### 📝 Логирование
- Логи в `{module_dir}/logs/`
- Включение через настройки модуля

---

## Быстрый старт

### Простой диалог с GigaChat

```php
<?php
use Beeralex\Gigachat\Services\ChatService;
use Beeralex\Gigachat\Entity\Chat\ChatParam;
use Beeralex\Gigachat\Entity\Chat\MessagesParam;

$chatService = service(ChatService::class);

$chatParam = new ChatParam(
    new MessagesParam(
        userMessage: 'Что такое GigaChat?',
        systemPrompt: 'Ты профессиональный консультант по AI технологиям.'
    )
);

try {
    $chat = $chatService->getChat($chatParam);
    
    // Получаем первый ответ (может быть несколько вариантов)
    $message = $chat->choices->first()->message->content;
    echo $message;
    
} catch (\Exception $e) {
    echo "Ошибка: " . $e->getMessage();
}
```

### Перевод текста

```php
<?php
use Beeralex\Gigachat\Services\ChatService;
use Beeralex\Gigachat\Entity\Chat\ChatParam;
use Beeralex\Gigachat\Entity\Chat\MessagesParam;

$chatService = service(ChatService::class);

$chatParam = new ChatParam(
    new MessagesParam(
        userMessage: 'GigaChat — это сервис, который умеет взаимодействовать с пользователем в формате диалога.',
        systemPrompt: 'Ты профессиональный переводчик на английский язык. Переведи точно сообщение пользователя.'
    )
);

$chat = $chatService->getChat($chatParam);
$translation = $chat->choices->first()->message->content;
```

### Получение списка моделей

```php
<?php
use Beeralex\Gigachat\Services\ModelsService;

$modelsService = service(ModelsService::class);
$models = $modelsService->getModels();

foreach ($models->data as $model) {
    echo "ID: {$model->id}\n";
    echo "Object: {$model->object}\n";
    echo "Owner: {$model->ownedBy}\n";
    echo "---\n";
}
```

### Проверка баланса

```php
<?php
use Beeralex\Gigachat\Services\BalanceService;

$balanceService = service(BalanceService::class);
$balance = $balanceService->getBalance();

foreach ($balance->data as $item) {
    echo "Модель: {$item->model}\n";
    echo "Баланс: {$item->balance}\n";
}
```

### Генерация embeddings

```php
<?php
use Beeralex\Gigachat\Services\EmbeddingService;

$embeddingService = service(EmbeddingService::class);
$embeddings = $embeddingService->getEmbeddings([
    'Пример текста для embedding',
    'Еще один текст'
]);

foreach ($embeddings->data as $embedding) {
    echo "Index: {$embedding->index}\n";
    echo "Вектор: " . implode(', ', array_slice($embedding->embedding, 0, 5)) . "...\n";
}
```

---

## Архитектура

### Сервисы

#### ChatService
Основной сервис для диалогов с AI.

```php
public function getChat(ChatParam $params): Chat
```

[Подробнее в API документации](docs/api.md#chatservice)

#### ModelsService
Получение списка доступных моделей.

```php
public function getModels(): Models
```

#### BalanceService
Проверка баланса моделей.

```php
public function getBalance(): Balance
```

#### EmbeddingService
Генерация векторных представлений текста.

```php
public function getEmbeddings(array $input, ?string $model = null): Embeddings
```

#### AuthService
Управление токенами авторизации (используется внутри других сервисов).

```php
protected function getAccessToken(): string
protected function refreshToken(): void
```

### Сущности

#### Chat
Результат запроса к чату.

```php
public readonly Choices $choices;        // Варианты ответов
public readonly int $created;            // Timestamp создания
public readonly string $model;           // Использованная модель
public readonly string $object;          // Тип объекта
public readonly Usage $usage;            // Статистика использования
```

#### ChatParam
Параметры для запроса к чату.

```php
public readonly MessagesParam $messages;
public readonly ?float $temperature;     // 0-2, по умолчанию 1
public readonly ?float $topP;            // 0-1
public readonly ?int $maxTokens;         // Максимум токенов в ответе
public readonly float $repetitionPenalty; // Штраф за повторения
public readonly bool $stream;            // Потоковая генерация
```

#### Message
Сообщение в диалоге.

```php
public readonly RoleChat $role;          // system/user/assistant
public readonly string $content;         // Текст сообщения
```

---

## Продвинутое использование

### Настройка параметров генерации

```php
use Beeralex\Gigachat\Entity\Chat\ChatParam;
use Beeralex\Gigachat\Entity\Chat\MessagesParam;

$chatParam = new ChatParam(
    messages: new MessagesParam(
        userMessage: 'Напиши стих про программирование',
        systemPrompt: 'Ты творческий поэт'
    ),
    temperature: 1.5,           // Креативность (0-2)
    topP: 0.8,                  // Nucleus sampling
    maxTokens: 500,             // Максимум токенов
    repetitionPenalty: 1.2      // Штраф за повторения
);

$chat = $chatService->getChat($chatParam);
```

### Множественные сообщения

```php
use Beeralex\Gigachat\Entity\Chat\MessageParam;
use Beeralex\Gigachat\Entity\Chat\MessagesParam;
use Beeralex\Gigachat\Enum\RoleChat;

$messages = new MessagesParam();
$messages->addMessage(new MessageParam(
    role: RoleChat::System,
    content: 'Ты эксперт по PHP'
));
$messages->addMessage(new MessageParam(
    role: RoleChat::User,
    content: 'Что такое namespace?'
));
$messages->addMessage(new MessageParam(
    role: RoleChat::Assistant,
    content: 'Namespace - это способ организации кода...'
));
$messages->addMessage(new MessageParam(
    role: RoleChat::User,
    content: 'Приведи пример использования'
));

$chatParam = new ChatParam($messages);
$chat = $chatService->getChat($chatParam);
```

### Выбор конкретной модели

```php
// Получаем список моделей
$models = $modelsService->getModels();

// Выбираем нужную
$specificModel = $models->findById('GigaChat-Pro');

// Используем в Options или передаем в запрос
// (см. API документацию для деталей)
```

### Обработка ошибок

```php
use Beeralex\Core\Exceptions\ApiClientException;
use Beeralex\Core\Exceptions\ApiClientUnauthorizedException;

try {
    $chat = $chatService->getChat($chatParam);
} catch (ApiClientUnauthorizedException $e) {
    // Ошибка авторизации (токен истек, неверные credentials)
    // Токен обновляется автоматически, но можно логировать
    error_log("Auth error: " . $e->getMessage());
} catch (ApiClientException $e) {
    // Другие ошибки API (rate limit, invalid params и т.д.)
    error_log("API error: " . $e->getMessage());
} catch (\Exception $e) {
    // Общие ошибки
    error_log("Error: " . $e->getMessage());
}
```

---

## Настройки модуля

Через админку Bitrix → Модули → beeralex.gigachat:

| Параметр | Описание |
|----------|----------|
| `authorization_key` | Ключ авторизации из ЛК GigaChat |
| `scope` | Scope доступа (например, `GIGACHAT_API_PERS`) |
| `base_oauth_url` | URL OAuth сервиса (обычно не меняется) |
| `base_gigachat_url` | URL API GigaChat (обычно не меняется) |
| `gigachat_model` | Модель по умолчанию для генерации ответов |
| `cert_enable` | Сертификаты НУЦ установлены на уровне системы |
| `logs_enable` | Включить логирование запросов |

---

## API Reference

[Подробная документация API](docs/api.md)

---

## Полезные ссылки

- [Документация GigaChat API](https://developers.sber.ru/docs/ru/gigachat/api/reference/rest)
- [Личный кабинет разработчика](https://developers.sber.ru/studio)
- [Установка сертификатов](https://developers.sber.ru/docs/ru/gigachat/certificates)
- [Примеры использования](https://developers.sber.ru/docs/ru/gigachat/examples)

---

## Зависимости

- `beeralex.core` - базовые классы и сервисы
- `guzzlehttp/guzzle` - HTTP клиент (транзитивная зависимость)

---

## Лицензия

MIT
