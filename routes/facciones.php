<?php

use App\Controllers\FaccionesController;
use App\Middleware\AuthMiddleware;
use App\Middleware\RoleMiddleware;
use Slim\App;

return function (App $app) {
    $app->group('/facciones', function ($group) {
        $group->get('', [FaccionesController::class, 'index']);
        $group->get('/{id}', [FaccionesController::class, 'show']);
        $group->post('', [FaccionesController::class, 'store']);
        $group->put('/{id}', [FaccionesController::class, 'update']);
        $group->delete('/{id}', [FaccionesController::class, 'destroy']);
    });
};
