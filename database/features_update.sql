-- Maslaki Platform - Features Update
-- Support for Appointments, Notifications, and Contests

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;

-- 1. Create Appointments table
CREATE TABLE IF NOT EXISTS `appointments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `status` enum('pending','confirmed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_appointment_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 2. Create Notifications table
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `type` varchar(50) DEFAULT 'info', -- info, success, warning, deadline
  `is_read` boolean DEFAULT false,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_notification_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 3. Create Contests table (Concours)
CREATE TABLE IF NOT EXISTS `contests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `institution_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `exam_date` date DEFAULT NULL,
  `registration_deadline` date DEFAULT NULL,
  `status` enum('open', 'closed', 'soon') DEFAULT 'soon',
  `is_featured` boolean DEFAULT false,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_contest_institution` FOREIGN KEY (`institution_id`) REFERENCES `institutions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- SEED DATA
-- --------------------------------------------------------

-- Sample Notifications for the existing student (ID: 2)
INSERT IGNORE INTO `notifications` (`student_id`, `message`, `type`, `created_at`) VALUES
(2, 'Bienvenue sur votre nouvel espace Maslaki !', 'success', NOW()),
(2, 'Le concours ENSA Casablanca est maintenant ouvert.', 'deadline', DATE_SUB(NOW(), INTERVAL 1 DAY)),
(2, 'N''oubliez pas de compléter votre profil pour de meilleures recommandations.', 'info', DATE_SUB(NOW(), INTERVAL 2 DAY));

-- Sample Contests
INSERT IGNORE INTO `contests` (`institution_id`, `title`, `description`, `exam_date`, `registration_deadline`, `status`, `is_featured`) VALUES
(1, 'Concours ENSA 2026', 'Concours d''accès aux Écoles Nationales des Sciences Appliquées.', '2026-07-15', '2026-06-30', 'open', true),
(2, 'Test d''Aptitude ENCG (TAFEM)', 'Test d''accès aux Écoles Nationales de Commerce et de Gestion.', '2026-07-20', '2026-07-05', 'soon', true),
(5, 'Concours ISCAE', 'Concours d''accès à l''Institut Supérieur de Commerce et d''Administration des Entreprises.', '2026-06-10', '2026-05-25', 'open', true),
(10, 'Concours EMI', 'Concours d''accès à l''École Mohammadia d''Ingénieurs.', '2026-07-01', '2026-06-15', 'soon', false);

-- Sample Appointment
INSERT IGNORE INTO `appointments` (`student_id`, `title`, `appointment_date`, `appointment_time`, `status`) VALUES
(2, 'Conseil Orientation - ENSA', '2026-05-10', '10:00:00', 'confirmed');

COMMIT;
