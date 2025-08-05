<?php
namespace App\Infrastructure\Repositories;

use Illuminate\Support\Collection;
use App\Domain\Models\Facciones;
use App\Domain\Repositories\FaccionesRepositoryInterface;

class EloquentFaccionesRepository implements FaccionesRepositoryInterface{
    public function getAll() : array {
        // SELECT * FROM Variedades;
        return Facciones::all()->toArray();
    }

    public function getById(int $id): ?Facciones {
        return Facciones::where('id', $id)->first();
    }
    

    public function create(array $data): Facciones {
        // En nombre dentro de facciones esta como UNIQUE esto es para manejar su error
        // Manejo de Error Nombre UNIQUE
        if (Facciones::where('nombre', $data['nombre'])->exists()) {
            throw new \Exception('El nombre ya está en uso', 409);
        }
        if (isset($data['id'])) {
            $exists = Facciones::find($data['id']);
            if ($exists) {
                return $exists; 
            }
        }
    
        return Facciones::create($data);
    }
    
    
    public function update(int $id, array $data): bool{
        $caracter = $this->getById($id);
        return $caracter ? $caracter->update($data) : false;
    }
    

    public function delete(int $id): bool{
        // SELECT * FROM Variedades WHERE id = $id;
        $caracter = Facciones::find($id);
        // DELETE FROM Variedades WHERE id = $id;
        return $caracter ? $caracter->delete() : false;   
    }
}