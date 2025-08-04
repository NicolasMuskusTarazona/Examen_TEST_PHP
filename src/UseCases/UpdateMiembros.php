<?php

namespace App\UseCases;

use App\Domain\Repositories\MiembrosRepositoryInterface;

class UpdateMiembros{

    public function __construct(private MiembrosRepositoryInterface $repo){}

    public function execute(int $id,array $data): ?bool{
        return $this->repo->update($id,$data);
    }
}