<?php

namespace App\Services;

/**
 * QRCodeService
 * 
 * Service de génération et validation de QR Codes pour les billets.
 * 
 * @package App\Services
 * @author FunLab Team
 * @version 1.0.0
 */
class QRCodeService
{
    /**
     * Génère un QR code pour une réservation
     * 
     * @param int $bookingId ID de la réservation
     * @param string $confirmationCode Code de confirmation
     * @return string Données du QR code (base64 ou URL)
     */
    public function generateQRCode(int $bookingId, string $confirmationCode): string
    {
        // Format des données du QR code
        $qrData = json_encode([
            'booking_id' => $bookingId,
            'confirmation_code' => $confirmationCode,
            'timestamp' => time(),
            'hash' => $this->generateSecurityHash($bookingId, $confirmationCode)
        ]);

        // Pour l'instant, on retourne les données JSON
        // Plus tard, vous pourrez utiliser une bibliothèque comme endroid/qr-code
        // pour générer une vraie image QR code
        
        return base64_encode($qrData);
    }

    /**
     * Génère un hash de sécurité pour le QR code
     * 
     * @param int $bookingId
     * @param string $confirmationCode
     * @return string Hash sécurisé
     */
    private function generateSecurityHash(int $bookingId, string $confirmationCode): string
    {
        $secret = getenv('encryption.key') ?? 'funlab_secret_key_2026';
        return hash_hmac('sha256', $bookingId . $confirmationCode, $secret);
    }

    /**
     * Valide un QR code
     * 
     * @param string $qrCodeData Données du QR code scanné
     * @return array ['valid' => bool, 'booking_id' => int|null, 'message' => string]
     */
    public function validateQRCode(string $qrCodeData): array
    {
        try {
            // Décoder les données
            $decoded = base64_decode($qrCodeData);
            $data = json_decode($decoded, true);

            if (!$data) {
                return [
                    'valid' => false,
                    'booking_id' => null,
                    'message' => 'QR Code invalide'
                ];
            }

            // Vérifier les champs requis
            if (!isset($data['booking_id']) || !isset($data['confirmation_code']) || !isset($data['hash'])) {
                return [
                    'valid' => false,
                    'booking_id' => null,
                    'message' => 'QR Code mal formé'
                ];
            }

            // Vérifier le hash de sécurité
            $expectedHash = $this->generateSecurityHash($data['booking_id'], $data['confirmation_code']);
            
            if ($data['hash'] !== $expectedHash) {
                return [
                    'valid' => false,
                    'booking_id' => null,
                    'message' => 'QR Code falsifié'
                ];
            }

            // Vérifier que la réservation existe
            $bookingModel = new \App\Models\BookingModel();
            $booking = $bookingModel->find($data['booking_id']);

            if (!$booking) {
                return [
                    'valid' => false,
                    'booking_id' => null,
                    'message' => 'Réservation introuvable'
                ];
            }

            // Vérifier le code de confirmation
            if ($booking['confirmation_code'] !== $data['confirmation_code']) {
                return [
                    'valid' => false,
                    'booking_id' => null,
                    'message' => 'Code de confirmation incorrect'
                ];
            }

            // Vérifier le statut de la réservation
            if ($booking['status'] === 'cancelled') {
                return [
                    'valid' => false,
                    'booking_id' => $data['booking_id'],
                    'message' => 'Réservation annulée'
                ];
            }

            if ($booking['status'] === 'completed') {
                return [
                    'valid' => false,
                    'booking_id' => $data['booking_id'],
                    'message' => 'Réservation déjà utilisée'
                ];
            }

            // Vérifier la date de la réservation
            $bookingDate = strtotime($booking['booking_date'] . ' ' . $booking['start_time']);
            $now = time();
            $tolerance = 30 * 60; // 30 minutes de tolérance

            if ($now < ($bookingDate - $tolerance)) {
                return [
                    'valid' => false,
                    'booking_id' => $data['booking_id'],
                    'message' => 'Trop tôt pour scanner ce billet'
                ];
            }

            if ($now > ($bookingDate + (2 * 60 * 60))) {
                return [
                    'valid' => false,
                    'booking_id' => $data['booking_id'],
                    'message' => 'Billet expiré'
                ];
            }

            // QR Code valide ✓
            return [
                'valid' => true,
                'booking_id' => $data['booking_id'],
                'message' => 'QR Code valide',
                'booking' => $booking
            ];

        } catch (\Exception $e) {
            log_message('error', 'Erreur validation QR Code : ' . $e->getMessage());
            
            return [
                'valid' => false,
                'booking_id' => null,
                'message' => 'Erreur lors de la validation'
            ];
        }
    }

    /**
     * Enregistre le check-in d'un participant
     * 
     * @param int $bookingId ID de la réservation
     * @return array ['success' => bool, 'message' => string]
     */
    public function checkIn(int $bookingId): array
    {
        try {
            $bookingModel = new \App\Models\BookingModel();
            $booking = $bookingModel->find($bookingId);

            if (!$booking) {
                return ['success' => false, 'message' => 'Réservation introuvable'];
            }

            // Marquer tous les participants comme présents
            $participantModel = new \App\Models\ParticipantModel();
            $participants = $participantModel->where('booking_id', $bookingId)->findAll();

            foreach ($participants as $participant) {
                if (!$participant['checked_in']) {
                    $participantModel->update($participant['id'], [
                        'checked_in' => 1,
                        'checked_in_at' => date('Y-m-d H:i:s')
                    ]);
                }
            }

            return [
                'success' => true,
                'message' => 'Check-in effectué avec succès',
                'participants_count' => count($participants)
            ];

        } catch (\Exception $e) {
            log_message('error', 'Erreur check-in : ' . $e->getMessage());
            
            return ['success' => false, 'message' => 'Erreur lors du check-in'];
        }
    }

    /**
     * Génère une URL de QR code (pour affichage)
     * 
     * @param string $qrData Données encodées du QR code
     * @return string URL de l'image QR code
     */
    public function getQRCodeImageUrl(string $qrData): string
    {
        // Utiliser un service externe comme api.qrserver.com
        return 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($qrData);
    }
}
