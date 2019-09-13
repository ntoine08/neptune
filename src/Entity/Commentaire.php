<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentaireRepository")
 */
class Commentaire
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $contenuCommentaire;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCommentaire;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="commentaires")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Article", inversedBy="commentaires")
     * @ORM\JoinColumn(nullable=false)
     */
    private $article;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContenuCommentaire(): ?string
    {
        return $this->contenuCommentaire;
    }

    public function setContenuCommentaire(string $contenuCommentaire): self
    {
        $this->contenuCommentaire = $contenuCommentaire;

        return $this;
    }

    public function getDateCommentaire(): ?\DateTimeInterface
    {
        return $this->dateCommentaire;
    }

    public function setDateCommentaire(\DateTimeInterface $dateCommentaire): self
    {
        $this->dateCommentaire = $dateCommentaire;

        return $this;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getArticle(): ?article
    {
        return $this->article;
    }

    public function setArticle(?article $article): self
    {
        $this->article = $article;

        return $this;
    }
}
