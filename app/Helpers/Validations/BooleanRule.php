<?php 


namespace App\Helpers\Validations;

use CodeIgniter\Validation\Rules;

class BooleanRule extends Rules
{
    /**
     * @param array|bool|float|int|object|string|null $str
     */
    public function boolean($str): bool
    {
        return in_array($str, [1, 0, '0', '1', 'true', 'false', 'on', 'off', 'yes', 'no', true, false], true);

    }
}