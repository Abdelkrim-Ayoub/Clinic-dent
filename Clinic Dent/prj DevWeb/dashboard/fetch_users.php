<?php
header('Content-Type: application/json');

// Database connection
$servername = "localhost";
$username = "root";
$password = "looserxbeep9999";
$dbname = "clinicdent";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode([]);
    exit;
}

// Prepare the SQL query based on role
$sql = "
   SELECT login.created_at, login.id_user, login.password, login.email, login.role, 
       COALESCE(patient.nom, doctor.nom, secretaire.nom) AS nom,
       COALESCE(patient.prenom, doctor.prenom, secretaire.prenom) AS prenom,
       COALESCE(patient.tel, doctor.tel, secretaire.tel) AS tel
FROM login
LEFT JOIN patient ON login.id_user = patient.id_user AND login.role = 'patient'
LEFT JOIN doctor ON login.id_user = doctor.id_user AND login.role = 'doctor'
LEFT JOIN secretaire ON login.id_user = secretaire.id_user AND login.role = 'secretaire'; ";
// Execute the query
$result = $conn->query($sql);

$users = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

echo json_encode($users);
?>