<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 */
class Article
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titre_article;

    /**
     * @ORM\Column(type="text")
     */
    private $contenu_article;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image_article;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $titre2_article;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $contenu2_article;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image2_article;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $titre3_article;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $contenu3_article;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_article;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="articles")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="articles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitreArticle(): ?string
    {
        return $this->titre_article;
    }

    public function setTitreArticle(string $titre_article): self
    {
        $this->titre_article = $titre_article;

        return $this;
    }

    public function getContenuArticle(): ?string
    {
        return $this->contenu_article;
    }

    public function setContenuArticle(string $contenu_article): self
    {
        $this->contenu_article = $contenu_article;

        return $this;
    }

    public function getImageArticle(): ?string
    {
        return $this->image_article;
    }

    public function setImageArticle(string $image_article): self
    {
        $this->image_article = $image_article;

        return $this;
    }

    public function getTitre2Article(): ?string
    {
        return $this->titre2_article;
    }

    public function setTitre2Article(?string $titre2_article): self
    {
        $this->titre2_article = $titre2_article;

        return $this;
    }

    public function getContenu2Article(): ?string
    {
        return $this->contenu2_article;
    }

    public function setContenu2Article(?string $contenu2_article): self
    {
        $this->contenu2_article = $contenu2_article;

        return $this;
    }

    public function getImage2Article(): ?string
    {
        return $this->image2_article;
    }

    public function setImage2Article(?string $image2_article): self
    {
        $this->image2_article = $image2_article;

        return $this;
    }

    public function getTitre3Article(): ?string
    {
        return $this->titre3_article;
    }

    public function setTitre3Article(?string $titre3_article): self
    {
        $this->titre3_article = $titre3_article;

        return $this;
    }

    public function getContenu3Article(): ?string
    {
        return $this->contenu3_article;
    }

    public function setContenu3Article(?string $contenu3_article): self
    {
        $this->contenu3_article = $contenu3_article;

        return $this;
    }

    public function getDateArticle(): ?\DateTimeInterface
    {
        return $this->date_article;
    }

    public function setDateArticle(\DateTimeInterface $date_article): self
    {
        $this->date_article = $date_article;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }
}
