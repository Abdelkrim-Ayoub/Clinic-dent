document.getElementById("loginForm").addEventListener("submit", function (event) {
    event.preventDefault(); // Prevent the default form submission

    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;

    // Perform basic validation
    if (!email || !password) {
        alert("Please enter both email and password.");
        return;
    }

    // Submit the data (Example: Send to a server or log in console)
    console.log("Email:", email);
    console.log("Password:", password);

    // Here you can make a request to your backend API
    // Example: 
    // fetch('your-backend-url', {
    //     method: 'POST',
    //     headers: { 'Content-Type': 'application/json' },
    //     body: JSON.stringify({ email, password }),
    // }).then(response => response.json())
    //   .then(data => console.log(data))
    //   .catch(error => console.error(error));

    alert("Form submitted successfully!");
});