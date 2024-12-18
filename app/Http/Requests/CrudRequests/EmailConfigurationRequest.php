<?php

namespace App\Http\Requests\CrudRequests;

use App\Helpers\ClassesBase\BaseRequest;

class EmailConfigurationRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            "MAIL_MAILER" => $this->textRule(true),
            "MAIL_HOST" => $this->textRule(true),
            "MAIL_PORT" => $this->numberRule(true),
            "MAIL_USERNAME" => $this->textRule(true),
            "MAIL_PASSWORD" => $this->textRule(true),
            "MAIL_FROM_ADDRESS" => $this->emailRule(true),
            "MAIL_FROM_NAME" => $this->textRule(true),
            "MAIL_ENCRYPTION" =>  $this->textRule(true),
            "default" => ["required","boolean"],
        ];
    }
}
