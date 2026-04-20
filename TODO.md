# Fix PDO MYSQL_ATTR_SSL_CA Deprecation

- [x] 1. Add use statements (use PDO; use Pdo\\Mysql;)
- [x] 2. Replace in mysql connection
- [x] 3. Replace in mariadb connection
- [x] 4. Update TODO.md with completion

**All code edits to config/database.php completed successfully! The deprecation warnings should now be resolved.**

# Data Backup Implementation

- [x] Install spatie/laravel-backup package
- [x] Publish backup configuration
- [x] Configure backup disk in filesystems.php
- [x] Configure mysqldump path for Laravel Herd MySQL
- [x] Set up automatic daily backups at 2:00 AM
- [x] Set up automatic cleanup at 3:00 AM
- [x] Test backup functionality

**Data backup system is now active! Backups run daily at 2:00 AM and old backups are cleaned up at 3:00 AM.**

Remaining:
- [ ] 5. Clear Laravel caches: php artisan config:clear && php artisan cache:clear
- [ ] 6. Reload/ test the application to confirm no more warnings
