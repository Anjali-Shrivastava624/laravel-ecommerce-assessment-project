# Laravel Multi-Authentication E-commerce Application

A comprehensive Laravel web application demonstrating multi-authentication, real-time updates, push notifications, and bulk product import capabilities.

## 🚀 Features

- **Multi-Authentication System**: Separate login/registration for Admin and Customer users
- **Product Management**: Full CRUD operations with bulk CSV import (up to 100k products)
- **Order Management**: Customer order placement and admin order status updates
- **Real-time Updates**: WebSocket-based live order status updates and user presence tracking
- **Push Notifications**: Browser push notifications for order status changes
- **Queue Processing**: Background job processing for large-scale imports
- **Comprehensive Testing**: Feature tests and unit tests included

## 📋 Requirements

- PHP 8.1+
- Laravel 11.x
- MySQL 5.7+
- Redis (for queues)
- Node.js & NPM
- Pusher account (for WebSocket broadcasting)

## 🛠️ Installation & Setup

### 1. Clone Repository
```bash
git clone <your-repository-url>
cd laravel-multi-auth-app
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Environment Configuration
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Database Setup
```bash
# Create database
mysql -u root -p
CREATE DATABASE laravel_multi_auth;
exit

# Run migrations
php artisan migrate
```

### 5. Storage Setup
```bash
php artisan storage:link
```

### 6. Queue Setup
```bash
# Create jobs table
php artisan queue:table
php artisan migrate

# Create failed jobs table
php artisan queue:failed-table
php artisan migrate
```

### 7. Pusher Configuration
1. Create account at [pusher.com](https://pusher.com)
2. Create new app and get credentials
3. Update `.env` file:
```env
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=your-app-id
PUSHER_APP_KEY=your-app-key
PUSHER_APP_SECRET=your-app-secret
PUSHER_APP_CLUSTER=your-cluster
```

### 8. Push Notifications Setup
1. Generate VAPID keys:
```bash
npx web-push generate-vapid-keys
```
2. Add to `.env`:
```env
VAPID_PUBLIC_KEY=your-public-key
VAPID_PRIVATE_KEY=your-private-key
```

### 9. Build Assets
```bash
npm run build
```

## 🏃‍♂️ Running the Application

### Start Development Servers
```bash
# Terminal 1: Laravel development server
php artisan serve

# Terminal 2: Queue worker
php artisan queue:work

# Terminal 3: WebSocket server (if using Laravel WebSockets)
php artisan websockets:serve
```

## 🔐 Multi-Authentication Strategy

### Guards Configuration
The application uses Laravel's built-in multi-auth system with custom guards:

- **Admin Guard**: `auth:admin` middleware
- **Customer Guard**: `auth:customer` middleware

### Route Protection
Routes are protected using role-based middleware:
```php
// Admin routes
Route::middleware(['auth', 'admin'])->group(function () {
    // Admin-only routes
});

// Customer routes  
Route::middleware(['auth', 'customer'])->group(function () {
    // Customer-only routes
});
```

### User Model Extensions
Users have a `role` field (`admin` or `customer`) with helper methods:
- `isAdmin()`: Check if user is admin
- `isCustomer()`: Check if user is customer
- `setOnlineStatus()`: Update online presence

## 🌐 WebSocket Implementation

### Broadcasting Stack
- **Driver**: Pusher
- **Frontend**: Laravel Echo + Pusher JS
- **Channels**: Private channels for user-specific updates, presence channels for admin dashboard

### Real-time Features
1. **Order Status Updates**: Customers receive live notifications when admins update order status
2. **User Presence**: Admin dashboard shows real-time online/offline status of all users

### Event Broadcasting
```php
// Order status updates
broadcast(new OrderStatusUpdated($order, $oldStatus))->toOthers();

