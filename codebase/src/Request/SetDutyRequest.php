<?php

namespace App\Request;

use DateTimeImmutable;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class SetDutyRequest
{
    /**
     * @Assert\NotBlank()
     * @Assert\Count(min = 2)
     * @Assert\All({
     *     @Assert\NotBlank,
     * })
     */
    private array $usernames = [];

    /**
     * @Assert\NotBlank()
     */
    private ?string $startedRaw;

    /**
     * @Assert\NotBlank()
     */
    private ?string $endedRaw;

    /**
     * @Assert\NotBlank()
     */
    private \DateTimeInterface $started;

    /**
     * @Assert\NotBlank()
     * @Assert\GreaterThan(propertyPath="started")
     * @Assert\GreaterThanOrEqual("today")
     */
    private \DateTimeInterface $ended;

    /**
     * @Assert\Length(max = 255)
     */
    private ?string $comment;

    public function __construct(Request $request)
    {
        $usernames = $request->request->get('usernames', '');
        $this->usernames = explode(',', trim($usernames));

        $this->startedRaw = $request->request->get('started');
        $this->endedRaw = $request->request->get('ended');
        if (false === strpos($this->endedRaw, ':')) {
            $this->endedRaw .= ' 23:59:59';
        }

        $this->started = new DateTimeImmutable($this->startedRaw);
        $this->ended = new DateTimeImmutable($this->endedRaw);

        $this->comment = $request->request->get('comment');
    }

    public function getUsernames(): array
    {
        return $this->usernames;
    }

    public function getStarted(): \DateTimeInterface
    {
        return $this->started;
    }

    public function getEnded(): \DateTimeInterface
    {
        return $this->ended;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }
}
