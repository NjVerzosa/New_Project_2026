// Combined validation script for account setup form

document.addEventListener('DOMContentLoaded', function () {
    // Initialize all form validation
    initUsernameValidation();
    initPasswordValidation();
    initDeviceIdGeneration();
});

// ==================== USERNAME VALIDATION ====================
function initUsernameValidation() {
    const usernameInput = document.getElementById('username');
    if (!usernameInput) return;

    usernameInput.addEventListener('input', validateUsername);

    // Add form submission validation
    const form = document.getElementById('form');
    if (form) {
        form.addEventListener('submit', function (e) {
            if (!validateUsername(true)) {
                e.preventDefault();
            }
        });
    }
}

function validateUsername(forSubmission = false) {
    const usernameInput = document.getElementById('username');
    const username = usernameInput.value;
    const errorElement = document.getElementById('usernameError');
    const successElement = document.getElementById('usernameSuccess');
    const regex = /^[A-Z][a-z]*$/;

    // Clear previous states
    usernameInput.classList.remove('is-invalid');
    usernameInput.classList.remove('is-valid');
    if (errorElement) errorElement.style.display = 'none';
    if (successElement) successElement.style.display = 'none';

    if (username.length === 0) {
        return !forSubmission; // Return true unless this is for form submission
    }

    // Check length requirements first
    if (username.length < 4) {
        usernameInput.classList.add('is-invalid');
        if (errorElement) {
            errorElement.textContent = 'Too short (minimum 4 characters)';
            errorElement.style.display = 'block';
        }
        return false;
    }

    if (username.length > 10) {
        usernameInput.classList.add('is-invalid');
        if (errorElement) {
            errorElement.textContent = 'Too long (maximum 10 characters)';
            errorElement.style.display = 'block';
        }
        return false;
    }

    // Check format requirements
    if (!regex.test(username)) {
        let errorMessage = '';

        if (!/^[A-Z]/.test(username)) {
            errorMessage = 'First letter must be capitalized';
        } else if (/\s/.test(username)) {
            errorMessage = 'No spaces allowed';
        } else if (/[^a-zA-Z]/.test(username)) {
            errorMessage = 'Only letters allowed (no numbers or special characters)';
        } else {
            errorMessage = 'Invalid username format';
        }

        usernameInput.classList.add('is-invalid');
        if (errorElement) {
            errorElement.textContent = errorMessage;
            errorElement.style.display = 'block';
        }
        return false;
    }

    usernameInput.classList.add('is-valid');
    if (successElement) successElement.style.display = 'block';
    return true;
}

