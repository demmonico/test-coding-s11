<?php

namespace App\Controller;

use App\Entity\Duty;
use App\Builder\DutyBuilder;
use App\Repository\DutyRepository;
use App\Request\SetDutyRequest;
use App\Service\Notificator\NotificatorInterface;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class DutyController extends AbstractController
{
    private DutyRepository $dutyRepository;
    private DutyBuilder $dutyBuilder;
    private NotificatorInterface $notificator;
    private LoggerInterface $logger;
    private EntityManagerInterface $entityManager;

    public function __construct(
        DutyRepository $dutyRepository,
        DutyBuilder $dutyBuilder,
        NotificatorInterface $notificator,
        EntityManagerInterface $entityManager,
        LoggerInterface $logger
    ) {
        $this->dutyRepository = $dutyRepository;
        $this->dutyBuilder = $dutyBuilder;
        $this->notificator = $notificator;
        $this->logger = $logger;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/duty", methods="GET", name="duty_get")
     * @param Request $request
     * @return Response
     */
    public function getDutiesAction(Request $request): Response
    {
        $filter = $request->get('filter');

        if ($filter === 'active') {
            $duties = $this->dutyRepository->findActive();
        } else {
            $duties = $this->dutyRepository->findAll();
        }

        return $this->json([
            'duties' => array_map(fn(Duty $duty) => $duty->asArray(), $duties)
        ]);
    }

    /**
     * @Route("/duty", methods="POST", name="duty_set")
     * @param ValidatorInterface $validator
     * @param Request $request
     * @return Response
     */
    public function setDutiesAction(ValidatorInterface $validator, Request $request): Response
    {
        // set and validate request
        $setDutyRequest = new SetDutyRequest($request);
        if (null !== $errorMessage = $this->validationErrorOrNull($validator, $setDutyRequest)) {
            return $this->json(['error' => $errorMessage], 400);
        }

        // batch build duties
        $duties = $this->dutyBuilder->batchBuild($setDutyRequest);

        // validate duties
        foreach ($duties as $duty) {
            if (null !== $errorMessage = $this->validationErrorOrNull($validator, $duty)) {
                return $this->json(['error' => $errorMessage], 400);
            }
        }

        try {
            // clean exists duties
            $existsDuties = $this->dutyRepository->findAll();
            foreach ($existsDuties as $duty) {
                $this->entityManager->remove($duty);
            }

            // insert new duties
            foreach ($duties as $duty) {
                $this->entityManager->persist($duty);
            }

            // flush
            $this->entityManager->flush();
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());

            return $this->json(['error' => 'Error: ' . $e->getMessage()], 500);
        }

        // send notifications
        $this->sendNotifications($duties);

        return $this->json([
            'duties' => array_map(fn($duty) => $duty->asArray(), $duties),
        ]);
    }

    private function validationErrorOrNull(ValidatorInterface $validator, $value): ?string
    {
        $errors = $validator->validate($value);

        if (count($errors) > 0) {
            return sprintf('Validation error: %s', (string) $errors);
        }

        return null;
    }

    private function sendNotifications(array $duties): bool
    {
        $notification = $this->notificator
            ->createNotification()
            ->addMessage('You are on call!');

        foreach ($duties as $duty) {
            /**
             * @var Duty $duty
             */
            $notification->addRecipient($duty->getUserContact());
        }

        try {
            return $this->notificator->send($notification);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }

        return false;
    }
}
