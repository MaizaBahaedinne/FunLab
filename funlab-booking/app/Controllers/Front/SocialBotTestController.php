<?php

namespace App\Controllers\Front;

use App\Controllers\BaseController;

class SocialBotTestController extends BaseController
{
    /**
     * Page de test pour les bots sociaux
     * URL: /social-bot-test
     */
    public function index()
    {
        $userAgent = $this->request->getUserAgent();
        
        $data = [
            'user_agent' => $userAgent->getAgentString(),
            'is_bot' => $userAgent->isRobot(),
            'browser' => $userAgent->getBrowser(),
            'platform' => $userAgent->getPlatform(),
            'ip_address' => $this->request->getIPAddress(),
            'server_time' => date('Y-m-d H:i:s'),
            'request_uri' => $this->request->getUri()->getPath(),
            'http_headers' => $this->request->headers(),
        ];
        
        // Retourner en JSON pour faciliter la lecture
        return $this->response
            ->setJSON($data)
            ->setHeader('Access-Control-Allow-Origin', '*')
            ->setHeader('Cache-Control', 'no-cache, no-store, must-revalidate');
    }
    
    /**
     * Test Open Graph pour Facebook
     * URL: /og-test
     */
    public function ogTest()
    {
        $data = [
            'title' => 'Test Open Graph - FunLab',
            'metaTitle' => '🎯 Test Facebook Sharing - FunLab Tunisie',
            'metaDescription' => 'Page de test pour vérifier le partage Facebook et les balises Open Graph.',
            'metaKeywords' => 'test, facebook, open graph, funlab',
            'canonicalUrl' => base_url('og-test'),
            'ogType' => 'website',
            'ogUrl' => base_url('og-test'),
            'ogTitle' => '🎯 Test Facebook Sharing - FunLab Tunisie',
            'ogDescription' => 'Page de test pour vérifier le partage Facebook et les balises Open Graph.',
            'ogImage' => base_url('assets/images/og-default.jpg'),
            'twitterUrl' => base_url('og-test'),
            'twitterTitle' => '🎯 Test Facebook Sharing - FunLab Tunisie',
            'twitterDescription' => 'Page de test pour vérifier le partage Facebook.',
            'twitterImage' => base_url('assets/images/og-default.jpg'),
            'activeMenu' => 'none',
        ];
        
        return view('front/og_test', $data);
    }
}
