# API Reference

Подробная документация по классам и методам модуля `beeralex.gigachat`.

## ChatService

Сервис для работы с чатом GigaChat.

### getChat()

Отправляет запрос к модели и получает ответ.

```php
public function getChat(ChatParam $params): Chat
```

**Параметры:**
- `$params` - параметры запроса (сообщения, настройки генерации)

**Возвращает:** `Chat` - результат с ответами модели

**Исключения:**
- `ApiClientUnauthorizedException` - автоматически обрабатывается (обновляет токен)
- `ApiClientException` - ошибки API (rate limit, invalid params)
- `RuntimeException` - общие ошибки

**Пример:**

```php
$chatService = service(ChatService::class);

$chatParam = new ChatParam(
    new MessagesParam('Привет!', 'Ты дружелюбный помощник')
);

$chat = $chatService->getChat($chatParam);
$response = $chat->choices->first()->message->content;
```

---

## Chat

Результат запроса к чату.

### Свойства

```php
public readonly Choices $choices;        // Коллекция вариантов ответов
public readonly int $created;            // Unix timestamp создания
public readonly string $model;           // Использованная модель
public readonly string $object;          // Тип объекта ("chat.completion")
public readonly Usage $usage;            // Статистика использования токенов
```

### Пример использования

```php
$chat = $chatService->getChat($chatParam);

// Получить первый ответ
$firstChoice = $chat->choices->first();
$message = $firstChoice->message->content;

// Получить причину завершения
$finishReason = $firstChoice->finishReason; // FinishReason enum

// Статистика
echo "Токенов в промпте: {$chat->usage->promptTokens}\n";
echo "Токенов в ответе: {$chat->usage->completionTokens}\n";
echo "Всего токенов: {$chat->usage->totalTokens}\n";
```

---

## ChatParam

Параметры для запроса к чату.

### Конструктор

```php
public function __construct(
    MessagesParam $messages,
    ?float $temperature = null,
    ?float $topP = null,
    ?int $maxTokens = null,
    float $repetitionPenalty = 1,
    bool $stream = false
)
```

**Параметры:**
- `$messages` - сообщения диалога
- `$temperature` - креативность (0-2), по умолчанию 1. Выше = креативнее
- `$topP` - nucleus sampling (0-1), фильтрация менее вероятных токенов
- `$maxTokens` - максимальное количество токенов в ответе
- `$repetitionPenalty` - штраф за повторения (>1 = меньше повторений)
- `$stream` - потоковая генерация (пока не поддерживается)

### Пример

```php
// Минимальный вариант
$chatParam = new ChatParam(
    new MessagesParam('Привет!', 'Ты помощник')
);

// С настройками
$chatParam = new ChatParam(
    messages: new MessagesParam('Напиши стих'),
    temperature: 1.5,           // Больше креатива
    topP: 0.9,
    maxTokens: 500,
    repetitionPenalty: 1.2      // Меньше повторений
);
```

---

## MessagesParam

Коллекция сообщений для диалога.

### Конструктор

```php
public function __construct(
    ?string $userMessage = null,
    ?string $systemPrompt = null
)
```

**Параметры:**
- `$userMessage` - сообщение пользователя
- `$systemPrompt` - системный промпт (роль модели)

### Методы

#### addMessage()

Добавляет сообщение в диалог.

```php
public function addMessage(MessageParam $message): self
```

#### getMessages()

Возвращает все сообщения.

```php
public function getMessages(): array
```

### Примеры

**Простой диалог:**

```php
$messages = new MessagesParam(
    userMessage: 'Что такое PHP?',
    systemPrompt: 'Ты эксперт по программированию'
);
```

**Множественные сообщения:**

```php
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
    content: 'Namespace - это...'
));
$messages->addMessage(new MessageParam(
    role: RoleChat::User,
    content: 'Приведи пример'
));
```

---

## MessageParam

Одно сообщение в диалоге.

### Конструктор

```php
public function __construct(
    RoleChat $role,
    string $content
)
```

