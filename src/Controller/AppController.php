<?php 

namespace App\Controller;

use App\Service\DailyUserBuyGenerator;
use App\Service\RunCommand;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;



class AppController extends AbstractController
{
    public function getPoints(DailyUserBuyGenerator $dailyUserBuyGenerator, RunCommand $runCommand): Response
    {
        $runCommand->run();
        $orderedListDailyUserBuy = $dailyUserBuyGenerator->getOrderedListDailyUserBuy();
        return $this->render('base.html.twig', [
            // this array defines the variables passed to the template,
            // where the key is the variable name and the value is the variable value
            // (Twig recommends using snake_case variable names: 'foo_bar' instead of 'fooBar')
            'orderedListDailyUserBuy' => $orderedListDailyUserBuy
        ]);
    }
}