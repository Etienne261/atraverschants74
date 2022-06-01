<?php

namespace App\Entity;

use App\Repository\BlogRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BlogRepository::class)
 */
class Blog
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
    private $tittle;


    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\OneToMany(targetEntity=ImagesBlog::class, mappedBy="blogs", orphanRemoval=true, cascade={"persist"})
     */
    private $imagesblog;

    public function __construct()
    {
        $this->imagesblog = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTittle(): ?string
    {
        return $this->tittle;
    }

    public function setTittle(string $tittle): self
    {
        $this->tittle = $tittle;

        return $this;
    }


    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return Collection<int, ImagesBlog>
     */
    public function getImagesblog(): Collection
    {
        return $this->imagesblog;
    }

    public function addImageblog(ImagesBlog $imageblog): self
    {
        if (!$this->imagesblog->contains($imageblog)) {
            $this->imagesblog[] = $imageblog;
            $imageblog->setBlogs($this);
        }

        return $this;
    }

    public function removeImageblog(ImagesBlog $imageblog): self
    {
        if ($this->imagesblog->removeElement($imageblog)) {
            // set the owning side to null (unless already changed)
            if ($imageblog->getBlogs() === $this) {
                $imageblog->setBlogs(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }


}
