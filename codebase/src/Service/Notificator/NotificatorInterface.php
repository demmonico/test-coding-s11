<?php

namespace App\Service\Notificator;

interface NotificatorInterface
{
    public function send(NotificationInterface $notification): bool;
    public function createNotification(): NotificationInterface;
}
