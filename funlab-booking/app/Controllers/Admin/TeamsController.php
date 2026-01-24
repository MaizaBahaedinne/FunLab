<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\TeamModel;
use App\Models\ParticipantModel;

class TeamsController extends BaseController
{
    protected $teamModel;
    protected $participantModel;

    public function __construct()
    {
        $this->teamModel = new TeamModel();
        $this->participantModel = new ParticipantModel();
    }

    /**
     * Afficher l'interface de gestion des équipes pour une réservation
     */
    public function manage($bookingId)
    {
        $bookingModel = new \App\Models\BookingModel();
        $booking = $bookingModel->find($bookingId);

        if (!$booking) {
            return redirect()->to('/admin/bookings')->with('error', 'Réservation introuvable');
        }

        // Récupérer les équipes avec leurs participants
        $teams = $this->teamModel->getTeamsWithParticipants($bookingId);

        // Récupérer les participants sans équipe
        $unassignedParticipants = $this->participantModel
            ->where('booking_id', $bookingId)
            ->where('team_id', null)
            ->findAll();

        $data = [
            'booking' => $booking,
            'teams' => $teams,
            'unassignedParticipants' => $unassignedParticipants
        ];

        return view('admin/teams/manage', $data);
    }

    /**
     * Créer une nouvelle équipe
     */
    public function create()
    {
        $bookingId = $this->request->getPost('booking_id');
        $name = $this->request->getPost('name');
        $color = $this->request->getPost('color') ?: '#667eea';

        if (!$bookingId || !$name) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Données manquantes'
            ]);
        }

        $teamId = $this->teamModel->insert([
            'booking_id' => $bookingId,
            'name' => $name,
            'color' => $color,
            'position' => $this->teamModel->where('booking_id', $bookingId)->countAllResults()
        ]);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Équipe créée',
            'team_id' => $teamId,
            'team' => $this->teamModel->find($teamId)
        ]);
    }

    /**
     * Mettre à jour une équipe
     */
    public function update($id)
    {
        $name = $this->request->getPost('name');
        $color = $this->request->getPost('color');

        $data = [];
        if ($name) $data['name'] = $name;
        if ($color) $data['color'] = $color;

        if (empty($data)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Aucune donnée à mettre à jour'
            ]);
        }

        $this->teamModel->update($id, $data);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Équipe mise à jour'
        ]);
    }

    /**
     * Supprimer une équipe
     */
    public function delete($id)
    {
        // Retirer les participants de cette équipe
        $this->participantModel
            ->where('team_id', $id)
            ->set(['team_id' => null])
            ->update();

        $this->teamModel->delete($id);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Équipe supprimée'
        ]);
    }

    /**
     * Assigner un participant à une équipe (drag & drop)
     */
    public function assignParticipant()
    {
        $participantId = $this->request->getPost('participant_id');
        $teamId = $this->request->getPost('team_id');

        if (!$participantId) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'ID participant manquant'
            ]);
        }

        // Si teamId est null, on retire le participant de l'équipe
        $this->participantModel->update($participantId, [
            'team_id' => $teamId ?: null
        ]);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Participant assigné'
        ]);
    }

    /**
     * Réorganiser l'ordre des équipes
     */
    public function reorder()
    {
        $positions = $this->request->getPost('positions'); // Array [teamId => position]

        if (!$positions || !is_array($positions)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Données invalides'
            ]);
        }

        foreach ($positions as $teamId => $position) {
            $this->teamModel->update($teamId, ['position' => $position]);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Ordre mis à jour'
        ]);
    }
}
