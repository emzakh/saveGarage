<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Voiture;
use App\Repository\VoitureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Form\VroomType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ShowCarController extends AbstractController
{

    /**
     * @Route("/catalogue", name="catalogue")
     */

    public function index(VoitureRepository $repo): Response
    {

        $voiture = $repo->findAll();

        return $this->render('catalogue/allvroom.html.twig', [
            'voitures' => $voiture,
        ]);
    }

    /**
     * Permet d'ajouter une voiture
     * @Route("/catalogue/new", name="new_voiture")
     * 
     *
     * @return Response
     */

    public function create(EntityManagerInterface $manager, Request $request)
    {
        $voiture = new Voiture();

        $image1 = new Image();
        $image1->setUrl('http://placehold.it/400x200')
            ->setCaption('Titre 1');
        $voiture->addImage($image1);

        $image2 = new Image();
        $image2->setUrl('http://placehold.it/400x200')
            ->setCaption('Titre 2');
        $voiture->addImage($image2);

        $form = $this->createForm(VroomType::class, $voiture);

        $form->handleRequest($request);

        // dump($voiture);

        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($voiture->getImages() as $image) {
                $image->setVoiture($voiture);
                $manager->persist($image);
            }
            
            $voiture->setAuthor($this->getUser());
            // on ajoute l'auteur mais attention maintenant il y a un risque de bug si on n'est pas connecté (à corriger)
            // $ad->setAuthor($this->getUser());

            $manager->persist($voiture);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'annonce <strong>{$voiture->getSlug()}</strong> a bien été enregistrée"
            );

            return $this->redirectToRoute('fichevroom', [
                'slug' => $voiture->getSlug()
            ]);
        }

        return $this->render('catalogue/addvroom.html.twig', [
            'myForm' => $form->createView()
        ]);
    }


    
    /**
     * @Route("/catalogue/{slug}", name="fichevroom")
     * @return Response
     */

    public function show(Voiture $voitureBDD): Response
    {
        return $this->render('catalogue/fichevroom.html.twig', [
            'showVoiture' => $voitureBDD,
        ]);
    }



        /**
     * Permet d'afficher le formulaire d'édition
     * @Route("/catalogue/{slug}/edit", name="edit")
     * @return Response
     */
    public function edit(Voiture $voiture, Request $request, EntityManagerInterface
    $manager)
    {
        $form = $this->createForm(VroomType::class, $voiture);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            foreach($voiture->getImages() as $image){
            $image->setVoiture($voiture);
            $manager->persist($image);
            }
            $manager->persist($voiture);
            $manager->flush();
            $this->addFlash(
            'success',
            "L'annonce <strong>{$voiture->getSlug()}</strong> a bien été
           modifée!"
            );
        return $this->redirectToRoute('fichevroom',[
        'slug' => $voiture->getSlug()
        ]);
        }
           
        return $this->render('catalogue/edit.html.twig', [
            'myForm' => $form->createView()
        ]);
    }
}
