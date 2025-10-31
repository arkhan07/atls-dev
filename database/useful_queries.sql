-- ============================================
-- USEFUL SQL QUERIES - ATLS INDONESIA
-- Purpose: Quick reference untuk troubleshooting dan monitoring
-- ============================================

-- ============================================
-- 1. CHECK ACTIVITY LOGS TABLE
-- ============================================

-- Check if activity_logs table exists
SELECT 
    TABLE_NAME, 
    TABLE_ROWS,
    CREATE_TIME,
    UPDATE_TIME,
    TABLE_COMMENT
FROM 
    information_schema.TABLES 
WHERE 
    TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'activity_logs';

-- View table structure
DESCRIBE activity_logs;

-- Count total logs
SELECT COUNT(*) as total_activity_logs FROM activity_logs;

-- ============================================
-- 2. VIEW ACTIVITY LOGS
-- ============================================

-- Recent activity logs (last 20)
SELECT 
    id,
    log_name,
    description,
    subject_id,
    subject_type,
    causer_id,
    DATE_FORMAT(created_at, '%d %b %Y %H:%i') as created
FROM 
    activity_logs
ORDER BY 
    created_at DESC
LIMIT 20;

-- Activity logs with package and user details
SELECT 
    al.id,
    al.log_name,
    al.description,
    p.title as package_title,
    p.status as package_status,
    u.name as user_name,
    u.email as user_email,
    al.properties,
    DATE_FORMAT(al.created_at, '%d %b %Y %H:%i:%s') as log_time
FROM 
    activity_logs al
LEFT JOIN 
    packages p ON al.subject_id = p.id AND al.subject_type = 'App\\\\Models\\\\Package'
LEFT JOIN 
    users u ON al.causer_id = u.id
ORDER BY 
    al.created_at DESC
LIMIT 30;

-- Activity logs by log_name
SELECT 
    log_name,
    COUNT(*) as total,
    DATE_FORMAT(MAX(created_at), '%d %b %Y') as last_activity
FROM 
    activity_logs
GROUP BY 
    log_name
ORDER BY 
    total DESC;

-- Status change logs only
SELECT 
    al.*,
    p.title as package_title
FROM 
    activity_logs al
LEFT JOIN 
    packages p ON al.subject_id = p.id
WHERE 
    al.log_name = 'status_changed'
ORDER BY 
    al.created_at DESC
LIMIT 20;

-- ============================================
-- 3. CHECK REGIONS
-- ============================================

-- List all active regions
SELECT 
    id,
    name,
    slug,
    whatsapp,
    is_active,
    order_num,
    DATE_FORMAT(created_at, '%d %b %Y') as created
FROM 
    regions
WHERE 
    is_active = 1
ORDER BY 
    order_num, name;

-- Regions with package count
SELECT 
    r.id,
    r.name,
    r.slug,
    r.is_active,
    COUNT(p.id) as total_packages,
    COUNT(CASE WHEN p.status = 'active' THEN 1 END) as active_packages
FROM 
    regions r
LEFT JOIN 
    packages p ON r.id = p.region_id
GROUP BY 
    r.id, r.name, r.slug, r.is_active
ORDER BY 
    r.order_num, r.name;

-- ============================================
-- 4. CHECK PACKAGES
-- ============================================

-- All active packages
SELECT 
    id,
    title,
    region_id,
    price,
    quota,
    status,
    start_date,
    end_date,
    DATE_FORMAT(created_at, '%d %b %Y') as created
FROM 
    packages
WHERE 
    status = 'active'
ORDER BY 
    start_date DESC;

-- Packages by region
SELECT 
    r.name as region_name,
    p.id,
    p.title,
    p.price,
    p.quota,
    p.status,
    p.start_date,
    u.name as agent_name
FROM 
    packages p
LEFT JOIN 
    regions r ON p.region_id = r.id
LEFT JOIN 
    users u ON p.user_id = u.id
ORDER BY 
    r.name, p.created_at DESC;

-- Packages without region_id (need to be assigned)
SELECT 
    id,
    title,
    user_id,
    price,
    status,
    created_at
