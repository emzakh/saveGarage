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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
     * @IsGranted("ROLE_USER")
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
     * @Security("(is_granted('ROLE_USER') and user === voiture.getAuthor()) or is_granted('ROLE_ADMIN')", message="Cette annonce ne vous appartient pas, vous ne pouvez pas la modifier")
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
   
     /**
     * Permet de supprimer une annonce
     * @Route("/catalogue/{slug}/delete", name="delete")
     * @Security("is_granted('ROLE_USER') and user === ad.getAuthor()", message="Vous n'avez pas le droit d'accèder à cette ressource")
     * @param Voiture $voiture
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(Voiture $voiture, EntityManagerInterface $manager)
    {
   $this->addFlash(
       'success',
       "L'annonce <strong>{$voiture->getSlug()}</strong> a bien été supprimée"
   );
   $manager->remove($voiture);
   $manager->flush();
   return $this->redirectToRoute("catalogue");
   }
    

    
}
