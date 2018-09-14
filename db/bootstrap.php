<?php
declare(strict_types=1); 

use SONFin\Application;
use SONFin\Plugins\DbPlugin;
use SONFin\ServiceContainer;
use SONFin\Plugins\AuthPlugin;

$serviceContainer = new ServiceContainer();
$app = new Application($serviceContainer);

$app->plugin(new DbPlugin());
$app->plugin(new AuthPlugin());

return $app;