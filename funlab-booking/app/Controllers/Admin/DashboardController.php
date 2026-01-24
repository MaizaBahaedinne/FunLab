<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class DashboardController extends BaseController
{
    protected $bookingModel;
    protected $gameModel;
    protected $roomModel;
    
    public function __construct()
    {
        $this->bookingModel = model('BookingModel');
        $this->gameModel = model('GameModel');
        $this->roomModel = model('RoomModel');
    }
    
    public function index()
    {
        return view('admin/dashboard/index');
    }

    public function stats()
    {
        try {
            $today = date('Y-m-d');
            $db = \Config\Database::connect();
            
            // Statistiques du jour
            $todayBookings = $this->bookingModel
                ->where('booking_date', $today)
                ->whereIn('status', ['confirmed', 'pending', 'in_progress'])
                ->countAllResults();
            
            $activeBookings = $this->bookingModel
                ->where('booking_date', $today)
                ->where('status', 'in_progress')
                ->countAllResults();
            
            $completedToday = $this->bookingModel
                ->where('booking_date', $today)
                ->where('status', 'completed')
                ->countAllResults();
            
            // Revenus du jour
            $revenueToday = $db->table('bookings')
                ->selectSum('total_price')
                ->where('booking_date', $today)
                ->whereIn('status', ['confirmed', 'completed', 'in_progress'])
                ->get()
                ->getRow()
                ->total_price ?? 0;
            
            // Réservations des 7 derniers jours
            $last7Days = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = date('Y-m-d', strtotime("-$i days"));
                $count = $this->bookingModel
                    ->where('booking_date', $date)
                    ->whereIn('status', ['confirmed', 'completed', 'in_progress'])
                    ->countAllResults();
                $last7Days[] = [
                    'date' => date('D d', strtotime($date)),
                    'count' => $count
                ];
            }
            
            // Répartition par jeu
            $gamesStats = $db->table('bookings')
                ->select('games.name, COUNT(bookings.id) as count')
                ->join('games', 'games.id = bookings.game_id')
                ->where('bookings.booking_date >=', date('Y-m-d', strtotime('-30 days')))
                ->whereIn('bookings.status', ['confirmed', 'completed', 'in_progress'])
                ->groupBy('games.id')
                ->orderBy('count', 'DESC')
                ->limit(5)
                ->get()
                ->getResultArray();
            
            // Réservations récentes
            $recentBookings = $this->bookingModel
                ->select('bookings.*, games.name as game_name')
                ->join('games', 'games.id = bookings.game_id')
                ->orderBy('bookings.created_at', 'DESC')
                ->limit(10)
                ->findAll();
            
            return $this->response->setJSON([
                'status' => 'success',
                'data' => [
                    'today' => $todayBookings,
                    'active' => $activeBookings,
                    'completed' => $completedToday,
                    'revenue' => round($revenueToday, 2),
                    'last7Days' => $last7Days,
                    'gamesStats' => $gamesStats,
                    'recentBookings' => $recentBookings
                ]
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Erreur stats dashboard: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Erreur lors du chargement des statistiques'
            ]);
        }
    }
}
