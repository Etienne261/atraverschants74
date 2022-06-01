<?php

namespace App\Entity;

use App\Repository\ImagesBlogRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ImagesBlogRepository::class)
 */
class ImagesBlog
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
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=Blog::class, inversedBy="imagesblog")
     * @ORM\JoinColumn(nullable=false)
     */
    private $blogs;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getBlogs(): ?Blog
    {
        return $this->blogs;
    }

    public function setBlogs(?Blog $blogs): self
    {
        $this->blogs = $blogs;

        return $this;
    }

}
