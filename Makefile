.PHONY: setup migrate seed serve test

# Run full project setup (install, env, key, migrate, seed)
setup:
	@echo "🔧 Installing PHP dependencies..."
	composer install

	@echo "📋 Copying .env file..."
	cp .env.example .env || true

	@echo "🔑 Generating application key..."
	php artisan key:generate

	@echo "⚙️ Running migrations..."
	php artisan migrate

	@echo "🌱 Seeding sample products (Optional)..."
	php artisan app:refresh

	@echo "✅ Laravel Wishlist API setup complete!"

# Run local server
serve:
	php artisan serve

# Run tests (with Pest)
test:
	php artisan test
