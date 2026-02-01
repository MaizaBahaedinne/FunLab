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
    public function getSetting($key, $default = null)
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
    public function setSetting($key, $value, $type = 'text', $category = 'general')
    {
        $setting = $this->where('key', $key)->first();

        // Encoder selon le type
        if ($type === 'json') {
            $value = json_encode($value);
        } elseif ($type === 'boolean') {
            $value = $value ? '1' : '0';
        }

        if ($setting) {
            // Désactiver validation pour update
            $this->skipValidation = true;
            return $this->update($setting['id'], [
                'value' => $value,
                'type' => $type,
                'category' => $category
            ]);
        }

        // Désactiver validation pour insert aussi
        $this->skipValidation = true;
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
        return $this->where('category', $category)->findAll();
    }

    /**
     * Récupérer tous les paramètres d'une catégorie sous forme de tableau associatif
     */
    public function getByCategoryAsArray($category)
    {
        $settings = $this->where('category', $category)->findAll();
        $result = [];

        foreach ($settings as $setting) {
            $result[$setting['key']] = $setting['value'];
        }

        return $result;
    }

    /**
     * Mettre à jour un paramètre (ou le créer s'il n'existe pas)
     */
    public function updateSetting($key, $value, $type = 'text', $category = null)
    {
        // Si pas de catégorie spécifiée, essayer de trouver le setting existant
        if ($category === null) {
            $existing = $this->where('key', $key)->first();
            if ($existing) {
                $category = $existing['category'];
                $type = $existing['type'];
            } else {
                // Déterminer la catégorie par le préfixe de la clé
                $parts = explode('_', $key);
                $category = $parts[0] ?? 'general';
            }
        }

        return $this->setSetting($key, $value, $type, $category);
    }
}
