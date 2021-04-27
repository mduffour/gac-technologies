<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="ticket_call")
 * @ORM\Entity(repositoryClass=TicketCallRepository::class)
 *
 * @UniqueEntity(
 *     fields={"invoicedAccount", "invoiceNumber", "userNumber", "date", "hour"},
 *     errorPath="ticket_call",
 *     message="This ticket call is already in database"
 * )
 */
class TicketCall
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="integer")
     */
    private int $invoicedAccount;

    /**
     * @ORM\Column(type="integer")
     */
    private int $invoiceNumber;

    /**
     * @ORM\Column(type="integer")
     */
    private int $userNumber;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTime $dateCall;

    /**
     * @ORM\Column(type="string")
     */
    private string $hourCall;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private int $realDuration;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private int $realVolume;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private int $invoicedDuration;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private int $invoiceVolume;

    /**
     * @ORM\Column(type="string")
     */
    private string $typeCall;

    public function getId(): int
    {
        return $this->id;
    }

    public function getInvoicedAccount(): int
    {
        return $this->invoicedAccount;
    }

    public function setInvoicedAccount(int $invoicedAccount): TicketCall
    {
        $this->invoicedAccount = $invoicedAccount;

        return $this;
    }

    public function getInvoiceNumber(): int
    {
        return $this->invoiceNumber;
    }

    public function setInvoiceNumber(int $invoiceNumber): TicketCall
    {
        $this->invoiceNumber = $invoiceNumber;

        return $this;
    }

    public function getUserNumber(): int
    {
        return $this->userNumber;
    }

    public function setUserNumber(int $userNumber): TicketCall
    {
        $this->userNumber = $userNumber;

        return $this;
    }

    public function getDateCall(): DateTime
    {
        return $this->dateCall;
    }

    public function setDateCall(DateTime $date): TicketCall
    {
        $this->dateCall = $date;

        return $this;
    }

    public function getHourCall(): string
    {
        return $this->hourCall;
    }

    public function setHourCall(string $hour): TicketCall
    {
        $this->hourCall = $hour;

        return $this;
    }

    public function getRealDuration(): ?int
    {
        return $this->realDuration;
    }

    public function setRealDuration(?int $realDuration): TicketCall
    {
        $this->realDuration = $realDuration;

        return $this;
    }

    public function getInvoiceDuration(): ?int
    {
        return $this->invoicedDuration;
    }

    public function setInvoiceDuration(?int $invoicedDuration): TicketCall
    {
        $this->invoicedDuration = $invoicedDuration;

        return $this;
    }

    public function getInvoiceVolume(): ?int
    {
        return $this->invoiceVolume;
    }

    public function setInvoiceVolume(?string $invoiceVolume): TicketCall
    {
        $this->invoiceVolume = $invoiceVolume;

        return $this;
    }

    public function getRealVolume(): ?int
    {
        return $this->realVolume;
    }

    public function setRealVolume(?string $realVolume): TicketCall
    {
        $this->realVolume = $realVolume;

        return $this;
    }

    public function getTypeCall(): string
    {
        return $this->typeCall;
    }

    public function setTypeCall(string $type): TicketCall
    {
        $this->typeCall = $type;

        return $this;
    }
}