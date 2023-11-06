<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

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
    private ?bool $validation = null;

    #[ORM\Column]
    private ?bool $exploitation = null;

    #[ORM\Column]
    private ?int $nombreVu = null;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    private ?Utilisateur $utilisateur = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\OneToMany(mappedBy: 'article', targetEntity: Categorie::class)]
    private Collection $categories;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
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
        $this->corps = $corps;

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

    public function isExploitation(): ?bool
    {
        return $this->exploitation;
    }

    public function setExploitation(bool $exploitation): static
    {
        $this->exploitation = $exploitation;

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

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): static
    {
        $this->utilisateur = $utilisateur;

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

    /**
     * @return Collection<int, Categorie>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Categorie $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
            $category->setArticle($this);
        }

        return $this;
    }

    public function removeCategory(Categorie $category): static
    {
        if ($this->categories->removeElement($category)) {
            // set the owning side to null (unless already changed)
            if ($category->getArticle() === $this) {
                $category->setArticle(null);
            }
        }

        return $this;
    }
}
