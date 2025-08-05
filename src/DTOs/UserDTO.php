<?php

namespace App\DTOs;

use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;

class UserDTO{
    private readonly string $password;

    public function __construct(
        public readonly string $nombre,
        public readonly string $email,
        string $password,
        public readonly string $rol = 'user'){
        $this->validate($nombre,$email,$password,$rol);
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    private function validate(string $nombre, string $email, string $password, string $rol) : void {
        try {
            // Nombre
            v::stringType()->length(min: 2, max: 50)->SetName('nombre')->assert($nombre);
            // Email
            v::email()->SetName('email')->assert($email);
            // Contraseña
            v::stringType()->length(min: 8, max: 100)
                ->regex('/[!@#$%^&*()\-_=+{};:,<.>]/')->regex('/[0-9]/')
                ->setName('contraseña')->assert($password);
            // Rol
            v::in(['user','admin'])->assert($rol);
        } catch (NestedValidationException $e) {
            throw new \InvalidArgumentException($e->getFullMessage());
        }
    }

    public function toArray(): array {
        return[
            "nombre" => $this->nombre,
            "password"=> $this->password,
            "rol"=> $this->rol,
            "email"=> $this->email,
        ];
    }
}