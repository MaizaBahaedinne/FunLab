<?php

namespace App\Services;

use App\Models\BookingModel;
use App\Models\RoomModel;
use App\Models\GameModel;
use App\Models\ParticipantModel;

/**
 * Service de génération de billets et d'envoi d'emails
 * 
 * Gère la création de PDF et l'envoi d'emails de confirmation
 */
class TicketService
{
    protected $qrCodeService;
    protected $bookingModel;
    protected $roomModel;
    protected $gameModel;
    protected $participantModel;

    public function __construct()
    {
        $this->qrCodeService = new QRCodeService();
        $this->bookingModel = new BookingModel();
        $this->roomModel = new RoomModel();
        $this->gameModel = new GameModel();
        $this->participantModel = new ParticipantModel();
    }

    /**
     * Génère un ticket HTML pour une réservation
     * 
     * @param int $bookingId
     * @return string HTML du ticket
     */
    public function generateTicket($bookingId)
    {
        $booking = $this->getBookingDetails($bookingId);

        if (!$booking) {
            throw new \Exception("Réservation non trouvée");
        }

        // Générer le QR code en base64 pour l'intégration dans HTML/PDF
        $qrCodeBase64 = $this->generateQRCodeImage($booking['qr_code']);

        return $this->renderTicketHTML($booking, $qrCodeBase64);
    }

