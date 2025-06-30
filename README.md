# Tech Store

A modern e-commerce platform for technology products built with Laravel.

## Features

- User authentication (including social login)
- Product catalog with categories
- Shopping cart functionality
- Order processing
- Admin dashboard
- Responsive design

## Requirements

- PHP 8.1 or higher
- Composer
- Node.js & NPM
- MySQL 5.7+ or MariaDB 10.3+
- Web server (Apache/Nginx)
- Git

## Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/jingfdev/tech-store.git
   cd tech-store
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install JavaScript dependencies**
   ```bash
   npm install
   npm run dev
   ```

4. **Create environment file**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure database**
   - Create a new MySQL database
   - Update `.env` file with your database credentials:
     ```
     DB_DATABASE=your_database_name
     DB_USERNAME=your_database_username
     DB_PASSWORD=your_database_password
     ```

6. **Run migrations and seed the database**
   ```bash
   php artisan migrate --seed
   ```

7. **Set up storage link**
   ```bash
   php artisan storage:link
   ```

8. **Configure mail settings** (optional)
   Update the mail configuration in `.env` if you want to enable email notifications.

## Running the Application

1. Start the development server:
   ```bash
   php artisan serve
   ```

2. For development with hot-reload (frontend):
   ```bash
   npm run dev
   ```

3. Access the application at `http://localhost:8000`

## Default Admin Account

After running the database seeder, you can log in with:
- Email: admin@example.com
- Password: password

## Environment Variables

- `APP_ENV`: Application environment (local, production, etc.)
- `APP_DEBUG`: Debug mode (true/false)
- `APP_URL`: Application URL
- `DB_*`: Database connection settings
- `MAIL_*`: Email configuration
- `GOOGLE_CLIENT_ID` & `GOOGLE_CLIENT_SECRET`: For Google login
- `FACEBOOK_CLIENT_ID` & `FACEBOOK_CLIENT_SECRET`: For Facebook login

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

