<?php

namespace App\Domain\Repositories;

use App\Domain\Models\Facciones;

interface FaccionesRepositoryInterface
{
    public function getAll(): array;
    public function getById(int $id): ?Facciones;
    public function create(array $data): Facciones;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}