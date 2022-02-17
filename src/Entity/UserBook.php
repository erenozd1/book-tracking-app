<?php

namespace App\Entity;

use App\Repository\UserBookRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserBookRepository::class)
 */
class UserBook
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Book::class, inversedBy="userBooks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $book;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="userBooks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $usr;

    /**
     * @ORM\Column(type="integer")
     */
    private $is_readed;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBook(): ?Book
    {
        return $this->book;
    }

    public function setBook(?Book $book): self
    {
        $this->book = $book;

        return $this;
    }

    public function getUsr(): ?User
    {
        return $this->usr;
    }

    public function setUsr(?User $usr): self
    {
        $this->usr = $usr;

        return $this;
    }

    public function getIsReaded(): ?int
    {
        return $this->is_readed;
    }

    public function setIsReaded(int $is_readed): self
    {
        $this->is_readed = $is_readed;

        return $this;
    }
}
