<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Monolog\DateTimeImmutable;
use DateTimeZone;


#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $corps = null;

    #[ORM\Column]
    private ?bool $validation = false;

    #[ORM\Column]
    private ?int $nombreVu = 0;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    private ?User $user = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\ManyToMany(targetEntity: Categorie::class, inversedBy: 'articles')]
    private Collection $categories;

    #[ORM\OneToMany(mappedBy: 'article', targetEntity: Commentaire::class)]
    private Collection $commentaires;

    #[ORM\OneToMany(mappedBy: 'article', targetEntity: Ressource::class , cascade: ['persist', 'remove'])]
    private Collection $ressources;

    /**
     * @var Collection<int, Favorie>
     */
    #[ORM\OneToMany(mappedBy: 'article', targetEntity: Favorie::class)]
    private Collection $favories;

    /**
     * @var Collection<int, EnAttente>
     */
    #[ORM\ManyToMany(targetEntity: EnAttente::class, mappedBy: 'articles')]
    private Collection $enAttentes;

    public function __construct()
    {
        $this->nombreVu = 0;
        $this->validation = false;
        $this->created_at = new DateTimeImmutable(false, new DateTimeZone('Europe/Paris'));
        $this->categories = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
        $this->ressources = new ArrayCollection();
        $this->favories = new ArrayCollection();
        $this->enAttentes = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getTitre();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getCorps(): ?string
    {
        return $this->corps;
    }

    public function setCorps(?string $corps): static
    {
        $this->corps = strip_tags($corps);

        return $this;
    }

    public function isValidation(): ?bool
    {
        return $this->validation;
    }

    public function setValidation(bool $validation): static
    {
        $this->validation = $validation;

        return $this;
    }

    public function getNombreVu(): ?int
    {
        return $this->nombreVu;
    }

    public function setNombreVu(int $nombreVu): static
    {
        $this->nombreVu = $nombreVu;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getDate(): string
    {
        return $this->created_at->format('d/m/Y');
    }


    /**
     * @return Collection<int, Categorie>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function getCategoriesString(): string
    {
        $res = '';
        foreach ($this->categories as $categorie) {
            $res .= $categorie->getNom() . ' ';
        }
        $res = rtrim($res);
        return $res;
    }

    public function addCategory(Categorie $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
        }

        return $this;
    }

    public function removeCategory(Categorie $category): static
    {
        $this->categories->removeElement($category);

        return $this;
    }



    /**
     * @return Collection<int, Commentaire>
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): static
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires->add($commentaire);
            $commentaire->setArticle($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): static
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getArticle() === $this) {
                $commentaire->setArticle(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getPseudoAuteur(): string
    {
        return $this->user->getPseudo();
    }

    public function getIdAuteur(): string
    {
        return $this->user->getId();
    }

    /**
     * @return Collection<int, Ressource>
     */
    public function getRessources(): Collection
    {
        return $this->ressources;
    }

    public function addRessource(Ressource $ressource): static
    {
        if (!$this->ressources->contains($ressource)) {
            $this->ressources->add($ressource);
            $ressource->setArticle($this);
        }

        return $this;
    }

    public function removeRessource(Ressource $ressource): static
    {
        if ($this->ressources->removeElement($ressource)) {
            // set the owning side to null (unless already changed)
            if ($ressource->getArticle() === $this) {
                $ressource->setArticle(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Favorie>
     */
    public function getFavories(): Collection
    {
        return $this->favories;
    }

    public function addFavory(Favorie $favory): self
    {
        if (!$this->favories->contains($favory)) {
            $this->favories->add($favory);
            $favory->setArticle($this);
        }

        return $this;
    }

    public function removeFavory(Favorie $favory): self
    {
        if ($this->favories->removeElement($favory)) {
            // set the owning side to null (unless already changed)
            if ($favory->getArticle() === $this) {
                $favory->setArticle(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, EnAttente>
     */
    public function getEnAttentes(): Collection
    {
        return $this->enAttentes;
    }

    public function addEnAttente(EnAttente $enAttente): static
    {
        if (!$this->enAttentes->contains($enAttente)) {
            $this->enAttentes->add($enAttente);
            $enAttente->addArticle($this);
        }

        return $this;
    }

    public function removeEnAttente(EnAttente $enAttente): static
    {
        if ($this->enAttentes->removeElement($enAttente)) {
            $enAttente->removeArticle($this);
        }

        return $this;
    }
}
