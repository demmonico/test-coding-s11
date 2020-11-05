<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Duty
 *
 * @ORM\Table(name="duties", indexes={@ORM\Index(name="duties_period_idx", columns={"started", "ended"}), @ORM\Index(name="duties_user_id_idx", columns={"user_id"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\DutyRepository")
 */
class Duty
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
     * @var string
     *
     * @ORM\Column(name="user_contact", type="string", length=255, nullable=false)
     *
     * @Assert\Regex(pattern="/^\+49[0-9]{8,11}$/i", message="User contact should be Germany valid +49dddddddd, {{ value }} provided")
     */
    private $userContact;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="started", type="datetime", nullable=false)
     */
    private $started;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ended", type="datetime", nullable=false)
     */
    private $ended;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime", nullable=false)
     */
    private $created;

    /**
     * @var string|null
     *
     * @ORM\Column(name="comment", type="string", length=255, nullable=true)
     * @Assert\Length(max=255)
     */
    private $comment;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    public function __construct(User $user, \DateTimeInterface $started, \DateTimeInterface $ended, ?string $comment)
    {
        $this->user = $user;
        $this->userContact = $user->getPhone();

        $this->started = $started;
        $this->ended = $ended;
        $this->comment = $comment;

        $this->created = new \DateTimeImmutable();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getStarted(): ?\DateTimeInterface
    {
        return $this->started;
    }

    public function getEnded(): ?\DateTimeInterface
    {
        return $this->ended;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getUserContact(): string
    {
        return $this->userContact;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function asArray(): array
    {
        return [
            'id' => $this->getId(),
            'started' => $this->getStarted(),
            'ended' => $this->getEnded(),
            'created' => $this->getCreated(),
            'user' => $this->getUser()->asArray(),
            'user_contact' => $this->getUserContact(),
            'comment' => $this->getComment(),
        ];
    }
}
