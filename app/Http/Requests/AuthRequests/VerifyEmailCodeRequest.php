<?php

namespace App\Http\Requests\AuthRequests;

use App\Helpers\ClassesBase\BaseRequest;
use Illuminate\Validation\Rule;

class VerifyEmailCodeRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'code' => ["required","string"],
        ];
    }
}
