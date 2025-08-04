<?php

namespace App\Domain\Repositories;

use App\Domain\Models\Miembros;

interface MiembrosRepositoryInterface
{
    public function getAll(): array;
    public function getById(int $id): ?Miembros;
    public function create(array $data): Miembros;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}