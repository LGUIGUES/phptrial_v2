<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SendFileController extends AbstractController
{
    #[Route('/sendfile', name: 'app_send_file')]
    public function index(): Response
    {
        return $this->render('send_file/send-file.html.twig', [
            'title' => 'API Test',
        ]);
    }
}
