<style>
    /* Enhanced Sidebar Styles */
    #sidebar {
        background: linear-gradient(135deg, #1e549f 0%, #0d2b5e 100%);
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
    }

    .sidebar-logo {
        padding: 15px;
        text-align: center;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .sidebar-logo img {
        width: 80px;
        height: auto;
        transition: all 0.3s;
    }

    .sidebar-logo:hover img {
        transform: scale(1.05);
    }

    .sidebar-header {
        color: rgba(255, 255, 255, 0.8);
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 15px 20px 5px;
        margin-top: 10px;
    }

    .sidebar-divider {
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        margin: 5px 20px;
    }

    .sidebar-item {
        position: relative;
        margin: 3px 0;
    }

    .sidebar-link {
        display: flex;
        align-items: center;
        padding: 12px 20px;
        color: rgba(255, 255, 255, 0.8);
        font-size: 0.95rem;
        transition: all 0.3s;
        border-left: 3px solid transparent;
    }

    .sidebar-link:hover {
        color: white;
        background: rgba(255, 255, 255, 0.1);
        text-decoration: none;
        border-left: 3px solid #4a9eff;
    }

    .sidebar-link i {
        width: 24px;
        text-align: center;
        font-size: 1.1rem;
        margin-right: 10px;
    }

    .sidebar-link.active {
        background: rgba(255, 255, 255, 0.15);
        border-left: 3px solid #4a9eff;
        color: white;
    }

    .sidebar-dropdown {
        padding-left: 20px;
        background: rgba(0, 0, 0, 0.1);
        overflow: hidden;
        max-height: 0;
        transition: max-height 0.3s ease;
    }

    .sidebar-dropdown.show {
        max-height: 500px;
    }

    .sidebar-dropdown .sidebar-link {
        padding: 10px 20px 10px 40px;
        font-size: 0.9rem;
    }

    .has-dropdown>.sidebar-link::after {
        content: "\f078";
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
        position: absolute;
        right: 20px;
        transition: transform 0.3s;
    }

    .has-dropdown.show>.sidebar-link::after {
        transform: rotate(180deg);
    }

    /* Category Colors */
    .dashboard-menu .sidebar-link:hover,
    .dashboard-menu .sidebar-link.active {
        border-left-color: #4CAF50;
    }

    .tasks-menu .sidebar-link:hover,
    .tasks-menu .sidebar-link.active {
        border-left-color: #FF9800;
    }

    .payments-menu .sidebar-link:hover,
    .payments-menu .sidebar-link.active {
        border-left-color: #2196F3;
    }

    .system-menu .sidebar-link:hover,
    .system-menu .sidebar-link.active {
        border-left-color: #9C27B0;
    }

    /* Badge for notifications */
    .sidebar-badge {
        position: absolute;
        right: 20px;
        background: #ff4757;
        color: white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        font-size: 0.7rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>

<aside id="sidebar" class="js-sidebar">
    <div class="h-100">
        <div class="sidebar-logo">
            <img src="./assets/image/logo.png" alt="Admin Logo">
        </div>

        <ul class="sidebar-nav">
            <!-- Dashboard Section -->
            <li class="sidebar-header dashboard-menu">Dashboard</li>
            <li class="sidebar-item">
                <a href="admin-Dashboard.php" class="sidebar-link" style="text-decoration: none; color: white; font-size: 15px;">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Overview</span>
                </a>
            </li>

            <hr class="sidebar-divider">

            <!-- Tasks Section -->
            <li class="sidebar-header tasks-menu">Tasks Monitor</li>
            <li class="sidebar-item">
                <a href="admin-all-tasks.php" class="sidebar-link" style="text-decoration: none; color: white; font-size: 15px;">
                    <i class="fas fa-tasks"></i>
                    <span>User Tasks</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="admin-numberSection.php" class="sidebar-link" style="text-decoration: none; color: white; font-size: 15px;">
                    <i class="fas fa-keyboard"></i>
                    <span>Encoding Tasks</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="admin-riddleSection.php" class="sidebar-link" style="text-decoration: none; color: white; font-size: 15px;">
                    <i class="fas fa-spell-check"></i>
                    <span>Riddle Task</span>
                </a>
            </li>

            <hr class="sidebar-divider">

            <!-- Payments Section -->
            <li class="sidebar-header payments-menu">Payments</li>
            <li class="sidebar-item has-dropdown">
                <a href="#" class="sidebar-link" style="text-decoration: none; color: white; font-size: 15px;">
                    <i class="fas fa-money-bill-wave"></i>
                    <span>Transactions</span>
                </a>
                <ul class="sidebar-dropdown">
                    <li class="sidebar-item">
                        <a href="admin-Mailer.php" class="sidebar-link" style="text-decoration: none; color: white; font-size: 15px;">
                            <i class="fas fa-wallet"></i>
                            <span>Cashout Requests</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="admin-Paid-Players.php" class="sidebar-link" style="text-decoration: none; color: white; font-size: 15px;">
                            <i class="fas fa-check-circle"></i>
                            <span>Pending Payments</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="admin-Payments.php" class="sidebar-link" style="text-decoration: none; color: white; font-size: 15px;">
                            <i class="fas fa-exchange-alt"></i>
                            <span>Add Transactions</span>
                        </a>
                    </li>
                </ul>
            </li>

            <hr class="sidebar-divider">

            <!-- System Section -->
            <li class="sidebar-header system-menu">System Admin</li>
            <li class="sidebar-item">
                <a href="admin-Management.php" class="sidebar-link" style="text-decoration: none; color: white; font-size: 15px;">
                    <i class="fas fa-cogs"></i>
                    <span>System Settings</span>
                </a>
            </li>
        </ul>
    </div>
</aside>

<script>
    // Dropdown functionality
    document.addEventListener('DOMContentLoaded', function() {
        const dropdownToggles = document.querySelectorAll('.has-dropdown > .sidebar-link');

        dropdownToggles.forEach(toggle => {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                const parent = this.parentElement;
                const dropdown = this.nextElementSibling;

                // Close other open dropdowns
                document.querySelectorAll('.sidebar-dropdown.show').forEach(openDropdown => {
                    if (openDropdown !== dropdown) {
                        openDropdown.classList.remove('show');
                        openDropdown.previousElementSibling.parentElement.classList.remove('show');
                    }
                });

                // Toggle current dropdown
                parent.classList.toggle('show');
                dropdown.classList.toggle('show');
            });
        });

        // Highlight current page
        const currentPage = window.location.pathname.split('/').pop();
        const links = document.querySelectorAll('.sidebar-link');

        links.forEach(link => {
            if (link.getAttribute('href') === currentPage) {
                link.classList.add('active');

                // Expand parent dropdown if exists
                const dropdownItem = link.closest('.sidebar-dropdown');
                if (dropdownItem) {
                    dropdownItem.classList.add('show');
                    dropdownItem.previousElementSibling.parentElement.classList.add('show');
                }
            }
        });
    });
</script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

<!-- Add Font Awesome for the eye icons -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
<!-- Bootstrap and jQuery scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script src="https://kit.fontawesome.com/ae360af17e.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/blockadblock@1.0.0/blockadblock.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">