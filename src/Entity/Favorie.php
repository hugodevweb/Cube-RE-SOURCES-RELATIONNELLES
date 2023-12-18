<?php

namespace App\Entity;

use App\Repository\FavorieRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FavorieRepository::class)]
class Favorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $utilisateur = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $article = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUtilisateur(): ?int
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(int $utilisateur): static
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    public function getArticle(): ?int
    {
        return $this->article;
    }

    public function setArticle(int $article): static
    {
        $this->article = $article;

        return $this;
    }
}
