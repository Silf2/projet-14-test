<?php

declare(strict_types=1);

namespace App\Model\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Gedmo\Mapping\Annotation\Slug;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

#[Entity]
#[Table('`tag`')]
class Tag
{
    #[Id]
    #[GeneratedValue]
    #[Column]
    private ?int $id = null;

    #[Column(unique: true)]
    #[Slug(fields: ['name'])]
    /** @phpstan-ignore-next-line */
    private string $code;

    #[NotBlank]
    #[Length(max: 30)]
    #[Column(length: 30)]
    private string $name;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Tag
    {
        $this->name = $name;
        return $this;
    }
}
