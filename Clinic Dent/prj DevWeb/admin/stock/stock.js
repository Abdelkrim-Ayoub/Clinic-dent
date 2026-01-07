document.addEventListener('DOMContentLoaded', function() {
    const addProductButton = document.getElementById('add-product-btn');
    const productModal = document.getElementById('product-modal');
    const closeModalButton = document.getElementById('close-modal-btn');
    const cancelModalButton = document.getElementById('cancel-modal-btn');
    const productForm = document.getElementById('product-form');
    const productTableBody = document.getElementById('product-table-body');
    let editingProductId = null; // To track the product being edited

    // Show product modal
    addProductButton.addEventListener('click', function() {
        editingProductId = null; // Reset the editing ID for adding new product
        document.getElementById('nom').value = '';
        document.getElementById('quantite').value = '';
        document.getElementById('exp_date').value = '';
        productModal.classList.remove('hidden');
    });

    // Close the modal
    closeModalButton.addEventListener('click', function() {
        productModal.classList.add('hidden');
    });

    cancelModalButton.addEventListener('click', function() {
        productModal.classList.add('hidden');
    });

    // Add product form submission
    productForm.addEventListener('submit', function(event) {
        event.preventDefault();

        const productData = {
            nom: document.getElementById('nom').value,
            quantite: document.getElementById('quantite').value,
            exp_date: document.getElementById('exp_date').value
        };

        let requestMethod = 'POST';
        let url = 'backend.php';

        if (editingProductId) {
            requestMethod = 'PUT'; // If we are editing, we use PUT
            productData.id = editingProductId;
            url = 'backend.php?id=' + editingProductId; // Send the product ID for editing
        }

        fetch(url, {
            method: requestMethod,
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(productData)
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            if (data.message === 'Produit ajouté avec succès.' || data.message === 'Produit modifié avec succès.') {
                loadProducts();
                productModal.classList.add('hidden');
            }
        })
        .catch(error => console.error('Error:', error));
    });

    // Fetch and display products
    function loadProducts() {
        fetch('backend.php', {
            method: 'GET'
        })
        .then(response => response.json())
        .then(products => {
            productTableBody.innerHTML = '';
            products.forEach(product => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${product.nom}</td>
                    <td>${product.quantite}</td>
                    <td>${product.exp_date}</td>
                    <td><button class="edit-btn" data-id="${product.id_item}">Edit</button></td>
                    <td><button class="remove-btn" data-id="${product.id_item}">Remove</button></td>
                `;
                productTableBody.appendChild(row);
            });

            // Handle product removal
            document.querySelectorAll('.remove-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const productId = this.getAttribute('data-id');
                    removeProduct(productId);
                });
            });

            // Handle product edit
            document.querySelectorAll('.edit-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const productId = this.getAttribute('data-id');
                    editProduct(productId);
                });
            });
        });
    }

    // Edit product
    function editProduct(id) {
        fetch('backend.php?id=' + id, {
            method: 'GET'
        })
        .then(response => response.json())
        .then(product => {
            document.getElementById('nom').value = product.nom;
            document.getElementById('quantite').value = product.quantite;
            document.getElementById('exp_date').value = product.exp_date;
            editingProductId = product.id_item; // Set the product ID for editing
            productModal.classList.remove('hidden'); // Show the modal
        });
    }

    // Remove product
    function removeProduct(id) {
        fetch(`backend.php?id=${id}`, {
            method: 'DELETE'
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            loadProducts();
        })
        .catch(error => console.error('Error:', error));
    }

    // Initial load of products
    loadProducts();
});