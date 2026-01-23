<?php

namespace Tests\Unit;

use CodeIgniter\Test\CIUnitTestCase;
use App\Services\AvailabilityService;
use App\Models\BookingModel;
use App\Models\RoomModel;
use App\Models\GameModel;
use App\Models\ClosureModel;

/**
 * AvailabilityServiceTest
 * 
 * Tests unitaires pour le service de disponibilité.
 * Ces tests vérifient le bon fonctionnement de la logique métier critique.
 * 
 * @package Tests\Unit
 */
class AvailabilityServiceTest extends CIUnitTestCase
{
    protected $availabilityService;
    protected $bookingModel;
    protected $roomModel;
    protected $gameModel;
    protected $closureModel;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->availabilityService = new AvailabilityService();
        $this->bookingModel = new BookingModel();
        $this->roomModel = new RoomModel();
        $this->gameModel = new GameModel();
        $this->closureModel = new ClosureModel();
    }

    /**
     * Test : Génération des créneaux pour un jeu de 60 minutes
     */
    public function testGeneratePossibleSlotsFor60MinuteGame()
    {
        // Utiliser la réflexion pour accéder à la méthode privée
        $reflection = new \ReflectionClass($this->availabilityService);
        $method = $reflection->getMethod('generatePossibleSlots');
        $method->setAccessible(true);

        $date = date('Y-m-d', strtotime('+1 day'));
        $slots = $method->invoke($this->availabilityService, 60, $date);

        // Vérifier que des créneaux sont générés
        $this->assertIsArray($slots);
        $this->assertNotEmpty($slots);

        // Vérifier le premier créneau
        $this->assertEquals('09:00:00', $slots[0]['start']);
        $this->assertEquals('10:00:00', $slots[0]['end']);

        // Vérifier qu'aucun créneau ne dépasse 22:00
        foreach ($slots as $slot) {
            $endTime = strtotime($slot['end']);
            $closingTime = strtotime('22:00:00');
            $this->assertLessThanOrEqual($closingTime, $endTime);
        }
    }

    /**
     * Test : Génération des créneaux pour un jeu de 30 minutes
     */
    public function testGeneratePossibleSlotsFor30MinuteGame()
    {
        $reflection = new \ReflectionClass($this->availabilityService);
        $method = $reflection->getMethod('generatePossibleSlots');
        $method->setAccessible(true);

        $date = date('Y-m-d', strtotime('+1 day'));
        $slots = $method->invoke($this->availabilityService, 30, $date);

        // Vérifier que le nombre de créneaux est correct
        // De 09:00 à 22:00 avec un jeu de 30 min et incrément de 30 min
        // Dernier créneau possible : 21:30-22:00
        $expectedMinSlots = 25; // Au moins 25 créneaux
        $this->assertGreaterThanOrEqual($expectedMinSlots, count($slots));
    }

    /**
     * Test : Vérification d'un créneau libre
     */
    public function testIsSlotFreeWithNoConflicts()
    {
        $roomId = 1;
        $date = date('Y-m-d', strtotime('+7 days')); // Date future sans réservations
        $startTime = '10:00:00';
        $endTime = '11:00:00';

        $result = $this->availabilityService->isSlotFree($roomId, $date, $startTime, $endTime);

        $this->assertTrue($result, 'Le créneau devrait être libre');
    }

    /**
     * Test : Détection de conflit - Chevauchement complet
     */
    public function testIsSlotFreeDetectsOverlap()
    {
        // Ce test nécessite une réservation existante dans la DB
        // Assurez-vous d'avoir des données de test ou utilisez une DB de test

        // Créer une réservation test
        $testBooking = [
            'room_id' => 1,
            'game_id' => 1,
            'booking_date' => date('Y-m-d', strtotime('+1 day')),
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'customer_name' => 'Test User',
            'customer_email' => 'test@test.com',
            'customer_phone' => '1234567890',
            'num_players' => 2,
            'total_price' => 50.00,
            'status' => 'confirmed',
            'confirmation_code' => 'TEST001'
        ];

        $bookingId = $this->bookingModel->insert($testBooking);

        // Tester un créneau qui chevauche
        $result = $this->availabilityService->isSlotFree(
            1,
            $testBooking['booking_date'],
            '10:30:00', // Commence pendant la réservation existante
            '11:30:00'
        );

        $this->assertFalse($result, 'Le créneau devrait être occupé (conflit détecté)');

        // Nettoyer
        $this->bookingModel->delete($bookingId);
    }

    /**
     * Test : Vérification des horaires d'ouverture
     */
    public function testIsWithinOpeningHours()
    {
        $reflection = new \ReflectionClass($this->availabilityService);
        $method = $reflection->getMethod('isWithinOpeningHours');
        $method->setAccessible(true);

        // Cas valides
        $this->assertTrue($method->invoke($this->availabilityService, '09:00:00', '10:00:00'));
        $this->assertTrue($method->invoke($this->availabilityService, '21:00:00', '22:00:00'));

        // Cas invalides
        $this->assertFalse($method->invoke($this->availabilityService, '08:00:00', '09:00:00')); // Avant ouverture
        $this->assertFalse($method->invoke($this->availabilityService, '21:30:00', '22:30:00')); // Après fermeture
        $this->assertFalse($method->invoke($this->availabilityService, '15:00:00', '14:00:00')); // Fin avant début
    }

    /**
     * Test : Validation de date
     */
    public function testIsValidDate()
    {
        $reflection = new \ReflectionClass($this->availabilityService);
        $method = $reflection->getMethod('isValidDate');
        $method->setAccessible(true);

        // Dates valides
        $this->assertTrue($method->invoke($this->availabilityService, '2026-01-25'));
        $this->assertTrue($method->invoke($this->availabilityService, '2026-12-31'));

        // Dates invalides
        $this->assertFalse($method->invoke($this->availabilityService, '2026-13-01')); // Mois invalide
        $this->assertFalse($method->invoke($this->availabilityService, '25-01-2026')); // Format incorrect
        $this->assertFalse($method->invoke($this->availabilityService, 'invalid')); // Texte
    }

    /**
     * Test : Vérification de fermeture globale
     */
    public function testIsClosedGlobal()
    {
        // Créer une fermeture globale test
        $closureDate = date('Y-m-d', strtotime('+10 days'));
        $testClosure = [
            'closure_date' => $closureDate,
            'all_rooms' => 1,
            'reason' => 'Test - Fermeture globale'
        ];

        $closureId = $this->closureModel->insert($testClosure);

        // Vérifier que la fermeture est détectée
        $result = $this->availabilityService->isClosed($closureDate);

        $this->assertTrue($result, 'La fermeture globale devrait être détectée');

        // Nettoyer
        $this->closureModel->delete($closureId);
    }

    /**
     * Test : Vérification de fermeture par salle
     */
    public function testIsClosedForSpecificRoom()
    {
        $closureDate = date('Y-m-d', strtotime('+15 days'));
        $testClosure = [
            'room_id' => 1,
            'closure_date' => $closureDate,
            'all_rooms' => 0,
            'reason' => 'Test - Maintenance salle 1'
        ];

        $closureId = $this->closureModel->insert($testClosure);

        // Vérifier que la fermeture est détectée pour cette salle
        $result = $this->availabilityService->isClosed($closureDate, 1);
        $this->assertTrue($result, 'La fermeture de la salle 1 devrait être détectée');

        // Vérifier qu'une autre salle n'est pas affectée
        $result2 = $this->availabilityService->isClosed($closureDate, 2);
        $this->assertFalse($result2, 'La salle 2 ne devrait pas être fermée');

        // Nettoyer
        $this->closureModel->delete($closureId);
    }

    /**
     * Test : Vérification complète de disponibilité
     */
    public function testCheckSlotAvailabilityFullValidation()
    {
        $futureDate = date('Y-m-d', strtotime('+3 days'));
        
        // Test avec des données valides
        $result = $this->availabilityService->checkSlotAvailability(
            1,      // room_id
            1,      // game_id
            $futureDate,
            '14:00:00',
            '15:00:00'
        );

        $this->assertIsArray($result);
        $this->assertArrayHasKey('available', $result);
        $this->assertArrayHasKey('message', $result);
    }

    /**
     * Test : Refus de réservation dans le passé
     */
    public function testCheckSlotAvailabilityRejectsPastDate()
    {
        $pastDate = date('Y-m-d', strtotime('-1 day'));
        
        $result = $this->availabilityService->checkSlotAvailability(
            1,
            1,
            $pastDate,
            '14:00:00',
            '15:00:00'
        );

        $this->assertFalse($result['available']);
        $this->assertStringContainsString('passé', $result['message']);
    }

    /**
     * Test : Refus de créneaux hors horaires
     */
    public function testCheckSlotAvailabilityRejectsOutOfHours()
    {
        $futureDate = date('Y-m-d', strtotime('+1 day'));
        
        $result = $this->availabilityService->checkSlotAvailability(
            1,
            1,
            $futureDate,
            '22:30:00', // Après la fermeture
            '23:30:00'
        );

        $this->assertFalse($result['available']);
        $this->assertStringContainsString('ouverture', $result['message']);
    }

    /**
     * Test : Récupération des salles pour un jeu
     */
    public function testGetRoomsForGame()
    {
        // Assumer que le jeu ID 1 a des salles associées
        $rooms = $this->availabilityService->getRoomsForGame(1);

        $this->assertIsArray($rooms);
        // Si vous avez des données de test, vérifier que des salles sont retournées
        // $this->assertNotEmpty($rooms);
    }

    /**
     * Test : Récupération des créneaux occupés
     */
    public function testGetOccupiedSlots()
    {
        $date = date('Y-m-d', strtotime('+1 day'));
        
        $occupiedSlots = $this->availabilityService->getOccupiedSlots(1, $date);

        $this->assertIsArray($occupiedSlots);
        // Les créneaux occupés peuvent être vides si aucune réservation
    }

    /**
     * Test de performance : Génération de créneaux (doit être rapide)
     */
    public function testPerformanceGenerateSlots()
    {
        $startTime = microtime(true);
        
        $reflection = new \ReflectionClass($this->availabilityService);
        $method = $reflection->getMethod('generatePossibleSlots');
        $method->setAccessible(true);

        $date = date('Y-m-d', strtotime('+1 day'));
        $slots = $method->invoke($this->availabilityService, 60, $date);

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        // La génération doit prendre moins de 100ms
        $this->assertLessThan(0.1, $executionTime, 'La génération des créneaux est trop lente');
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}
