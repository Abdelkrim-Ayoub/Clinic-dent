<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "looserxbeep9999";
$dbname = "clinicdent";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Handle user deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $email = $_POST['email'];

    // Fetch the user's details using the email
    $userQuery = "SELECT * FROM login WHERE email = ?";
    $stmt = $conn->prepare($userQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $userResult = $stmt->get_result()->fetch_assoc();

    if ($userResult) {
        // Delete the user from the corresponding role table
        $role = $userResult['role'];

        $deleteRoleQuery = "DELETE FROM $role WHERE email = ?"; // Using email in the role table
        $deleteRoleStmt = $conn->prepare($deleteRoleQuery);
        $deleteRoleStmt->bind_param("s", $email);
        $deleteRoleStmt->execute();

        // Delete the user from the login table
        $deleteUserQuery = "DELETE FROM login WHERE email = ?"; // Using email in the login table
        $deleteUserStmt = $conn->prepare($deleteUserQuery);
        $deleteUserStmt->bind_param("s", $email);
        $deleteUserStmt->execute();
    }
}

// Handle user edit

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit'])) {
    $email = $_POST['email'];  // User email to be updated
    $new_role = $_POST['role'];  // New role selected

    // Fetch user details from the login table
    $userQuery = "SELECT * FROM login WHERE email = ?";  // Using email in login table
    $stmt = $conn->prepare($userQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $userResult = $stmt->get_result()->fetch_assoc();

    if ($userResult) {
        $id_user = $userResult['Id_user'];  // Use id_user instead of email
        $current_role = $userResult['role'];  // Get the current role

        // Transfer data from the old role table to the new role table
        switch ($current_role) {
            case 'Patient':
                $roleDataQuery = "SELECT * FROM patient WHERE id_user = ?";
                break;
            case 'Doctor':
                $roleDataQuery = "SELECT * FROM doctor WHERE id_user = ?";
                break;
            case 'Secretaire':
                $roleDataQuery = "SELECT * FROM secretaire WHERE id_user = ?";
                break;
            default:
                $roleDataQuery = "";
                break;
        }

        if ($roleDataQuery) {
            $stmtRoleData = $conn->prepare($roleDataQuery);
            $stmtRoleData->bind_param("i", $id_user);  // Use id_user (integer) for the query
            $stmtRoleData->execute();
            $roleData = $stmtRoleData->get_result()->fetch_assoc();

            if ($roleData) {
                // Insert the data into the new role table first
                $insertQuery = ''; // Initialize insertQuery variable
                switch ($new_role) {
                    case 'patient':
                        $insertQuery = "INSERT INTO patient (nom, prenom, tel, email, created_at, id_user) VALUES (?, ?, ?, ?, ?, ?)"; 
                        break;
                    case 'doctor':
                        $insertQuery = "INSERT INTO doctor (nom, prenom, tel, email, created_at, id_user) VALUES (?, ?, ?, ?, ?, ?)";
                        break;
                    case 'secretaire':
                        $insertQuery = "INSERT INTO secretaire (nom, prenom, tel, email, created_at, id_user) VALUES (?, ?, ?, ?, ?, ?)";
                        break;
                    default:
                        echo "Unknown role: $new_role";
                        break;
                }

                // If insert query is set, proceed with insertion
                if (!empty($insertQuery)) {
                    $stmtInsert = $conn->prepare($insertQuery);
                    $stmtInsert->bind_param(
                        "sssssi",
                        $roleData['Nom'],     // Ensure nom is uppercase
                        $roleData['Prenom'],  // Ensure prenom is uppercase
                        $roleData['Tel'],
                        $roleData['Email'],
                        $roleData['created_at'],
                        $id_user // Use id_user for insertion
                    );

                    if ($stmtInsert->execute()) {
                        // Only delete from the old table if the insert was successful
                        echo "User transferred successfully to the new role.";

                        // Delete the data from the old role table
                        $deleteRoleQuery = "DELETE FROM $current_role WHERE id_user = ?";
                        $stmtDeleteRole = $conn->prepare($deleteRoleQuery);
                        $stmtDeleteRole->bind_param("i", $id_user);  // Use id_user for deletion
                        $stmtDeleteRole->execute();

                        // Update the role in the login table
                        $updateRoleQuery = "UPDATE login SET role = ? WHERE id_user = ?";
                        $stmtUpdateRole = $conn->prepare($updateRoleQuery);
                        $stmtUpdateRole->bind_param("si", $new_role, $id_user);  // Use id_user for update
                        $stmtUpdateRole->execute();
                    } else {
                        echo "Error inserting data into new role table: " . $stmtInsert->error;
                    }
                }
            }
        }
    } else {
        // If no user was found
        echo "User with email $email not found.";
    }
}
?>