<?php

namespace App\Service;

Interface MovieDBApiClientInterface
{
    public function requestApi(string $urlPath, array $params = []): mixed;
}
