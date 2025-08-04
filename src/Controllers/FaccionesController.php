<?php
namespace App\Controllers;

use App\Domain\Repositories\FaccionesRepositoryInterface;
use App\UseCases\CreateFacciones;
use App\UseCases\GetAllFacciones;
use App\UseCases\GetByIdFacciones;
use App\UseCases\UpdateFacciones;
use App\UseCases\DeleteFacciones;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class FaccionesController {
    public function __construct(private FaccionesRepositoryInterface $repo) {}

    public function index(Request $request, Response $response): Response {
        $useCase = new GetAllFacciones($this->repo);
        $faccion = $useCase->execute();
        $response->getBody()->write(json_encode($faccion));
        return $response;
    }

    public function show(Request $request, Response $response, array $args): Response {
        $useCase = new GetByIdFacciones($this->repo);
        $id = $args['id'];
        $faccion = $useCase->execute($id);
        if (!$faccion) {
            $response->getBody()->write(json_encode(["error" => "Faccion no registrado en la plataforma"]));
            return $response->withStatus(404);
        }
        $response->getBody()->write(json_encode($faccion));
        return $response;
    }

    public function store(Request $request, Response $response): Response {
        $data = $request->getParsedBody();
        $useCase = new CreateFacciones($this->repo);
        $faccion = $useCase->execute($data);
        $response->getBody()->write(json_encode($faccion));
        return $response->withStatus(201);
    }

    public function update(Request $request, Response $response, array $args): Response {
        $id = $args['id']; // SIN (int)
        $data = $request->getParsedBody();
        $useCase = new UpdateFacciones($this->repo);
        $success = $useCase->execute($id, $data);
        if (!$success) {
            $response->getBody()->write(json_encode(["error" => "Faccion no registrado en la plataforma"]));
            return $response->withStatus(404);
        }
        $response->getBody()->write(json_encode(['message' => 'Faccion Actualizada']));
        return $response->withStatus(200);
    }
    public function destroy(Request $request, Response $response, array $args): Response {
        $id = $args['id'];
    
        $useCase = new DeleteFacciones($this->repo);
        $success = $useCase->execute($id);
    
        if (!$success) {
            $response->getBody()->write(json_encode([
                "error" => "Faccion no encontrada o ya fue eliminada"
            ]));
            return $response->withStatus(404);
        }
    
        $response->getBody()->write(json_encode(['message' => 'Faccion Eliminada']));
        return $response->withStatus(200);
    }
    
}
