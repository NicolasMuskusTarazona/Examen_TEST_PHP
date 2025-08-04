<?php

namespace App\UseCases;

use App\Domain\Repositories\UserRepositoryInterface;

class GetAllUsers{
    public function __construct(private UserRepositoryInterface $repo){}
    public function execute(): array{
        return $this->repo->getAll();
    }
}