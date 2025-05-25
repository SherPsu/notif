# Laravel Notification Center

A complete notification system built with Laravel that allows users to send notifications to administrators. The system supports both database and email notifications with a modern UI.

## Features

- **Dual User Roles**: Regular users and admin users with separate authentication
- **Email Notifications**: Markdown mailables for beautiful email templates
- **Database Notifications**: Complete notification log with read status tracking
- **AJAX Integration**: Real-time notification counters and status updates
- **Modern UI**: Responsive design using Bootstrap 5 and Font Awesome

## Tech Stack

- **Backend**: PHP + Laravel
- **Frontend**: HTML + CSS + JavaScript
- **JavaScript Libraries**: jQuery for AJAX functionality
- **CSS Framework**: Bootstrap 5
- **Icons**: Font Awesome
- **Database**: MySQL

## Demo Accounts

### Regular User
- **Email**: user@example.com
- **Password**: password

### Admin User
- **Email**: admin@example.com
- **Password**: password

## Installation

1. Clone the repository
2. Configure your `.env` file with database settings
3. Run database migrations and seeders:
   ```
   php artisan migrate --seed
   ```
4. Start the server:
   ```
   php artisan serve
   ```
5. Visit `http://localhost:8000` in your browser

## Usage

### User Flow
1. Login as a regular user
2. Navigate to "Send Notification" in the main menu
3. Fill out the notification form with subject and message
4. Submit the form to send the notification to all admins

### Admin Flow
1. Login as an admin user
2. View all notifications in the dashboard
3. Click on a notification to view details
4. Mark notifications as read/unread
5. Real-time notification counter shows unread notifications

## Project Structure

- **Models**: User, AdminUser, Notification
- **Controllers**: UserController, AdminController, NotificationController
- **Views**: Separate views for users and admins
- **Authentication**: Custom auth for both user types
- **Notifications**: Database + Email delivery

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
