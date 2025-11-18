# Storage Setup Guide

This document explains the storage configuration for image uploads in the ATLS application.

## Storage Structure

All uploaded files are stored in `storage/app/public/` and accessed via the `public/storage` symlink.

```
storage/app/public/
├── team/                      # Team member photos
├── regions/
│   ├── icons/                 # Region icon images
│   └── banners/               # Region banner images
└── registrations/
    ├── payment-proofs/        # Payment proof uploads (images/PDFs)
    └── certificates/          # Certificate files
```

## Initial Setup

### 1. Create Storage Symlink

The symlink `public/storage` should point to `storage/app/public`.

**Manual Creation:**
```bash
ln -sfn ../storage/app/public public/storage
```

**Using Artisan (if available):**
```bash
php artisan storage:link
```

### 2. Create Required Directories

Run this command to create all necessary directories:

```bash
mkdir -p storage/app/public/{team,regions/icons,regions/banners,registrations/payment-proofs,registrations/certificates}
```

### 3. Set Permissions

Ensure proper permissions for the storage directory:

```bash
chmod -R 775 storage
chmod -R 775 public/storage
```

For production with specific user/group:
```bash
chown -R www-data:www-data storage
chown -R www-data:www-data public/storage
```

## File Upload Configurations

### Team Members
- **Upload Path:** `storage/app/public/team/`
- **Access URL:** `asset('storage/team/filename.jpg')`
- **Controller:** `App\Http\Controllers\Admin\TeamController`
- **Max Size:** 2MB
- **Allowed Types:** jpeg, png, jpg, gif

### Regions
- **Icon Path:** `storage/app/public/regions/icons/`
- **Banner Path:** `storage/app/public/regions/banners/`
- **Access URL:** `asset('storage/regions/icons/filename.jpg')`
- **Controller:** `App\Http\Controllers\Admin\RegionController`
- **Max Size:** 2MB
- **Allowed Types:** jpeg, png, gif

### Payment Proofs (ATLS Registrations)
- **Upload Path:** `storage/app/public/registrations/payment-proofs/`
- **Access URL:** `asset('storage/registrations/payment-proofs/filename.jpg')`
- **Controller:** `App\Http\Controllers\RegistrationController`
- **Max Size:** 5MB
- **Allowed Types:** jpg, jpeg, png, pdf

### Certificates
- **Upload Path:** `storage/app/public/registrations/certificates/`
- **Access URL:** `asset('storage/registrations/certificates/filename.pdf')`
- **Controller:** `App\Http\Controllers\RegistrationController`
- **Max Size:** 5MB
- **Allowed Types:** pdf, jpg, jpeg, png

## Model Accessors

All models use accessors to generate public URLs without strict file existence checks:

### TeamMember Model
```php
public function getImageUrlAttribute()
{
    if ($this->image) {
        return asset('storage/' . $this->image);
    }
    return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&size=200';
}
```

### Region Model
```php
public function getIconImageUrlAttribute()
{
    if ($this->icon_image) {
        return asset('storage/' . $this->icon_image);
    }
    return null;
}

public function getBannerImageUrlAttribute()
{
    if ($this->banner_image) {
        return asset('storage/' . $this->banner_image);
    }
    return null;
}
```

### ATLsRegistration Model
```php
public function getPaymentProofUrlAttribute()
{
    if ($this->payment_proof) {
        return asset('storage/' . $this->payment_proof);
    }
    return null;
}

public function getCertificateFileUrlAttribute()
{
    if ($this->certificate_file) {
        return asset('storage/' . $this->certificate_file);
    }
    return null;
}
```

## Troubleshooting

### Images Not Displaying

1. **Check symlink exists:**
   ```bash
   ls -la public/storage
   ```
   Should show: `storage -> ../storage/app/public`

2. **Check directories exist:**
   ```bash
   ls -la storage/app/public/
   ```

3. **Check file permissions:**
   ```bash
   ls -la storage/app/public/team/
   ls -la storage/app/public/regions/icons/
   ```

4. **Check uploaded files:**
   ```bash
   find storage/app/public -type f -name "*.jpg" -o -name "*.png" -o -name "*.pdf"
   ```

### Upload Failures

1. **Check PHP upload limits** in `php.ini`:
   ```ini
   upload_max_filesize = 10M
   post_max_size = 10M
   ```

2. **Check Laravel storage configuration** in `config/filesystems.php`:
   ```php
   'public' => [
       'driver' => 'local',
       'root' => storage_path('app/public'),
       'url' => env('APP_URL').'/storage',
       'visibility' => 'public',
   ],
   ```

3. **Check disk space:**
   ```bash
   df -h
   ```

## Security Considerations

1. **Never store sensitive files** in `public` disk
2. **Always validate file types** before upload
3. **Limit file sizes** appropriately
4. **Use unique filenames** to prevent overwrites
5. **Sanitize filenames** to prevent path traversal attacks

## Deployment Checklist

When deploying to production:

- [ ] Run `php artisan storage:link` or create symlink manually
- [ ] Create all required directories
- [ ] Set proper ownership (www-data:www-data or appropriate user)
- [ ] Set proper permissions (775 for directories, 664 for files)
- [ ] Ensure PHP upload limits are adequate
- [ ] Test file uploads in all modules
- [ ] Test file display in frontend and admin
- [ ] Check error logs for any permission issues

## Recent Fixes (November 2025)

### Issues Fixed:
1. ✅ Storage symlink was missing - created manually
2. ✅ Required directories didn't exist - created all necessary folders
3. ✅ Model accessors had strict file_exists checks - removed for better performance
4. ✅ Payment proof upload didn't set timestamp - added payment_proof_uploaded_at
5. ✅ Team member images used wrong path - aligned with controller storage path
6. ✅ Region images had file_exists checks - removed for consistency

### Changes Made:
- Created storage symlink: `public/storage -> ../storage/app/public`
- Created directories: team, regions/icons, regions/banners, registrations/*
- Updated `TeamMember::getImageUrlAttribute()` to use direct path
- Updated `Region::getIconImageUrlAttribute()` and `getBannerImageUrlAttribute()`
- Updated `ATLsRegistration::getPaymentProofUrlAttribute()` and `getCertificateFileUrlAttribute()`
- Added `payment_proof_uploaded_at` timestamp on upload in `RegistrationController`
