<?php
header('Content-Type: application/json');

// Database connection
$servername = "localhost";
$username = "root";
$password = "looserxbeep9999";
$dbname = "clinicdent";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die(json_encode(['error' => 'Connection failed: ' . $e->getMessage()]));
}

// Handle POST request (Add Appointment)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['client_name'], $data['doctor'], $data['service'], $data['appointment_date'], $data['appointment_time'])) {
        try {
            $query = $pdo->prepare("INSERT INTO appointments (client_name, doctor, service, appointment_date, appointment_time) VALUES (:client_name, :doctor, :service, :appointment_date, :appointment_time)");
            $query->execute([
                ':client_name' => $data['client_name'],
                ':doctor' => $data['doctor'],
                ':service' => $data['service'],
                ':appointment_date' => $data['appointment_date'],
                ':appointment_time' => $data['appointment_time']
            ]);
            echo json_encode(['message' => 'Rendez-vous réservé avec succès !']);
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Erreur SQL : ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['error' => 'Données manquantes']);
    }
    exit;
}

// Handle GET request (Fetch Appointments)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $stmt = $pdo->query("SELECT * FROM appointments");
        $events = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $events[] = [
                'title' => $row['client_name'],
                'start' => $row['appointment_date'] . 'T' . $row['appointment_time'],
                'extendedProps' => [
                    'doctor' => $row['doctor'],
                    'service' => $row['service']
                ]
            ];
        }
        echo json_encode($events);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Erreur SQL : ' . $e->getMessage()]);
    }
    exit;
}
?>