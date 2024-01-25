// Example JavaScript code

function validateLogin(event) {
    event.preventDefault(); // Prevent form submission

    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;

    // Example: Check if username and password meet certain conditions to determine user type
    const isAdmin = checkIfAdmin(username, password);
    const isCustomer = checkIfCustomer(username, password);

    if (isAdmin) {
        // If the user is an admin
        document.getElementById('loginMessage').textContent = 'Logged in as admin.';
        // Redirect to admin dashboard or perform necessary actions
    } else if (isCustomer) {
        // If the user is a customer
        document.getElementById('loginMessage').textContent = 'Logged in as customer.';
        // Redirect to customer dashboard or perform necessary actions
    } else {
        // If the user does not match any criteria
        document.getElementById('loginMessage').textContent = 'Invalid username or password.';
    }
}

// Example functions to check if user is admin or customer
function checkIfAdmin(username, password) {
    // Example logic to determine if the user is an admin
    // For demonstration purposes, let's say admin credentials are hardcoded
    return username === 'admin' && password === 'adminpassword';
}

function checkIfCustomer(username, password) {
    // Example logic to determine if the user is a customer
    // For demonstration purposes, let's say customer credentials are hardcoded
    return username === 'customer' && password === 'customerpassword';
}



/* script.js */

function showInfo(popupId) {
    var infoPopup = document.getElementById(popupId);
    infoPopup.style.display = infoPopup.style.display === "none" ? "block" : "none";
    setTimeout(function () {
      infoPopup.style.display = "none";
    }, 5000); // Hide after 5 seconds
  }
  