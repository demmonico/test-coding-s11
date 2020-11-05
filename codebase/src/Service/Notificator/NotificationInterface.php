<?php

namespace App\Service\Notificator;

interface NotificationInterface
{
    public function addRecipient(string $recipient): self;
    public function getRecipients(): array;
    public function addMessage(string $message): self;
    public function getMessage(): string;
}
