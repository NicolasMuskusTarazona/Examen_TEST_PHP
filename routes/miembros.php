<?php

use App\Controllers\MiembrosController;
use App\Middleware\AuthMiddleware;
use App\Middleware\RoleMiddleware;
use Slim\App;

return function (App $app) {
    $app->group('/miembros', function ($group) {
        $group->get('', [MiembrosController::class, 'index']);
        $group->get('/{id}', [MiembrosController::class, 'show']);
        $group->post('', [MiembrosController::class, 'store']);
        $group->put('/{id}', [MiembrosController::class, 'update']);
        $group->delete('/{id}', [MiembrosController::class, 'destroy']);
    });
};
