<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AllowSocialBots implements FilterInterface
{
    /**
     * Liste des bots de réseaux sociaux à autoriser
     */
    private array $allowedBots = [
        'facebookexternalhit',  // Facebook
        'Facebot',              // Facebook
        'Twitterbot',           // Twitter
        'LinkedInBot',          // LinkedIn
        'WhatsApp',             // WhatsApp
        'Slackbot',             // Slack
        'TelegramBot',          // Telegram
        'Discordbot',           // Discord
        'Pinterestbot',         // Pinterest
        'redditbot',            // Reddit
    ];

    /**
     * Vérifie si le User-Agent est un bot social et le laisse passer
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return RequestInterface|void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $userAgent = $request->getUserAgent()->getAgentString();
        
        // Vérifier si c'est un bot social
        foreach ($this->allowedBots as $bot) {
            if (stripos($userAgent, $bot) !== false) {
                // Log pour debug (optionnel)
                log_message('info', 'Social bot detected: ' . $bot . ' - User Agent: ' . $userAgent);
                
                // Marquer la requête comme provenant d'un bot social
                $request->socialBot = true;
                break;
            }
        }
        
        return $request;
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return ResponseInterface|void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Pas d'action après la requête
        return $response;
    }
}
