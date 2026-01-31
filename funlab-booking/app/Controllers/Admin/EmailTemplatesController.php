<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\EmailTemplateModel;

class EmailTemplatesController extends BaseController
{
    protected $templateModel;

    public function __construct()
    {
        helper('permission');
        $this->templateModel = new EmailTemplateModel();
    }

    public function index()
    {
        // Vérifier les permissions
        if ($redirect = checkPermissionOrRedirect('settings', 'view')) {
            return $redirect;
        }

        $data = [
            'title' => 'Templates d\'emails',
            'pageTitle' => 'Templates d\'emails',
            'activeMenu' => 'email-templates',
            'breadcrumbs' => [
                'Admin' => base_url('admin'),
                'Templates' => null
            ],
            'templates' => $this->templateModel->findAll()
        ];

        return view('admin/layouts/header', $data)
             . view('admin/layouts/sidebar', $data)
             . view('admin/layouts/topbar', $data)
             . view('admin/email_templates/index', $data)
             . view('admin/layouts/footer');
    }

    public function edit($id)
    {
        if ($redirect = checkPermissionOrRedirect('settings', 'edit')) {
            return $redirect;
        }

        $template = $this->templateModel->find($id);
        
        if (!$template) {
            return redirect()->to('/admin/email-templates')
                           ->with('error', 'Template introuvable');
        }

        $data = [
            'title' => 'Modifier le template',
            'pageTitle' => 'Modifier le template : ' . $template['name'],
            'activeMenu' => 'email-templates',
            'breadcrumbs' => [
                'Admin' => base_url('admin'),
                'Templates' => base_url('admin/email-templates'),
                'Modifier' => null
            ],
            'template' => $template,
            'variables' => $this->templateModel->getTemplateVariables($id)
        ];

        return view('admin/layouts/header', $data)
             . view('admin/layouts/sidebar', $data)
             . view('admin/layouts/topbar', $data)
             . view('admin/email_templates/edit', $data)
             . view('admin/layouts/footer');
    }

    public function update($id)
    {
        if ($redirect = checkPermissionOrRedirect('settings', 'edit')) {
            return $redirect;
        }

        $rules = [
            'subject' => 'required|min_length[3]',
            'body' => 'required|min_length[10]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'subject' => $this->request->getPost('subject'),
            'description' => $this->request->getPost('description'),
            'body' => $this->request->getPost('body'),
            'isActive' => $this->request->getPost('isActive') ? 1 : 0
        ];

        if ($this->templateModel->update($id, $data)) {
            return redirect()->to('/admin/email-templates')
                           ->with('success', 'Template mis à jour avec succès');
        }

        return redirect()->back()
                       ->withInput()
                       ->with('error', 'Erreur lors de la mise à jour');
    }

    public function preview($id)
    {
        $template = $this->templateModel->find($id);
        
        if (!$template) {
            return $this->response->setJSON(['error' => 'Template introuvable']);
        }

        // Données d'exemple
        $sampleData = [
            'customerName' => 'Ahmed Ben Ali',
            'reference' => 'FL20260131-001',
            'gameName' => 'Escape Room Mystère',
            'bookingDate' => '31 Janvier 2026',
            'bookingTime' => '14:00',
            'numberOfPlayers' => '4',
            'totalAmount' => '100.00',
            'qrCodeLink' => base_url('booking/ticket/FL20260131-001'),
            'address' => 'Adresse FunLab, Tunis',
            'phone' => '+216 XX XXX XXX',
            'siteName' => 'FunLab Booking',
            'siteUrl' => base_url(),
            'verificationCode' => '123456',
            'refundAmount' => '100.00'
        ];

        try {
            $rendered = $this->templateModel->renderTemplate($template['name'], $sampleData);
            return $this->response->setJSON([
                'success' => true,
                'html' => $rendered['body']
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'error' => $e->getMessage()
            ]);
        }
    }

    public function test($id)
    {
        if ($redirect = checkPermissionOrRedirect('settings', 'edit')) {
            return $redirect;
        }

        $email = $this->request->getPost('email');
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Email invalide'
            ]);
        }

        $template = $this->templateModel->find($id);
        
        if (!$template) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Template introuvable'
            ]);
        }

        // Données d'exemple
        $sampleData = [
            'customerName' => 'Utilisateur Test',
            'reference' => 'FL20260131-TEST',
            'gameName' => 'Escape Room Test',
            'bookingDate' => date('d F Y'),
            'bookingTime' => '14:00',
            'numberOfPlayers' => '4',
            'totalAmount' => '100.00',
            'qrCodeLink' => base_url(),
            'address' => 'Adresse de test',
            'phone' => '+216 XX XXX XXX',
            'siteName' => 'FunLab Booking',
            'siteUrl' => base_url(),
            'verificationCode' => '123456',
            'refundAmount' => '50.00'
        ];

        try {
            $rendered = $this->templateModel->renderTemplate($template['name'], $sampleData);
            
            $emailService = \Config\Services::email();
            $emailService->setTo($email);
            $emailService->setSubject('[TEST] ' . $rendered['subject']);
            $emailService->setMessage($rendered['body']);
            
            if ($emailService->send()) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Email de test envoyé avec succès à ' . $email
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Erreur lors de l\'envoi : ' . $emailService->printDebugger(['headers'])
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
