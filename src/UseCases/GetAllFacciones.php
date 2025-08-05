<?php

namespace App\UseCases;

use App\Domain\Repositories\FaccionesRepositoryInterface;

class GetAllFacciones{
    public function __construct(private FaccionesRepositoryInterface $repo){}
    public function execute(): array{
        return $this->repo->getAll();
    }
}