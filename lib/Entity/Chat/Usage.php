<?php
declare(strict_types=1);
namespace Beeralex\Gigachat\Entity\Chat;

/**
 * Данные об использовании токенов в запросе
 */
class Usage
{
    /**
     * Количество токенов во входящем сообщении (роль user)
     */
    public readonly int $promtTokens;

    /**
     * Количество токенов, сгенерированных моделью (роль assistant)
     */
    public readonly int $completionTokens;

    /**
     * Общее количество токенов.
     */
    public readonly int $totalTokens;

    public readonly int $precachedPromtTokens;

    public function __construct(array $usage)
    {   
        $this->promtTokens = $usage['prompt_tokens'];
        $this->completionTokens = $usage['completion_tokens'];
        $this->totalTokens = $usage['total_tokens'];
        $this->precachedPromtTokens = $usage['precached_prompt_tokens'];
    }
}