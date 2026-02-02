<?php

namespace App\Models;

use CodeIgniter\Model;

class SlideModel extends Model
{
    protected $table            = 'slides';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'title',
        'subtitle',
        'description',
        'image',
        'button_text',
        'button_link',
        'button_style',
        'text_color',
        'overlay_opacity',
        'order',
        'active'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'title' => 'required|min_length[3]|max_length[255]',
        'image' => 'required',
        'order' => 'permit_empty|integer'
    ];

    protected $validationMessages = [
        'title' => [
            'required' => 'Le titre est obligatoire',
            'min_length' => 'Le titre doit contenir au moins 3 caractères'
        ]
    ];

    /**
     * Get active slides ordered by order field
     */
    public function getActiveSlides()
    {
        return $this->where('active', 1)
                    ->orderBy('order', 'ASC')
                    ->findAll();
    }
}
