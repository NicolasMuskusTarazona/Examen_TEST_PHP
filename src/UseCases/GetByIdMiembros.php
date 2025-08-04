<?php

namespace App\UseCases;

use App\Domain\Models\Miembros;
use App\Domain\Repositories\MiembrosRepositoryInterface;

class GetByIdMiembros{

    public function __construct(private MiembrosRepositoryInterface $repo){}

    public function execute(int $id): ?Miembros {
        return $this->repo->getById($id);
    }
}