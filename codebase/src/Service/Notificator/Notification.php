<?php

namespace App\Service\Notificator;

use Symfony\Component\Validator\Constraints as Assert;

final class Notification implements NotificationInterface
{
    /**
     * @var array
     * @Assert\All({
     *     @Assert\NotBlank,
     *     @Assert\Regex(pattern="/^\+[0-9]{10,13}$/i", message="Phone number should be valid +dddddddddd")
     * })
     */
    private array $recipients = [];

    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\Length (max=255)
     */
    private string $message = '';

    public function addRecipient(string $recipient): NotificationInterface
    {
        $this->recipients[] = $recipient;

        return $this;
    }

    public function addMessage(string $message): NotificationInterface
    {
        $this->message = $message;

        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getRecipients(): array
    {
        return $this->recipients;
    }
}