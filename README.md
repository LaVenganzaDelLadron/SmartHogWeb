# 🧠 Pig Farm Management System
The Pig Farm Management System is a comprehensive web application designed to manage and track various aspects of a pig farm, including pig batches, growth stages, feeding schedules, and notifications. The system aims to provide a user-friendly and efficient way to monitor and control the daily operations of a pig farm, ensuring optimal productivity and profitability.

## 🚀 Features
- **Pig Batch Management**: Create, edit, and delete pig batches, including attributes such as batch ID, name, number of pigs, and growth stage.
- **Growth Stage Management**: Define and manage growth stages for pig batches, including attributes such as growth stage name and description.
- **Feeding Schedule Management**: Create and manage feeding schedules for pig batches, including attributes such as feeding time, quantity, and type of feed.
- **Notification System**: Receive notifications for important events, such as feeding times, pig batch creation, and growth stage changes.
- **User Management**: Manage user accounts, including attributes such as name, email, and password.
- **API Endpoints**: Utilize API endpoints for authentication, pig batch management, growth stage management, feeding schedule management, and notification management.

## 🛠️ Tech Stack
- **Frontend**: Php
- **Backend**: Python
- **Database**:  Postgresql
- **API**: Laravel API
- **Authentication**: Laravel Sanctum
- **Queue System**: Laravel Queue
- **Logging**: Laravel Logging

## 📦 Installation
To install the Pig Farm Management System, follow these steps:
1. Clone the repository using `git clone`.
2. Run `composer install` to install the dependencies.
3. Run `php artisan migrate` to create the database tables.
4. Run `php artisan db:seed` to seed the database with initial data.
5. Run `php artisan serve` to start the development server.

## 💻 Usage
To use the Pig Farm Management System, follow these steps:
1. Access the application through the web interface or API endpoints.
2. Create a user account and log in to the system.
3. Create and manage pig batches, growth stages, feeding schedules, and notifications.
4. Utilize the API endpoints for authentication, pig batch management, growth stage management, feeding schedule management, and notification management.

## 📂 Project Structure
```markdown
app
├── Console
├── Exceptions
├── Http
│   ├── Controllers
│   ├── Kernel.php
│   ├── Middleware
│   └── Requests
├── Models
│   ├── Pen.php
│   ├── GrowthStage.php
│   ├── PigBatch.php
│   ├── User.php
│   └── Notification.php
├── Providers
│   ├── AppServiceProvider.php
│   └── RouteServiceProvider.php
├── Services
└── bootstrap
    ├── app.php
    └── providers.php
config
├── app.php
├── auth.php
├── broadcast.php
├── cache.php
├── database.php
├── filesystems.php
├── logging.php
├── mail.php
├── queue.php
├── services.php
└── session.php
database
├── migrations
└── seeds
public
├── index.php
└── webpack.mix.js
resources
├── js
└── sass
routes
├── api.php
└── web.php
tests
├── Feature
├── Unit
└── ExampleTest.php
vendor
└── composer
```


## 🤝 Contributing
To contribute to the Pig Farm Management System, please follow these steps:
1. Fork the repository using `git fork`.
2. Create a new branch using `git branch`.
3. Make changes to the code and commit them using `git commit`.
4. Push the changes to the remote repository using `git push`.
5. Create a pull request to merge the changes into the main branch.

## 📬 Contact
For any questions or concerns, please contact us at [darkglitch5417@gmail.com]

## 💖 Thanks
