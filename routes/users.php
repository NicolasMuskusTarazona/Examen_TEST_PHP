<?php

use App\Controllers\UserController;
use Slim\App;

return function(App $app){
    // Rutas de registro
    $app->group('/register', function($group){
        $group->post('/user', [UserController::class, 'createUser']);
        $group->post('/admin', [UserController::class, 'createAdmin']);
    });

    // Rutas CRUD completas de usuarios
    $app->group('/users', function($group){
        $group->get('', [UserController::class, 'getAllUsers']);
        $group->get('/{id}', [UserController::class, 'getUserById']);
        $group->put('/{id}', [UserController::class, 'updateUser']);
        $group->delete('/{id}', [UserController::class, 'deleteUser']);
    });
};