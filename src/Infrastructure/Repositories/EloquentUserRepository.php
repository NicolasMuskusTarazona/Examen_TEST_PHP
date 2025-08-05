<?php
namespace App\Infrastructure\Repositories;

use App\Domain\Models\User;
use App\Domain\Repositories\UserRepositoryInterface;
use Exception;
use App\DTOs\UserDTO;

class EloquentUserRepository implements UserRepositoryInterface
{
    // CREATE
    public function create(UserDTO $dto): User
    {
        $data = $dto->toArray();

        $exists = User::where('email', $data['email'])->first();
        if ($exists) {
            throw new Exception('Error el usuario ya existe');
        }

        return User::create($data);
    }

    // GET ALL
    public function getAll(): array
    {
        return User::all()->toArray();
    }

    // GET BY ID
    public function getById(int $id): ?User
    {
        return User::find($id);
    }

    // UPDATE
    public function update(int $id, UserDTO $dto): bool
    {
        $user = User::find($id);

        if (!$user) {
            throw new Exception('Error usuario no encontrado');
        }

        $data = $dto->toArray();

        $emailExistente = User::where('email', $data['email'])
                            ->where('id', '!=', $id)
                            ->first();

        if ($emailExistente) {
            throw new Exception('Error ese email ya está en uso');
        }

        return $user->update($data);
    }

    // DELETE
    public function delete(int $id): bool
    {
        $user = User::find($id);

        if (!$user) {
            throw new Exception('Error usuario no encontrado');
        }

        return $user->delete();
    }

    public function authenticate(string $email, string $password): User
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            throw new Exception('Correo no registrado');
        }

        if ($user->password !== $password) {
            throw new Exception('Contraseña incorrecta');
        }

        return $user;
    }
}
