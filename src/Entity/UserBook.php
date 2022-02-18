<?php

namespace App\Entity;

use App\Repository\UserBookRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;

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
     * @ORM\JoinColumn(columnDefinition="book_id")
     */
    private $book;

    /**
     * @Groups({"UserBookBody"})
     * @ORM\Column(type="integer",nullable=false)
     */
    private $book_id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="userBooks")
     * @ORM\JoinColumn(columnDefinition="usr_id")
     */
    private $usr;

    /**
     * @Groups({"UserBookBody"})
     * @ORM\Column(type="integer",nullable=false)
     */
    private $usr_id;

    /**
     * @Groups({"UserBookBody"})
     * @ORM\Column(type="integer")
     */
    private $is_readied;

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
        return $this->is_readied;
    }

    public function setIsReadied(int $is_readied): self
    {
        $this->is_readied = $is_readied;

        return $this;
    }
}
