<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
    <script>
        async function fetchUsers() {
            const response = await fetch('fetch_users.php');
            const data = await response.json();
            const tbody = document.querySelector('tbody');
            tbody.innerHTML = '';

            if (data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="8">No users found.</td></tr>';
                return;
            }

            data.forEach(user => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${user.id_user}</td>
                    <td>${user.nom || ''}</td>
                    <td>${user.prenom || ''}</td>
                    <td>${user.tel || ''}</td>
                    <td>${user.email || ''}</td>
                    <td>
                        <form method="POST" action="edit_user.php" style="display: inline;">
                            <input type="hidden" name="user_id" value="${user.id_user}">
                            <select name="role">
                                <option value="patient" ${user.role === 'Patient' ? 'selected' : ''}>Patient</option>
                                <option value="doctor" ${user.role === 'Doctor' ? 'selected' : ''}>Doctor</option>
                                <option value="secretaire" ${user.role === 'Secretaire' ? 'selected' : ''}>Secretaire</option>
                            </select>
                            <input type="hidden" name="email" value="${user.email}">
                            <button type="submit" name="edit" class="edit-btn">Edit</button>
                        </form>
                    </td>
                    <td>
                        <form method="POST" action="edit_user.php" style="display: inline;">
                            <input type="hidden" name="email" value="${user.email}">
                            <button type="submit" name="delete" class="remove-btn">Remove</button>
                        </form>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        document.addEventListener('DOMContentLoaded', fetchUsers);
    </script>
</head>
<body>
    <header>
        <h1>Admin Dashboard</h1>
    </header>

    <div class="container">
        <h2>Liste des comptes</h2>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nom</th>
                    <th>Prenom</th>
                    <th>Tel</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr><td colspan="8">Loading...</td></tr>
            </tbody>
        </table>
    </div>
</body>
</html>