// ==================== PASSWORD VALIDATION ====================
function initPasswordValidation() {
    const passwordInput = document.getElementById('password');
    if (!passwordInput) return;

    passwordInput.addEventListener('input', validatePassword);

    // Add form submission validation
    const form = document.getElementById('form');
    if (form) {
        form.addEventListener('submit', function (e) {
            if (!validatePassword(true)) {
                e.preventDefault();
            }
        });
    }

    // Initialize password toggle
    const togglePassword = document.querySelector('.toggle-password');
    if (togglePassword) {
        togglePassword.addEventListener('click', function () {
            const icon = this.querySelector('i');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    }
}

function validatePassword(forSubmission = false) {
    const passwordInput = document.getElementById('password');
    const password = passwordInput.value;
    const errorElement = document.getElementById('passwordError');
    const successElement = document.getElementById('passwordSuccess');
    const strengthBar = document.getElementById('password-strength-bar');

    // Clear previous states
    passwordInput.classList.remove('is-invalid');
    passwordInput.classList.remove('is-valid');
    if (errorElement) errorElement.style.display = 'none';
    if (successElement) successElement.style.display = 'none';

    if (password.length === 0) {
        if (strengthBar) {
            strengthBar.style.width = '0%';
            strengthBar.className = 'progress-bar';
        }
        resetPasswordRequirements();
        return !forSubmission;
    }

    // Check requirements
    const hasMinLength = password.length >= 8;
    const hasUpperCase = /[A-Z]/.test(password);
    const hasLowerCase = /[a-z]/.test(password);
    const hasNumber = /[0-9]/.test(password);
    const hasSpecialChar = /[!@#$%^&*(),.?":{}|<>]/.test(password);

    // Update requirement indicators
    updatePasswordRequirement('req-length', hasMinLength);
    updatePasswordRequirement('req-uppercase', hasUpperCase);
    updatePasswordRequirement('req-lowercase', hasLowerCase);
    updatePasswordRequirement('req-number', hasNumber);
    updatePasswordRequirement('req-special', hasSpecialChar);

    // Calculate strength score (0-100)
    let strength = 0;
    if (hasMinLength) strength += 20;
    if (hasUpperCase) strength += 20;
    if (hasLowerCase) strength += 20;
    if (hasNumber) strength += 20;
    if (hasSpecialChar) strength += 20;

    // Update strength bar
    if (strengthBar) {
        strengthBar.style.width = strength + '%';

        // Set bar color based on strength
        if (strength < 40) {
            strengthBar.className = 'progress-bar bg-danger';
        } else if (strength < 80) {
            strengthBar.className = 'progress-bar bg-warning';
        } else {
            strengthBar.className = 'progress-bar bg-success';
        }
    }

    // Validate for form submission
    if (strength < 80) {
        passwordInput.classList.add('is-invalid');
        if (errorElement) {
            errorElement.textContent = forSubmission
                ? 'Please meet all password requirements'
                : 'Password is not strong enough';
            errorElement.style.display = 'block';
        }
        return false;
    }

    passwordInput.classList.add('is-valid');
    if (successElement) successElement.style.display = 'block';
    return true;
}

function updatePasswordRequirement(elementId, met) {
    const element = document.getElementById(elementId);
    if (element) {
        if (met) {
            element.classList.remove('text-muted');
            element.classList.add('text-success');
            element.innerHTML = element.innerHTML.replace('far fa-circle', 'fas fa-check-circle');
        } else {
            element.classList.remove('text-success');
            element.classList.add('text-muted');
            element.innerHTML = element.innerHTML.replace('fas fa-check-circle', 'far fa-circle');
        }
    }
}

function resetPasswordRequirements() {
    const requirements = document.querySelectorAll('#password-requirements li');
    requirements.forEach(req => {
        req.classList.remove('text-success');
        req.classList.add('text-muted');
        req.innerHTML = req.innerHTML.replace('fas fa-check-circle', 'far fa-circle');
    });
}

// ==================== DEVICE ID GENERATION ====================
function initDeviceIdGeneration() {
    const generateBtn = document.querySelector('.generate-device-id');
    if (generateBtn) {
        generateBtn.addEventListener('click', function (e) {
            e.preventDefault();
            generateDeviceId();
        });
    }
}

function generateDeviceId() {
    // Generate a fingerprint based on available browser/device information
    const fingerprint = {
        userAgent: navigator.userAgent,
        platform: navigator.platform,
        hardwareConcurrency: navigator.hardwareConcurrency || 'unknown',
        deviceMemory: navigator.deviceMemory || 'unknown',
        screenResolution: `${window.screen.width}x${window.screen.height}`,
        colorDepth: window.screen.colorDepth,
        timezone: Intl.DateTimeFormat().resolvedOptions().timeZone,
        language: navigator.language,
        touchSupport: 'ontouchstart' in window,
        cookiesEnabled: navigator.cookieEnabled,
        doNotTrack: navigator.doNotTrack || 'unknown'
    };

    // Create a hash of the fingerprint to use as device ID
    const fingerprintString = JSON.stringify(fingerprint);
    let hash = 0;

    if (fingerprintString.length === 0) return hash.toString();

    for (let i = 0; i < fingerprintString.length; i++) {
        const char = fingerprintString.charCodeAt(i);
        hash = ((hash << 5) - hash) + char;
        hash = hash & hash; // Convert to 32bit integer
    }

    // Set the device ID field value
    const deviceIdField = document.getElementById('device_id');
    if (deviceIdField) {
        deviceIdField.value = 'device_' + Math.abs(hash).toString(16);
    }

    return hash.toString();
}


// Add event listeners to show/hide input groups when checkboxes are clicked
document.getElementById('usernameCheckbox').addEventListener('change', function () {
    document.getElementById('usernameInputGroup').style.display = this.checked ? 'block' : 'none';
});

document.getElementById('deviceIdCheckbox').addEventListener('change', function () {
    document.getElementById('deviceIdInputGroup').style.display = this.checked ? 'block' : 'none';
});

document.getElementById('passwordCheckbox').addEventListener('change', function () {
    document.getElementById('passwordInputGroup').style.display = this.checked ? 'block' : 'none';
});


document.getElementById('checkUsernameBtn').addEventListener('click', function () {
    const usernameInput = document.getElementById('username');
    const username = usernameInput.value.trim();
    const errorDiv = document.getElementById('usernameError');
    const successDiv = document.getElementById('usernameSuccess');
    const checkingDiv = document.getElementById('usernameChecking');

    // Clear previous messages
    errorDiv.style.display = 'none';
    successDiv.style.display = 'none';

    // Show checking indicator
    checkingDiv.style.display = 'block';

    // Send request to server
    fetch('checking-data.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `username=${encodeURIComponent(username)}`
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            checkingDiv.style.display = 'none';

            if (data.error) {
                showError(data.error);
            } else if (data.exists) {
                showError('Username is already in use');
            } else {
                showSuccess('Username is available!');
            }
        })
        .catch(error => {
            checkingDiv.style.display = 'none';
            showError('Error checking username: ' + error.message);
            console.error('Error:', error);
        });

    function showError(message) {
        errorDiv.textContent = message;
        errorDiv.style.display = 'block';
        usernameInput.classList.add('is-invalid');
        usernameInput.classList.remove('is-valid');
    }

    function showSuccess(message) {
        successDiv.textContent = message;
        successDiv.style.display = 'block';
        usernameInput.classList.add('is-valid');
        usernameInput.classList.remove('is-invalid');
    }
});


// Generate or retrieve device ID
let uniqueID = localStorage.getItem('device_id');

// Toggle device ID section when checkbox is clicked
document.getElementById('deviceIdCheckbox').addEventListener('change', function () {
    const deviceIdGroup = document.getElementById('deviceIdInputGroup');
    deviceIdGroup.style.display = this.checked ? 'block' : 'none';

    if (this.checked && uniqueID) {
        document.getElementById('device_display').value = uniqueID;
        document.getElementById('device_id').value = uniqueID;
    }
});


// Initialize if checkbox is checked on page load
document.addEventListener("DOMContentLoaded", function () {
    if (document.getElementById('deviceIdCheckbox').checked) {
        document.getElementById('deviceIdInputGroup').style.display = 'block';
        if (uniqueID) {
            document.getElementById('device_display').value = uniqueID;
            document.getElementById('device_id').value = uniqueID;
        }
    }
});