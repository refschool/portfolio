<?php

namespace App\Event;

use App\Entity\Contact;
use Symfony\Contracts\EventDispatcher\Event;

class MessageSuccessEvent extends Event
{

    private $message;

    public function __construct(Contact $message)
    {
        $this->message = $message;
    }

    public function getMessage(): Contact
    {
        return $this->message;
    }
}
