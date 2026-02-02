<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ContactMessageModel;

class ContactController extends BaseController
{
    protected $contactModel;

    public function __construct()
    {
        $this->contactModel = new ContactMessageModel();
    }

    /**
     * Liste des messages de contact
     */
    public function index()
    {
        if (!canAccessModule('contacts')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Accès refusé.');
        }

        $data = [
            'title' => 'Messages de Contact',
            'activeMenu' => 'contacts',
            'messages' => $this->contactModel->orderBy('created_at', 'DESC')->findAll(),
            'unreadCount' => $this->contactModel->getUnreadCount(),
        ];

        return view('admin/contacts/index', $data);
    }

    /**
     * Voir un message
     */
    public function view($id)
    {
        if (!canAccessModule('contacts')) {
            return redirect()->to('/admin/dashboard')->with('error', 'Accès refusé.');
        }

        $message = $this->contactModel->find($id);
        
        if (!$message) {
            return redirect()->to('/admin/contacts')->with('error', 'Message non trouvé');
        }

        // Marquer comme lu
        if ($message['status'] === 'new') {
            $this->contactModel->markAsRead($id);
        }

        $data = [
            'title' => 'Message de Contact',
            'activeMenu' => 'contacts',
            'message' => $message,
        ];

        return view('admin/contacts/view', $data);
    }

    /**
     * Marquer comme répondu
     */
    public function markReplied($id)
    {
        if (!canAccessModule('contacts')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Accès refusé']);
        }

        if ($this->contactModel->markAsReplied($id)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Message marqué comme répondu']);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Erreur']);
    }

    /**
     * Supprimer un message
     */
    public function delete($id)
    {
        if (!canAccessModule('contacts')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Accès refusé']);
        }

        if ($this->contactModel->delete($id)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Message supprimé avec succès']);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Erreur lors de la suppression']);
    }
}
