<?php

namespace App\Services;

class MailService extends AbstractService
{
    /**
     * Email sender
     * @var string
     */
    var $sender;

    /**
     * MailService constructor.
     *
     * @param string $sender
     */
    public function __construct(string $sender)
    {
        $this->sender = $sender;

        parent::__construct();
    }

    /**
     * This is the fake mail function
     *
     * @param string $to
     * @param string $content
     */
    public function sendEmail(string $to, string $content)
    {
        // TODO Implement sending email
        // parametrs:
        //     $this->sender - send email from (sender)
        //     $to           - send email to (receiver)
        //     $content      - email content
    }
}
