CREATE TABLE `encode` (
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `acc_number` int(255) NOT NULL DEFAULT 0,
  `email` varchar(255) DEFAULT NULL,
  `score` int(255) DEFAULT NULL,
  `date` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `all_tasks` (
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `acc_number` int(255) NOT NULL DEFAULT 0,
  `email` varchar(255) DEFAULT NULL,
  `score` int(255) DEFAULT NULL,
  `date` varchar(255) DEFAULT NULL,
    `status` varchar(255) NOT NULL DEFAULT 'PENDING'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `riddle` (
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `acc_number` int(255) NOT NULL DEFAULT 0,
  `email` varchar(255) DEFAULT NULL,
  `score` int(255) DEFAULT NULL,
  `date` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `records` (
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `acc_number` int(11) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `amount` varchar(255) DEFAULT NULL,
  `ref` varchar(255) DEFAULT NULL,
  `date_requested` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `users` (
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `my_referral` varchar(255) DEFAULT NULL,
  `my_referral_earnings` int(255) NOT NULL DEFAULT 0,
   `registered_at` varchar(255) DEFAULT NULL,
  `terms` int(255) NOT NULL DEFAULT 0,
  `acc_number` varchar(255) NOT NULL DEFAULT '0',
  `role` varchar(255) NOT NULL DEFAULT 'Player',
  `email` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `status` int(255) DEFAULT 0,
  `code` int(255) NOT NULL DEFAULT 0,
  `profile` varchar(255) NOT NULL DEFAULT 'profile.jpg',
  `balance` int(255) NOT NULL DEFAULT 50,
  `total_income` int(255) NOT NULL DEFAULT 0,
  `number` varchar(255) DEFAULT NULL,
  `amount` int(255) DEFAULT NULL,
  `date_requested` varchar(255) DEFAULT NULL,
  `request_status` varchar(255) DEFAULT NULL,
  `last_login_date` varchar(255) DEFAULT NULL,
  `daily_login` int(255) NOT NULL DEFAULT 0,
  `device_id` varchar(255) DEFAULT NULL,
  `my_invitation_code` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
