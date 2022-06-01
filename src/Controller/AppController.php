<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    /** routage:
     * @Route("/", name="app_index")
     */
    public function index(): Response
    {
        return $this->render('app/index.html.twig');
    }
    
    /**
     * @Route("/enfants", name="app_children")
     */
    public function children(): Response 
    {
        return $this->render('app/enfants.html.twig');
    }

    /**
     * @Route("/tarif", name="app_tarif")
     */
    public function tarif(): Response 
    {
        return $this->render('app/tarif.html.twig');
    }

    /**
     * @Route("/adultes", name="app_adult")
     */
    public function adult(): Response 
    {
        return $this->render('app/adultes.html.twig');
    }

    /**
     * @Route("/voix", name="app_voix")
     */
    public function voix(): Response 
    {
        return $this->render('app/voix.html.twig');
    }

    /**
     * @Route("/alto", name="app_alto")
     */
    public function alto(): Response 
    {
        return $this->render('app/alto.html.twig');
    }

    /**
     * @Route("/soprane", name="app_soprane")
     */
    public function soprane(): Response 
    {
        return $this->render('app/soprane.html.twig');
    }

    /**
     * @Route("/tenor", name="app_tenor")
     */
    public function tenor(): Response 
    {
        return $this->render('app/tenor.html.twig');
    }

    /**
     * @Route("/basse", name="app_basse")
     */
    public function basse(): Response 
    {
        return $this->render('app/basse.html.twig');
    }

    /**
     * @Route("/évènements", name="app_events")
     */
    public function events(): Response 
    {
        return $this->render('app/events.html.twig');
    }

    /**
     * @Route("/programme", name="app_program")
     */
    public function program(): Response 
    {
        return $this->render('app/program.html.twig');
    }

    /**
     * @Route("/apropos", name="app_about")
     */
    public function about(): Response 
    {
        return $this->render('app/about.html.twig');
    }


}
