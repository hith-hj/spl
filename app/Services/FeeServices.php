<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Fee;

final class FeeServices
{
    public function get(object $feeable)
    {
        Truthy(! method_exists($feeable, 'fees'), 'fees method missing');
        $fees = $feeable->fees;
        NotFound($fees, 'Fees');

        return $fees;
    }

    public function find(int $id)
    {
        $fee = Fee::query()
            ->with(['subject', 'holder'])
            ->where('id', $id)
            ->first();
        NotFound($fee, 'Fee');

        return $fee;
    }
}
