<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Vérifier si l'utilisateur est connecté en tant qu'admin
        $session = session();
        
        if (!$session->get('isLoggedIn') || !$session->get('isAdmin')) {
            return redirect()->to('/admin/login')->with('error', 'Accès non autorisé');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Pas d'action après la requête
    }
}
