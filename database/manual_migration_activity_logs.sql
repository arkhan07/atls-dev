-- ============================================
-- MANUAL SQL MIGRATION
-- File: create_activity_logs_table.sql
-- Purpose: Create activity_logs table manually
-- Date: 30 Oct 2025
-- ============================================

-- Drop table if exists (HATI-HATI! Ini akan hapus data existing)
-- DROP TABLE IF EXISTS `activity_logs`;

-- Create activity_logs table
CREATE TABLE IF NOT EXISTS `activity_logs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `log_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nama log: created, updated, deleted, status_changed',
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Deskripsi aktivitas',
  `subject_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'ID dari subject (package, user, dll)',
  `subject_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Type dari subject (App\\Models\\Package)',
  `causer_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'ID user yang melakukan aksi',
  `causer_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Type dari causer (App\\Models\\User)',
  `properties` json DEFAULT NULL COMMENT 'Data perubahan dalam format JSON',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `activity_logs_log_name_index` (`log_name`),
  KEY `activity_logs_subject_id_index` (`subject_id`),
  KEY `activity_logs_subject_type_index` (`subject_type`),
  KEY `activity_logs_causer_id_index` (`causer_id`),
  KEY `activity_logs_causer_type_index` (`causer_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabel untuk menyimpan log aktivitas package dan entity lainnya';

-- Verify table created
SELECT 
    TABLE_NAME, 
    TABLE_ROWS, 
    CREATE_TIME,
    TABLE_COMMENT
FROM 
    information_schema.TABLES 
WHERE 
    TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'activity_logs';

-- Sample: View structure
DESCRIBE activity_logs;

-- ============================================
-- OPTIONAL: Insert sample data for testing
-- ============================================

-- Sample insert (uncomment jika ingin test)
/*
INSERT INTO activity_logs 
    (log_name, description, subject_id, subject_type, causer_id, causer_type, properties, created_at, updated_at)
VALUES 
    ('created', 'Package created', 1, 'App\\Models\\Package', 1, 'App\\Models\\User', '{"title":"Test Package"}', NOW(), NOW()),
    ('updated', 'Package updated', 1, 'App\\Models\\Package', 1, 'App\\Models\\User', '{"price":"500000"}', NOW(), NOW()),
    ('status_changed', 'Package status changed to inactive', 1, 'App\\Models\\Package', 1, 'App\\Models\\User', '{"old_status":"active","new_status":"inactive"}', NOW(), NOW());
*/

-- ============================================
-- VERIFY DATA
-- ============================================

-- Check if table is empty
SELECT COUNT(*) as total_logs FROM activity_logs;

-- View recent logs
SELECT 
    id,
    log_name,
    description,
    subject_id,
    subject_type,
    causer_id,
    created_at
FROM 
    activity_logs
ORDER BY 
    created_at DESC
LIMIT 10;

-- ============================================
-- USEFUL QUERIES FOR MONITORING
-- ============================================

-- Count logs by log_name
SELECT 
    log_name,
    COUNT(*) as total
FROM 
    activity_logs
GROUP BY 
    log_name
ORDER BY 
    total DESC;

-- Recent package activities
SELECT 
    al.*,
    p.title as package_title,
    u.name as user_name
FROM 
    activity_logs al
LEFT JOIN 
    packages p ON al.subject_id = p.id AND al.subject_type = 'App\\\\Models\\\\Package'
LEFT JOIN 
    users u ON al.causer_id = u.id
WHERE 
    al.subject_type = 'App\\\\Models\\\\Package'
ORDER BY 
    al.created_at DESC
LIMIT 20;

-- Cleanup old logs (older than 90 days) - OPTIONAL
-- DELETE FROM activity_logs WHERE created_at < DATE_SUB(NOW(), INTERVAL 90 DAY);

-- ============================================
-- ROLLBACK (if needed)
-- ============================================

-- Uncomment to drop table
-- DROP TABLE IF EXISTS activity_logs;
