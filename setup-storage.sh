#!/bin/bash

# ATLS Storage Setup Script
# This script creates the necessary storage directories and symlinks
# for image upload functionality

echo "================================================"
echo "ATLS Storage Setup Script"
echo "================================================"
echo ""

# Colors for output
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Function to print colored output
print_success() {
    echo -e "${GREEN}✓${NC} $1"
}

print_info() {
    echo -e "${BLUE}ℹ${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}⚠${NC} $1"
}

print_error() {
    echo -e "${RED}✗${NC} $1"
}

# Check if we're in the correct directory
if [ ! -f "artisan" ]; then
    print_error "This script must be run from the Laravel root directory"
    exit 1
fi

print_info "Starting storage setup..."
echo ""

# Step 1: Create storage symlink
echo "Step 1: Creating storage symlink..."
if [ -L "public/storage" ]; then
    print_warning "Symlink already exists. Removing old symlink..."
    rm public/storage
fi

ln -sfn ../storage/app/public public/storage
if [ $? -eq 0 ]; then
    print_success "Storage symlink created: public/storage -> ../storage/app/public"
else
    print_error "Failed to create storage symlink"
    exit 1
fi
echo ""

# Step 2: Create required directories
echo "Step 2: Creating required directories..."

directories=(
    "storage/app/public/team"
    "storage/app/public/regions/icons"
    "storage/app/public/regions/banners"
    "storage/app/public/registrations/payment-proofs"
    "storage/app/public/registrations/certificates"
)

for dir in "${directories[@]}"; do
    if [ ! -d "$dir" ]; then
        mkdir -p "$dir"
        print_success "Created: $dir"
    else
        print_info "Already exists: $dir"
    fi
done
echo ""

# Step 3: Create .gitkeep files
echo "Step 3: Creating .gitkeep files..."
for dir in "${directories[@]}"; do
    touch "$dir/.gitkeep"
    print_success "Created .gitkeep in: $dir"
done
echo ""

# Step 4: Set permissions
echo "Step 4: Setting permissions..."

# Check if running as root or with sudo
if [ "$EUID" -eq 0 ]; then
    print_warning "Running as root. Setting ownership to www-data..."

    # Set ownership
    chown -R www-data:www-data storage
    chown -R www-data:www-data public/storage
    print_success "Ownership set to www-data:www-data"

    # Set permissions
    chmod -R 775 storage
    chmod -R 775 public/storage
    print_success "Permissions set to 775"
else
    # Not running as root, just set permissions
    print_info "Not running as root. Setting permissions only..."
    chmod -R 775 storage
    chmod -R 775 public/storage
    print_success "Permissions set to 775"

    print_warning "Note: You may need to run 'sudo chown -R www-data:www-data storage' in production"
fi
echo ""

# Step 5: Verify setup
echo "Step 5: Verifying setup..."

# Check symlink
if [ -L "public/storage" ]; then
    print_success "Storage symlink verified"
else
    print_error "Storage symlink missing!"
    exit 1
fi

# Check directories
all_exist=true
for dir in "${directories[@]}"; do
    if [ ! -d "$dir" ]; then
        print_error "Directory missing: $dir"
        all_exist=false
    fi
done

if [ "$all_exist" = true ]; then
    print_success "All directories verified"
else
    print_error "Some directories are missing!"
    exit 1
fi
echo ""

# Display summary
echo "================================================"
echo "Storage Setup Complete!"
echo "================================================"
echo ""
echo "Created directories:"
for dir in "${directories[@]}"; do
    echo "  - $dir"
done
echo ""
echo "Symlink:"
echo "  - public/storage -> ../storage/app/public"
echo ""
echo "Next steps:"
echo "  1. Test image uploads in Admin > Team Management"
echo "  2. Test image uploads in Admin > Regional Management"
echo "  3. Test payment proof uploads in User Dashboard"
echo "  4. Verify images display correctly on homepage"
echo ""
echo "For troubleshooting, see: STORAGE_SETUP.md"
echo ""
print_success "Setup completed successfully!"
