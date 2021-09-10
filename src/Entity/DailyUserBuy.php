<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class DailyUserBuy
{
    private $date;
    private int $period;
    private int $totalpoints;
    private float $totalEuros;
    private User $user;
    private $products;


    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getDate(): ?String
    {
        return $this->date;
    }

    public function setDate(String $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getTotalpoints(): ?int
    {
        return $this->totalpoints;
    }

    public function setTotalpoints(?int $totalpoints): self
    {
        $this->totalpoints = $totalpoints;

        return $this;
    }

    public function getTotalEuros(): ?float
    {
        return $this->totalEuros;
    }

    public function setTotalEuros(?float $totalEuros): self
    {
        $this->totalEuros = $totalEuros;

        return $this;
    }

    /**
     * Get the value of user
     */ 
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set the value of user
     *
     * @return  self
     */ 
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    public function addProducts(?Product $product): self
    {
        $this->products[] = $product;

        return $this;
    }

    public function getPeriod(): ?int
    {
        return $this->period;
    }

    public function setPeriod(?int $period): self
    {
        $this->period = $period;

        return $this;
    }
    
}
