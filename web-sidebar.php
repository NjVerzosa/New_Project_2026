<aside id="sidebar" class="js-sidebar" style="background: #1e549f">
    <div class=" h-100">
        <div class="sidebar-logo" style="position: relative;text-align: center; padding: 10px;">
            <img src="images/logo.png" alt="Logo Icon" style="width: 100px; height: 70px; margin-bottom: 2px; border-radius: 10px; position: relative; z-index: 1;">
        </div>
        <ul class="sidebar-nav">
            <hr>
            <li class="sidebar-item">
                <a href="web-dashboard.php" class=" sidebar-link">
                    <i class="fa-solid fa-list pe-2"></i>
                    Dashboard
                </a>
            </li>
            <li class="sidebar-item">
                <a href="#" class="sidebar-link collapsed" data-bs-target="#pages" data-bs-toggle="collapse" aria-expanded="false">
                    <i class="fas fa-gamepad pe-2"></i>
                    <span class="menu-text">Games</span>
                    <i class="three-dots-icon fas fa-ellipsis-v"></i>
                </a>
                <ul id="pages" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar" style="margin-left: 25px;">
                    <li class="sidebar-item">
                        <a href="web-encoding.php" class="sidebar-link game-menu-link">
                            <i class="fa-solid fa-keyboard pe-2"></i>
                            <span class="game-menu-text">Encoding Tasks</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="web-riddle.php" class="sidebar-link game-menu-link">
                            <i class="fa-solid fa-puzzle-piece pe-2"></i>
                            <span class="game-menu-text">Riddle Task</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="sidebar-item">
                <a href="#" class="sidebar-link collapsed" data-bs-target="#account" data-bs-toggle="collapse" aria-expanded="false">
                    <i class="fas fa-user pe-2"></i>
                    <span class="menu-text">Account</span>
                    <i class="three-dots-icon fas fa-ellipsis-v"></i>
                </a>
                <ul id="account" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                    <li class="sidebar-item">
                        <a href="web-account.php" class="sidebar-link submenu-link">
                            <i class="fas fa-wallet pe-2"></i>
                            <span class="menu-text">Withdrawal</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="web-payment.php" class="sidebar-link submenu-link">
                            <i class="fa-solid fa-crown pe-2"></i>
                            <span class="menu-text">Premium</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="sidebar-item">
                <a href="web-referral.php" class="sidebar-link">
                    <i class="fa-solid fa-user-plus pe-2"></i>
                    My Referral
                </a>
            </li>

            <li class="sidebar-item">
                <a href="web-logout.php" rel="nofollow" class="sidebar-link">
                    <i class="fa-solid fa-right-from-bracket pe-2"></i> Logout
                </a>
            </li>
        </ul>
    </div>
</aside>