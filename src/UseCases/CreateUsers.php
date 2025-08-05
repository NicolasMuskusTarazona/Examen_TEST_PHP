<?php

namespace App\UseCases;

use App\Domain\Models\User;
use App\DTOs\UserDTO;
use App\Domain\Repositories\UserRepositoryInterface;

class CreateUsers{

    public function __construct(private UserRepositoryInterface $repo){}

    public function execute(UserDTO $dto): User{
        return $this->repo->create($dto);
    }
}