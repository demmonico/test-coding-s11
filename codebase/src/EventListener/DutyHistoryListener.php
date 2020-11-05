<?php

namespace App\EventListener;

use App\Entity\Duty;
use App\Entity\DutyHistory;
use App\Enum\DutyHistoryAction;
use App\Factory\DutyHistoryFactory;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Psr\Log\LoggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class DutyHistoryListener
{
    private DutyHistoryFactory $factory;
    private EntityManagerInterface $entityManager;
    private ValidatorInterface $validator;
    private LoggerInterface $logger;

    public function __construct(
        DutyHistoryFactory $factory,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator,
        LoggerInterface $logger
    ) {
        $this->factory = $factory;
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->logger = $logger;
    }

    public function postPersist(Duty $duty, LifecycleEventArgs $event)
    {
        $this->saveDutyHistory(
            $this->factory->createDutyHistory($duty, DutyHistoryAction::ACTION_SET)
        );
    }

    public function preRemove(Duty $duty, LifecycleEventArgs $event)
    {
        $this->saveDutyHistory(
            $this->factory->createDutyHistory($duty, DutyHistoryAction::ACTION_UNSET)
        );
    }

    private function saveDutyHistory(DutyHistory $dutyHistory)
    {
        $errors = $this->validator->validate($dutyHistory);
        if (count($errors) > 0) {
            $this->logger->error(sprintf('Validation error: %s', (string) $errors));
            return false;
        }

        try {
            $this->entityManager->persist($dutyHistory);
            $this->entityManager->flush();
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
            return false;
        }

        return true;
    }
}