FROM 
    packages
WHERE 
    region_id IS NULL
ORDER BY 
    created_at DESC;

-- Package registration statistics
SELECT 
    p.id,
    p.title,
    p.quota,
    COUNT(reg.id) as registered,
    (p.quota - COUNT(reg.id)) as remaining,
    ROUND((COUNT(reg.id) / p.quota * 100), 2) as fill_percentage
FROM 
    packages p
LEFT JOIN 
    registrations reg ON p.id = reg.package_id
WHERE 
    p.status = 'active'
GROUP BY 
    p.id, p.title, p.quota
ORDER BY 
    fill_percentage DESC;

-- ============================================
-- 5. FIX/UPDATE QUERIES
-- ============================================

-- Assign region to packages by agent/user
-- (Update region_id based on user location or manual assignment)
UPDATE packages 
SET region_id = 1  -- Ganti dengan region_id yang sesuai
WHERE user_id = 5  -- Ganti dengan user_id agent
  AND region_id IS NULL;

-- Update package status
UPDATE packages 
SET status = 'active'
WHERE id = 1;  -- Ganti dengan package_id

-- Fix missing region_id (example: set Jakarta as default)
UPDATE packages 
SET region_id = (SELECT id FROM regions WHERE slug = 'jakarta' LIMIT 1)
WHERE region_id IS NULL
  AND status = 'active';

-- ============================================
-- 6. MONITORING QUERIES
-- ============================================

-- Check database tables
SHOW TABLES;

-- Check recent package changes
SELECT 
    p.id,
    p.title,
    p.status,
    p.updated_at,
    u.name as last_updated_by
FROM 
    packages p
LEFT JOIN 
    users u ON p.user_id = u.id
ORDER BY 
    p.updated_at DESC
LIMIT 20;

-- Check users (agents/members)
SELECT 
    id,
    name,
    email,
    role,
    status,
    DATE_FORMAT(created_at, '%d %b %Y') as joined
FROM 
    users
WHERE 
    role = 'agent'  -- or 'member', 'admin'
ORDER BY 
    created_at DESC;

-- Check registrations
SELECT 
    r.id,
    r.package_id,
    p.title as package_title,
    r.name as participant_name,
    r.email,
    r.phone,
    r.payment_status,
    DATE_FORMAT(r.created_at, '%d %b %Y') as registered_at
FROM 
    registrations r
LEFT JOIN 
    packages p ON r.package_id = p.id
ORDER BY 
    r.created_at DESC
LIMIT 30;

-- ============================================
-- 7. CLEANUP QUERIES (OPTIONAL)
-- ============================================

-- Delete old activity logs (older than 90 days)
-- WARNING: Uncomment only if you want to cleanup
-- DELETE FROM activity_logs 
-- WHERE created_at < DATE_SUB(NOW(), INTERVAL 90 DAY);

-- Delete test/dummy packages
-- WARNING: Uncomment only if you want to cleanup
-- DELETE FROM packages 
-- WHERE title LIKE '%test%' OR title LIKE '%dummy%';

-- ============================================
-- 8. BACKUP QUERIES
-- ============================================

-- Export activity_logs (for backup)
-- Run this in terminal:
-- mysqldump -u username -p database_name activity_logs > activity_logs_backup.sql

-- Export packages with region
-- SELECT ... INTO OUTFILE format (requires file permission)
SELECT 
    p.id,
    p.title,
    r.name as region_name,
    p.price,
    p.quota,
    p.status,
    p.start_date,
    p.end_date
FROM 
    packages p
LEFT JOIN 
    regions r ON p.region_id = r.id
INTO OUTFILE '/tmp/packages_export.csv'
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n';

-- ============================================
-- END OF QUERIES
-- ============================================

-- Notes:
-- - Ganti 'database_name', 'username', 'password' dengan nilai yang sesuai
-- - Backup database sebelum menjalankan UPDATE/DELETE queries
-- - Test queries di staging environment terlebih dahulu
-- - Gunakan WHERE clause untuk limit scope UPDATE/DELETE queries
