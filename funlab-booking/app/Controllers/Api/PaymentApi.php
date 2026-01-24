<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Services\PaymentService;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * API de gestion des paiements
 */
class PaymentApi extends BaseController
{
    protected $paymentService;

    public function __construct()
    {
        $this->paymentService = new PaymentService();
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
    }

    /**
     * OPTIONS pour CORS
     */
    public function options()
    {
        return $this->response->setStatusCode(200);
    }

    /**
     * Calculer le total d'une réservation
     * POST /api/payment/calculate
     */
    public function calculate()
    {
        try {
            $data = $this->request->getJSON(true);

            if (!isset($data['game_id'], $data['num_players'])) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Données manquantes'
                ])->setStatusCode(400);
            }

            $pricing = $this->paymentService->calculateBookingTotal($data);

            return $this->response->setJSON([
                'success' => true,
                'pricing' => $pricing
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    /**
     * Valider un code promo
     * POST /api/payment/validate-promo
     */
    public function validatePromo()
    {
        try {
            $data = $this->request->getJSON(true);
            $code = $data['code'] ?? '';
            $subtotal = $data['subtotal'] ?? 0;

            if (empty($code)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Code promo requis'
                ])->setStatusCode(400);
            }

            $discount = $this->paymentService->calculatePromoDiscount($code, $subtotal);

            if ($discount > 0) {
                return $this->response->setJSON([
                    'success' => true,
                    'discount' => $discount,
                    'message' => "Code promo appliqué : -{$discount} TND"
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Code promo invalide ou expiré'
                ])->setStatusCode(400);
            }

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    /**
     * Créer un paiement Stripe
     * POST /api/payment/stripe/create
     */
    public function createStripePayment()
    {
        try {
            $data = $this->request->getJSON(true);

            if (!isset($data['booking_id'], $data['amount'])) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Données manquantes'
                ])->setStatusCode(400);
            }

            $paymentType = $data['payment_type'] ?? 'full';
            $result = $this->paymentService->createStripePayment(
                $data['booking_id'],
                $data['amount'],
                $paymentType
            );

            return $this->response->setJSON($result);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    /**
     * Webhook Stripe
     * POST /api/payment/stripe/webhook
     */
    public function stripeWebhook()
    {
        \Stripe\Stripe::setApiKey(getenv('STRIPE_SECRET_KEY'));
        $endpoint_secret = getenv('STRIPE_WEBHOOK_SECRET');

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sig_header, $endpoint_secret);

            // Gérer l'événement
            switch ($event->type) {
                case 'payment_intent.succeeded':
                    $paymentIntent = $event->data->object;
                    $this->paymentService->confirmStripePayment($paymentIntent->id);
                    log_message('info', 'Payment succeeded: ' . $paymentIntent->id);
                    break;

                case 'payment_intent.payment_failed':
                    $paymentIntent = $event->data->object;
                    log_message('error', 'Payment failed: ' . $paymentIntent->id);
                    break;

                default:
                    log_message('info', 'Unhandled event type: ' . $event->type);
            }

            return $this->response->setJSON(['success' => true]);

        } catch (\UnexpectedValueException $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid payload'
            ])->setStatusCode(400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid signature'
            ])->setStatusCode(400);
        }
    }

    /**
     * Créer un paiement sur place
     * POST /api/payment/onsite
     */
    public function createOnsitePayment()
    {
        try {
            $data = $this->request->getJSON(true);

            if (!isset($data['booking_id'], $data['amount'])) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Données manquantes'
                ])->setStatusCode(400);
            }

            $paymentType = $data['payment_type'] ?? 'full';
            $paymentId = $this->paymentService->createCashPayment(
                $data['booking_id'],
                $data['amount'],
                $paymentType
            );

            return $this->response->setJSON([
                'success' => true,
                'payment_id' => $paymentId,
                'message' => 'Paiement sur place enregistré'
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    /**
     * Confirmer un paiement cash
     * POST /api/payment/confirm/{id}
     */
    public function confirmPayment($id)
    {
        try {
            if (!session()->get('isStaff')) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Non autorisé'
                ])->setStatusCode(403);
            }

            $success = $this->paymentService->confirmCashPayment($id);

            if ($success) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Paiement confirmé'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Impossible de confirmer le paiement'
                ])->setStatusCode(400);
            }

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    /**
     * Rembourser un paiement
     * POST /api/payment/refund/{id}
     */
    public function refund($id)
    {
        try {
            if (!session()->get('isAdmin')) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Non autorisé'
                ])->setStatusCode(403);
            }

            $data = $this->request->getJSON(true);
            $amount = $data['amount'] ?? 0;
            $reason = $data['reason'] ?? '';

            if ($amount <= 0) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Montant invalide'
                ])->setStatusCode(400);
            }

            $success = $this->paymentService->refundPayment($id, $amount, $reason);

            if ($success) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Remboursement effectué'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Impossible d\'effectuer le remboursement'
                ])->setStatusCode(400);
            }

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    /**
     * Historique des paiements d'un client
     * GET /api/payment/history
     */
    public function history()
    {
        try {
            $userId = session()->get('userId');

            if (!$userId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Non authentifié'
                ])->setStatusCode(401);
            }

            $db = \Config\Database::connect();
            $payments = $db->table('payments')
                ->select('payments.*, bookings.booking_date, bookings.customer_name, games.name as game_name')
                ->join('bookings', 'bookings.id = payments.booking_id')
                ->join('games', 'games.id = bookings.game_id')
                ->where('bookings.user_id', $userId)
                ->orderBy('payments.created_at', 'DESC')
                ->get()
                ->getResultArray();

            return $this->response->setJSON([
                'success' => true,
                'payments' => $payments
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    /**
     * Générer une facture
     * POST /api/payment/invoice/generate
     */
    public function generateInvoice()
    {
        try {
            $data = $this->request->getJSON(true);
            $bookingId = $data['booking_id'] ?? 0;

            if (!$bookingId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'ID de réservation requis'
                ])->setStatusCode(400);
            }

            $invoiceId = $this->paymentService->generateInvoice($bookingId);

            return $this->response->setJSON([
                'success' => true,
                'invoice_id' => $invoiceId,
                'message' => 'Facture générée'
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ])->setStatusCode(500);
        }
    }
}
