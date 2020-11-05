<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * DutyHistory
 *
 * @ORM\Table(name="duty_history", indexes={@ORM\Index(name="duties_duty_id_idx", columns={"duty_id"}), @ORM\Index(name="duties_user_id_idx", columns={"user_id"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\DutyHistoryRepository")
 */
class DutyHistory
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="bigint", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="duty_id", type="bigint", nullable=false, options={"unsigned"=true})
     *
     * @Assert\NotBlank
     */
    private $dutyId;

    /**
     * @var int
     *
     * @ORM\Column(name="user_id", type="bigint", nullable=false, options={"unsigned"=true})
     *
     * @Assert\NotBlank
     */
    private $userId;

    /**
     * @var string
     *
     * @ORM\Column(name="act", type="string", length=0, nullable=false)
     *
     * @Assert\NotBlank
     * @Assert\Choice(callback={"App\Enum\DutyHistoryAction", "getActions"})
     */
    private $act;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime", nullable=false)
     */
    private $created;

    /**
     * @var string
     *
     * @ORM\Column(name="duty_data", type="text", length=65535, nullable=false)
     *
     * @Assert\NotBlank
     * @Assert\Length(max=65535)
     */
    private $dutyData;

    public function __construct(Duty $duty, string $action)
    {
        $this->dutyId = $duty->getId();
        $this->userId = $duty->getUser()->getId();
        $this->dutyData = json_encode($duty->asArray());

        $this->act = $action;

        $this->created = new \DateTimeImmutable();
    }
}
