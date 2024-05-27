<?php

namespace App\Entity;

use App\Repository\CommentaireRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Monolog\DateTimeImmutable;
use DateTimeZone;

#[ORM\Entity(repositoryClass: CommentaireRepository::class)]
class Commentaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $contenu = null;

    #[ORM\Column]
    private ?bool $est_actif = null;

    #[ORM\ManyToOne(inversedBy: 'commentaires')]
    private ?Article $article = null;

    #[ORM\Column(nullable: true)]
    private ?int $parent = null;

    #[ORM\ManyToOne(inversedBy: 'commentaires')]
    private ?User $user = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    public function __construct()
    {
        $this->est_actif = true;
        $this->created_at = new DateTimeImmutable(false, new DateTimeZone('Europe/Paris'));
    }
  
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): static
    {
        $this->contenu = strip_tags($contenu);

        return $this;
    }

    public function isEstActif(): ?bool
    {
        return $this->est_actif;
    }

    public function setEstActif(bool $est_actif): static
    {
        $this->est_actif = $est_actif;

        return $this;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): static
    {
        $this->article = $article;

        return $this;
    }

    public function getParent(): ?int
    {
        return $this->parent;
    }

    public function setParent(?int $parent): static
    {
        $this->parent = $parent;

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
        return $this->created_at->format('m/d/Y H:i');
    }
}
