<?php
include 'admin-sessions.php';

if (isset($_POST['offSession'])) {
    if (!empty($_POST['selectedRecords'])) {
        $selectedRecords = $_POST['selectedRecords'];

        foreach ($selectedRecords as $itemId) {
            $itemId = mysqli_real_escape_string($con, $itemId);
            $usersQuery = "UPDATE users SET login_time = NULL WHERE id = '$itemId'";
            mysqli_query($con, $usersQuery);
        }
        header("Location: admin-Dashboard.php");
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>EP | Player Dashboard</title>
    <?php include 'parts/frameworks.html'; ?>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <div class="wrapper">
        <?php include './parts/sidebar.php'; ?>
        <div class="main">
            <nav class="navbar navbar-expand px-3 border-bottom">
                <button class="btn" id="sidebar-toggle" type="button">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="navbar-collapse navbar">
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a href="#" data-bs-toggle="dropdown" class="nav-icon pe-md-0">
                                <img src="assets/image/logo.png" class="avatar img-fluid rounded" alt="">
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a href="logout.php" class="dropdown-item">Logout</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="content px-3 py-">
                <div class="container-fluid">
                    <div class="mb-3">
                        <h4>Player Dashboard</h4>
                    </div>
                    <div class="row">

                        <?php include 'parts/monitor.php'; ?>

                    </div>
                    <div class="card">
                        <div class="card-header bg-primary text-white text-center">
                            <div class="row align-items-center justify-content-between">

                                <!-- Search Form -->
                                <div class="col-md-6 col-lg-4 mb-1 mb-md-0 d-flex justify-content-start">
                                    <form action="" method="POST" class="d-flex align-items-center">
                                        <input type="text" id="searchInput" name="search" class="form-control me-2"
                                            placeholder="Search by email, username."
                                            style="border-radius: 0.5rem 0 0 0.5rem; border: 1px solid #007bff;"
                                            oninput="updatePlaceholder()" required>
                                        <button type="submit" name="go" class="btn btn-secondary">
                                            Search
                                        </button>
                                    </form>
                                </div>

                            </div>
                        </div>

                        <script>
                            function updatePlaceholder() {
                                const input = document.getElementById('searchInput');
                                const basePlaceholder = "Search by email, username.";
                                const userInput = input.value.trim();

                                // Check if the user input is not empty
                                if (userInput) {
                                    input.placeholder = `${userInput}@gmail.com`;
                                } else {
                                    input.placeholder = basePlaceholder; // Reset to original placeholder
                                }
                            }
                        </script>
                        <div class="card-body">
                            <div class="table-responsive">
                                <form action="" method="POST">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th style="white-space: nowrap;text-align: center;">Acc. Number</th>
                                                <th style="white-space: nowrap;text-align: center;">Time</th>
                                                <th style="white-space: nowrap;text-align: center;">Email</th>
                                                <th style="white-space: nowrap;text-align: center;">Username</th>
                                                <th style="white-space: nowrap;text-align: center;">Profile</th>
                                                <th style="white-space: nowrap;text-align: center;">Balance</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $result = null;
                                            if (isset($_POST['go']) && !empty($_POST['search'])) {
                                                $search = mysqli_real_escape_string($con, $_POST['search']);
                                                $sql = "SELECT * FROM users WHERE email LIKE '%$search%'";
                                                $result = mysqli_query($con, $sql);
                                            } else {
                                                $sql = "SELECT * FROM users ORDER BY registered_at DESC";
                                                $result = mysqli_query($con, $sql);
                                            }
                                            if ($result && mysqli_num_rows($result) > 0) {
                                                while ($data = mysqli_fetch_assoc($result)) {

                                                    // Remove "@gmail.com" from the email
                                                    $email = str_replace('@gmail.com', '', $data['email']);
                                            ?>
                                                    <tr class="<?= $rowClass; ?>">
                                                        <td style="white-space: nowrap; text-align:center;"><?= $data['acc_number']; ?></td>

                                                        <td style="white-space: nowrap; text-align:center;"><?= $data['login_time']; ?></td>



                                                        <td style="white-space: nowrap; text-align:center;"><?= $email; ?></td> <!-- Display modified email -->
                                                        <td style="white-space: nowrap; text-align:center;"><?= $data['username']; ?></td>
                                                        <td style="white-space: nowrap; text-align:center;">
                                                            <img src="userProfile/<?php echo htmlspecialchars($data['profile']); ?>" onclick="toggleZoom(this)" alt="" style="width:20px;height:20px;cursor:pointer;">
                                                        </td>
                                                        <td style="white-space: nowrap; text-align:center;"><?= $data['earned_coins']; ?></td>
                                                    </tr>
                                            <?php
                                                }
                                            } else {
                                                echo "<tr><td colspan='9'>No records found</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <a href="#" class="theme-toggle">
                <i class="fa-regular fa-moon"></i>
                <i class="fa-regular fa-sun"></i>
            </a>
        </div>
    </div>

    <div class="modal fade text-center" id="profileImageModal" tabindex="-1" aria-labelledby="profileImageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="margin: 0; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
            <div class="modal-content" style="background: transparent; border: none; box-shadow: none; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; padding: 0; position: relative;">
                <!-- Close Button -->
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    style="position: absolute; top: 10px; right: 10px; background: blue; border-radius: 50%; padding: 5px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);">
                </button>
                <!-- Profile Image -->
                <img id="profileImageZoom" src="" alt="Profile Image" style="width: 90%; max-width: 320px; height: auto; object-fit: cover; border: 5px solid #fff; border-radius: 10px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);">
            </div>
        </div>
    </div>



    <style>
        .faded-blue {
            background-color: rgba(31, 126, 226, 0.2);
        }


        /* Modal Container */
        .modal-content {
            border-radius: 20px;
            border: none;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            background: linear-gradient(to bottom, #ffffff, #f8f9fa);
            animation: fadeInModal 0.4s ease-out;
        }
    </style>




    <script>
        function toggleZoom(profileImage) {
            const profileImageZoom = document.getElementById("profileImageZoom");
            profileImageZoom.src = profileImage.src; // Set the source of the modal image to the clicked image
            const profileImageModal = new bootstrap.Modal(document.getElementById('profileImageModal'), {
                keyboard: false
            });
            profileImageModal.show(); // Show the modal
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Get the "Select All" checkbox and the individual checkboxes
            const selectAllCheckbox = document.getElementById("selectAll");
            const individualCheckboxes = document.querySelectorAll(".checkbox");

            // Add a click event listener to the "Select All" checkbox
            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener("click", function() {
                    // Iterate through individual checkboxes and set their checked property
                    individualCheckboxes.forEach(function(checkbox) {
                        checkbox.checked = selectAllCheckbox.checked;
                    });
                });
            }
        });
        document.addEventListener("DOMContentLoaded", function() {
            // Get the "Select All" checkbox and the individual checkboxes
            const selectAdminCheckbox = document.getElementById("selectAdmin");
            const individualCheckboxes = document.querySelectorAdmin(".checkbox");

            // Add a click event listener to the "Select All" checkbox
            if (selectAdmin) {
                selectAdminCheckbox.addEventListener("click", function() {
                    // Iterate through individual checkboxes and set their checked property
                    individualCheckboxes.forEach(function(checkbox) {
                        checkbox.checked = selectAdminCheckbox.checked;
                    });
                });
            }
        });
    </script>
    <script>
        const sidebarToggle = document.querySelector("#sidebar-toggle");
        sidebarToggle.addEventListener("click", function() {
            document.querySelector("#sidebar").classList.toggle("collapsed");
        });

        document.querySelector(".theme-toggle").addEventListener("click", () => {
            toggleLocalStorage();
            toggleRootClass();
        });

        function toggleRootClass() {
            const current = document.documentElement.getAttribute('data-bs-theme');
            const inverted = current == 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-bs-theme', inverted);
        }

        function toggleLocalStorage() {
            if (isLight()) {
                localStorage.removeItem("light");
            } else {
                localStorage.setItem("light", "set");
            }
        }

        function isLight() {
            return localStorage.getItem("light");
        }

        if (isLight()) {
            toggleRootClass();
        }
    </script>
</body>

</html>