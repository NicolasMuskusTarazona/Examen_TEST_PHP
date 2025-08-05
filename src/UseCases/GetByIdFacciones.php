<?php

namespace App\UseCases;

use App\Domain\Models\Facciones;
use App\Domain\Repositories\FaccionesRepositoryInterface;

class GetByIdFacciones{

    public function __construct(private FaccionesRepositoryInterface $repo){}

    public function execute(int $id): ?Facciones {
        return $this->repo->getById($id);
    }
}