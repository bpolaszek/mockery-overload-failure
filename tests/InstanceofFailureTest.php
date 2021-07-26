<?php

declare(strict_types=1);

namespace Tests;

use App\EmailNotifier;
use Mockery;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

it('fails giving the appropriate inheritance of the mock', function () {
    $mailer = Mockery::mock(MailerInterface::class);
    $notifier = new EmailNotifier($mailer, 'from@example.com');

    $email = Mockery::mock('overload:'.Email::class);
    $email->shouldReceive('from')->with('from@example.com')->andReturn($email);
    $email->shouldReceive('to')->with('to@example.com')->andReturn($email);
    $email->shouldReceive('text')->with('You should look at this')->andReturn($email);

    $mailer->shouldReceive('send')->with($email);
    $notifier->notify('to@example.com', 'You should look at this');
});
