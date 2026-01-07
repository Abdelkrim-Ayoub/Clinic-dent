<?php
// stock.php
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WEBDENT - Liste de produits</title>
    <link rel="stylesheet" href="stock.css">
</head>
<body>
  
    <nav class="navbar">
        <h1>WEBDENT</h1>
        <ul>
            <li><a href="../admin/adm.php">Accueil</a></li>
            <li><a href="../calendrier/calendrier.php">Calendrier</a></li>
            <li><a href="#">Prothése</a></li>
            <li><a href="stock.php">Stock</a></li>
        </ul>
    </nav>

    <div class="main-content">
        <div class="header">
            <h2>Liste de produits</h2>
            <button id="add-product-btn" class="btn-primary">Ajouter un produit +</button>
        </div>

        <table id="product-table" class="product-table">
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Quantité</th>
                    <th>Date d'expiration</th>
                    <th>Actions</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="product-table-body">
                <!-- Product rows will be dynamically inserted here -->
            </tbody>
        </table>
    </div>

    <!-- Formulaire Modal -->
    <div id="product-modal" class="modal hidden">
        <div class="modal-content">
            <span id="close-modal-btn" class="close-btn">&times;</span>
            <h2 id="modal-title">Ajouter un produit</h2>
            <form id="product-form">
                <input type="hidden" id="product-id"> <!-- Hidden input for the product ID -->

                <div class="form-group">
                    <label for="nom">Nom du produit :</label>
                    <input type="text" id="nom" name="nom" required>
                </div>
                <div class="form-group">
                    <label for="quantite">Quantité :</label>
                    <input type="number" id="quantite" name="quantite" required>
                </div>
                <div class="form-group">
                    <label for="exp_date">Date d'expiration :</label>
                    <input type="date" id="exp_date" name="exp_date" required>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-primary">Ajouter</button>
                    <button type="button" id="cancel-modal-btn" class="btn-secondary">Annuler</button>
                </div>
            </form>
        </div>
    </div>

    <script src="stock.js"></script>
</body>
</html>