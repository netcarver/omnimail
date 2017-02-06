<?php

namespace Omnimail\Tests;

use Omnimail\Exception\Exception;
use PHPUnit\Framework\TestCase;
use Omnimail\Email;
use Omnimail\SendinBlue;

class SendinBlueTest extends TestCase
{
    public function testErrorMessageIsThrownWithIncorrectDetails()
    {
        $this->expectException(Exception::class);

        $accessKey = 'apikey';

        $sender = new SendinBlue($accessKey);

        $email = (new Email())
            ->addTo('your@email.com')
            ->setFrom('from@email.com')
            ->setSubject('Hello, world!')
            ->setTextBody('Hello World! How are you?');

        $sender->send($email);
    }
}
