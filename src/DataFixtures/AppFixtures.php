<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Image;
use App\Entity\Voiture;
use Cocur\Slugify\Slugify;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        for ($u = 1; $u <= 12; $u++) {

            $faker = Factory::create('FR-fr');
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
                ->setSlug($slug);


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
