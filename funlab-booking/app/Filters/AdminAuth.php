<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Vérifier si l'utilisateur est connecté en tant qu'admin ou staff
        $session = session();
        
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/admin/login')->with('error', 'Veuillez vous connecter');
        }
        
        // Vérifier si l'utilisateur a les droits admin ou staff
        if (!$session->get('isAdmin') && !$session->get('isStaff')) {
            return redirect()->to('/')->with('error', 'Accès non autorisé - Réservé au personnel');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Pas d'action après la requête
    }
}
