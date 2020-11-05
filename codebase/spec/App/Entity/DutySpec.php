<?php

namespace spec\App\Entity;

use App\Entity\Duty;
use App\Entity\User;
use PhpSpec\ObjectBehavior;

class DutySpec extends ObjectBehavior
{
    const USER_PHONE = 'testPhone';
    const COMMENT = 'test comment';

    public function it_is_initializable()
    {
        $this->shouldHaveType(Duty::class);
    }

    public function let(User $user, \DateTimeImmutable $started, \DateTimeImmutable $ended)
    {
        $user->getPhone()->willReturn(self::USER_PHONE);
        $user->asArray()->willReturn(array_fill(0, 5, random_bytes(8)));

        $this->beConstructedWith($user, $started, $ended, null);
    }

    public function it_auto_assign_fields(User $user, \DateTimeImmutable $started, \DateTimeImmutable $ended)
    {
        $this->getUser()->shouldReturnAnInstanceOf(User::class);
        $this->getUser()->shouldBe($user);
        $this->getUserContact()->shouldBe(self::USER_PHONE);

        $this->getStarted()->shouldBe($started);
        $this->getEnded()->shouldBe($ended);

        $this->getCreated()->shouldReturnAnInstanceOf(\DateTimeImmutable::class);
    }

    public function it_has_array_view()
    {
        $this->asArray()->shouldBeArray();
        $this->asArray()->shouldHaveCount(7);
    }

    public function it_has_optional_comment(User $user, \DateTimeImmutable $started, \DateTimeImmutable $ended)
    {
        $this->beConstructedWith($user, $started, $ended, self::COMMENT);

        $this->getComment()->shouldBe(self::COMMENT);
    }
}
