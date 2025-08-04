<?php
namespace App\Infrastructure\Repositories;

use Illuminate\Support\Collection;
use App\Domain\Models\Miembros;
use App\Domain\Repositories\MiembrosRepositoryInterface;

class EloquentMiembrosRepository implements MiembrosRepositoryInterface{
    public function getAll() : array {
        // SELECT * FROM Variedades;
        return Miembros::all()->toArray();
    }

    public function getById(int $id): ?Miembros {
        return Miembros::where('id', $id)->first();
    }
    

    public function create(array $data): Miembros {
        if (isset($data['id'])) {
            $exists = Miembros::find($data['id']);
            if ($exists) {
                return $exists; 
            }
        }
    
        return Miembros::create($data);
    }
    
    
    public function update(int $id, array $data): bool{
        $caracter = $this->getById($id);
        return $caracter ? $caracter->update($data) : false;
    }
    

    public function delete(int $id): bool{
        // SELECT * FROM Variedades WHERE id = $id;
        $caracter = Miembros::find($id);
        // DELETE FROM Variedades WHERE id = $id;
        return $caracter ? $caracter->delete() : false;   
    }
}