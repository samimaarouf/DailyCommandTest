<?php

namespace App\Entity;

class Product
{

    private $type;
    private $nbSold;

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getNbSold(): ?int
    {
        return $this->nbSold;
    }

    public function setNbSold(int $nbSold): self
    {
        $this->nbSold = $nbSold;

        return $this;
    }
}
