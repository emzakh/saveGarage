<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\VoitureRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=VoitureRepository::class)
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity(
 * fields={"marque","slug"}
 * ) 
 * message="Une autre annonce possède déjà ce titre, merci de le modifier"
 * 
 * 
 */
class Voiture
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $marque;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $modele;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Image(mimeTypes={"image/png", "image/jpeg", "image/jpg", "image/gif"}, mimeTypesMessage="Vous devez upload un fichier jpg, png ou gif")
     * @Assert\File(maxSize="1024k", maxSizeMessage="Taille du fichier trop grande")
     */
    private $img_cover;

    /**
     * @ORM\Column(type="integer")
     */
    private $km;

    /**
     * @ORM\Column(type="integer")
     */
    private $prix;

    /**
     * @ORM\Column(type="integer")
     */
    private $nb_proprio;

    /**
     * @ORM\Column(type="integer")
     */
    private $cylindre;

    /**
     * @ORM\Column(type="integer")
     */
    private $puissance;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $carburant;

    /**
     * @ORM\Column(type="date")
     */
    private $date_circu;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $transmission;

    /**
     * @ORM\Column(type="text")
     * 
     */
    private $description;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $sup_option;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity=Image::class, mappedBy="voiture", orphanRemoval=true)
     * @Assert\Valid()
     */
    private $images;


      /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="showVoiture")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

      /**
     * Permet d'initialiser le slug automatiquement s'il n'est pas fourni 
     * @ORM\PrePersist
     * @ORM\PreUpdate
     * 
     * @return Response
     */
    public function initializeSlug(){
        if(empty($this->slug)){
            $slugify = new Slugify();
            $this->slug = $slugify->slugify($this->marque.'-'.$this->modele.'-'.rand(1,10000));        

        }
    }


    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(string $marque): self
    {
        $this->marque = $marque;

        return $this;
    }

    public function getModele(): ?string
    {
        return $this->modele;
    }

    public function setModele(string $modele): self
    {
        $this->modele = $modele;

        return $this;
    }

    public function getImgCover(): ?string
    {
        return $this->img_cover;
    }

    public function setImgCover(string $img_cover): self
    {
        $this->img_cover = $img_cover;

        return $this;
    }

    public function getKm(): ?int
    {
        return $this->km;
    }

    public function setKm(int $km): self
    {
        $this->km = $km;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getNbProprio(): ?int
    {
        return $this->nb_proprio;
    }

    public function setNbProprio(int $nb_proprio): self
    {
        $this->nb_proprio = $nb_proprio;

        return $this;
    }

    public function getCylindre(): ?int
    {
        return $this->cylindre;
    }

    public function setCylindre(int $cylindre): self
    {
        $this->cylindre = $cylindre;

        return $this;
    }

    public function getPuissance(): ?int
    {
        return $this->puissance;
    }

    public function setPuissance(int $puissance): self
    {
        $this->puissance = $puissance;

        return $this;
    }

    public function getCarburant(): ?string
    {
        return $this->carburant;
    }

    public function setCarburant(string $carburant): self
    {
        $this->carburant = $carburant;

        return $this;
    }

    public function getDateCircu(): ?\DateTimeInterface
    {
        return $this->date_circu;
    }

    public function setDateCircu(\DateTimeInterface $date_circu): self
    {
        $this->date_circu = $date_circu;

        return $this;
    }

    public function getTransmission(): ?string
    {
        return $this->transmission;
    }

    public function setTransmission(string $transmission): self
    {
        $this->transmission = $transmission;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getSupOption(): ?string
    {
        return $this->sup_option;
    }

    public function setSupOption(?string $sup_option): self
    {
        $this->sup_option = $sup_option;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setVoiture($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getVoiture() === $this) {
                $image->setVoiture(null);
            }
        }

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }


}
