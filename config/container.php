<?php

declare(strict_types=1);

use Zend\ServiceManager\ServiceManager;

// Load configuration
$config = require __DIR__ . '/config.php';

$dependencies = $config['dependencies'];
$dependencies['services']['config'] = $config;

// Build container
$serviceManager = new ServiceManager($dependencies);
$subscribers = $config['subscribers'] ?? [];

foreach ($subscribers as $subscriber) {
    $serviceManager->build($subscriber);
}

return $serviceManager;
