<?php

namespace App\Controller;


use App\Repository\VoitureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /** 
     *@Route("/", name="homepage")
     *@param VoitureRepository $repo
     *@return Response
     */

    public function index(VoitureRepository $repo): Response
    {
        $myLimit = 3;
        $voiture = $repo->findBy([], ["id" => "DESC"], $myLimit, null);
        return $this->render('home/index.html.twig', [
            'voitures' => $voiture,
        ]);
    }


  

}
