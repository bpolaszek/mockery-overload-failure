<?php

declare(strict_types=1);

namespace App;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

final class EmailNotifier
{
    public function __construct(
        private MailerInterface $mailer,
        private string $sender,
    ) {
    }

    public function notify(string $recipient, string $text): void
    {
        $this->mailer->send(
            (new Email())
            ->from($this->sender)
            ->to($recipient)
            ->text($text)
        );
    }
}
