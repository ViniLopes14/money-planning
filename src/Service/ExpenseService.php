<?php

namespace App\Service;

use App\Entity\Expense;
use DateInterval;
use Psr\Log\LoggerInterface;

class ExpenseService
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function repetition(Expense $expense): void
    {
        if ($expense->getRepetitionType() != 1) {
            $installments = $expense->getRepetitionInstallment();
            $oneMonth = new DateInterval('P1M');
            $date = $expense->getDate();
            $date->add($oneMonth);


            /*$date = $date->format('Y-m-d');
            $date = new \DateTime($date);*/



            //$this->logger->info('----------------');
            //$this->logger->info(print_r($date, true));
            

            for ($x = 1; $x <= $installments; $x++) {
                //$expense->setDate($date);
            }
        }
    }
}
