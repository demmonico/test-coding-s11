<?php

namespace spec\App\EventListener;

use App\Entity\Duty;
use App\Entity\DutyHistory;
use App\Entity\User;
use App\EventListener\DutyHistoryListener;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use PhpSpec\ObjectBehavior;
use Psr\Log\LoggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class DutyHistoryListenerSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(DutyHistoryListener::class);
    }

    public function let(
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator,
        LoggerInterface $logger
    ) {
        $this->beConstructedWith($entityManager, $validator, $logger);
    }

//    public function it_saves_post_persist_state(
//        Duty $duty,
//        DutyHistory $dutyHistory,
//        User $user,
//        LifecycleEventArgs $event,
//        ValidatorInterface $validator,
//        EntityManagerInterface $entityManager,
//        LoggerInterface $logger
//    ) {
//        $duty->getUser()->willReturn($user);
//        $duty->asArray()->willReturn(array_fill(0, 7, random_bytes(8)));
////        $validator->validate($dutyHistory)->willReturn([]);
////        $entityManager->persist($dutyHistory)->shouldBeCalled();
////        $entityManager->flush()->shouldBeCalled();
//
////        $dutyHistory = new DutyHistory($duty, 'set');
//        $validator->validate($dutyHistory)->willReturn([]);
//
//        $this->beConstructedWith($entityManager, $validator, $logger);
//
//        $this->postPersist($duty, $event);
//    }
}
