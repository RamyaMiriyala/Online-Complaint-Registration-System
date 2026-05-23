-- ============================================================
--  EduPlex v2 — Database Additions
--  Run this SQL in phpMyAdmin or MySQL terminal
--  on top of your existing complaint_system database
-- ============================================================

USE `complaint_system`;

-- ────────────────────────────────────────────────────────────
-- 1. WATCHMEN TABLE
--    Stores watchman/gate staff login credentials
-- ────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `watchmen` (
  `id`         int(11)      NOT NULL AUTO_INCREMENT,
  `name`       varchar(100) DEFAULT NULL,
  `username`   varchar(60)  NOT NULL,
  `password`   varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Default watchman account  (change password after first login)
INSERT INTO `watchmen` (`name`, `username`, `password`) VALUES
('Gate Security', 'watchman', 'watch123');

-- ────────────────────────────────────────────────────────────
-- 2. VERIFY permissions TABLE has all required columns
--    (These already exist in the original schema — listed here
--     for reference only. DO NOT run if table already exists.)
-- ────────────────────────────────────────────────────────────

-- The existing `permissions` table already supports:
--   id, user_email, role (student|teacher), category, reason,
--   from_date, to_date, active_until, status, created_at
--
-- The watchman dashboard queries:
--   WHERE status = 'approved'
--     AND from_date <= CURDATE()
--     AND to_date   >= CURDATE()
--
-- So make sure the column types are correct:

-- ALTER TABLE `permissions`
--   MODIFY `from_date`    date DEFAULT NULL,
--   MODIFY `to_date`      date DEFAULT NULL,
--   MODIFY `active_until` datetime DEFAULT NULL;

-- ────────────────────────────────────────────────────────────
-- 3. SAMPLE WATCHMAN DATA (optional — for testing)
-- ────────────────────────────────────────────────────────────

-- Add a second watchman if needed:
-- INSERT INTO `watchmen` (`name`, `username`, `password`) VALUES
-- ('Night Guard', 'guard2', 'pass456');

-- ────────────────────────────────────────────────────────────
-- DONE. You can now log in at watchman_login.php with:
--   Username : watchman
--   Password : watch123
-- ────────────────────────────────────────────────────────────
