<?php

namespace App\Services;

use App\Models\BookingModel;
use CodeIgniter\Database\ConnectionInterface;

/**
 * Service de gestion des paiements
 * 
 * Gère les paiements Stripe, cash, et génération de factures
 */
class PaymentService
{
    protected $db;
    protected $bookingModel;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->bookingModel = new BookingModel();
    }

    /**
     * Calculer le montant total d'une réservation
     */
    public function calculateBookingTotal(array $bookingData): array
    {
        $gameId = $bookingData['game_id'];
        $numParticipants = $bookingData['num_participants'];
        
        // Récupérer le prix du jeu
        $game = $this->db->table('games')->where('id', $gameId)->get()->getRowArray();
        
        if (!$game) {
            throw new \Exception('Jeu introuvable');
        }

        // Calcul du prix
        $basePrice = $game['price'] ?? 0;
        $pricePerPerson = $game['price_per_person'] ?? 0;
        
        $subtotal = $basePrice;
        if ($pricePerPerson > 0) {
            $subtotal = $pricePerPerson * $numParticipants;
        }

        // Appliquer code promo si présent
        $discount = 0;
        $promoCode = $bookingData['promo_code'] ?? null;
        
        if ($promoCode) {
            $discount = $this->calculatePromoDiscount($promoCode, $subtotal);
        }

        // TVA (19% en Tunisie)
        $taxRate = 0.19;
        $tax = ($subtotal - $discount) * $taxRate;
        
        $total = $subtotal - $discount + $tax;

        // Acompte si requis
        $depositRequired = $game['deposit_required'] ?? 0;
        $depositPercentage = $game['deposit_percentage'] ?? 30;
        $depositAmount = $depositRequired ? ($total * $depositPercentage / 100) : 0;

        return [
            'subtotal' => round($subtotal, 2),
            'discount' => round($discount, 2),
            'tax' => round($tax, 2),
            'tax_rate' => $taxRate,
            'total' => round($total, 2),
            'deposit_required' => (bool)$depositRequired,
            'deposit_amount' => round($depositAmount, 2),
            'remaining_amount' => round($total - $depositAmount, 2),
            'currency' => 'TND'
        ];
    }

    /**
     * Calculer la réduction d'un code promo
     */
    public function calculatePromoDiscount(string $code, float $subtotal): float
    {
        $promo = $this->db->table('promo_codes')
            ->where('code', $code)
            ->where('is_active', 1)
            ->get()
            ->getRowArray();

        if (!$promo) {
            return 0;
        }

        // Vérifier validité
        $now = date('Y-m-d H:i:s');
        if ($promo['valid_from'] && $promo['valid_from'] > $now) {
            return 0;
        }
        if ($promo['valid_until'] && $promo['valid_until'] < $now) {
            return 0;
        }

        // Vérifier montant minimum
        if ($promo['min_amount'] && $subtotal < $promo['min_amount']) {
            return 0;
        }

        // Vérifier limite d'utilisation
        if ($promo['usage_limit'] && $promo['usage_count'] >= $promo['usage_limit']) {
            return 0;
        }

        // Calculer réduction
        $discount = 0;
        if ($promo['discount_type'] === 'percentage') {
            $discount = $subtotal * ($promo['discount_value'] / 100);
            // Limiter à max_discount si défini
            if ($promo['max_discount'] && $discount > $promo['max_discount']) {
                $discount = $promo['max_discount'];
            }
        } else {
            $discount = $promo['discount_value'];
        }

        return round($discount, 2);
    }

    /**
     * Créer un paiement Stripe
     */
    public function createStripePayment(int $bookingId, float $amount, string $paymentType = 'full'): array
    {
        \Stripe\Stripe::setApiKey(getenv('STRIPE_SECRET_KEY'));

        $booking = $this->bookingModel->find($bookingId);
        
        if (!$booking) {
            throw new \Exception('Réservation introuvable');
        }

        try {
            // Créer PaymentIntent
            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => round($amount * 100), // Stripe utilise les centimes
                'currency' => 'tnd',
                'metadata' => [
                    'booking_id' => $bookingId,
                    'customer_name' => $booking['customer_name'],
                    'payment_type' => $paymentType
                ],
                'description' => "Réservation #{$bookingId} - {$booking['customer_name']}"
            ]);

            // Enregistrer le paiement
            $paymentId = $this->db->table('payments')->insert([
                'booking_id' => $bookingId,
                'customer_id' => $booking['user_id'] ?? null,
                'amount' => $amount,
                'currency' => 'TND',
                'payment_method' => 'stripe',
                'payment_type' => $paymentType,
                'status' => 'pending',
                'stripe_payment_intent' => $paymentIntent->id,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            return [
                'success' => true,
                'payment_id' => $paymentId,
                'client_secret' => $paymentIntent->client_secret,
                'payment_intent_id' => $paymentIntent->id
            ];

        } catch (\Exception $e) {
            log_message('error', 'Stripe Payment Error: ' . $e->getMessage());
            throw new \Exception('Erreur lors de la création du paiement: ' . $e->getMessage());
        }
    }

    /**
     * Confirmer un paiement Stripe
     */
    public function confirmStripePayment(string $paymentIntentId): bool
    {
        \Stripe\Stripe::setApiKey(getenv('STRIPE_SECRET_KEY'));

        try {
            $paymentIntent = \Stripe\PaymentIntent::retrieve($paymentIntentId);

            if ($paymentIntent->status === 'succeeded') {
                // Mettre à jour le paiement
                $this->db->table('payments')
                    ->where('stripe_payment_intent', $paymentIntentId)
                    ->update([
                        'status' => 'completed',
                        'stripe_charge_id' => $paymentIntent->charges->data[0]->id ?? null,
                        'paid_at' => date('Y-m-d H:i:s'),
                        'transaction_id' => $paymentIntent->id,
                        'metadata' => json_encode([
                            'receipt_url' => $paymentIntent->charges->data[0]->receipt_url ?? null
                        ])
                    ]);

                // Récupérer le payment pour mettre à jour la réservation
                $payment = $this->db->table('payments')
                    ->where('stripe_payment_intent', $paymentIntentId)
                    ->get()
                    ->getRowArray();

                if ($payment) {
                    $this->updateBookingPaymentStatus($payment['booking_id'], $payment['amount']);
                }

                return true;
            }

            return false;

        } catch (\Exception $e) {
            log_message('error', 'Stripe Confirmation Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Créer un paiement cash/sur place
     */
    public function createCashPayment(int $bookingId, float $amount, string $paymentType = 'full'): int
    {
        $booking = $this->bookingModel->find($bookingId);
        
        if (!$booking) {
            throw new \Exception('Réservation introuvable');
        }

        $paymentId = $this->db->table('payments')->insert([
            'booking_id' => $bookingId,
            'customer_id' => $booking['user_id'] ?? null,
            'amount' => $amount,
            'currency' => 'TND',
            'payment_method' => 'cash',
            'payment_type' => $paymentType,
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return $paymentId;
    }

    /**
     * Confirmer un paiement cash
     */
    public function confirmCashPayment(int $paymentId): bool
    {
        $payment = $this->db->table('payments')->where('id', $paymentId)->get()->getRowArray();
        
        if (!$payment) {
            return false;
        }

        $this->db->table('payments')
            ->where('id', $paymentId)
            ->update([
                'status' => 'completed',
                'paid_at' => date('Y-m-d H:i:s'),
                'transaction_id' => 'CASH-' . date('YmdHis') . '-' . $paymentId
            ]);

        $this->updateBookingPaymentStatus($payment['booking_id'], $payment['amount']);

        return true;
    }

    /**
     * Mettre à jour le statut de paiement d'une réservation
     */
    protected function updateBookingPaymentStatus(int $bookingId, float $paidAmount): void
    {
        $booking = $this->bookingModel->find($bookingId);
        
        if (!$booking) {
            return;
        }

        $totalPrice = $booking['total_price'];
        $currentPaid = $booking['paid_amount'] ?? 0;
        $newPaid = $currentPaid + $paidAmount;
        $remaining = $totalPrice - $newPaid;

        $paymentStatus = 'unpaid';
        if ($newPaid >= $totalPrice) {
            $paymentStatus = 'paid';
            $remaining = 0;
        } elseif ($newPaid > 0) {
            $paymentStatus = 'partial';
        }

        $this->bookingModel->update($bookingId, [
            'paid_amount' => $newPaid,
            'remaining_amount' => $remaining,
            'payment_status' => $paymentStatus
        ]);
    }

    /**
     * Générer une facture
     */
    public function generateInvoice(int $bookingId): int
    {
        $booking = $this->bookingModel
            ->select('bookings.*, games.name as game_name, games.price, rooms.name as room_name, users.email as customer_email')
            ->join('games', 'games.id = bookings.game_id')
            ->join('rooms', 'rooms.id = bookings.room_id')
            ->join('users', 'users.id = bookings.user_id', 'left')
            ->find($bookingId);

        if (!$booking) {
            throw new \Exception('Réservation introuvable');
        }

        // Générer numéro de facture
        $year = date('Y');
        $lastInvoice = $this->db->table('invoices')
            ->like('invoice_number', "INV-{$year}-", 'after')
            ->orderBy('id', 'DESC')
            ->limit(1)
            ->get()
            ->getRowArray();

        $nextNumber = 1;
        if ($lastInvoice) {
            $parts = explode('-', $lastInvoice['invoice_number']);
            $nextNumber = intval($parts[2] ?? 0) + 1;
        }

        $invoiceNumber = sprintf('INV-%s-%05d', $year, $nextNumber);

        // Items de la facture
        $items = [
            [
                'description' => $booking['game_name'],
                'quantity' => $booking['num_participants'],
                'unit_price' => $booking['price'],
                'total' => $booking['total_price']
            ]
        ];

        $invoiceId = $this->db->table('invoices')->insert([
            'invoice_number' => $invoiceNumber,
            'booking_id' => $bookingId,
            'customer_id' => $booking['user_id'],
            'customer_name' => $booking['customer_name'],
            'customer_email' => $booking['customer_email'] ?? $booking['customer_email'],
            'customer_phone' => $booking['customer_phone'],
            'amount_subtotal' => $booking['total_price'] / 1.19, // Sans TVA
            'amount_tax' => $booking['total_price'] - ($booking['total_price'] / 1.19),
            'amount_discount' => $booking['discount_amount'] ?? 0,
            'amount_total' => $booking['total_price'],
            'tax_rate' => 19.00,
            'items' => json_encode($items),
            'status' => $booking['payment_status'] === 'paid' ? 'paid' : 'sent',
            'issued_at' => date('Y-m-d H:i:s'),
            'due_at' => $booking['booking_date'] . ' ' . $booking['start_time'],
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return $invoiceId;
    }

    /**
     * Rembourser un paiement
     */
    public function refundPayment(int $paymentId, float $amount, string $reason = ''): bool
    {
        $payment = $this->db->table('payments')->where('id', $paymentId)->get()->getRowArray();
        
        if (!$payment || $payment['status'] !== 'completed') {
            return false;
        }

        try {
            if ($payment['payment_method'] === 'stripe') {
                \Stripe\Stripe::setApiKey(getenv('STRIPE_SECRET_KEY'));
                
                $refund = \Stripe\Refund::create([
                    'charge' => $payment['stripe_charge_id'],
                    'amount' => round($amount * 100)
                ]);
            }

            $this->db->table('payments')
                ->where('id', $paymentId)
                ->update([
                    'status' => 'refunded',
                    'refunded_at' => date('Y-m-d H:i:s'),
                    'refund_amount' => $amount,
                    'refund_reason' => $reason
                ]);

            // Mettre à jour la réservation
            $this->bookingModel->update($payment['booking_id'], [
                'payment_status' => 'refunded',
                'status' => 'cancelled'
            ]);

            return true;

        } catch (\Exception $e) {
            log_message('error', 'Refund Error: ' . $e->getMessage());
            return false;
        }
    }
}