**Параметры:**
- `$role` - роль (System/User/Assistant)
- `$content` - текст сообщения

### Пример

```php
use Beeralex\Gigachat\Enum\RoleChat;

$systemMessage = new MessageParam(
    role: RoleChat::System,
    content: 'Ты профессиональный переводчик'
);

$userMessage = new MessageParam(
    role: RoleChat::User,
    content: 'Translate: Hello world'
);
```

---

## Choice

Один вариант ответа модели.

### Свойства

```php
public readonly Message $message;              // Сообщение-ответ
public readonly int $index;                    // Индекс варианта
public readonly FinishReason $finishReason;    // Причина завершения
```

### FinishReason enum

```php
enum FinishReason: string {
    case Stop = 'stop';              // Модель завершила генерацию естественным образом
    case Length = 'length';          // Достигнут max_tokens
    case ContentFilter = 'content_filter';  // Контент отфильтрован
    case BlackList = 'blacklist';    // Использованы запрещенные слова
    case FunctionCall = 'function_call';    // Вызов функции
}
```

---

## Choices

Коллекция вариантов ответов.

### Методы

#### first()

Возвращает первый вариант ответа.

```php
public function first(): Choice
```

#### all()

Возвращает все варианты.

```php
public function all(): array
```

### Пример

```php
$chat = $chatService->getChat($chatParam);

// Первый ответ
$firstAnswer = $chat->choices->first()->message->content;

// Все варианты
foreach ($chat->choices->all() as $choice) {
    echo "Вариант {$choice->index}: {$choice->message->content}\n";
}
```

---

## Usage

Статистика использования токенов.

### Свойства

```php
public readonly int $promptTokens;       // Токенов в запросе
public readonly int $completionTokens;   // Токенов в ответе
public readonly int $totalTokens;        // Всего токенов
```

---

## ModelsService

Сервис для получения списка доступных моделей.

### getModels()

```php
public function getModels(): Models
```

**Возвращает:** `Models` - список доступных моделей

**Пример:**

```php
$modelsService = service(ModelsService::class);
$models = $modelsService->getModels();

foreach ($models->data as $model) {
    echo "{$model->id} - {$model->ownedBy}\n";
}
```

---

## Models

Коллекция моделей.

### Свойства

```php
/** @var Model[] */
public readonly array $data;
public readonly string $object;
```

### Методы

#### first()

```php
public function first(): Model
```

#### findById()

Находит модель по ID.

```php
public function findById(string $id): ?Model
```

**Пример:**

```php
$models = $modelsService->getModels();

// Первая модель
$defaultModel = $models->first();

// Конкретная модель
$proModel = $models->findById('GigaChat-Pro');
if ($proModel) {
    echo "Найдена модель: {$proModel->id}";
}
```

---

## Model

Информация о модели.

### Свойства

```php
public readonly string $id;          // ID модели
public readonly string $object;      // Тип объекта
public readonly string $ownedBy;     // Владелец
```

---

## BalanceService

Сервис для проверки баланса моделей.

### getBalance()

```php
public function getBalance(): Balance
```

**Возвращает:** `Balance` - информация о балансе

**Пример:**

```php
$balanceService = service(BalanceService::class);
$balance = $balanceService->getBalance();

foreach ($balance->data as $item) {
    echo "Модель {$item->model}: {$item->balance} токенов\n";
}
```

---

## Balance

Информация о балансе.

### Свойства

```php
/** @var BalanceItem[] */
public readonly array $data;
public readonly string $object;
```

---

## BalanceItem

Баланс одной модели.

### Свойства

```php
public readonly string $model;       // ID модели
public readonly int $balance;        // Количество доступных токенов
```

---

## EmbeddingService

Сервис для генерации векторных представлений текста.

### getEmbeddings()

Генерирует embeddings для текстов.

```php
public function getEmbeddings(array $input, ?string $model = null): Embeddings
```

