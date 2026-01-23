<?php

if (!function_exists('format_booking_date')) {
    /**
     * Formate une date de réservation
     */
    function format_booking_date($date, $format = 'd/m/Y')
    {
        return date($format, strtotime($date));
    }
}

if (!function_exists('format_time_slot')) {
    /**
     * Formate un créneau horaire
     */
    function format_time_slot($startTime, $endTime)
    {
        return date('H:i', strtotime($startTime)) . ' - ' . date('H:i', strtotime($endTime));
    }
}

if (!function_exists('get_booking_status_badge')) {
    /**
     * Retourne le badge HTML pour le statut d'une réservation
     */
    function get_booking_status_badge($status)
    {
        $badges = [
            'pending'   => '<span class="badge bg-warning">En attente</span>',
            'confirmed' => '<span class="badge bg-success">Confirmée</span>',
            'cancelled' => '<span class="badge bg-danger">Annulée</span>',
            'completed' => '<span class="badge bg-info">Terminée</span>',
        ];

        return $badges[$status] ?? '<span class="badge bg-secondary">Inconnu</span>';
    }
}

if (!function_exists('calculate_duration')) {
    /**
     * Calcule la durée entre deux horaires
     */
    function calculate_duration($startTime, $endTime)
    {
        $start = strtotime($startTime);
        $end = strtotime($endTime);
        $duration = ($end - $start) / 60; // en minutes
        
        return $duration;
    }
}

if (!function_exists('format_price')) {
    /**
     * Formate un prix
     */
    function format_price($price, $currency = '€')
    {
        return number_format($price, 2, ',', ' ') . ' ' . $currency;
    }
}

if (!function_exists('is_time_available')) {
    /**
     * Vérifie si un créneau horaire est disponible
     */
    function is_time_available($roomId, $date, $startTime, $endTime)
    {
        // Cette fonction devrait appeler le service de disponibilité
        return true;
    }
}
