<?

use Beeralex\Gigachat\Options;
use Beeralex\Gigachat\Services\BalanceService;
use Beeralex\Gigachat\Services\ChatService;
use Beeralex\Gigachat\Services\EmbeddingService;
use Beeralex\Gigachat\Services\ModelsService;

return [
    'services' => [
        Options::class => [
            'className' => Options::class
        ],
        BalanceService::class => [
            'className' => BalanceService::class
        ],
        ChatService::class => [
            'className' => ChatService::class
        ],
        EmbeddingService::class => [
            'className' => EmbeddingService::class
        ],
        ModelsService::class => [
            'className' => ModelsService::class
        ],
    ],
];