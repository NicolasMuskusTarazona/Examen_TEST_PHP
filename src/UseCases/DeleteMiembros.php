<?php

namespace App\UseCases;

use App\Domain\Repositories\MiembrosRepositoryInterface;

class DeleteMiembros{

    public function __construct(private MiembrosRepositoryInterface $repo){}

    public function execute(int $id): bool {
        return $this->repo->delete($id );
    }
}