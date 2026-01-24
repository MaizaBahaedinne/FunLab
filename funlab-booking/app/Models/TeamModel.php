<?php

namespace App\Models;

use CodeIgniter\Model;

class TeamModel extends Model
{
    protected $table            = 'teams';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'booking_id',
        'name',
        'color',
        'position'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'booking_id' => 'required|integer',
        'name'       => 'required|min_length[2]|max_length[255]'
    ];

    protected $validationMessages = [
        'booking_id' => [
            'required' => 'L\'ID de réservation est requis'
        ],
        'name' => [
            'required' => 'Le nom de l\'équipe est requis',
            'min_length' => 'Le nom doit contenir au moins 2 caractères'
        ]
    ];

    protected $skipValidation = false;

    /**
     * Récupérer les équipes avec leurs participants
     */
    public function getTeamsWithParticipants($bookingId)
    {
        $teams = $this->where('booking_id', $bookingId)
                      ->orderBy('position', 'ASC')
                      ->findAll();

        $participantModel = new \App\Models\ParticipantModel();

        foreach ($teams as &$team) {
            $team['participants'] = $participantModel
                ->where('team_id', $team['id'])
                ->findAll();
        }

        return $teams;
    }
}
