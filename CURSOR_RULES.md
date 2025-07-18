# Personal Finance Tracker - Cursor Rules

## Overview

This is a Laravel-based Personal Finance Tracker application with user authentication, transaction management, and customizable features. The app uses Bootstrap for styling and includes advanced features like tag systems, image uploads, and user preferences.

## Core Technologies

-   **Backend**: Laravel 11 (PHP)
-   **Database**: MySQL
-   **Frontend**: Bootstrap 5, Blade templates
-   **Authentication**: Laravel Breeze
-   **File Storage**: Laravel Storage (public disk)

## Key Features

### Authentication

-   User registration and login via Laravel Breeze
-   Email verification support
-   Password reset functionality
-   Profile management
-   Secure session handling

### Dashboard

-   **Summary Cards**: Total balance, income, expenses, savings goals
-   **Recent Transactions**: Scrollable list with filtering and sorting
-   **Quick Actions**: Add income/expense buttons
-   **Visual Indicators**: Camera icon for transactions with images
-   **Three-dot Menu**: Clean dropdown for View/Edit/Delete actions
-   **Hover Effects**: Enhanced visual feedback for interactions

### Transactions

-   **CRUD Operations**: Create, read, update, delete transactions
-   **Tag System**: Predefined tags with color coding
-   **Dynamic Tag Selection**: Dropdown changes based on transaction type
-   **Custom Tags**: Users can enter custom tags if needed
-   **Image Upload**: Optional receipt/bill uploads (JPEG, PNG, JPG, GIF, max 2MB)
-   **Validation**: Comprehensive form validation
-   **User Isolation**: Users can only access their own transactions

### Tag System

-   **Predefined Tags**:
    -   Income: Salary, Freelance, Investment, Gift, Bonus, Other
    -   Expense: Food, Transportation, Housing, Utilities, Entertainment, Healthcare, Shopping, Education, Other
-   **Color Coding**: Each tag has a unique Bootstrap color
-   **Customizable Colors**: Users can customize tag colors in settings
-   **Visual Hierarchy**: Tags displayed prominently above transaction details

### Settings

-   **Tag Color Customization**: Users can change colors for any tag
-   **Live Preview**: Real-time color preview in settings
-   **User Preferences**: Stored per user in database
-   **Default Fallback**: Uses default colors if no custom colors set

### Image Management

-   **Secure Storage**: Images stored in `storage/app/public/transaction-images/`
-   **File Validation**: Type and size restrictions
-   **Preview System**: Modal popup for viewing images
-   **Auto Cleanup**: Old images deleted when replaced or transaction deleted
-   **Visual Indicators**: Camera icon and View button for transactions with images

## Database Structure

### Tables

-   **users**: User accounts and authentication
-   **transactions**: Financial transactions with tags and optional images
-   **user_settings**: User preferences including custom tag colors
-   **migrations**: Laravel migration tracking

### Key Fields

-   **transactions**: id, description, amount, type, tag, image_path, user_id, timestamps
-   **user_settings**: id, user_id, setting_key, setting_value, timestamps

## File Structure

```
app/
├── Http/Controllers/
│   ├── DashboardController.php      # Dashboard logic and filtering
│   ├── TransactionController.php    # Transaction CRUD operations
│   ├── SettingsController.php       # User settings management
│   └── Auth/                        # Authentication controllers
├── Models/
│   ├── Transaction.php              # Transaction model with tag colors
│   ├── User.php                     # User model with relationships
│   └── UserSetting.php              # User settings model
└── View/Components/                 # Blade components

resources/views/
├── dashboard.blade.php              # Main dashboard with transactions
├── transaction.blade.php            # Transaction form (create/edit)
├── settings.blade.php               # Settings page for customization
└── layouts/
    ├── app.blade.php                # Main layout
    └── navigation.blade.php         # Navigation bar

database/
├── migrations/                      # Database migrations
└── seeders/
    └── TransactionSeeder.php        # Sample transaction data
```

## Styling Guidelines

-   **Bootstrap 5**: Primary framework for styling
-   **Custom Colors**: Tag colors use Bootstrap color classes
-   **Responsive Design**: Mobile-friendly layouts
-   **Clean UI**: Minimal, professional appearance
-   **Hover Effects**: Enhanced user interaction feedback
-   **Consistent Spacing**: Proper margins and padding throughout

## Security Features

-   **CSRF Protection**: All forms include CSRF tokens
-   **User Isolation**: Users can only access their own data
-   **File Validation**: Strict image upload restrictions
-   **SQL Injection Prevention**: Eloquent ORM with proper queries
-   **XSS Protection**: Blade template escaping

## Performance Considerations

-   **Eager Loading**: Relationships loaded efficiently
-   **Image Optimization**: Proper storage and retrieval
-   **Database Indexing**: User-specific queries optimized
-   **Caching**: User settings cached appropriately

## Development Guidelines

-   **Laravel Conventions**: Follow Laravel best practices
-   **Blade Templates**: Use proper Blade syntax and components
-   **Database Migrations**: Always use migrations for schema changes
-   **Validation**: Comprehensive form and data validation
-   **Error Handling**: Proper error messages and fallbacks
-   **Testing**: Unit and feature tests for critical functionality

## Customization Features

-   **Tag Colors**: Fully customizable per user
-   **Transaction Types**: Extensible income/expense system
-   **Image Support**: Optional receipt storage
-   **User Preferences**: Extensible settings system
-   **Responsive Design**: Works on all device sizes

## Future Enhancements

-   Budget tracking and goals
-   Reports and analytics
-   Export functionality
-   Multi-currency support
-   Recurring transactions
-   Category management
-   Advanced filtering and search
