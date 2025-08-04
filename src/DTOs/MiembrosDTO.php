<?php

namespace App\DTOs;

use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\ValidationException;
use InvalidArgumentException;

class CaracteristicasDTO
{
    public static function fromArray(array $data): array
    {
        try {
            v::key('rango', v::in(['lider', 'oficial', 'miembro']))
            ->assert($data);
        } catch (ValidationException $e) {
            throw new InvalidArgumentException('Campos invalidos | rango = lider o oficial o miembro');
        }

        return $data; // datos ya validados
    }
}
