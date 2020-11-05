<?php

namespace spec\App\Factory;

use App\Entity\Duty;
use App\Entity\DutyHistory;
use App\Entity\User;
use App\Factory\DutyHistoryFactory;
use PhpSpec\ObjectBehavior;

class DutyHistoryFactorySpec extends ObjectBehavior
{
    const ACT_SET = 'set';

    public function it_is_initializable()
    {
        $this->shouldHaveType(DutyHistoryFactory::class);
    }

    public function it_creates_duty_history(Duty $duty, User $user)
    {
        $duty->getId()->willReturn(rand());
        $duty->getUser()->willReturn($user);
        $duty->asArray()->willReturn(array_fill(0, 7, random_bytes(8)));

        $dutyHistory = $this->createDutyHistory($duty, self::ACT_SET);

        $dutyHistory->shouldBeAnInstanceOf(DutyHistory::class);
        $dutyHistory->getAct()->shouldBeEqualTo(self::ACT_SET);
    }
}
