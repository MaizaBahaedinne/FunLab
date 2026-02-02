<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\NewsletterModel;

class NewsletterController extends BaseController
{
    protected $newsletterModel;

    public function __construct()
    {
        helper('permission');
        $this->newsletterModel = new NewsletterModel();
    }

    /**
     * Liste des abonnés newsletter
     */
    public function index()
    {
        if (!canAccessModule('contacts')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Accès refusé.');
        }

        $data = [
            'title' => 'Abonnés Newsletter',
            'activeMenu' => 'newsletters',
            'subscribers' => $this->newsletterModel->orderBy('subscribed_at', 'DESC')->findAll(),
            'activeCount' => $this->newsletterModel->getActiveCount(),
        ];

        return view('admin/newsletters/index', $data);
    }

    /**
     * Supprimer un abonné
     */
    public function delete($id)
    {
        if (!canAccessModule('contacts')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Accès refusé']);
        }

        if ($this->newsletterModel->delete($id)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Abonné supprimé avec succès']);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Erreur lors de la suppression']);
    }

    /**
     * Exporter les emails (CSV)
     */
    public function export()
    {
        if (!canAccessModule('contacts')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Accès refusé.');
        }

        $subscribers = $this->newsletterModel->where('status', 'active')->findAll();
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=newsletter_subscribers_' . date('Y-m-d') . '.csv');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['Email', 'Date d\'inscription', 'Adresse IP']);
        
        foreach ($subscribers as $subscriber) {
            fputcsv($output, [
                $subscriber['email'],
                $subscriber['subscribed_at'],
                $subscriber['ip_address'] ?? '',
            ]);
        }
        
        fclose($output);
        exit;
    }
}
