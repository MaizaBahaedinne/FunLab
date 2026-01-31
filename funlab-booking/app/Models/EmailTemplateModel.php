<?php

namespace App\Models;

use CodeIgniter\Model;

class EmailTemplateModel extends Model
{
    protected $table = 'email_templates';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'name',
        'subject',
        'description',
        'body',
        'variables',
        'isActive'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'createdAt';
    protected $updatedField = 'updatedAt';

    /**
     * Récupérer un template par son nom
     */
    public function getTemplateByName(string $name)
    {
        return $this->where('name', $name)
                    ->where('isActive', 1)
                    ->first();
    }

    /**
     * Récupérer tous les templates actifs
     */
    public function getActiveTemplates()
    {
        return $this->where('isActive', 1)
                    ->orderBy('name', 'ASC')
                    ->findAll();
    }

    /**
     * Remplacer les variables dans le template
     */
    public function renderTemplate(string $templateName, array $data): array
    {
        $template = $this->getTemplateByName($templateName);
        
        if (!$template) {
            throw new \Exception("Template '$templateName' introuvable");
        }

        // Remplacer les variables dans le sujet
        $subject = $template['subject'];
        foreach ($data as $key => $value) {
            $subject = str_replace('{{' . $key . '}}', $value, $subject);
        }

        // Remplacer les variables dans le corps
        $body = $template['body'];
        foreach ($data as $key => $value) {
            $body = str_replace('{{' . $key . '}}', $value, $body);
        }

        return [
            'subject' => $subject,
            'body' => $body
        ];
    }

    /**
     * Récupérer les variables disponibles pour un template
     */
    public function getTemplateVariables(int $id)
    {
        $template = $this->find($id);
        if (!$template || !$template['variables']) {
            return [];
        }
        return json_decode($template['variables'], true) ?: [];
    }
}
