# Girl Lockers - Deployment Guide

## Prerequisites

- PHP 8.1 or higher
- MySQL 8.0 or higher
- Composer
- Node.js 18+ and npm
- Web server (Apache/Nginx) with mod_rewrite enabled

## Local Development Setup

### 1. Clone and Install Dependencies

```bash
# Clone the repository
git clone <repository-url> girlockers
cd girlockers

# Install PHP dependencies
composer install

# Install Node dependencies
npm install
```

### 2. Environment Configuration

```bash
# Copy the example environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure your database in .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=girlockers
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 3. Database Setup

```bash
# Run migrations
php artisan migrate

# (Optional) Seed the database with sample data
php artisan db:seed
```

### 4. Storage Linking

```bash
# Create symbolic link for public storage
php artisan storage:link
```

### 5. Development Server

```bash
# Start the development server
php artisan serve

# In another terminal, start Vite for asset compilation
npm run dev
```

Visit `http://localhost:8000` to see your application.

## Production Deployment (Hostinger/Shared Hosting)

### 1. Build Production Assets

```bash
# Build optimized assets for production
npm run build
```

### 2. Optimize Laravel

```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize autoloader
composer install --optimize-autoloader --no-dev
```

### 3. Upload Files

Upload all files to your hosting server via FTP/SFTP:
- Upload the entire project to your web root (usually `public_html` or `www`)
- If your hosting uses a separate public directory:
  - Upload all files except `public/` to a folder above web root (e.g., `/home/username/girlockers`)
  - Upload contents of `public/` to your web root (e.g., `/home/username/public_html`)
  - Update `index.php` paths to point to the correct directories

### 4. Set Permissions

```bash
# Set correct permissions for storage and cache
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### 5. Configure Environment

- Copy `.env.example` to `.env` on the server
- Update all environment variables for production:
  - Set `APP_ENV=production`
  - Set `APP_DEBUG=false`
  - Set `APP_URL` to your domain
  - Configure database credentials
  - Generate a new `APP_KEY` if not set

### 6. Run Migrations

```bash
php artisan migrate --force
```

### 7. Enable HTTPS

Uncomment HTTPS redirect in `public/.htaccess`:

```apache
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}/$1 [R=301,L]
```

### 8. Create Admin User

After deployment, create an admin user via tinker:

```bash
php artisan tinker

# In tinker console:
$user = new App\Models\User();
$user->name = 'Admin';
$user->email = 'admin@girlockers.com';
$user->password = bcrypt('your-secure-password');
$user->role = 'admin';
$user->has_full_access = true;
$user->save();
```

## Post-Deployment Checklist

- [ ] Verify database connection
- [ ] Test user registration
- [ ] Test admin login
- [ ] Upload a test course with modules and lessons
- [ ] Test video playback (YouTube and local)
- [ ] Test comment and like functionality
- [ ] Test access request flow
- [ ] Verify mobile responsiveness
- [ ] Check all security headers (use securityheaders.com)
- [ ] Test HTTPS redirect
- [ ] Verify asset caching
- [ ] Monitor error logs

## Troubleshooting

### Storage Link Not Working

If `storage:link` doesn't work on shared hosting:
1. Delete the `public/storage` symlink if it exists
2. Manually create it via FTP or use this PHP script:
   ```php
   symlink('/home/username/girlockers/storage/app/public', '/home/username/public_html/storage');
   ```

### 500 Internal Server Error

1. Check file permissions (755 for directories, 644 for files)
2. Check `.htaccess` is properly uploaded
3. Enable error display temporarily to see the actual error:
   ```
   APP_DEBUG=true
   ```
4. Check Laravel logs in `storage/logs/laravel.log`

### Cache Issues After Updates

Clear all caches:
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

Then re-cache:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Database Migration Errors

If migrations fail:
1. Check database user has CREATE, ALTER, DROP permissions
2. Increase `max_execution_time` in php.ini
3. Run migrations one by one to identify the problem

## Maintenance Mode

To enable maintenance mode during updates:

```bash
# Enable maintenance mode
php artisan down

# Perform updates

# Disable maintenance mode
php artisan up
```

## Backup Strategy

### Database Backup

```bash
# Export database
mysqldump -u username -p girlockers > backup-$(date +%Y%m%d).sql
```

### File Backup

Important directories to backup:
- `storage/app/public/courses/` - Course images
- `storage/app/public/lessons/` - Local video files
- `.env` - Environment configuration
- `database/` - Migration files

## Performance Optimization

### Enable OPcache

Add to your `php.ini`:

```ini
opcache.enable=1
opcache.memory_consumption=128
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=4000
opcache.revalidate_freq=60
opcache.fast_shutdown=1
```

### Database Optimization

```bash
# Optimize tables
php artisan db:optimize
```

### Asset Compression

All assets are automatically minified during `npm run build`.
The `.htaccess` file enables Gzip compression for text assets.

## Security Recommendations

1. **Never commit `.env` file to version control**
2. **Use strong database passwords**
3. **Keep PHP and dependencies updated**
4. **Enable HTTPS redirect in production**
5. **Set restrictive file permissions**
6. **Monitor logs regularly**
7. **Use CSRF protection** (enabled by default)
8. **Validate all user inputs** (implemented in Livewire components)

## Support

For issues or questions:
- Check Laravel documentation: https://laravel.com/docs
- Check Livewire documentation: https://livewire.laravel.com
- Review application logs: `storage/logs/laravel.log`

## Version Information

- Laravel: 11.x
- Livewire: 3.x
- PHP: 8.1+
- MySQL: 8.0+
- Node.js: 18+
