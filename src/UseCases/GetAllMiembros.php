<?php

namespace App\UseCases;

use App\Domain\Repositories\MiembrosRepositoryInterface;

class GetAllMiembros{
    public function __construct(private MiembrosRepositoryInterface $repo){}
    public function execute(): array{
        return $this->repo->getAll();
    }
}