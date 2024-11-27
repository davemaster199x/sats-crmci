# Property Management CRM - CodeIgniter Edition

## Overview

An upgraded version of the Property Management CRM, rebuilt using CodeIgniter 4 framework. This version maintains all the robust features of the original system while adding modern architecture, improved security, and enhanced performance.

## Introduction

The SARS CRM is used by staff for their day to day activities such as scheduling jobs for technicians.

## System Architecture

### Directory Structure

```
property-crm/
├── app/
│   ├── Config/
│   │   ├── Routes.php
│   │   ├── Database.php
│   │   └── Email.php
│   ├── Controllers/
│   │   ├── Agency/
│   │   │   ├── AgencyController.php
│   │   │   ├── BookingController.php
│   │   │   └── AuditController.php
│   │   ├── Property/
│   │   │   ├── PropertyController.php
│   │   │   └── AlarmController.php
│   │   ├── Calendar/
│   │   │   ├── CalendarController.php
│   │   │   └── EventController.php
│   │   └── Financial/
│   │       ├── ExpenseController.php
│   │       └── TargetController.php
│   ├── Models/
│   │   ├── AgencyModel.php
│   │   ├── PropertyModel.php
│   │   ├── CalendarModel.php
│   │   └── FinancialModel.php
│   ├── Views/
│   │   ├── agency/
│   │   ├── property/
│   │   ├── calendar/
│   │   └── financial/
│   ├── Helpers/
│   └── Libraries/
├── public/
│   ├── assets/
│   │   ├── css/
│   │   ├── js/
│   │   └── images/
│   └── uploads/
└── vendor/
```

## Core Features

### Agency Management Module

- Enhanced agency profile management
- Integrated booking system
- Advanced audit trail
- Target management system
- Automated booking notes
- Multi-branch support

### Property Management Module

- Dynamic property listings
- Automated alarm system
- Advanced pricing management
- Property status tracking
- Unit availability monitoring
- Maintenance scheduling

### Calendar System

- Interactive calendar interface
- Event management
- Booking integration
- Automated notifications
- Resource scheduling
- Mobile-responsive design

### Financial Module

- Comprehensive expense tracking
- Target monitoring
- Financial reporting
- Invoice generation
- Payment processing
- Budget management

## Technical Requirements

### Server Requirements

- PHP 8.1 or higher
- MySQL 8.0+
- Apache/Nginx
- Composer
- Memory: 4GB minimum
- Storage: 50GB minimum

### Required Extensions

- intl
- json
- mbstring
- mysqlnd
- xml
- curl

## Installation

1. Clone the repository

```bash
git clone https://github.com/your-org/property-crm.git
cd property-crm
```

2. Install dependencies

```bash
composer install
```

3. Configure environment

```bash
cp env .env
# Edit database and application settings
```

4. Run migrations

```bash
php spark migrate
php spark db:seed InitialSetup
```

5. Set permissions

```bash
chmod -R 755 writable/
chmod -R 755 public/uploads/
```

## Configuration

### Database Setup

```php
// app/Config/Database.php
public $default = [
    'hostname' => 'localhost',
    'database' => 'property_crm',
    'username' => 'your_username',
    'password' => 'your_password',
    'DBDriver' => 'MySQLi',
    'DBPrefix' => '',
    'pConnect' => false,
    'DBDebug'  => true,
    'charset'  => 'utf8mb4',
    'DBCollat' => 'utf8mb4_unicode_ci',
];
```

### Email Configuration

```php
// app/Config/Email.php
public $fromEmail = 'noreply@propertycrm.com';
public $fromName  = 'Property CRM';
public $protocol  = 'smtp';
public $SMTPHost  = 'your.smtp.host';
public $SMTPUser  = 'smtp_username';
public $SMTPPass  = 'smtp_password';
```

## Upgraded Features

### Enhanced Security

- Advanced role-based access control
- Two-factor authentication
- API authentication
- Enhanced password policies
- Session management
- Activity logging

### Improved Performance

- Database query optimization
- Caching implementation
- Asset minification
- Lazy loading
- AJAX implementations
- Response compression

### Modern UI/UX

- Responsive design
- Interactive dashboards
- Real-time updates
- Advanced form validation
- Dynamic data tables
- Enhanced notifications

## API Integration

### RESTful Endpoints

```
/api/v1/agencies     - Agency management
/api/v1/properties   - Property operations
/api/v1/calendar     - Calendar management
/api/v1/financial    - Financial operations
```

### Authentication

```php
// Example API authentication
$token = service('token');
$auth  = $token->authenticate($request);
```

## Development Guidelines

### Coding Standards

- Follow PSR-12
- Use CodeIgniter conventions
- Implement SOLID principles
- Write comprehensive tests
- Document all methods

### Version Control

```bash
# Create feature branch
git checkout -b feature/new-feature

# Commit changes
git commit -m "Add new feature"

# Push changes
git push origin feature/new-feature
```

## Testing

### Unit Testing

```bash
# Run all tests
php spark test

# Run specific suite
php spark test --filter AgencyTest
```

### Integration Testing

```bash
# Run integration tests
php spark test --group integration
```

## Maintenance

### Regular Tasks

- Database optimization
- Log rotation
- Cache clearing
- Security updates
- Backup verification

### Automated Tasks

```bash
# Set up cron jobs
* * * * * cd /path-to-project && php spark schedule:run
```

## Troubleshooting

### Common Issues

1. Database connection errors

   - Check credentials
   - Verify MySQL service
   - Check network connectivity

2. Upload issues
   - Verify directory permissions
   - Check file size limits
   - Validate file types

## Support

- Documentation: `/docs`
- Email: support@propertycrm.com
- Issue Tracking: GitHub Issues
- Knowledge Base: `/kb`

---

Version: 2.0.0
Last Updated: November 2024
Copyright © 2024 Property CRM
