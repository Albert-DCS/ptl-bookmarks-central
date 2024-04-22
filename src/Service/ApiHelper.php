<?php

namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Path;


class ApiHelper
{
    private $apiKey;
    private $baseUrl;
    private $httpClient;
    private $router;

    private $filesystem;
    private $oauthDir = '../var/oauth';

    public function __construct(RouterInterface $router) {
        $this->router = $router;
        $this->filesystem = new Filesystem();
        try {
            $this->filesystem->mkdir($this->oauthDir);
        } catch (IOExceptionInterface $exception) {
            echo "An error occurred while creating your directory at ".$exception->getPath();
        }
    }

    public function setParameters($api_key, $base_url) {
        $this->apiKey = $api_key;
        $this->baseUrl = $base_url;
        $this->httpClient = HttpClient::create();
    }

    private function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    private function base64url($string) {
        return rtrim( strtr( base64_encode( $string ), '+/', '-_' ), '=' );
    }

    private function challenger($string) {
        $binary = hash('sha256', $string, true);
        return $this->base64url($binary);
    }

    public function InitOAuth() {
        // This will start the OAuth authorization flow described in
        // https://developer.etsy.com/documentation/essentials/authentication/

        // Step 1: Request an Authorization Code
        ////////////////////////////////////////

        $state = $this->generateRandomString();
        $code_verifier = $this->generateRandomString(50);
        $code_challenge = $this->challenger($code_verifier);

        $params = [
            'response_type' => 'code',
            'client_id' => $this->apiKey,
            'redirect_uri' => $this->router->generate('app_oauth_authorize', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'scope' => 'transactions_r',
            'state' => $state,
            'code_challenge' => $code_challenge,
            'code_challenge_method' => 'S256'
        ];

        // save state and code verifier for further PKCE steps
        $this->filesystem->dumpFile($this->oauthDir.'/'.$state, $code_verifier);

        return 'https://www.etsy.com/oauth/connect?' . http_build_query($params);
    }

    public function get($endpoint, $params = []) {
        // Implement GET request logic
    }


}