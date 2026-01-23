<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

/**
 * Filtre d'authentification
 * Vérifie si l'utilisateur est connecté avant d'accéder aux pages protégées
 */
class Auth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Vérifier si l'utilisateur est connecté
        if (!session()->get('isLoggedIn')) {
            // Rediriger vers la page de login avec un message
            return redirect()->to('/auth/login')
                ->with('error', 'Veuillez vous connecter pour accéder à cette page');
        }

        // Si l'utilisateur est connecté, continuer
        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Rien à faire après la requête
    }
}
