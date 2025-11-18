-- Create gallery_categories table
-- Run this SQL if you cannot run `php artisan migrate`

CREATE TABLE IF NOT EXISTS `gallery_categories` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `gallery_categories_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample data (optional)
INSERT INTO `gallery_categories` (`name`, `slug`, `description`, `icon`, `sort_order`, `status`, `created_at`, `updated_at`) VALUES
('Events', 'events', 'Event photos and documentation', 'fi-rr-calendar', 1, 'active', NOW(), NOW()),
('Training', 'training', 'Training and workshop documentation', 'fi-rr-graduation-cap', 2, 'active', NOW(), NOW()),
('Certificates', 'certificates', 'Certificates and awards', 'fi-rr-diploma', 3, 'active', NOW(), NOW()),
('Team', 'team', 'Team photos and activities', 'fi-rr-users-alt', 4, 'active', NOW(), NOW()),
('Facilities', 'facilities', 'Facilities and infrastructure', 'fi-rr-building', 5, 'active', NOW(), NOW());

-- Verification query
SELECT * FROM gallery_categories;
