<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     * Cette première route renvoie sur la page d'accueil au démarrage du site
     * On affiche notre template (plus tard on pourra lui passer des variables et autres
     */
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }
}
