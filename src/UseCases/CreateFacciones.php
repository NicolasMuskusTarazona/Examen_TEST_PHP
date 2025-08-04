<?php


namespace App\UseCases;

use App\Domain\Models\Facciones;
use App\Domain\Repositories\FaccionesRepositoryInterface;

class CreateFacciones{

    public function __construct(private FaccionesRepositoryInterface $repo){}
    public function execute(array $data): ?Facciones{
        return $this->repo->create($data);
    }
}