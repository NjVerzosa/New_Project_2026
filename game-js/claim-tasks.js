function claimTasks(easyGameId) {
    // Ensure userId, userEmail, and csrfToken are defined
    if (!acc_number || !userEmail || !csrfToken) {
        Swal.fire({
            icon: 'error',
            text: 'User session is invalid. Please log in again.',
            width: '280px'
        });
        return;
    }

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "./includes/claim-tasks.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        // Change the button text to "Claimed" and disable it
                        const claimButton = document.getElementById('dailyRewards');
                        if (claimButton) {
                            claimButton.textContent = 'CLAIMED';
                            claimButton.classList.remove('btn-primary');
                        }

                        Swal.fire({
                            icon: 'success',
                            text: response.message,
                            width: '280px'
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            text: response.message || 'Failed to claim task. Contact Us',
                            width: '280px'
                        });
                    }
                } catch (error) {
                    console.error("Error parsing JSON response:", error, "Response:", xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        text: 'Invalid server response. Please try again.',
                        width: '280px'
                    });
                }
            } else {
                console.error("Error in request:", xhr.status, xhr.responseText);
                Swal.fire({
                    icon: 'error',
                    text: 'Server error: ' + xhr.status + '. Please try again later.',
                    width: '280px'
                });
            }
        }
    };

    // Send data to the server
    xhr.send(`acc_number=${encodeURIComponent(acc_number)}&csrf_token=${encodeURIComponent(csrfToken)}&email=${encodeURIComponent(userEmail)}&task_id=${encodeURIComponent(easyGameId)}`);
}