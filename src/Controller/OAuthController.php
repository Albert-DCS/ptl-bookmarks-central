<?php

namespace App\Controller;

use App\Service\ApiHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class OAuthController extends AbstractController
{
    #[Route('/oauth', name: 'app_oauth')]
    public function index(ApiHelper $ApiHelper): Response
    {
        // Create the APIHelper service and request for an OAuth Authorization
        $api_key = $this->getParameter('etsy.keystring');
        $base_url = 'https://api.etsy.com/v3/application';

        $ApiHelper->setParameters($api_key, $base_url);
        $startOAuthUrl = $ApiHelper->InitOAuth();

        return $this->redirect($startOAuthUrl);

    }

    #[Route('/oauth/authorize', name: 'app_oauth_authorize')]
    public function authorize(ApiHelper $ApiHelper = new ApiHelper()): Response
    {
        return $this->render('o_auth/index.html.twig', [
            'controller_name' => 'OAuthController',
        ]);
    }
}
