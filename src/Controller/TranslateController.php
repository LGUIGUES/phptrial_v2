<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TranslateController extends AbstractController
{   
    public function __construct(
        private array $languages,
    ){}

    #[Route('/translate/{locale}', name: 'app_translate')]
    public function translate(Request $request, $locale): Response
    {  
        if (in_array($locale, $this->languages)) {
            // Save language in Session
            $request->getSession()->set('_locale', $locale);
            
            // Return on home page                       
            return $this->redirect($request->headers->get('referer'));
        } else {
            $locale = 'en'; // Default local
            return $this->redirectToRoute('app_error');
        }        
    }
}
