<?php

namespace App\Controllers;

use App\Domain\Repositories\UserRepositoryInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\DTOs\UserDTO;

class UserController
{
    public function __construct(private UserRepositoryInterface $repo) {}
    // GET ALL USER

    public function getAllUsers(Request $request, Response $response): Response{
    $users = $this->repo->getAll();
    $response->getBody()->write(json_encode($users));
    return $response->withStatus(200);
    }

    // GET USER BY ID

    public function getUserById(Request $request, Response $response, array $args): Response{
    $id = (int)$args['id'];
    $user = $this->repo->getById($id);

    if (!$user) {
        $response->getBody()->write(json_encode(['error' => 'Usuario no encontrado']));
        return $response->withStatus(404);
    }

    $response->getBody()->write(json_encode($user));
    return $response->withStatus(200);
    }

// CREATE USER
public function createUser(Request $request, Response $response): Response {
    $data = $request->getParsedBody();

    $dto = new UserDTO(
        nombre: $data['nombre'] ?? '',
        email: $data['correo'] ?? '',
        password: $data['contraseña'] ?? '',
        rol: 'user',
    );

    try {
        $user = $this->repo->create($dto);

        $response->getBody()->write(json_encode([
            'message' => 'Usuario registrado exitosamente',
            'user' => $user
        ]));

        return $response->withStatus(201);
    } catch (\Exception $e) {
        $response->getBody()->write(json_encode([
            'error' => $e->getMessage()
        ]));

        return $response->withStatus(400);
    }
}


    // CREATE ADMIN
    public function createAdmin(Request $request, Response $response): Response{
        $data = $request->getParsedBody();
        $dto = new UserDTO(
            nombre: $data['nombre'] ?? '',
            email: $data['correo'] ?? '',
            password: $data['contraseña'] ?? '',
            rol: 'admin',
        );
        $user = $this->repo->create($dto);
        $response->getBody()->write(json_encode($user));
        return $response->withStatus(201);
    }

    // UPDATE USER
    public function updateUser(Request $request, Response $response, array $args): Response{
        $id = (int)$args['id'];
        $data = $request->getParsedBody();
    
        $dto = new UserDTO(
            nombre: $data['nombre'] ?? '',
            email: $data['email'] ?? '',
            password: $data['password'] ?? '',
            rol: $data['rol'] ?? 'user',
        );
    
        try {
            $success = $this->repo->update($id, $dto);
        
            if (!$success) {
                throw new \Exception('No se pudo actualizar');
            }
        
            $response->getBody()->write(json_encode(['message' => 'Usuario actualizado']));
            return $response->withStatus(200);
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withStatus(400);
        }
    }
    // DELETE USER
    public function deleteUser(Request $request, Response $response, array $args): Response{
    $id = (int)$args['id'];

    try {
        $success = $this->repo->delete($id);

        if (!$success) {
            throw new \Exception('No se pudo eliminar');
        }

        $response->getBody()->write(json_encode(['message' => 'Usuario eliminado']));
        return $response->withStatus(200);
    } catch (\Exception $e) {
        $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
        return $response->withStatus(400);
    }
}
}