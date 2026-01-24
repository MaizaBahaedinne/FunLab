<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name',
        'username',
        'email',
        'password',
        'first_name',
        'last_name',
        'phone',
        'role',
        'auth_provider',
        'provider_id',
        'avatar',
        'email_verified',
        'is_active',
        'last_login'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'email' => 'required|valid_email|is_unique[users.email,id,{id}]',
        'password' => 'permit_empty|min_length[8]',
        'role' => 'permit_empty|in_list[customer,staff,admin]'
    ];

    protected $validationMessages = [
        'email' => [
            'required' => 'L\'email est requis',
            'valid_email' => 'L\'email doit être valide',
            'is_unique' => 'Cet email est déjà utilisé'
        ],
        'password' => [
            'min_length' => 'Le mot de passe doit contenir au moins 8 caractères'
        ]
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    /**
     * Hash le mot de passe avant insertion/mise à jour
     */
    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password']) && !empty($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }
        return $data;
    }

    /**
     * Vérifie le mot de passe
     */
    public function verifyPassword($email, $password)
    {
        $user = $this->where('email', $email)->first();
        
        if (!$user) {
            return false;
        }

        // Vérifier que l'utilisateur utilise l'authentification native
        if ($user['auth_provider'] !== 'native') {
            return false;
        }

        // Vérifier le mot de passe
        if (password_verify($password, $user['password'])) {
            // Mettre à jour last_login
            $this->update($user['id'], ['last_login' => date('Y-m-d H:i:s')]);
            return $user;
        }

        return false;
    }

    /**
     * Trouve ou crée un utilisateur via OAuth
     */
    public function findOrCreateOAuthUser($provider, $providerData)
    {
        // Chercher par provider_id
        $user = $this->where('auth_provider', $provider)
                     ->where('provider_id', $providerData['id'])
                     ->first();

        if ($user) {
            // Mettre à jour last_login
            $this->update($user['id'], ['last_login' => date('Y-m-d H:i:s')]);
            return $user;
        }

        // Chercher par email
        $user = $this->where('email', $providerData['email'])->first();

        if ($user) {
            // Lier le compte existant au provider
            $this->update($user['id'], [
                'auth_provider' => $provider,
                'provider_id' => $providerData['id'],
                'avatar' => $providerData['avatar'] ?? $user['avatar'],
                'email_verified' => 1,
                'last_login' => date('Y-m-d H:i:s')
            ]);
            return $this->find($user['id']);
        }

        // Créer un nouveau compte
        $userData = [
            'email' => $providerData['email'],
            'first_name' => $providerData['first_name'] ?? '',
            'last_name' => $providerData['last_name'] ?? '',
            'username' => $providerData['username'] ?? $this->generateUsername($providerData['email']),
            'auth_provider' => $provider,
            'provider_id' => $providerData['id'],
            'avatar' => $providerData['avatar'] ?? null,
            'email_verified' => 1,
            'is_active' => 1,
            'role' => 'customer',
            'last_login' => date('Y-m-d H:i:s')
        ];

        $userId = $this->insert($userData);
        return $this->find($userId);
    }

    /**
     * Génère un username unique à partir de l'email
     */
    protected function generateUsername($email)
    {
        $username = explode('@', $email)[0];
        $baseUsername = $username;
        $counter = 1;

        while ($this->where('username', $username)->first()) {
            $username = $baseUsername . $counter;
            $counter++;
        }

        return $username;
    }

    /**
     * Vérifie si l'email existe
     */
    public function emailExists($email)
    {
        return $this->where('email', $email)->countAllResults() > 0;
    }

    /**
     * Crée un token de réinitialisation de mot de passe
     */
    public function createPasswordResetToken($email)
    {
        $db = \Config\Database::connect();
        
        $token = bin2hex(random_bytes(32));
        $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $db->table('password_resets')->insert([
            'email' => $email,
            'token' => $token,
            'expires_at' => $expiresAt
        ]);

        return $token;
    }

    /**
     * Vérifie un token de réinitialisation
     */
    public function verifyPasswordResetToken($token)
    {
        $db = \Config\Database::connect();
        
        $reset = $db->table('password_resets')
                    ->where('token', $token)
                    ->where('expires_at >', date('Y-m-d H:i:s'))
                    ->get()
                    ->getRowArray();

        return $reset;
    }

    /**
     * Supprime un token de réinitialisation
     */
    public function deletePasswordResetToken($token)
    {
        $db = \Config\Database::connect();
        $db->table('password_resets')->where('token', $token)->delete();
    }
}