// User presence updates
broadcast(new UserPresenceUpdated($user, $isOnline));
```

## 🔔 Push Notifications

### Setup Process
1. **User Subscription**: Users subscribe to push notifications via service worker
2. **Server Storage**: Subscription details stored in user model
3. **Notification Sending**: Server sends notifications via WebPush library

### Implementation
```php
// Send notification
$pushService = new PushNotificationService();
$pushService->sendNotification($user, $title, $body, $data);
```

### Service Worker
Handles push notification reception and display in browser.

## 📦 Bulk Import System

### Architecture
- **File Upload**: Admin uploads CSV/Excel files
- **Queue Processing**: `ImportProductsJob` handles large files
- **Chunked Reading**: Processes 1000 rows at a time
- **Validation**: Each row validated before insertion
- **Error Handling**: Invalid rows logged, valid rows processed

### Import Process
1. File uploaded and stored
2. Job dispatched to queue
3. File read in chunks
4. Data validated and inserted
5. Progress logged
6. Temporary file cleaned up

### CSV Format
```csv
name,description,price,category,stock,image
"Product Name","Product Description",99.99,"Category",100,"image.jpg"
```

## 🧪 Testing

### Running Tests
```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit

# Run with coverage
php artisan test --coverage
```

### Test Coverage
- **Feature Tests**: Authentication, product creation, order placement, multi-auth
- **Unit Tests**: Product import job, data validation, business logic

### Key Test Cases
1. **ProductCreationTest**: Admin can create products, customers cannot
2. **OrderPlacementTest**: Order creation, stock validation, payment processing
3. **MultiAuthTest**: Role-based access control
4. **ProductImportJobTest**: CSV processing, validation, error handling

## 📁 Project Structure

```
app/
├── Events/                 # WebSocket events
├── Http/
│   ├── Controllers/
│   │   ├── Admin/         # Admin controllers
│   │   └── Customer/      # Customer controllers
│   └── Middleware/        # Authentication middleware
├── Jobs/                  # Queue jobs (import, notifications)
├── Listeners/             # Event listeners
├── Models/                # Eloquent models
└── Services/              # Business logic services

resources/
├── views/
│   ├── admin/            # Admin blade templates
│   ├── customer/         # Customer blade templates
│   └── layouts/          # Shared layouts
└── js/                   # Frontend assets

tests/
├── Feature/              # Integration tests
└── Unit/                 # Unit tests
```

## 🔧 Key Artisan Commands

```bash
# Create admin user
php artisan tinker
User::create(['name' => 'Admin', 'email' => 'admin@test.com', 'password' => bcrypt('password'), 'role' => 'admin']);

# Process queue jobs
php artisan queue:work

# Clear application cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Generate application key
php artisan key:generate
```

## 📊 Performance Optimizations

### Database
- Proper indexing on frequently queried columns
- Eager loading for relationships
- Pagination for large datasets

### Queue Processing
- Chunked imports for memory efficiency
- Background processing prevents timeouts
- Job retry mechanism for failed imports

### Caching
- Query result caching for frequently accessed data
- View caching for improved response times

## 🚨 Security Features

- CSRF protection on all forms
- Role-based access control
- SQL injection prevention via Eloquent ORM
- XSS protection through Blade templating
- File upload validation and sanitization

## 🐛 Troubleshooting

### Common Issues

1. **Queue Jobs Not Processing**
   ```bash
   php artisan queue:restart
   php artisan queue:work
   ```

2. **WebSocket Connection Failed**
   - Check Pusher credentials
   - Verify network connectivity
   - Check browser console for errors

3. **Push Notifications Not Working**
   - Verify VAPID keys
   - Check HTTPS requirement
   - Ensure service worker is registered

4. **Import Job Fails**
   - Check file permissions
   - Verify CSV format
   - Monitor queue:work logs

### Debug Commands
```bash
# Check queue status
php artisan queue:monitor

# View failed jobs
php artisan queue:failed

# Retry failed jobs
php artisan queue:retry all

# Check logs
tail -f storage/logs/laravel.log
```

## 📈 Architectural Decisions

### Why Multi-Auth with Single Users Table?
- Simplified user management
- Easy role switching if needed
- Shared user functionality (notifications, presence)

### Why Queue-Based Import?
- Prevents timeout on large files
- Better user experience
- Scalable processing
- Error isolation

### Why Pusher Over WebSockets Package?
- Reliable service with fallbacks
- Easy scaling
- Better browser compatibility
- Reduced server resource usage

## 🤝 Contributing

1. Fork the repository
2. Create feature branch: `git checkout -b feature-name`
3. Commit changes: `git commit -am 'Add feature'`
4. Push to branch: `git push origin feature-name`
5. Submit pull request

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 📞 Support

For support, email your-email@domain.com or create an issue on GitHub.

---

**Note**: This application is built for demonstration purposes and includes all the required features from the assignment specification. The UI is intentionally minimal to focus on backend functionality, real-time features, and system architecture.
