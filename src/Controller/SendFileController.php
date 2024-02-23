<?php

namespace App\Controller;

use App\Form\SendFileType;
use App\Service\ServiceFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SendFileController extends AbstractController
{   
    public function __construct(
        private ServiceFile $serviceFile,
    ){}

    #[Route('/sendfile', name: 'app_send_file')]
    public function sendFile(Request $request): Response
    {   
        $form = $this->createForm(SendFileType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            // Send form to Service for processing
            $response = $this->serviceFile->convertDataFile($form);

            if (array_key_exists('error', $response)) {
                
                $this->addFlash('error', $response['error']);

            } elseif (array_key_exists('success', $response)) {
                
                $this->addFlash('success', 'The API call was successful.');
                return $this->render('result_api/result-api.html.twig', [
                    'title' => 'API Results',
                    'apiResponse' => $response['success'],
                    'apiSubItems' => $response['submittedItems'],
                ]);
            }       
        }

        return $this->render('send_file/send-file.html.twig', [
            'title' => 'Send file',
            'sendFile' => $form->createView(),
        ]);
    }
}
