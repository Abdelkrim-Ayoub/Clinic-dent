<?php
header('Content-Type: application/json');

// Database connection
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "webdent";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['message' => 'Erreur de connexion à la base de données: ' . $conn->connect_error]));
}

// Handle GET request: Fetch appointments
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $query = "SELECT * FROM appointments";
    $result = $conn->query($query);

    if (!$result) {
        echo json_encode(['message' => 'Erreur lors de la récupération des rendez-vous: ' . $conn->error]);
        exit;
    }

    $events = [];
    while ($row = $result->fetch_assoc()) {
        $events[] = [
            'title' => $row['client_name'],  // Fix here: use the actual client_name field value
            'start' => $row['appointment_date'] . 'T' . $row['appointment_time'],
            'extendedProps' => [
                'doctor' => $row['doctor'],
                'service' => $row['service']
            ]
        ];
    }
    echo json_encode($events, JSON_PRETTY_PRINT);
    exit;
}

// Handle POST request: Add appointment
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate required fields
    if (
        empty($data['client_name']) ||
        empty($data['doctor']) ||
        empty($data['service']) ||
        empty($data['appointment_date']) ||
        empty($data['appointment_time'])
    ) {
        echo json_encode(['message' => 'Données manquantes. Veuillez remplir tous les champs.']);
        exit;
    }

    // Check availability
    $stmt = $conn->prepare("SELECT * FROM appointments WHERE appointment_date = ? AND appointment_time = ?");
    $stmt->bind_param("ss", $data['appointment_date'], $data['appointment_time']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(['message' => 'Ce créneau est déjà réservé.']);
        exit;
    }

    // Add appointment
    $stmt = $conn->prepare("INSERT INTO appointments (client_name, doctor, service, appointment_date, appointment_time) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param(
        "sssss",
        $data['client_name'],
        $data['doctor'],
        $data['service'],
        $data['appointment_date'],
        $data['appointment_time']
    );

    if ($stmt->execute()) {
        echo json_encode(['message' => 'Rendez-vous réservé avec succès !']);
    } else {
        echo json_encode(['message' => 'Erreur lors de la réservation: ' . $stmt->error]);
    }

    $stmt->close();
    exit;
}

// Close connection
$conn->close();
?>