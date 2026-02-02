<?php

namespace App\Models;

use CodeIgniter\Model;

class ContactMessageModel extends Model
{
    protected $table = 'contact_messages';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'name', 'email', 'phone', 'subject', 'message', 
        'status', 'replied_at', 'ip_address', 'created_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = '';
    protected $deletedField = '';

    // Validation
    protected $validationRules = [
        'name' => 'required|min_length[3]|max_length[100]',
        'email' => 'required|valid_email',
        'subject' => 'required|min_length[3]|max_length[200]',
        'message' => 'required|min_length[10]',
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'Le nom est requis.',
            'min_length' => 'Le nom doit contenir au moins 3 caractères.',
        ],
        'email' => [
            'required' => 'L\'email est requis.',
            'valid_email' => 'L\'email doit être valide.',
        ],
        'subject' => [
            'required' => 'Le sujet est requis.',
        ],
        'message' => [
            'required' => 'Le message est requis.',
            'min_length' => 'Le message doit contenir au moins 10 caractères.',
        ],
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    /**
     * Get unread messages count
     */
    public function getUnreadCount(): int
    {
        return $this->where('status', 'new')->countAllResults();
    }

    /**
     * Mark message as read
     */
    public function markAsRead(int $id): bool
    {
        return $this->update($id, ['status' => 'read']);
    }

    /**
     * Mark message as replied
     */
    public function markAsReplied(int $id): bool
    {
        return $this->update($id, [
            'status' => 'replied',
            'replied_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
