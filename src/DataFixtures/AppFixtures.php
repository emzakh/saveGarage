<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Image;
use App\Entity\User;
use App\Entity\Voiture;
use Cocur\Slugify\Slugify;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
        // gestion du hash de password
        private $encoder;

        public function __construct(UserPasswordEncoderInterface $encoder)
        {
            $this->encoder = $encoder;
        }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('FR-fr');
        // gestion des utilisateurs 
        $users = []; // initialisation d'un tableau pour associer Ad et User
        $genres = ['male','femelle'];

        for($u=1; $u <= 10; $u++){
            $user = new User();
            $genre = $faker->randomElement($genres);

            $picture = 'https://randomuser.me/api/portraits/';
            $pictureId = $faker->numberBetween(1,99).'.jpg';
            $picture .= ($genre == 'male' ? 'men/' : 'women/').$pictureId;

            $hash = $this->encoder->encodePassword($user,'password');

            $user->setFirstName($faker->firstName($genre))
                ->setLastName($faker->lastName())
                ->setEmail($faker->email())
                ->setIntroduction($faker->sentence())
                ->setDescription('<p>'.join('</p><p>', $faker->paragraphs(3)).'</p>')
                ->setPassword($hash)
                ->setPicture('');

            $manager->persist($user);
            $users[]= $user; // ajouter l'utilisateur fraichement créé dans le tableau pour l'association avec les annonces    

    }
        for ($u = 1; $u <= 12; $u++) {
            $voiture = new Voiture();
            $slugify = new Slugify();
            $marque = $faker->lexify('constructor ???');
            $modele = $faker->lexify('car ??????');
            $dateCircu = $faker->dateTime($max = 'now', $timezone = null);
            $description = $faker->paragraph(3);
            $supOption = '<p>' . join('</p><p>', $faker->sentences(rand(5, 15), false)) . '</p>';
            $carburant = ['essence', 'diesel'];
            $transmission = ['automatique', 'manuel'];
            $slug = $slugify->slugify($marque . '-' . $modele . '-' . rand(1, 100000));
            $user = $users[rand(0,count($users)-1)];


            $voiture->setMarque($marque)
                ->setModele($modele)
                ->setImgCover('https://placekitten.com/1000/350')
                ->setKm(rand(100, 250000))
                ->setPrix(rand(400, 50000))
                ->setNbProprio(rand(1, 5))
                ->setCylindre(rand(800, 8000))
                ->setPuissance(rand(10, 2000))
                ->setCarburant($faker->randomElement($carburant))
                ->setDateCircu($dateCircu)
                ->setTransmission($faker->randomElement($transmission))
                ->setDescription($description)
                ->setSupOption($supOption)
                ->setSlug($slug)
                ->setAuthor($user);


            $manager->persist($voiture);

            for ($i = 1; $i <= rand(2, 4); $i++) {
                $image = new Image();
                $image->setUrl('https://placekitten.com/350/350')
                    ->setCaption($faker->sentence())
                    ->setVoiture($voiture);
                $manager->persist($image);
            }
            $manager->flush();
        }
    }
}
