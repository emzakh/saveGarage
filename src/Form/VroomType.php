<?php

namespace App\Form;

use App\Entity\Voiture;
use App\Form\ImageType;
use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class VroomType extends ApplicationType
{
 
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('marque',TextType::class,$this->getConfiguration('marque','Marque de votre voiture'))
            ->add('modele',TextType::class,$this->getConfiguration('modele','Modèle de votre voiture'))
            ->add('img_cover',UrlType::class,$this->getConfiguration('url de l\'image','Veuillez entrer l\'adresse de votre image'))
            ->add('km',IntegerType::class,$this->getConfiguration('Nombre de km','km au compteur'))
            ->add('prix',MoneyType::class, $this->getConfiguration('Prix de votre voiture','Indiquez le prix demandé'))
            ->add('nbProprio',IntegerType::class,$this->getConfiguration('Nombre de proprietaire','Indiquez le nombre de propriétaire'))
            ->add('cylindre',IntegerType::class,$this->getConfiguration('Cylindrée','Cylindrée'))
            ->add('puissance',IntegerType::class,$this->getConfiguration('Puissance en chevaux','Exemple : 450'))
            ->add('carburant',TextType::class,$this->getConfiguration('Carburant','essence ou diesel?'))
            ->add('dateCircu',DateType::class,$this->getConfiguration('Date de premiere mise en circulation',''))
            ->add('transmission',TextType::class,$this->getConfiguration('transmission','Automatique/Manuel'))
            ->add('description',TextareaType::class, $this->getConfiguration('Description','Description détaillée de votre voiture'))
            ->add('supOption',TextareaType::class,$this->getConfiguration('Option de votre voiture','Options supplémentaires du véhicule'))
            ->add('slug',TextType::class, $this->getConfiguration('Slug','Adresse web (automatique)',[
                'required'=>false
            ]))
            ->add(
                'images',
                CollectionType::class,
                [
                    'entry_type' => ImageType::class,
                    'allow_add' => true, // permet d'ajouter de nouveaux éléments et ajouter un data_prototype
                    'allow_delete' => true
                ]
            )

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Voiture::class,
        ]);
    }

}
