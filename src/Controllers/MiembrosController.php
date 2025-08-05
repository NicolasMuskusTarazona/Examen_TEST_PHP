<?php
namespace App\Controllers;

use App\Domain\Repositories\MiembrosRepositoryInterface;
use App\UseCases\CreateMiembros;
use App\UseCases\GetAllMiembros;
use App\UseCases\GetByIdMiembros;
use App\UseCases\UpdateMiembros;
use App\UseCases\DeleteMiembros;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class MiembrosController {
    public function __construct(private MiembrosRepositoryInterface $repo) {}

    public function index(Request $request, Response $response): Response {
        $useCase = new GetAllMiembros($this->repo);
        $faccion = $useCase->execute();
        $response->getBody()->write(json_encode($faccion));
        return $response;
    }

    public function show(Request $request, Response $response, array $args): Response {
        $useCase = new GetByIdMiembros($this->repo);
        $id = $args['id'];
        $miembro = $useCase->execute($id);
        if (!$miembro) {
            $response->getBody()->write(json_encode(["error" => "Miembro no registrado en la plataforma"]));
            return $response->withStatus(404);
        }
        $response->getBody()->write(json_encode($miembro));
        return $response;
    }

    public function store(Request $request, Response $response): Response {
        $data = $request->getParsedBody();
        $useCase = new CreateMiembros($this->repo);
        $miembro = $useCase->execute($data);
        $response->getBody()->write(json_encode($miembro));
        return $response->withStatus(201);
    }

    public function update(Request $request, Response $response, array $args): Response {
        $id = $args['id']; // SIN (int)
        $data = $request->getParsedBody();
        $useCase = new UpdateMiembros($this->repo);
        $success = $useCase->execute($id, $data);
        if (!$success) {
            $response->getBody()->write(json_encode(["error" => "Miembro no registrado en la plataforma"]));
            return $response->withStatus(404);
        }
        $response->getBody()->write(json_encode(['message' => 'Miembro Actualizada']));
        return $response->withStatus(200);
    }
    public function destroy(Request $request, Response $response, array $args): Response {
        $id = $args['id'];
    
        $useCase = new DeleteMiembros($this->repo);
        $success = $useCase->execute($id);
    
        if (!$success) {
            $response->getBody()->write(json_encode([
                "error" => "Miembro no encontrada o ya fue eliminada"
            ]));
            return $response->withStatus(404);
        }
    
        $response->getBody()->write(json_encode(['message' => 'Miembro Eliminada']));
        return $response->withStatus(200);
    }
    
}
