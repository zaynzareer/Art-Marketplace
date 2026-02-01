# Art Marketplace

An academic full-stack web application for buying and selling artwork online, built with modern web technologies and best practices for security, authentication, and performance.

## Project Overview

Crafty is an e-commerce platform designed to connect artists (sellers) with art enthusiasts (buyers). The platform provides a secure, scalable, and user-friendly environment for discovering, listing, and purchasing artwork. This project serves as an educational demonstration of web development patterns, including OAuth authentication, real-time notifications, API token security, and advanced caching features.

## Key Features

### 🎨 Core E-Commerce Functionality
- **Product Catalog**: Browse and search artwork with detailed product information
- **Shopping Cart**: Add/remove items with real-time cart updates using Livewire
- **Order Management**: Complete order processing with confirmation emails
- **Seller Dashboard**: Tools for sellers to list and manage their artwork inventory

### 🔐 Authentication & Security
- **Multi-Factor Authentication**: Secure login with Jetstream
- **OAuth Integration**: Social authentication support (configured via Socialite)
- **API Token Security**: Sanctum-based token authentication with revocation on logout
- **Authorization Gates**: Fine-grained permission control for sellers and buyers
- **Security Headers**: Comprehensive CSRF protection and security headers

### ⚡ Real-Time Features
- **Toast Notifications**: Real-time user feedback via Livewire components
- **Order Confirmations**: Automated email notifications on purchase
- **API Listeners**: Event-driven architecture with login/logout token management

### 💾 Performance & Caching
- **Redis Caching**: Advanced caching architecture for improved response times
- **Cache Strategy**: Optimized caching for product listings and user data
- **Database Observers**: Automatic cache invalidation on data changes

### 🌐 Cloud Deployment
- **AWS Integration**: Complete deployment on AWS
- **Scalable Architecture**: Built for production environments with AutoScaling paired with ALB. CI/CD pipeline is established using Github Actions. Cloudfront is configured for faster reload times. The application uses HTTPS through ACM.

## Tech Stack

### Backend
- **Framework**: Laravel 12
- **Database ORM**: Eloquent
- **Authentication**: Jetstream + Sanctum
- **Real-time UI**: Livewire 3
- **Payment**: Stripe (via Cashier)
- **Social Auth**: Socialite
- **Caching**: Redis + Predis
- **Email**: Mail queue system

### Frontend
- **Build Tool**: Vite
- **Styling**: Tailwind CSS
- **Framework**: Livewire components
- **HTTP Client**: Axios

### Testing & Quality
- **Testing**: PHPUnit
- **Code Quality**: Pint (linter)
- **Mocking**: Mockery

## Project Structure

```
app/
├── Actions/          # Fortify & Jetstream actions
├── Console/          # Artisan commands
├── Helpers/          # Utility functions (cache helpers)
├── Http/             # Controllers, middleware, API resources
├── Jobs/             # Queue jobs (email sending)
├── Listeners/        # Event listeners (token management)
├── Livewire/         # Real-time components (Buyer, Seller, Toast)
├── Mail/             # Mailable classes
├── Models/           # Eloquent models (User, Product, Order, Cart)
├── Observers/        # Model observers for cache invalidation
├── Policies/         # Authorization policies
└── Traits/           # Shared functionality
```

## Key Learning Outcomes

This project demonstrates:
- **RESTful API Design**: Token-based authentication and API security
- **Event-Driven Architecture**: Listeners and queue jobs for async processing
- **Authorization Patterns**: Gates and policies for access control
- **Real-Time Interactions**: Livewire for reactive components without full page reloads
- **Performance Optimization**: Caching strategies and database query optimization
- **Payment Integration**: Secure payment processing with Stripe
- **Production Deployment**: AWS configuration and deployment best practices
- **Email Management**: Queue-based email delivery with confirmations

## Getting Started

### Prerequisites
- PHP 8.2+
- Composer
- Node.js & npm
- MySQL/PostgreSQL database
- Redis (for caching)
- AWS account (for deployment)

### Installation

1. Clone the repository
2. Install PHP dependencies:
   ```bash
   composer install
   ```
3. Install JavaScript dependencies:
   ```bash
   npm install
   ```
4. Copy environment file:
   ```bash
   cp .env.example .env
   ```
5. Generate application key:
   ```bash
   php artisan key:generate
   ```
6. Run migrations:
   ```bash
   php artisan migrate
   ```
7. Build frontend assets:
   ```bash
   npm run build
   ```
8. Start development server:
   ```bash
   php artisan serve
   npm run dev
   ```

## Testing

Run the test suite:
```bash
php artisan test
```

## License

The Art Marketplace project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
