<?php
declare(strict_types=1);
namespace Beeralex\Gigachat;

use Beeralex\Core\Config\AbstractOptions;

final class Options extends AbstractOptions
{
    public readonly string $authorizationKey;
    public readonly string $scope;
    public readonly string $baseOauthUrl;
    public readonly string $baseGigaChatUrl;
    public readonly string $defaultModel;
    public readonly bool $certEnable;
    public readonly bool $logsEnable;

    protected function mapOptions(array $options): void
    {
        $this->authorizationKey = $options['authorization_key'] ?? '';
        $this->scope = $options['scope'] ?? '';
        $this->baseOauthUrl = $options['base_oauth_url'] ?? '';
        $this->baseGigaChatUrl = $options['base_gigachat_url'] ?? '';
        $this->logsEnable = ($options['logs_enable'] ?? '') === 'Y';
        $this->defaultModel = $options['gigachat_model'] ?? '';
        $this->certEnable = ($options['cert_enable'] ?? '') === 'Y';
    }

    protected function validateOptions(): void
    {
        if(!$this->authorizationKey || !$this->scope || !$this->baseOauthUrl || !$this->baseGigaChatUrl){
            throw new \RuntimeException(
                "Не заполнены обязательные настройки модуля (Ключ авторизации, Scope, Базовый адрес запроса для получения токена или Базовый адрес запроса к GigaChat API)"
            );
        }
    }

    public function getModuleId(): string
    {
        return 'beeralex.gigachat';
    }
}
