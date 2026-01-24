<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingModel extends Model
{
    protected $table            = 'settings';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'key',
        'value',
        'type',
        'category',
        'description'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'key' => 'required|is_unique[settings.key,id,{id}]',
        'type' => 'required|in_list[text,textarea,number,boolean,image,json]'
    ];

    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    /**
     * Récupérer une valeur de configuration par clé
     */
    public function get($key, $default = null)
    {
        $setting = $this->where('key', $key)->first();
        
        if (!$setting) {
            return $default;
        }

        // Décoder selon le type
        switch ($setting['type']) {
            case 'boolean':
                return (bool) $setting['value'];
            case 'number':
                return (int) $setting['value'];
            case 'json':
                return json_decode($setting['value'], true);
            default:
                return $setting['value'];
        }
    }

    /**
     * Définir une valeur de configuration
     */
    public function set($key, $value, $type = 'text', $category = 'general')
    {
        $setting = $this->where('key', $key)->first();

        // Encoder selon le type
        if ($type === 'json') {
            $value = json_encode($value);
        } elseif ($type === 'boolean') {
            $value = $value ? '1' : '0';
        }

        if ($setting) {
            return $this->update($setting['id'], [
                'value' => $value,
                'type' => $type,
                'category' => $category
            ]);
        }

        return $this->insert([
            'key' => $key,
            'value' => $value,
            'type' => $type,
            'category' => $category
        ]);
    }

    /**
     * Récupérer tous les paramètres d'une catégorie
     */
    public function getByCategory($category)
    {
        $settings = $this->where('category', $category)->findAll();
        $result = [];

        foreach ($settings as $setting) {
            $result[$setting['key']] = $this->get($setting['key']);
        }

        return $result;
    }
}
