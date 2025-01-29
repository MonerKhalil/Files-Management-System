<?php

namespace App\Helpers\ClassesProcess;

use App\Exceptions\MainException;
use App\Helpers\MyApp;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

class MailProcess
{

    public function SendMail(mixed $emails,Mailable $mailable,bool $withException = false, $objMailConfig = null){
        try {
            $this->setConfigMail($objMailConfig);
            Mail::to($emails)->send($mailable);
        }catch (\Exception $exception){
            //Code Error
            if ($withException){
                throw new MainException($exception->getMessage());
            }
        }
    }

    public function setConfigMail($objMailConfig = null){
        $objMailConfig ??= MyApp::Classes()->cacheProcess->getDefaultConfigMail();
        if (!is_null($objMailConfig)){
            Config::set('mail.driver', $objMailConfig->MAIL_MAILER);
            Config::set('mail.host', $objMailConfig->MAIL_HOST);
            Config::set('mail.port', $objMailConfig->MAIL_PORT);
            Config::set('mail.username', $objMailConfig->MAIL_USERNAME);
            Config::set('mail.password', $objMailConfig->MAIL_PASSWORD);
            Config::set('mail.encryption', $objMailConfig->MAIL_PASSWORD);
            Config::set('mail.from.address', $objMailConfig->MAIL_FROM_ADDRESS);
            Config::set('mail.from.name', $objMailConfig->MAIL_FROM_NAME);
        }
    }
}
