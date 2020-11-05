<?php

namespace App\Service\Notificator;

use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\Exception\LogicException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class LogNotificator implements NotificatorInterface
{
    private ValidatorInterface $validator;
    private LoggerInterface $logger;

    public function __construct(ValidatorInterface $validator, LoggerInterface $logger)
    {
        $this->validator = $validator;
        $this->logger = $logger;
    }

    public function send(NotificationInterface $notification): bool
    {
        if (!$this->validate($notification)) {
            throw new LogicException('Notification is not valid');
        }

        $this->logger->info(sprintf(
            "Notification with message '%s' has been sent to follow numbers: %s",
            $notification->getMessage(),
            implode(',', $notification->getRecipients())
        ));

        return true;
    }

    public function createNotification(): NotificationInterface
    {
        return new Notification();
    }

    private function validate(NotificationInterface $notification): bool
    {
        $errors = $this->validator->validate($notification);

        return count($errors) === 0;
    }
}
