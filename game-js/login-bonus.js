
function claimBonus() {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "./includes/login-bonus.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        // Change the button text to "Claimed" and disable it
                        const claimButton = document.getElementById('dailyLoginButton');
                        claimButton.textContent = 'CLAIMED';
                        claimButton.classList.remove('btn-primary');
                        window.location = './dashboard';
                    } else {
                        Swal.fire({
                            icon: 'info',
                            text: response.message,
                            width: '280px'
                        });
                    }
                } catch (error) {
                    console.error("Error parsing JSON response:", error);
                    Swal.fire({
                        icon: 'error',
                        text: 'An unexpected error occurred.',
                        width: '280px'
                    });
                }
            } else {
                console.error("Error in request:", xhr.status);
                Swal.fire({
                    icon: 'error',
                    text: 'Unable to process the request. Please try again later.',
                    width: '280px'
                });
            }
        }
    };

    xhr.send(`acc_number=${accNumber}&csrf_token=${csrfToken}&email=${userEmail}`);
}