**Параметры:**
- `$input` - массив текстов для обработки
- `$model` - модель для генерации (опционально)

**Возвращает:** `Embeddings` - векторные представления

**Пример:**

```php
$embeddingService = service(EmbeddingService::class);

$embeddings = $embeddingService->getEmbeddings([
    'Первый текст для embedding',
    'Второй текст для embedding'
]);

foreach ($embeddings->data as $embedding) {
    echo "Index: {$embedding->index}\n";
    echo "Размерность: " . count($embedding->embedding) . "\n";
    echo "Первые 5 значений: " . 
         implode(', ', array_slice($embedding->embedding, 0, 5)) . "\n";
}
```

---

## Embeddings

Коллекция embeddings.

### Свойства

```php
/** @var Embedding[] */
public readonly array $data;
public readonly string $object;
public readonly string $model;
public readonly Usage $usage;
```

---

## Embedding

Векторное представление одного текста.

### Свойства

```php
public readonly string $object;      // Тип объекта
public readonly array $embedding;    // Вектор (массив float)
public readonly int $index;          // Индекс в batch
```

---

## AuthService

Базовый сервис для авторизации (используется внутри других сервисов).

### Методы

#### getAccessToken()

Получает действующий токен (из кэша или создает новый).

```php
protected function getAccessToken(): string
```

#### refreshToken()

Принудительно обновляет токен.

```php
protected function refreshToken(): void
```

**Примечание:** Токен автоматически кэшируется на 30 минут и обновляется при необходимости.

---

## RoleChat enum

Роли сообщений в диалоге.

```php
enum RoleChat: string {
    case System = 'system';          // Системный промпт
    case User = 'user';              // Сообщение пользователя
    case Assistant = 'assistant';    // Ответ модели
}
```

**Использование:**

```php
use Beeralex\Gigachat\Enum\RoleChat;

$message = new MessageParam(
    role: RoleChat::System,
    content: 'Ты эксперт'
);
```

---

## Options

Класс настроек модуля.

### Свойства

```php
public readonly string $authorizationKey;    // Ключ авторизации
public readonly string $scope;               // Scope доступа
public readonly string $baseOauthUrl;        // URL OAuth
public readonly string $baseGigaChatUrl;     // URL API
public readonly string $defaultModel;        // Модель по умолчанию
public readonly bool $certEnable;            // Сертификаты установлены
public readonly bool $logsEnable;            // Логирование
```

### Использование

```php
$options = service(\Beeralex\Gigachat\Options::class);

echo "Модель по умолчанию: {$options->defaultModel}\n";
echo "Логирование: " . ($options->logsEnable ? 'Вкл' : 'Выкл') . "\n";

if (!$options->certEnable) {
    echo "Внимание: SSL проверка отключена\n";
}
```

---

## Обработка ошибок

### Типы исключений

```php
use Beeralex\Core\Exceptions\ApiClientException;
use Beeralex\Core\Exceptions\ApiClientUnauthorizedException;

try {
    $chat = $chatService->getChat($chatParam);
} catch (ApiClientUnauthorizedException $e) {
    // Ошибка авторизации - токен истек
    // Обрабатывается автоматически внутри сервиса
    error_log("Auth failed: " . $e->getMessage());
} catch (ApiClientException $e) {
    // API ошибка (rate limit, invalid params)
    $statusCode = $e->getCode();
    error_log("API error [{$statusCode}]: " . $e->getMessage());
} catch (\RuntimeException $e) {
    // Не заполнены обязательные настройки
    error_log("Configuration error: " . $e->getMessage());
} catch (\Exception $e) {
    // Общая ошибка
    error_log("Error: " . $e->getMessage());
}
```

---

## Логирование

При включенном `logs_enable` все запросы логируются:

```php
// Логи находятся в:
// {module_dir}/logs/
// Например: /local/modules/beeralex.gigachat/logs/

$options = service(\Beeralex\Gigachat\Options::class);
if ($options->logsEnable) {
    // Логирование активно
}
```
