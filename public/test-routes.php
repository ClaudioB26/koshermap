<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// public/ está dentro del proyecto, vendor/ está un nivel arriba
require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$routes = \Illuminate\Support\Facades\Route::getRoutes();
foreach ($routes as $route) {
    if (str_contains($route->uri(), 'sync')) {
        echo $route->methods()[0] . ' /' . $route->uri() . '<br>';
    }
}
echo '<br>OK';
