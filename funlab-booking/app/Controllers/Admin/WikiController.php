<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class WikiController extends BaseController
{
    public function __construct()
    {
        helper('permission');
    }

    public function index()
    {
        return $this->page('accueil');
    }

    public function page($slug = 'accueil')
    {
        // Liste de toutes les pages disponibles
        $pages = [
            'accueil' => 'Accueil',
            'installation' => 'Installation & Configuration',
            'utilisateurs' => 'Guide Utilisateurs',
            'admin' => 'Guide Administrateur',
            'staff' => 'Guide Staff',
            'permissions' => 'Système de Permissions',
            'reservations' => 'Gestion des Réservations',
            'jeux' => 'Gestion des Jeux',
            'paiements' => 'Système de Paiement',
            'email' => 'Configuration Email',
            'api' => 'Documentation API',
            'database' => 'Structure Base de Données',
            'securite' => 'Sécurité',
            'maintenance' => 'Maintenance & Sauvegarde',
            'troubleshooting' => 'Dépannage'
        ];

        // Vérifier si la page existe
        if (!isset($pages[$slug])) {
            return redirect()->to('/admin/wiki')->with('error', 'Page introuvable');
        }

        $data = [
            'title' => $pages[$slug],
            'activeMenu' => 'wiki',
            'currentPage' => $slug,
            'pages' => $pages
        ];

        return view('admin/wiki/layout', $data);
    }
}
