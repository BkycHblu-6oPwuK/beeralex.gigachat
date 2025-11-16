<?php
declare(strict_types=1);
namespace Beeralex\Gigachat\Entity\Embedding;

class Usage
{
    /**
     * Количество токенов в строке, для которой сгенерирован эмбеддинг.
     */
    public readonly int $promtTokens;

    public function __construct(array $usage)
    {   
        $this->promtTokens = $usage['prompt_tokens'];
    }
}