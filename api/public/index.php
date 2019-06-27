<?php

declare(strict_types=1);

use App\Core\Infrastructure\Symfony\Kernel;
use Symfony\Component\Debug\Debug;
use Symfony\Component\HttpFoundation\Request;

require dirname(__DIR__).'/config/bootstrap.php';

if ($_SERVER['APP_DEBUG']) {
    umask(0000);

    Debug::enable();
}

if ($_SERVER['ON_HEROKU'] ?? $_ENV['ON_HEROKU'] ?? false) {
    Request::setTrustedProxies(
        // trust *all* requests
        ['127.0.0.1', $_SERVER['REMOTE_ADDR']],

        // only trust X-Forwarded-Port/-Proto, not -Host
        Request::HEADER_X_FORWARDED_AWS_ELB
    );
} else {
    if ($trustedProxies = $_SERVER['TRUSTED_PROXIES'] ?? $_ENV['TRUSTED_PROXIES'] ?? false) {
        Request::setTrustedProxies(explode(',', $trustedProxies), Request::HEADER_X_FORWARDED_ALL);
    }

    if ($trustedHosts = $_SERVER['TRUSTED_HOSTS'] ?? $_ENV['TRUSTED_HOSTS'] ?? false) {
        Request::setTrustedHosts([$trustedHosts]);
    }
}

$kernel = new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
