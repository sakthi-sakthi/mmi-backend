<?php

namespace App\Traits;
use Carbon\Carbon;
trait CommonTraits {

    public function formatDate($date)
    {
        if (empty($date)) {
            return null;
        }
        $carbonDate = Carbon::parse($date);
        return $carbonDate->format('d-m-Y');
    }

}