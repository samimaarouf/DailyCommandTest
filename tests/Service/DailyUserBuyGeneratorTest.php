<?php

namespace App\Tests\Service;

use App\Repository\DailyUserBuyRepository;
use App\Service\DailyUserBuyGenerator;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertEquals;

class DailyUserBuyGeneratorTest extends TestCase
{   
    private $dailyUserBuyGenerator;

    public function setUp() : void{
        $this->dailyUserBuyGenerator = new DailyUserBuyGenerator();
    }

    public function testCountEuros() : void
    {
        $a = 10;
        $total = $this->dailyUserBuyGenerator->countEuros($a);
        assertEquals(0.01, $total);
    }
 
}
