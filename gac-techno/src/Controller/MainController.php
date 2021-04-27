<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\TicketCallRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function index(TicketCallRepository $ticketCallRepository): Response
    {
        return $this->render('main.html.twig', [
            'numberSms' => $ticketCallRepository->findTotalSms(),
            'totalCallDuration' => $ticketCallRepository->findTotalRealDuration(),
            'infoUsersTop10' => $ticketCallRepository->findTop10InvoiceVolumes(),
        ]);
    }
}