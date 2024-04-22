<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(): Response
    {
        $orders = $this->getAllOrders();
        dd($orders);

        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }

    private function getAllOrders()
    {
        $api_key = $this->getParameter('etsy.keystring');

        return $api_key;
    }
}
