<?php


namespace App\UseCases;

use App\Domain\Models\Miembros;
use App\Domain\Repositories\MiembrosRepositoryInterface;

class CreateMiembros{

    public function __construct(private MiembrosRepositoryInterface $repo){}
    public function execute(array $data): ?Miembros{
        return $this->repo->create($data);
    }
}