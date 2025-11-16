<?php
declare(strict_types=1);
namespace Beeralex\Gigachat\Entity\Chat;

use Beeralex\Gigachat\Enum\FinishReason;

/**
 * Ответ модели
 */
class Choice
{
    /**
     * Объект сообщения в ответе
     */
    public readonly Message $message;

    /**
     * Индекс сообщения в массиве
     */
    public readonly string $index;
    
    /**
     * Причина завершения гипотезы
     */
    public readonly FinishReason $finishReason;

    public function __construct(array $choice)
    {   
        $this->message = new Message($choice['message']);
        $this->index = $choice['index'];
        $this->finishReason = match ($choice['finish_reason']) {
            FinishReason::STOP->value => FinishReason::STOP,
            FinishReason::LENGTH->value => FinishReason::LENGTH,
            FinishReason::FUNCTION_CALL->value => FinishReason::FUNCTION_CALL,
            FinishReason::BLACKLIST->value => FinishReason::BLACKLIST,
            FinishReason::ERROR->value => FinishReason::ERROR,
        };
    }
}