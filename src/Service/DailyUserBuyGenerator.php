<?php

namespace App\Service;

use App\Entity\DailyUserBuy;
use App\Entity\Product;
use App\Entity\User;
use DateTime;

class DailyUserBuyGenerator
{
    private $listDailyUserBuy = array();
    private $orderedListDailyUserBuy;
    private bool $isProduct1HasBeenSold = false;

    public function create($userProducts) : void
    {
        foreach ($userProducts as $userProduct) {
            $user = $this->createUser($userProduct);

            $product1 = $this->createProduct($userProduct, 1);
            $product2 = $this->createProduct($userProduct, 2);
            $product3 = $this->createProduct($userProduct, 3);
            $product4 = $this->createProduct($userProduct, 4);

            $listProduct = array();
            array_push($listProduct, $product1, $product2, $product3, $product4);

            $dailyUserBuy = $this->createDailyUserBuy($userProduct, $user, $listProduct);
            $totalPoints = $this->countPoints($listProduct);
            $totalEuros = $this->countEuros($totalPoints);
            $period = $this->getPeriod($dailyUserBuy->getDate());

            $dailyUserBuy->setTotalpoints($totalPoints);
            $dailyUserBuy->setTotalEuros($totalEuros);
            $dailyUserBuy->setPeriod($period);

            $this->listDailyUserBuy[] = $dailyUserBuy;
        }
        $userSearched = "123456789";
        $orderedList = $this->orderDailyUserBuyByUser($userSearched);
        $this->orderedListDailyUserBuy = $orderedList;
    }

    public function createUser($dailyUserBuyRow): User
    {
        $user = new User();
        $strColumnUserId = "UTILISATEUR";
        $userId = ($dailyUserBuyRow[$strColumnUserId]);
        $user->setUserid($userId);
        return $user;
    }

    public function createProduct($dailyUserBuyRow, $type): Product
    {
        $strColumnProduct = "PRODUIT " . $type;
        $nbSold = $dailyUserBuyRow[$strColumnProduct];
        $product = new Product();
        $product->setType($type);
        $product->setNbSold($nbSold);

        if ($type = 1 && $nbSold >= 1) {
            $this->isProduct1HasBeenSold = true;
        }
        return $product;
    }

    //Create a DailyUserBuy 
    //@input $dailyUserRow, one dailyUserBuy in the row format, corresponds at one line in the array
    //@input $user, it's the correspondant User to the dailyUserBuyRow
    //@input $listProduct, it's the correspondant list of products to the dailyUserBuyRow
    //@return, the DailyUserBuy created
    public function createDailyUserBuy($dailyUserBuyRow, $user, $listProduct): DailyUserBuy
    {
        $strColumnDate = "DATE";
        $dailyUserBuy = new DailyUserBuy();
        $date = DateTime::createFromFormat('d/m/Y', $dailyUserBuyRow[$strColumnDate]);
        $date = $date->format('d/m/Y');
        $dailyUserBuy->setDate($date);
        $dailyUserBuy->setUser($user);
        foreach ($listProduct as $product) {
            $dailyUserBuy->addProducts($product);
        }
        return $dailyUserBuy;
    }

    //For a list of product, calculate the total of points earned by each product
    //@input array, list of product
    //@return int, total of points
    public function countPoints($listProduct) : int
    {
        $totalPoints = 0;
        foreach ($listProduct as $product) {
            $productType = $product->getType();
            $nbPoints = 0;
            switch ($productType) {
                case 1:
                    $nbPoints = $product->getNbSold() * 5;
                    break;
                case 2:
                    if ($this->isProduct1HasBeenSold) {
                        $nbPoints = $product->getNbSold() * 5;
                    } else {
                        $nbPoints = 0;
                    }
                    break;
                case 3:
                    $nbPoints = (floor($product->getNbSold() / 2)) * 15;
                    break;
                case 4:
                    $nbPoints = $product->getNbSold() * 35;
                    break;
            }
            $totalPoints += $nbPoints;
        }
        $this->isProduct1HasBeenSold = false;
        return $totalPoints;
    }

    //Count the euros correspond at the points given
    //@input int, the points to convert
    //@return float, the euros correspondant to the points given
    public function countEuros($points): float
    {
        return $points * 0.001;
    }

    //Return the period correspondant to the date given
    //@input string, the date correspondant
    //@return int, the period correspondant
    public function getPeriod($date): int
    {
        $date = str_replace('/', '-', $date);
        $date = strtotime($date);
        $period1start = strtotime("01-01-2021");
        $period1end = strtotime("30-04-2021");
        $period2start = strtotime("01-05-2021");
        $period2end = strtotime("31-08-2021");
        $period3start = strtotime("01-10-2021");
        $period3end = strtotime("31-12-2021");

        $period = 0;

        if ($date >= $period1start && $date <= $period1end) {
            $period = 1;
        } else if ($date >= $period2start && $date <= $period2end) {
            $period = 2;
        } else if ($date >= $period3start && $date <= $period3end) {
            $period = 3;
        }

        return $period;
    }

    //Return an array sorted, for one user, of the format [PERIOD, POINTS, EUROS] 
    //@input String, the userId
    //@return array, the array sorted
    public function orderDailyUserBuyByUser($userId) : array
    {
        $arrayPeriod1 = array("PERIODE" => "PERIODE 1", "POINTS" => 0, "EUROS" => 0);
        $arrayPeriod2 = array("PERIODE" => "PERIODE 2", "POINTS" => 0, "EUROS" => 0);
        $arrayPeriod3 = array("PERIODE" => "PERIODE 3", "POINTS" => 0, "EUROS" => 0);

        foreach ($this->listDailyUserBuy as $dailyUserBuy) {           
            if ($dailyUserBuy->getUser()->getUserid() == $userId) {
                switch($dailyUserBuy->getPeriod()){
                    case 1 :
                        $arrayPeriod1["POINTS"] += $dailyUserBuy->getTotalpoints();
                        $arrayPeriod1["EUROS"] += $dailyUserBuy->getTotalEuros();
                        break;
                    case 2 :
                        $arrayPeriod2["POINTS"] += $dailyUserBuy->getTotalpoints();
                        $arrayPeriod2["EUROS"] += $dailyUserBuy->getTotalEuros();
                        break;
                    case 3 :
                        $arrayPeriod3["POINTS"] += $dailyUserBuy->getTotalpoints();
                        $arrayPeriod3["EUROS"] += $dailyUserBuy->getTotalEuros();
                        break;
                }
            }
        }
        $arraySorted = [$arrayPeriod1, $arrayPeriod2, $arrayPeriod3];
        return $arraySorted;
    }

    //return the array done by orderDailyUserBuyByUser
    public function getOrderedListDailyUserBuy() : array{
        return $this->orderedListDailyUserBuy;
    }
}
