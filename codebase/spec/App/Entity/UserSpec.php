<?php

namespace spec\App\Entity;

use App\Entity\User;
use PhpSpec\ObjectBehavior;

class UserSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(User::class);
    }

    public function it_auto_assign_fields()
    {
        $this->getCreated()->shouldReturnAnInstanceOf(\DateTimeImmutable::class);
    }

    public function it_has_array_view()
    {
        $self = $this->setName('testName');

        $self->asArray()->shouldBeArray();
        $self->asArray()->shouldHaveCount(5);
    }
}
