<?php
declare(strict_types=1);
namespace Beeralex\Gigachat\Services;

use Bitrix\Main\Web\Uri;
use Beeralex\Core\Dto\CacheSettingsDto;
use Symfony\Component\Uid\Uuid;

/**
 * @link https://developers.sber.ru/docs/ru/gigachat/api/reference/rest/post-token
 */
class AuthService extends ApiService
{
    private ?string $token = null;
    private CacheSettingsDto $cacheSettings;

    public function __construct()
    {
        parent::__construct();
        $this->cacheSettings = new CacheSettingsDto(1800, 'gigachat_access_token', '/gigachat/token');
    }

    /** 
     * @throws \RuntimeException
     * @throws \Exception
     */
    public function getAccessToken(): string
    {
        if (!$this->token) {
            $this->setToken();
        }
        return $this->token;
    }

    /** 
     * @throws \RuntimeException
     * @throws \Exception
     */
    public function refreshToken()
    {
        $this->setToken(true);
    }

    private function setToken(bool $isRefresh = false): void
    {
        $result = [];

        if ($isRefresh) {
            $this->cache->clean($this->cacheSettings->key, $this->cacheSettings->dir);
        }

        $result = $this->makeRequest();

        if (!isset($result['access_token'], $result['expires_at'])) {
            throw new \RuntimeException('Ошибка получения токена');
        }

        $timestamp = (new \DateTime())->getTimestamp();

        if ($result['expires_at'] < $timestamp) {
            $this->setToken(true);
            return;
        }

        $this->token = $result['access_token'];
    }

    private function makeRequest()
    {
        return $this->post(new Uri("{$this->options->baseOauthUrl}/api/v2/oauth"), $this->getData(), $this->getHeaders(), $this->cacheSettings);
    }

    private function getHeaders(): array
    {
        return [
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Accept' => 'application/json',
            'RqUID' => Uuid::v4()->toString(),
            'Authorization' => "Basic {$this->options->authorizationKey}",
        ];
    }

    private function getData(): array
    {
        return [
            'scope' => $this->options->scope,
        ];
    }
}
