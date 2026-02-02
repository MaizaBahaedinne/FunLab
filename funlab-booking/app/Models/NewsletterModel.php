<?php

namespace App\Models;

use CodeIgniter\Model;

class NewsletterModel extends Model
{
    protected $table = 'newsletter_subscribers';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['email', 'status', 'subscribed_at', 'unsubscribed_at', 'ip_address'];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'subscribed_at';
    protected $updatedField = '';
    protected $deletedField = '';

    // Validation
    protected $validationRules = [
        'email' => 'required|valid_email|is_unique[newsletter_subscribers.email,id,{id}]',
    ];

    protected $validationMessages = [
        'email' => [
            'required' => 'L\'email est requis.',
            'valid_email' => 'L\'email doit être valide.',
            'is_unique' => 'Cet email est déjà inscrit à la newsletter.',
        ],
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    /**
     * Subscribe a new email
     */
    public function subscribe(string $email, ?string $ipAddress = null): bool|int
    {
        // Check if email already exists
        $existing = $this->where('email', $email)->first();
        
        if ($existing) {
            // If unsubscribed, reactivate
            if ($existing['status'] === 'unsubscribed') {
                return $this->update($existing['id'], [
                    'status' => 'active',
                    'subscribed_at' => date('Y-m-d H:i:s'),
                    'unsubscribed_at' => null,
                ]);
            }
            return false; // Already subscribed
        }

        return $this->insert([
            'email' => $email,
            'status' => 'active',
            'subscribed_at' => date('Y-m-d H:i:s'),
            'ip_address' => $ipAddress,
        ]);
    }

    /**
     * Unsubscribe an email
     */
    public function unsubscribe(string $email): bool
    {
        $subscriber = $this->where('email', $email)->first();
        
        if (!$subscriber) {
            return false;
        }

        return $this->update($subscriber['id'], [
            'status' => 'unsubscribed',
            'unsubscribed_at' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Get active subscribers count
     */
    public function getActiveCount(): int
    {
        return $this->where('status', 'active')->countAllResults();
    }

    /**
     * Get all active subscribers emails
     */
    public function getActiveEmails(): array
    {
        $subscribers = $this->where('status', 'active')->findAll();
        return array_column($subscribers, 'email');
    }
}
