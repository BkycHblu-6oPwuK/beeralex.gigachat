<?php
declare(strict_types=1);
namespace Beeralex\Gigachat\Entity\Chat;

/**
 * Настройки для запроса к чату.
 * @link https://developers.sber.ru/docs/ru/gigachat/api/reference/rest/post-chat
 */
class ChatParam
{
    /**
     * Сообщения пользователя
     */
    public readonly MessagesParam $messages;

    /**
     * Температура выборки. Чем выше значение, тем более случайным будет ответ модели. Если значение температуры находится в диапазоне от 0 до 0.001, параметры temperature и top_p будут сброшены в режим, обеспечивающий максимально детерменированный (стабильный) ответ модели. При значениях температуры больше двух, набор токенов в ответе модели может отличаться избыточной случайностью.
     */
    public readonly ?float $temperature;

    /**
     * Параметр используется как альтернатива температуре (поле temperature). Задает вероятностную массу токенов, которые должна учитывать модель. Так, если передать значение 0.1, модель будет учитывать только токены, чья вероятностная масса входит в верхние 10%. Значение по умолчанию зависит от выбранной модели (поле model) и может изменяться с обновлениями модели. Значение изменяется в диапазоне от 0 до 1 включительно.
     */
    public readonly ?float $topP;

    /**
     * Указывает что сообщения надо передавать по частям в потоке. Сообщения передаются по протоколу SSE.
     * Функционал не реализован и значение всегда false
     */
    public readonly bool $stream;

    /**
     * Максимальное количество токенов, которые будут использованы для создания ответов.
     */
    public readonly ?int $maxTokens;

    /**
     * Количество повторений слов: Значение 1.0 — нейтральное значение. При значении больше 1 модель будет стараться не повторять слова. Значение по умолчанию зависит от выбранной модели (поле model) и может изменяться с обновлениями модели.
     */
    public readonly float $repetitionPenalty;

    /**
     * Параметр потокового режима ("stream": "true"). Задает минимальный интервал в секундах, который проходит между отправкой токенов. Например, если указать 1, сообщения будут приходить каждую секунду, но размер каждого из них будет больше, так как за секунду накапливается много токенов.
     */
    //public readonly ?int $updateInterval;

    /**
     * @param MessagesParam $messages Сообщения пользователя
     * @param ?float $temperature Температура выборки
     * @param ?float $toP Задает вероятностную массу токенов, которые должна учитывать модель. Значение изменяется в диапазоне от 0 до 1 включительно.
     * @param ?int $maxTokens Максимальное количество токенов, которые будут использованы для создания ответов.
     * @param ?float $repetitionPenalty Количество повторений слов
     */
    public function __construct(MessagesParam $messages, ?float $temperature = null, ?float $topP = null, ?int $maxTokens = null, ?float $repetitionPenalty = null)
    {
        if($topP){
            if($topP < 0){
                $topP = 0.0;
            } elseif($topP > 1){
                $topP = 1.0;
            }
        }
        $this->messages = $messages;
        $this->temperature = $temperature;
        $this->topP = $topP;
        $this->maxTokens = $maxTokens;
        $this->repetitionPenalty = $repetitionPenalty ?? 1;

        $this->stream = false;
        //$this->updateInterval = null;
    }
}
