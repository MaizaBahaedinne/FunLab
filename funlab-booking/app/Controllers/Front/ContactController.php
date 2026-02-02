<?php

namespace App\Controllers\Front;

use App\Controllers\BaseController;
use App\Models\SettingModel;
use App\Models\NewsletterModel;
use App\Models\ContactMessageModel;

class ContactController extends BaseController
{
    protected $settingModel;
    protected $newsletterModel;
    protected $contactMessageModel;

    public function __construct()
    {
        $this->settingModel = new SettingModel();
        $this->newsletterModel = new NewsletterModel();
        $this->contactMessageModel = new ContactMessageModel();
    }

    /**
     * Page de contact
     */
    public function index()
    {
        // Charger les paramètres de contact
        $contactSettings = [];
        $settings = $this->settingModel->getByCategory('contact');
        
        if (!empty($settings) && is_array($settings)) {
            foreach ($settings as $setting) {
                if (isset($setting['key']) && isset($setting['value'])) {
                    $contactSettings[$setting['key']] = $setting['value'];
                }
            }
        }

        // Charger les horaires si pas de texte personnalisé
        if (empty($contactSettings['contact_hours_text'])) {
            $hoursSettings = $this->settingModel->getByCategory('hours');
            $hours = [];
            if (!empty($hoursSettings) && is_array($hoursSettings)) {
                foreach ($hoursSettings as $setting) {
                    if (isset($setting['key']) && isset($setting['value'])) {
                        $hours[$setting['key']] = $setting['value'];
                    }
                }
            }
            $contactSettings['hours'] = $hours;
        }

        // Préparer les données SEO
        $metaDescription = "Contactez FunLab Tunisie - " . ($contactSettings['contact_address'] ?? '') . " - " . ($contactSettings['contact_phone'] ?? '');

        $data = [
            'title' => 'Contact - FunLab Tunisie',
            'activeMenu' => 'contact',
            'contactSettings' => $contactSettings,
            
            // SEO
            'metaTitle' => 'Contactez-Nous - FunLab Tunisie',
            'metaDescription' => $metaDescription,
            'metaKeywords' => 'contact funlab, adresse funlab, téléphone funlab tunisie, formulaire contact',
            'canonicalUrl' => base_url('contact'),
            
            // Open Graph
            'ogType' => 'website',
            'ogUrl' => base_url('contact'),
            'ogTitle' => 'Contactez FunLab Tunisie',
            'ogDescription' => $metaDescription
        ];

        return view('front/contact', $data);
    }

    /**
     * Traiter l'envoi du formulaire de contact
     */
    public function send()
    {
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'name' => 'required|min_length[3]|max_length[100]',
            'email' => 'required|valid_email',
            'phone' => 'permit_empty|min_length[8]|max_length[20]',
            'subject' => 'required|min_length[3]|max_length[200]',
            'message' => 'required|min_length[10]|max_length[2000]'
        ], [
            'name' => [
                'required' => 'Le nom est requis',
                'min_length' => 'Le nom doit contenir au moins 3 caractères'
            ],
            'email' => [
                'required' => 'L\'email est requis',
                'valid_email' => 'L\'email doit être valide'
            ],
            'subject' => [
                'required' => 'Le sujet est requis',
                'min_length' => 'Le sujet doit contenir au moins 3 caractères'
            ],
            'message' => [
                'required' => 'Le message est requis',
                'min_length' => 'Le message doit contenir au moins 10 caractères'
            ]
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $validation->getErrors());
        }

        // Récupérer les données
        $name = $this->request->getPost('name');
        $email = $this->request->getPost('email');
        $phone = $this->request->getPost('phone');
        $subject = $this->request->getPost('subject');
        $message = $this->request->getPost('message');

        // Enregistrer dans la base de données
        $contactData = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'subject' => $subject,
            'message' => $message,
            'ip_address' => $this->request->getIPAddress(),
            'status' => 'new'
        ];

        $this->contactMessageModel->insert($contactData);

        // Récupérer l'email de destination depuis les paramètres
        $contactSettings = $this->settingModel->getByCategory('contact');
        $toEmail = '';
        if (!empty($contactSettings) && is_array($contactSettings)) {
            foreach ($contactSettings as $setting) {
                if (isset($setting['key']) && $setting['key'] === 'contact_receive_email' && isset($setting['value'])) {
                    $toEmail = $setting['value'];
                    break;
                }
            }
        }

        // Si pas d'email configuré, utiliser l'email par défaut
        if (empty($toEmail)) {
            $toEmail = 'contact@funlab.tn'; // Email par défaut
        }

        try {
            // Charger le service Email
            $emailService = \Config\Services::email();
            
            $emailService->setFrom($email, $name);
            $emailService->setTo($toEmail);
            $emailService->setSubject('[Contact FunLab] ' . $subject);
            
            $emailBody = "
                <h2>Nouveau message depuis le formulaire de contact</h2>
                <p><strong>Nom:</strong> {$name}</p>
                <p><strong>Email:</strong> {$email}</p>
                <p><strong>Téléphone:</strong> " . ($phone ?: 'Non renseigné') . "</p>
                <p><strong>Sujet:</strong> {$subject}</p>
                <hr>
                <p><strong>Message:</strong></p>
                <p>" . nl2br(esc($message)) . "</p>
            ";
            
            $emailService->setMessage($emailBody);

            if ($emailService->send()) {
                return redirect()->to('/contact')
                               ->with('success', 'Votre message a été envoyé avec succès ! Nous vous répondrons dans les plus brefs délais.');
            } else {
                log_message('error', 'Email send failed: ' . $emailService->printDebugger(['headers']));
                return redirect()->back()
                               ->withInput()
                               ->with('error', 'Une erreur est survenue lors de l\'envoi. Veuillez réessayer ou nous contacter directement.');
            }
        } catch (\Exception $e) {
            log_message('error', 'Contact form exception: ' . $e->getMessage());
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Une erreur est survenue. Veuillez nous contacter directement par téléphone ou email.');
        }
    }

    /**
     * S'abonner à la newsletter
     */
    public function subscribe()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        $email = $this->request->getPost('email');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Email invalide'
            ]);
        }

        $ipAddress = $this->request->getIPAddress();
        $result = $this->newsletterModel->subscribe($email, $ipAddress);

        if ($result) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Merci ! Vous êtes maintenant inscrit à notre newsletter.'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Cet email est déjà inscrit à notre newsletter.'
            ]);
        }
    }

    /**
     * Se désabonner de la newsletter
     */
    public function unsubscribe($email = null)
    {
        if (!$email) {
            $email = $this->request->getGet('email');
        }

        if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return view('front/newsletter_unsubscribe', [
                'title' => 'Désinscription Newsletter',
                'success' => false,
                'message' => 'Email invalide ou manquant'
            ]);
        }

        $result = $this->newsletterModel->unsubscribe($email);

        return view('front/newsletter_unsubscribe', [
            'title' => 'Désinscription Newsletter',
            'success' => $result,
            'message' => $result 
                ? 'Vous avez été désinscrit avec succès de notre newsletter.' 
                : 'Cet email n\'est pas inscrit à notre newsletter.'
        ]);
    }
}
