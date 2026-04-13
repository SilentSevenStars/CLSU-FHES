# Fix PDO MYSQL_ATTR_SSL_CA Deprecation

- [x] 1. Add use statements (use PDO; use Pdo\\Mysql;)
- [x] 2. Replace in mysql connection
- [x] 3. Replace in mariadb connection
- [x] 4. Update TODO.md with completion

**All code edits to config/database.php completed successfully! The deprecation warnings should now be resolved.**

Remaining:
- [ ] 5. Clear Laravel caches: php artisan config:clear && php artisan cache:clear
- [ ] 6. Reload/ test the application to confirm no more warnings
