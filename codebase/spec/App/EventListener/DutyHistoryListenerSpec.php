<?php

namespace spec\App\EventListener;

use App\Entity\Duty;
use App\Entity\DutyHistory;
use App\EventListener\DutyHistoryListener;
use App\Factory\DutyHistoryFactory;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use PhpSpec\ObjectBehavior;
use Psr\Log\LoggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class DutyHistoryListenerSpec extends ObjectBehavior
{
    const ACT_SET = 'set';
    const ACT_UNSET = 'unset';

    public function it_is_initializable()
    {
        $this->shouldHaveType(DutyHistoryListener::class);
    }

    public function let(
        DutyHistoryFactory $factory,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator,
        LoggerInterface $logger
    ) {
        $this->beConstructedWith($factory, $entityManager, $validator, $logger);
    }

    public function it_saves_post_persist_state(
        DutyHistoryFactory $factory,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator,
        LoggerInterface $logger,
        Duty $duty,
        DutyHistory $dutyHistory,
        LifecycleEventArgs $event
    ) {
        $factory->createDutyHistory($duty, self::ACT_SET)->willReturn($dutyHistory);
        $validator->validate($dutyHistory)->willReturn([]);

        $this->beConstructedWith($factory, $entityManager, $validator, $logger);

        $this->postPersist($duty, $event);
    }

    public function it_saves_pre_remove_state(
        DutyHistoryFactory $factory,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator,
        LoggerInterface $logger,
        Duty $duty,
        DutyHistory $dutyHistory,
        LifecycleEventArgs $event
    ) {
        $factory->createDutyHistory($duty, self::ACT_UNSET)->willReturn($dutyHistory);
        $validator->validate($dutyHistory)->willReturn([]);

        $this->beConstructedWith($factory, $entityManager, $validator, $logger);

        $this->preRemove($duty, $event);
    }
}
