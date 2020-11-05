<?php

namespace App\Builder;

use App\Entity\Duty;
use App\Repository\UserRepository;
use App\Request\SetDutyRequest;

class DutyBuilder
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function batchBuild(SetDutyRequest $request): array
    {
        $duties = [];

        foreach ($request->getUsernames() as $username) {
            $duties[] = $this->build($username, $request->getStarted(), $request->getEnded(), $request->getComment());
        }

        return $duties;
    }

    private function build(string $username, \DateTimeInterface $started, \DateTimeInterface $ended, ?string $comment)
    {
        $user = $this->userRepository->findOneBy(['name' => $username]);

        return new Duty($user, $started, $ended, $comment);
    }
}