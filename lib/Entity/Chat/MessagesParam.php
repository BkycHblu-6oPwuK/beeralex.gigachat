<?php
declare(strict_types=1);
namespace Beeralex\Gigachat\Entity\Chat;

use Beeralex\Gigachat\Enum\RoleChat;

/**
 * Хранит сообщения к модели, их может быть много если сохранять контекст диалога с моделью
 */
class MessagesParam
{
    /**
     * @var MessageParam[] $messages Сообщения пользователя
     */
    private array $messages;

    /**
     * @param string $content Сообщение пользователя
     */
    public function __construct(?string $userPromt = null, ?string $systemPromt = null)
    {
        $this->messages = [];
        /** системный промт должен быть первым */
        if($systemPromt){
            $this->messages[] = new MessageParam(RoleChat::SYSTEM, $systemPromt);
        }
        if($userPromt){
            $this->messages[] = new MessageParam(RoleChat::USER, $userPromt);
        }
    }

    /**
     * Добавить сообщение, системное будет проигнорировано, его нужно передавать в констуктор класса
     */
    public function addMessage(MessageParam $message): static
    {
        if($message->role !== RoleChat::SYSTEM){
            $this->messages[] = $message;
        }
        return $this;
    }

    /**
     * @return MessageParam[] Сообщения пользователя
     */
    public function getMessages(): array
    {
        return $this->messages;
    }
}
