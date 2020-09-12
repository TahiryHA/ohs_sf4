<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LevelRepository")
 */
class Level
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
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="level")
     */
    private $users;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\AcademicYear", inversedBy="levels")
     */
    private $academicYear;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\News", mappedBy="level")
     */
    private $news;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->academicYear = new ArrayCollection();
        $this->news = new ArrayCollection();
    }

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

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addLevel($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            $user->removeLevel($this);
        }

        return $this;
    }

    /**
     * @return Collection|AcademicYear[]
     */
    public function getAcademicYear(): Collection
    {
        return $this->academicYear;
    }

    public function addAcademicYear(AcademicYear $academicYear): self
    {
        if (!$this->academicYear->contains($academicYear)) {
            $this->academicYear[] = $academicYear;
        }

        return $this;
    }

    public function removeAcademicYear(AcademicYear $academicYear): self
    {
        if ($this->academicYear->contains($academicYear)) {
            $this->academicYear->removeElement($academicYear);
        }

        return $this;
    }

    /**
     * @return Collection|News[]
     */
    public function getNews(): Collection
    {
        return $this->news;
    }

    public function addNews(News $news): self
    {
        if (!$this->news->contains($news)) {
            $this->news[] = $news;
            $news->addLevel($this);
        }

        return $this;
    }

    public function removeNews(News $news): self
    {
        if ($this->news->contains($news)) {
            $this->news->removeElement($news);
            $news->removeLevel($this);
        }

        return $this;
    }
}
