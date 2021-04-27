<?php

declare(strict_types=1);

namespace App\Helper;

use Doctrine\ORM\EntityManagerInterface;
use App\Enum\Utils;
use DateTime;
use DateTimeZone;
use Exception;
use RuntimeException;
use Symfony\Component\Console\Helper\ProgressBar;

final class CallPersister
{
    private const MODULO_INSERT = 100;
    private const TABLE = 'ticket_call';
    private const REGEX_DURATION = '/^\d{2}:\d{2}:\d{2}$/';

    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em) 
    {
        $this->em = $em;
    }

    /**
     * @param string[][] $dataFile
     * @param ProgressBar $progressBar
     * 
     * @throws RuntimeException
    */
    public function save(iterable $dataFile, ProgressBar $progressBar): int
    {
        $i = 1;
        foreach($dataFile as $rawTicketCall)
        {
            try {
                $insertLines[] = sprintf(
                    '(%s)', 
                    implode(', ', $this->formatDataTicketCall($rawTicketCall))
                );

                if ($i % self::MODULO_INSERT === 0 || count($dataFile) === $i) {
                    $this->insertTicketCalls($insertLines);

                    $progressBar->advance(count($insertLines));

                    $insertLines = [];
                }
                $i++;
            } catch (Exception $e) {
                throw new RuntimeException(
                    sprintf('Error while inserting data around line %d', $i)
                );
            }
        }
        
        return count($dataFile);
    }

    /**
     * @param string[] $insertLines
    */
    private function insertTicketCalls(array $insertLines): void
    {
        $query = sprintf(
            'INSERT INTO %s (%s) VALUES %s',
            self::TABLE,
            implode(', ', Utils::getColumnNames()),
            implode(', ', $insertLines)
        );

        $stmt = $this->em->getConnection()->prepare($query);
        $stmt->executeStatement();
    }

    /**
     * @param string[] $rawTicketCall
     * 
     * @return string[]
    */
    private function formatDataTicketCall(array $rawTicketCall): array
    {
        $durationReal = preg_match(self::REGEX_DURATION, $rawTicketCall[Utils::DURATION_REAL_VOLUME]);
        $durationInvoice = preg_match(self::REGEX_DURATION, $rawTicketCall[Utils::DURATION_INVOICE_VOLUME]);
        return [
            (int) $rawTicketCall[Utils::INVOICED_ACCOUNT],
            (int) $rawTicketCall[Utils::INVOICE_NUMBER],
            (int) $rawTicketCall[Utils::USER_NUMBER],
            '"'.DateTime::createFromFormat('d/m/Y', $rawTicketCall[Utils::DATE])->format('Y-m-d H:i:s').'"',
            '"'.$rawTicketCall[Utils::HOUR].'"',
            !empty($durationReal) ? 
            $this->getSecondsFromDuration($rawTicketCall[Utils::DURATION_REAL_VOLUME]) :
             'NULL',
            empty($durationReal) && !empty($rawTicketCall[Utils::DURATION_REAL_VOLUME]) ?
             (int) $rawTicketCall[Utils::DURATION_REAL_VOLUME] :
              'NULL',
            !empty($durationInvoice) ? 
            $this->getSecondsFromDuration($rawTicketCall[Utils::DURATION_INVOICE_VOLUME]) :
             'NULL',
            empty($durationInvoice) && !empty($rawTicketCall[Utils::DURATION_INVOICE_VOLUME]) ?
             (int) $rawTicketCall[Utils::DURATION_INVOICE_VOLUME] :
              'NULL',
            '"'.$rawTicketCall[Utils::TYPE].'"'
        ];
    }

    private function getSecondsFromDuration(string $value): int
    {
        $dt = new DateTime(sprintf("1970-01-01 %s", $value), new DateTimeZone('UTC'));
        return (int)$dt->getTimestamp();
    }
}