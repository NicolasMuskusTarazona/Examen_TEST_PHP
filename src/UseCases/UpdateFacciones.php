<?php

namespace App\UseCases;

use App\Domain\Repositories\FaccionesRepositoryInterface;

class UpdateFacciones{

    public function __construct(private FaccionesRepositoryInterface $repo){}

    public function execute(int $id,array $data): ?bool{
        return $this->repo->update($id,$data);
    }
}