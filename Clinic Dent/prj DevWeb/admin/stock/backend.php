<?php

header('Content-Type: application/json');

try {
    // Database connection using mysqli
    $servernom = "localhost";
    $usernom = "root";
    $password = "looserxbeep9999";
    $dbnom = "clinicdent";

    // Create connection
    $conn = new mysqli($servernom, $usernom, $password, $dbnom);

    // Check connection
    if ($conn->connect_error) {
        die(json_encode(['message' => 'Connection failed: ' . $conn->connect_error]));
    }

    // Get the stocks
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if (isset($_GET['id'])) {
            // Fetch a single stock item for editing
            $id = $_GET['id'];
            $query = "SELECT * FROM stock WHERE id_item = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $product = $result->fetch_assoc();

            echo json_encode($product);
        } else {
            // Fetch all stock items
            $query = "SELECT * FROM stock";
            $result = $conn->query($query);

            if ($result === false) {
                die(json_encode(['message' => 'Error fetching stock data.']));
            }

            $stocks = [];
            while ($row = $result->fetch_assoc()) {
                $stocks[] = $row;
            }

            echo json_encode($stocks);
        }
        exit;
    }

    // Add a stock
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['nom'], $data['quantite'], $data['exp_date'])) {
            echo json_encode(['message' => 'Données invalides.']);
            exit;
        }

        $nom = $data['nom'];
        $quantite = $data['quantite'];
        $exp_date = $data['exp_date'];

        $query = "INSERT INTO stock (nom, quantite, exp_date) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            die(json_encode(['message' => 'Error preparing statement: ' . $conn->error]));
        }

        $stmt->bind_param("sis", $nom, $quantite, $exp_date);

        if ($stmt->execute()) {
            echo json_encode(['message' => 'Stock ajouté avec succès.']);
        } else {
            echo json_encode(['message' => 'Error inserting stock: ' . $stmt->error]);
        }
        exit;
    }

    // Delete a stock
    if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        if (!isset($_GET['id'])) {
            echo json_encode(['message' => 'ID du stock manquant.']);
            exit;
        }

        $id = $_GET['id'];
        $query = "DELETE FROM stock WHERE id_item = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo json_encode(['message' => 'Stock supprimé avec succès.']);
        } else {
            echo json_encode(['message' => 'Error deleting stock: ' . $stmt->error]);
        }
        exit;
    }

    // Update a stock
    if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['id'], $data['nom'], $data['quantite'], $data['exp_date'])) {
            echo json_encode(['message' => 'Données invalides.']);
            exit;
        }

        $id = $data['id'];
        $nom = $data['nom'];
        $quantite = $data['quantite'];
        $exp_date = $data['exp_date'];

        $query = "UPDATE stock SET nom = ?, quantite = ?, exp_date = ? WHERE id_item = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sisi", $nom, $quantite, $exp_date, $id);

        if ($stmt->execute()) {
            echo json_encode(['message' => 'Stock modifié avec succès.']);
        } else {
            echo json_encode(['message' => 'Error updating stock: ' . $stmt->error]);
        }
        exit;
    }

} catch (Exception $e) {
    error_log('Erreur MySQLi: ' . $e->getMessage());
    echo json_encode(['message' => 'Erreur: ' . $e->getMessage()]);
}

?>