<?php

// Contenedor
use DI\Container;

//Factory Interface
use Psr\Http\Message\ResponseFactoryInterface;

// Usuario
use App\Domain\Repositories\UserRepositoryInterface;
use App\Infrastructure\Repositories\EloquentUserRepository;

// Manejo de errores
use App\Handler\CustomErrorHandler;
use Slim\Handlers\ErrorHandler;
use Slim\Interfaces\ErrorHandlerInterface;

// Facciones
use App\Domain\Repositories\FaccionesRepositoryInterface;
use App\Infrastructure\Repositories\EloquentFaccionesRepository;
$container = new Container();

// 1. User
$container->set(UserRepositoryInterface::class,function(){
    return new EloquentUserRepository();
});

// 2. Facciones
$container->set(FaccionesRepositoryInterface::class,function(){
    return new EloquentFaccionesRepository();
});
// Manejo de Errores
$container->set(ErrorHandlerInterface::class, function () use ($container){
    return new CustomErrorHandler(
        $container->get(ResponseFactoryInterface::class)
    );
});

return $container;