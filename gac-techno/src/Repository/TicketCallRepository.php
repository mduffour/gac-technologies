<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\TicketCall;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class TicketCallRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TicketCall::class);
    }

    public function findTotalSms(): int
    {
        $qb = $this->createQueryBuilder('t');

        $result = $qb
        ->where(
            $qb->expr()->like('t.typeCall', ':sms')
        )
        ->setParameter('sms','%sms%')
        ->getQuery()
        ->getScalarResult();

        return count($result);
    }

    /**
     * @return string[][]
    */
    public function findTop10InvoiceVolumes(): array
    {
        $sqlTop10InvoiceVolume = '
            SELECT t.user_number, 
            SUBSTRING_INDEX(GROUP_CONCAT(t.invoice_volume ORDER BY t.invoice_volume DESC), ",", 10) top10
            FROM ticket_call t
            WHERE t.hour_call NOT BETWEEN :from AND :to
            GROUP BY t.user_number
        ';

        $stmt = $this->getEntityManager()->getConnection()->prepare($sqlTop10InvoiceVolume);
        $stmt->executeStatement(['from' => '08:00:00', 'to' => '18:00:00']);

        return $stmt->fetchAllAssociative();
    }

    public function findTotalRealDuration(): string
    {
        $date = new DateTimeImmutable();
        $qb = $this->createQueryBuilder('t');

        $result = $qb
        ->select('SUM(t.realDuration) as totalRealDuration')
        ->where(
            $qb->expr()->like('t.typeCall', ':type')
        )
        ->andWhere('t.dateCall >= :dateRequested')
        ->setParameter('type', '%appel%')
        ->setParameter('dateRequested', $date->setDate(2012, 2, 15), \Doctrine\DBAL\Types\Types::DATETIME_IMMUTABLE)
        ->getQuery()
        ->getSingleScalarResult();

        return $this->secondstoHuman($result);
    }

    private function secondstoHuman(string $ss): string
    {
        return sprintf(
            '%d month(s), %d day(s), %d hour(s), %d minute(s), %d second(s)', 
            floor($ss/2592000), 
            floor(($ss%2592000)/86400), 
            floor(($ss%86400)/3600), 
            floor(($ss%3600)/60), 
            $ss%60
        );
    } 
}