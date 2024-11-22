echo "Running migrations..."
php artisan migrate --force

echo "Seeding database..."
php artisan db:seed --force

# Start the PHP server
echo "Starting PHP server..."
exec php artisan serve --host=0.0.0.0 --port=8080