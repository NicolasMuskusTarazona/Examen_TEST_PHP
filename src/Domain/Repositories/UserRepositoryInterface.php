<?php
// 2.

namespace App\Domain\Repositories;

use App\Domain\Models\User;
use App\DTOs\UserDTO;

interface UserRepositoryInterface
{
    // POST
    public function create(UserDTO $dto): User;

    // GET
    public function getAll(): array;

    // GET
    public function getById(int $id): ?User;

    // PUT
    public function update(int $id, UserDTO $dto): bool;

    // DELETE
    public function delete(int $id): bool;
}