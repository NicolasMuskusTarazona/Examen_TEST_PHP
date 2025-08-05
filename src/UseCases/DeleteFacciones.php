<?php

namespace App\UseCases;

use App\Domain\Repositories\FaccionesRepositoryInterface;

class DeleteFacciones{

    public function __construct(private FaccionesRepositoryInterface $repo){}

    public function execute(int $id): bool {
        return $this->repo->delete($id );
    }
}