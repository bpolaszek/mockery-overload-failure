#  Mockery Hard Dependency mocking bug

Trying to assert that the `Mailer` service successflly sent an `Email` 
with the appropriate recipient / sender / body.

```php
# src/EmailNotifier.php
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
```

- `Symfony\Component\Mime\Email` implements `Symfony\Component\Mime\RawMessage`
- `Symfony\Component\Mailer\MailerInterface::send` expects a `Symfony\Component\Mime\RawMessage` as 1st argument.

## Simple overload mock

```php
$mailer = Mockery::mock(MailerInterface::class);
$notifier = new EmailNotifier($mailer, 'from@example.com');
$email = Mockery::mock('overload:'.Email::class);
```

```bash
php vendor/bin/pest tests/InstanceofFailureTest.php
```

> Mockery_0_Symfony_Component_Mailer_MailerInterface::send(): 
> Argument #1 ($message) must be of type Symfony\Component\Mime\RawMessage, 
> Symfony\Component\Mime\Email given

## Overload with additional interface

```php
$mailer = Mockery::mock(MailerInterface::class);
$notifier = new EmailNotifier($mailer, 'from@example.com');
$email = Mockery::mock('overload:'.Email::class, RawMessage::class);
```

```bash
php vendor/bin/pest tests/DuplicateDeclarationTest.php
```

> PHP Fatal error:  Cannot redeclare Symfony\Component\Mime\Email::__construct() 
> in vendor/mockery/mockery/library/Mockery/Loader/EvalLoader.php(34) : 
> eval()'d code on line 1013