    /**
     * Envoie le ticket par email
     * 
     * @param int $bookingId
     * @param string $email
     * @return bool
     */
    public function sendTicketByEmail($bookingId, $email)
    {
        $booking = $this->getBookingDetails($bookingId);

        if (!$booking) {
            log_message('error', "Tentative d'envoi email pour réservation inexistante: $bookingId");
            return false;
        }

        try {
            // Générer le contenu du ticket
            $qrCodeBase64 = $this->generateQRCodeImage($booking['qr_code']);
            $ticketHTML = $this->renderTicketHTML($booking, $qrCodeBase64);

            // Configuration email
            $emailService = \Config\Services::email();
            
            $emailService->setFrom('noreply@funlab.tn', 'FunLab Tunisie');
            $emailService->setTo($email);
            $emailService->setSubject('Votre billet FunLab - Réservation ' . $booking['confirmation_code']);
            
            // Email HTML
            $emailService->setMessage($this->renderEmailTemplate($booking, $qrCodeBase64));
            $emailService->setMailType('html');

            // Envoi
            $sent = $emailService->send();

            if ($sent) {
                log_message('info', "Email envoyé avec succès pour réservation $bookingId à $email");
                return true;
            } else {
                log_message('error', "Échec envoi email pour réservation $bookingId: " . $emailService->printDebugger());
                return false;
            }

        } catch (\Exception $e) {
            log_message('error', "Erreur envoi email pour réservation $bookingId: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Génère un PDF du ticket (placeholder - nécessite TCPDF ou Dompdf)
     * 
     * @param int $bookingId
     * @return string|null Chemin du fichier PDF ou null
     */
    public function generateTicketPDF($bookingId)
    {
        // TODO: Implémenter avec TCPDF ou Dompdf
        // Pour l'instant, retourne le HTML qui peut être converti côté client
        
        $ticketHTML = $this->generateTicket($bookingId);
        
        // Option 1: Utiliser wkhtmltopdf (ligne de commande)
        // exec("wkhtmltopdf - output.pdf", $ticketHTML);
        
        // Option 2: Utiliser Dompdf
        // $dompdf = new \Dompdf\Dompdf();
        // $dompdf->loadHtml($ticketHTML);
        // $dompdf->render();
        // return $dompdf->output();
        
        // Pour l'instant, retourner le HTML
        return $ticketHTML;
    }

    /**
     * Récupère les détails complets d'une réservation
     * 
     * @param int $bookingId
     * @return array|null
     */
    protected function getBookingDetails($bookingId)
    {
        $booking = $this->bookingModel
            ->select('bookings.*, rooms.name as room_name, rooms.location as room_location, 
                     games.name as game_name, games.description as game_description, 
                     games.duration as game_duration, games.min_players, games.max_players')
            ->join('rooms', 'rooms.id = bookings.room_id')
            ->join('games', 'games.id = bookings.game_id')
            ->find($bookingId);

        if (!$booking) {
            return null;
        }

        // Récupérer les participants
        $participants = $this->participantModel
            ->where('booking_id', $bookingId)
            ->findAll();

        $booking['participants'] = $participants;

        return $booking;
    }

    /**
     * Génère l'image QR code en base64
     * 
     * @param string $qrData
     * @return string Base64 encoded image
     */
    protected function generateQRCodeImage($qrData)
    {
        // Utiliser une API externe pour générer le QR code
        $size = 300;
        $url = "https://api.qrserver.com/v1/create-qr-code/?size={$size}x{$size}&data=" . urlencode($qrData);
        
        try {
            $imageData = file_get_contents($url);
            return 'data:image/png;base64,' . base64_encode($imageData);
        } catch (\Exception $e) {
            log_message('error', "Erreur génération QR code: " . $e->getMessage());
            return '';
        }
    }

    /**
     * Rend le HTML du ticket
     * 
     * @param array $booking
     * @param string $qrCodeBase64
     * @return string
     */
    protected function renderTicketHTML($booking, $qrCodeBase64)
    {
        $bookingDate = date('d/m/Y', strtotime($booking['booking_date']));
        $bookingDay = $this->getDayName($booking['booking_date']);
        $startTime = substr($booking['start_time'], 0, 5);
        $endTime = substr($booking['end_time'], 0, 5);

        $html = <<<HTML
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billet FunLab - {$booking['confirmation_code']}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Arial', sans-serif; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
        }
        .ticket {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
        }
        .ticket-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .ticket-header h1 { font-size: 2rem; margin-bottom: 10px; }
        .ticket-header p { font-size: 1.2rem; opacity: 0.9; }
        .ticket-body { padding: 30px; }
        .confirmation-code {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 30px;
            border: 2px dashed #667eea;
        }
        .confirmation-code h2 { 
            font-size: 2.5rem; 
            color: #667eea; 
            letter-spacing: 3px;
            margin-bottom: 5px;
        }
        .confirmation-code p { color: #6c757d; }
        .details { margin-bottom: 30px; }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 15px 0;
            border-bottom: 1px solid #e0e0e0;
        }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { 
            font-weight: bold; 
            color: #495057;
            display: flex;
            align-items: center;
        }
        .detail-value { 
            color: #212529;
            text-align: right;
        }
        .qr-section {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
        }
        .qr-section img {
            max-width: 300px;
            border: 5px solid white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .qr-section p {
            margin-top: 15px;
            color: #6c757d;
            font-size: 0.9rem;
        }
        .instructions {
            background: #e7f3ff;
            border-left: 4px solid #2196F3;
            padding: 15px;
            margin-top: 30px;
            border-radius: 5px;
        }
        .instructions h3 {
            color: #2196F3;
            margin-bottom: 10px;
            font-size: 1.1rem;
        }
        .instructions ul {
            list-style: none;
            padding-left: 0;
        }
        .instructions li {
            padding: 5px 0;
            color: #495057;
        }
        .instructions li:before {
            content: "✓ ";
            color: #28a745;
            font-weight: bold;
            margin-right: 10px;
        }
        .footer {
            text-align: center;
            padding: 20px;
            color: #6c757d;
            font-size: 0.85rem;
            border-top: 1px solid #e0e0e0;
        }
        .icon { margin-right: 8px; }
        @media print {
            body { background: white; padding: 0; }
            .ticket { box-shadow: none; }
        }
    </style>
</head>
<body>
    <div class="ticket">
        <div class="ticket-header">
            <h1>🎮 FunLab Tunisie</h1>
            <p>Votre Billet Électronique</p>
        </div>
        
        <div class="ticket-body">
            <div class="confirmation-code">
                <h2>{$booking['confirmation_code']}</h2>
                <p>Code de confirmation</p>
            </div>

            <div class="details">
                <div class="detail-row">
                    <span class="detail-label">
                        <span class="icon">🎯</span> Activité
                    </span>
                    <span class="detail-value"><strong>{$booking['game_name']}</strong></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">
                        <span class="icon">📍</span> Salle
                    </span>
                    <span class="detail-value">{$booking['room_name']}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">
                        <span class="icon">📅</span> Date
                    </span>
                    <span class="detail-value">{$bookingDay} {$bookingDate}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">
                        <span class="icon">🕐</span> Horaire
                    </span>
                    <span class="detail-value">{$startTime} - {$endTime}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">
                        <span class="icon">👥</span> Nombre de joueurs
                    </span>
                    <span class="detail-value">{$booking['num_players']} personne(s)</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">
                        <span class="icon">👤</span> Nom
                    </span>
                    <span class="detail-value">{$booking['customer_name']}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">
                        <span class="icon">💰</span> Prix total
                    </span>
                    <span class="detail-value"><strong>{$booking['total_price']} DT</strong></span>
                </div>
            </div>

            <div class="qr-section">
                <h3>QR Code d'Accès</h3>
                <img src="{$qrCodeBase64}" alt="QR Code">
                <p>Présentez ce QR code à votre arrivée</p>
            </div>

            <div class="instructions">
                <h3>📋 Instructions Importantes</h3>
                <ul>
                    <li>Arrivez 10 minutes avant votre créneau</li>
                    <li>Présentez ce billet (imprimé ou sur smartphone)</li>
                    <li>Le QR code sera scanné à l'entrée</li>
                    <li>En cas de retard, contactez-nous au +216 XX XXX XXX</li>
                </ul>
            </div>
        </div>

        <div class="footer">
            <p>FunLab Tunisie - Centre de Loisirs Interactifs</p>
            <p>Adresse : [Votre Adresse] | Tél : +216 XX XXX XXX</p>
            <p>Email : contact@funlab.tn | www.funlab.tn</p>
        </div>
    </div>
</body>
</html>
HTML;

        return $html;
    }

    /**
     * Rend le template email
     * 
     * @param array $booking
     * @param string $qrCodeBase64
     * @return string
     */
    protected function renderEmailTemplate($booking, $qrCodeBase64)
    {
        $bookingDate = date('d/m/Y', strtotime($booking['booking_date']));
        $bookingDay = $this->getDayName($booking['booking_date']);
        $startTime = substr($booking['start_time'], 0, 5);
        $endTime = substr($booking['end_time'], 0, 5);

        return <<<HTML
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { 
            font-family: Arial, sans-serif; 
            line-height: 1.6; 
            color: #333;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 { margin: 0; font-size: 28px; }
        .content { padding: 30px; }
        .confirmation-box {
            background: #f8f9fa;
            border-left: 4px solid #28a745;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .confirmation-box h2 {
            margin: 0 0 10px 0;
            color: #28a745;
            font-size: 24px;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .details-table td {
            padding: 12px;
            border-bottom: 1px solid #e0e0e0;
        }
        .details-table td:first-child {
            font-weight: bold;
            color: #666;
            width: 40%;
        }
        .qr-section {
            text-align: center;
            margin: 30px 0;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
        }
        .qr-section img {
            max-width: 250px;
            border: 3px solid white;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .alert {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #666;
            font-size: 14px;
            border-top: 1px solid #e0e0e0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>🎮 FunLab Tunisie</h1>
            <p>Confirmation de Réservation</p>
        </div>

        <div class="content">
            <p>Bonjour <strong>{$booking['customer_name']}</strong>,</p>
            
            <p>Nous avons le plaisir de confirmer votre réservation chez FunLab Tunisie !</p>

            <div class="confirmation-box">
                <h2>✓ Réservation Confirmée</h2>
                <p style="margin: 0;">Code de confirmation : <strong style="font-size: 20px; color: #667eea;">{$booking['confirmation_code']}</strong></p>
            </div>

            <table class="details-table">
                <tr>
                    <td>🎯 Activité</td>
                    <td><strong>{$booking['game_name']}</strong></td>
                </tr>
                <tr>
                    <td>📍 Salle</td>
                    <td>{$booking['room_name']}</td>
                </tr>
                <tr>
                    <td>📅 Date</td>
                    <td>{$bookingDay} {$bookingDate}</td>
                </tr>
                <tr>
                    <td>🕐 Horaire</td>
                    <td><strong>{$startTime} - {$endTime}</strong></td>
                </tr>
                <tr>
                    <td>👥 Joueurs</td>
                    <td>{$booking['num_players']} personne(s)</td>
                </tr>
                <tr>
                    <td>💰 Prix total</td>
                    <td><strong>{$booking['total_price']} DT</strong></td>
                </tr>
            </table>

            <div class="qr-section">
                <h3 style="margin-top: 0;">Votre Billet Électronique</h3>
                <img src="{$qrCodeBase64}" alt="QR Code">
                <p style="margin-bottom: 0; color: #666;">Présentez ce QR code à votre arrivée</p>
            </div>

            <div class="alert">
                <strong>⚠️ Important :</strong>
                <ul style="margin: 10px 0 0 0; padding-left: 20px;">
                    <li>Arrivez 10 minutes avant votre créneau</li>
                    <li>Présentez ce billet (sur smartphone ou imprimé)</li>
                    <li>En cas d'empêchement, contactez-nous rapidement</li>
                </ul>
            </div>

            <p>Nous avons hâte de vous accueillir pour une expérience inoubliable !</p>

            <p style="margin-top: 30px;">
                Cordialement,<br>
                <strong>L'équipe FunLab Tunisie</strong>
            </p>
        </div>

        <div class="footer">
            <p><strong>FunLab Tunisie</strong> - Centre de Loisirs Interactifs</p>
            <p>Adresse : [Votre Adresse] | Tél : +216 XX XXX XXX</p>
            <p>Email : contact@funlab.tn | www.funlab.tn</p>
            <p style="font-size: 12px; margin-top: 15px; color: #999;">
                Vous recevez cet email car vous avez effectué une réservation sur FunLab Tunisie.
            </p>
        </div>
    </div>
</body>
</html>
HTML;
    }

    /**
     * Obtient le nom du jour en français
     * 
     * @param string $date
     * @return string
     */
    protected function getDayName($date)
    {
        $days = [
            'Monday' => 'Lundi',
            'Tuesday' => 'Mardi',
            'Wednesday' => 'Mercredi',
            'Thursday' => 'Jeudi',
            'Friday' => 'Vendredi',
            'Saturday' => 'Samedi',
            'Sunday' => 'Dimanche'
        ];

        $dayName = date('l', strtotime($date));
        return $days[$dayName] ?? $dayName;
    }

    /**
     * Envoie un email d'annulation
     * 
     * @param int $bookingId
     * @param string $reason
     * @return bool
     */
    public function sendCancellationEmail($bookingId, $reason = '')
    {
        $booking = $this->getBookingDetails($bookingId);

        if (!$booking) {
            return false;
        }

        try {
            $emailService = \Config\Services::email();
            
            $emailService->setFrom('noreply@funlab.tn', 'FunLab Tunisie');
            $emailService->setTo($booking['customer_email']);
            $emailService->setSubject('Annulation de votre réservation - ' . $booking['confirmation_code']);
            
            $message = $this->renderCancellationEmail($booking, $reason);
            $emailService->setMessage($message);
            $emailService->setMailType('html');

            return $emailService->send();

        } catch (\Exception $e) {
            log_message('error', "Erreur envoi email annulation: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Rend le template email d'annulation
     * 
     * @param array $booking
     * @param string $reason
     * @return string
     */
    protected function renderCancellationEmail($booking, $reason)
    {
        $bookingDate = date('d/m/Y', strtotime($booking['booking_date']));
        $startTime = substr($booking['start_time'], 0, 5);

        $reasonText = $reason ? "<p><strong>Raison :</strong> $reason</p>" : '';

        return <<<HTML
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; background: #f4f4f4; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 10px; overflow: hidden; }
        .header { background: #dc3545; color: white; padding: 30px; text-align: center; }
        .content { padding: 30px; }
        .alert-box { background: #f8d7da; border-left: 4px solid #dc3545; padding: 20px; margin: 20px 0; border-radius: 5px; color: #721c24; }
        .footer { background: #f8f9fa; padding: 20px; text-align: center; color: #666; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Annulation de Réservation</h1>
        </div>
        <div class="content">
            <p>Bonjour <strong>{$booking['customer_name']}</strong>,</p>
            <div class="alert-box">
                <h3 style="margin-top: 0;">❌ Réservation Annulée</h3>
                <p>Votre réservation <strong>{$booking['confirmation_code']}</strong> a été annulée.</p>
                $reasonText
            </div>
            <p><strong>Détails de la réservation annulée :</strong></p>
            <ul>
                <li>Activité : {$booking['game_name']}</li>
                <li>Date : {$bookingDate}</li>
                <li>Horaire : {$startTime}</li>
            </ul>
            <p>Pour toute question, n'hésitez pas à nous contacter.</p>
            <p>Cordialement,<br><strong>L'équipe FunLab Tunisie</strong></p>
        </div>
        <div class="footer">
            <p>FunLab Tunisie | Tél : +216 XX XXX XXX | Email : contact@funlab.tn</p>
        </div>
    </div>
</body>
</html>
HTML;
    }
}
