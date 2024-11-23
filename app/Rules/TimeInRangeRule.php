<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Carbon;

class TimeInRangeRule implements Rule
{
    protected $startTime;
    protected $endTime;

    public function __construct($startTime, $endTime)
    {
        $this->startTime = Carbon::parse($startTime);
        $this->endTime = Carbon::parse($endTime);
    }

    public function passes($attribute, $value)
    {
        $time = Carbon::parse($value);
        return $time->between($this->startTime, $this->endTime);
    }

    public function message()
    {
        return 'The :attribute must be between the available booking times.';
    }
}
