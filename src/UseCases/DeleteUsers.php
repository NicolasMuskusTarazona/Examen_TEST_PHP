<?php

namespace App\UseCases;

use App\Domain\Repositories\UserRepositoryInterface;

class DeleteUsers{

    public function __construct(private UserRepositoryInterface $repo){}

    public function execute(int $id): bool {
        return $this->repo->delete($id);
    }
}