<?php
declare(strict_types=1);
namespace Beeralex\Gigachat\Services;

use Beeralex\Core\Service\Api\ApiService as CoreApiService;
use Beeralex\Core\Service\Api\ClientService;
use Beeralex\Gigachat\Options;

/**
 * @property-read Options $options
 */
abstract class ApiService extends CoreApiService
{
    public function __construct()
    {
        $this->clientService = service(ClientService::class);
        $this->options = service(Options::class);
        
        if(!$this->options->certEnable){
            $this->clientService->disableSslVerification();
        }
    }

    public function logsEnabled(): bool
    {
        return $this->options->logsEnable;
    }
}
