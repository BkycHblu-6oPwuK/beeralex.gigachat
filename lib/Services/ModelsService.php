<?php
declare(strict_types=1);
namespace Beeralex\Gigachat\Services;

use Bitrix\Main\Web\Uri;
use Beeralex\Core\Dto\CacheSettingsDto;
use Beeralex\Core\Exceptions\ApiClientUnauthorizedException;
use Beeralex\Gigachat\Entity\Models\Models;

/**
 * @link https://developers.sber.ru/docs/ru/gigachat/api/reference/rest/get-models
 */
class ModelsService extends AuthService
{
    protected CacheSettingsDto $cacheSettings;

    public function __construct()
    {
        parent::__construct();
        $this->cacheSettings = new CacheSettingsDto(1800, 'gigachat_models', '/gigachat/models');
    }

    /**
     * @throws \RuntimeException
     * @throws \Exception
     */
    public function getModels() : Models
    {
        try {
            $result = $this->makeRequest();
        } catch (ApiClientUnauthorizedException $e){
            $this->refreshToken();
            $result = $this->makeRequest();
        }
        if(empty($result['data'])){
            throw new \RuntimeException("Ошибка получения моделей");
        }
        return new Models($result);
    }

    protected function makeRequest()
    {
        return $this->get(new Uri("{$this->options->baseGigaChatUrl}/api/v1/models"), null, $this->getHeaders(), $this->cacheSettings);
    }

    protected function getHeaders(): array
    {
        return [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->getAccessToken(),
        ];
    }
}
