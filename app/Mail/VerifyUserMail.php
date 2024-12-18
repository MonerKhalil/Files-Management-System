<?php

namespace App\Mail;

use App\Helpers\MyApp;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyUserMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(private $token)
    {
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Reset Password Email")
            ->markdown('email-templates.auth.verify-user')
            ->with([
                "front_url" => MyApp::Classes()->cacheProcess->getGeneralSettings("front_url")->value ?? "#",
                "website_logo" => MyApp::Classes()->cacheProcess->getWebsiteSettings("website_logo")->value ?? "-",
                "website_copyright" => MyApp::Classes()->cacheProcess->getWebsiteSettings("website_copyright")->value ?? "-",
                "token" => $this->token,
            ]);
    }
}
