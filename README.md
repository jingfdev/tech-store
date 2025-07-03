# Tech Store

A comprehensive e-commerce solution designed for my Web and Cloud Technology class project, it features cutting-edge Laravel architecture. Integrating with Stripe for payments and Google and GitHub for social authentication, and also implements a secure order verification system. 

## Features

- Secure user authentication with social media integration
- Advanced product catalog with categorization
- Dynamic shopping cart with real-time updates
- Streamlined order management system
- Mobile-first responsive interface
- Secure order verification system

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
- `STRIPE_PUBLISHABLE_KEY` & `STRIPE_SECRET_KEY`: For Stripe payments
- `STRIPE_WEBHOOK_SECRET`: For Stripe webhooks

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request