# MOSRAC Exhibition Management System

A comprehensive **Exhibition Management, Quotation, Booking, Invoice, Payment and Attendee Management System** developed for the **Ministry of Sport, Recreation, Arts and Culture (MOSRAC)**.

The system provides separate portals for **MOSRAC administrators** and **exhibitors**, allowing the Ministry to manage exhibitions while exhibitors can submit quotation requests, manage bookings, track invoices, submit payments and manage attendees.

---

## Overview

The MOSRAC Exhibition Management System digitizes the process of managing exhibition events and exhibitors from initial quotation requests through booking, invoicing, payment and attendee management.

The platform provides two distinct experiences:

### MOSRAC Administration

Administrators can:

* Create and manage exhibitions
* Configure exhibition spaces
* Configure stand types
* Configure stand positions
* Manage furniture and services
* Review exhibitor quotation requests
* Approve or reject quotations
* Manage bookings
* Manage exhibitors and clients
* Manage invoices
* Verify payments
* Manage attendees
* Approve or reject attendees
* Generate and manage badges
* Monitor exhibition activity
* Manage users and permissions
* Manage system settings
* Track expenses

### Exhibitor Portal

Exhibitors can:

* View available exhibitions
* Submit quotation requests
* Select exhibition spaces and stands
* Select furniture and additional services
* Specify the number of people attending
* Track quotation status
* Confirm approved quotations
* View bookings
* View invoices
* Track payment status
* Submit payment proof
* Manage attendees
* View badges
* Manage their profile

---

## Key Workflow

The system follows a strict approval workflow to ensure that exhibitors cannot bypass the Ministry's approval process.

```text
                    MOSRAC ADMIN
                         │
                         ▼
                 Create Exhibition
                         │
                         ▼
                  Publish Event
                         │
                         ▼
                    EXHIBITOR
                         │
                         ▼
                 Select Exhibition
                         │
                         ▼
              Enter Exhibitor Details
                         │
                         ▼
              Select Number of People
                         │
                         ▼
             Configure Space & Stand
                         │
                         ▼
                Select Furniture
                         │
                         ▼
                Select Services
                         │
                         ▼
                  Review Request
                         │
                         ▼
                Submit Quotation
                         │
                         ▼
              PENDING ADMIN APPROVAL
                         │
                  ┌──────┴──────┐
                  │             │
                  ▼             ▼
               APPROVED      REJECTED
                  │
                  ▼
             EXHIBITOR REVIEWS
                  │
                  ▼
          EXHIBITOR CONFIRMS
                  │
                  ▼
              BOOKING CREATED
                  │
                  ▼
             INVOICE GENERATED
                  │
                  ▼
                 PAYMENT
                  │
                  ▼
            ADMIN VERIFIES PAYMENT
                  │
                  ▼
          ATTENDEES & BADGES
```

### Important Business Rule

Submitting a quotation **does not create a booking**.

A booking is created only when:

1. The exhibitor submits the quotation.
2. MOSRAC approves the quotation.
3. The exhibitor confirms the approved quotation.

This ensures that all bookings are authorized by MOSRAC.

---

## Main Features

### Exhibition Management

Administrators can create and configure exhibitions with:

* Event information
* Exhibition spaces/halls
* Stand types
* Stand positions
* Furniture
* Additional services
* Attendee types
* Exhibition capacity

Exhibitors can only select from events made available by administrators.

---

### Quotation Management

The quotation system allows exhibitors to configure their exhibition requirements before submitting a request.

The quotation wizard includes:

1. Event selection
2. Exhibitor/company information
3. Number of people attending
4. Exhibition space and stand configuration
5. Furniture selection
6. Additional services
7. Quotation review
8. Terms and submission

Submitted quotations remain:

**Pending Admin Approval**

until reviewed by MOSRAC.

---

### Multi-Step Quotation Wizard

The quotation process uses a step-by-step wizard instead of presenting all fields on one page.

```text
1. Select Event
        ↓
2. Exhibitor Information
        ↓
3. Number of People
        ↓
4. Space & Stand
        ↓
5. Furniture
        ↓
6. Services
        ↓
7. Review
        ↓
8. Terms & Submit
```

The wizard starts with no selections made, requiring the exhibitor to explicitly choose their requirements.

---

### People / Attendee Management

Exhibitors can specify the number of people attending an exhibition.

The system stores the value as:

```text
people_count
```

for the quotation and carries it over to the booking after quotation approval and exhibitor confirmation.

Administrators can manage and approve individual attendees.

---

### Booking Management

Bookings are created only after the quotation has been:

```text
Approved by MOSRAC
        +
Confirmed by Exhibitor
```

Administrators can view and manage bookings across all exhibitions.

Exhibitors can only view their own bookings.

---

### Invoice Management

Invoices can be generated from confirmed bookings.

Exhibitors can:

* View invoices
* View invoice totals
* View amount paid
* View outstanding balance
* Track payment status

Administrators can manage the complete invoice lifecycle.

---

### Payment Management

Exhibitors can submit payment information and proof of payment.

Administrators can:

* Review submitted payments
* Verify payments
* Reject payments
* Update invoice balances
* Monitor outstanding balances

Typical payment lifecycle:

```text
Payment Submitted
        ↓
Pending Verification
        ↓
Admin Review
        ↓
Verified / Rejected
```

---

### Attendee Management

Exhibitors can manage attendees associated with their bookings.

Administrators can:

* View all attendees
* Approve attendees
* Reject attendees
* Manage attendee information
* Generate badges

---

### Badge Management

The system supports:

* Individual badge generation
* Batch badge printing
* Badge status management
* Attendee-to-badge association

---

## Role-Based Access Control

The system has two primary roles.

| Role          | Access                                                             |
| ------------- | ------------------------------------------------------------------ |
| Administrator | Full MOSRAC management functionality                               |
| Exhibitor     | Own quotations, bookings, invoices, payments, attendees and badges |

### Administrator

`role_id = 1`

Administrators have access to the complete administration system.

### Exhibitor

`role_id = 2`

Exhibitors are restricted to their own portal and records.

Server-side authorization prevents exhibitors from accessing administrator functionality by manually entering admin URLs.

---

## Authentication

### Exhibitor Login

```text
http://localhost:8000/login
```

Exhibitors are redirected to:

```text
/customer/dashboard
```

### MOSRAC Administrator Login

```text
http://localhost:8000/admin/mosrac
```

Administrators are redirected to:

```text
/dashboard
```

The administrator login is intentionally separated from the general exhibitor login.

---

## Admin Dashboard

The MOSRAC dashboard provides an overview of the exhibition system.

It can include:

* Total exhibitions
* Upcoming exhibitions
* Total exhibitors
* Pending quotations
* Approved quotations
* Rejected quotations
* Total bookings
* Pending bookings
* Total attendees
* Pending attendee approvals
* Total invoices
* Outstanding balances
* Payments awaiting verification
* Recent activity
* Upcoming exhibitions
* Action-required items

---

## Exhibitor Dashboard

The exhibitor portal provides a personalized overview of the exhibitor's activity.

It includes information such as:

* Upcoming exhibition
* Quotation status
* Booking status
* Number of people attending
* Invoice balance
* Payment status
* Attendee status
* Badge availability
* Required actions

Exhibitors only see information belonging to their own account.

---

## Technology Stack

### Backend

* PHP
* Laravel
* Laravel Blade
* Eloquent ORM

### Database

* MySQL / compatible relational database

### Frontend

* HTML5
* CSS3
* JavaScript
* Bootstrap / existing project UI framework

### Development Tools

* Composer
* Node.js / NPM
* Git
* GitHub

---

## System Architecture

The system follows a Laravel MVC architecture.

```text
                    ┌──────────────────┐
                    │     Browser      │
                    └────────┬─────────┘
                             │
                             ▼
                    ┌──────────────────┐
                    │ Laravel Routes   │
                    └────────┬─────────┘
                             │
              ┌──────────────┴──────────────┐
              │                             │
              ▼                             ▼
     ┌─────────────────┐           ┌─────────────────┐
     │  Admin Portal   │           │ Exhibitor Portal│
     └────────┬────────┘           └────────┬────────┘
              │                             │
              └──────────────┬──────────────┘
                             ▼
                    ┌──────────────────┐
                    │   Controllers    │
                    └────────┬─────────┘
                             │
                             ▼
                    ┌──────────────────┐
                    │ Eloquent Models  │
                    └────────┬─────────┘
                             │
                             ▼
                    ┌──────────────────┐
                    │     Database     │
                    └──────────────────┘
```

---

## Core Models

The system includes models for:

* User
* Role
* Permission
* Client
* Event
* Event Space
* Stand Type
* Space Position
* Furniture
* Event Service
* Attendee Type
* Quotation
* Quotation Item
* Booking
* Booking Item
* Booking Status History
* Invoice
* Invoice Item
* Invoice Email
* Payment
* Attendee
* Badge
* Expense
* Settings
* Email Template

---

## Installation

### Requirements

Before installing the system, ensure you have:

* PHP 8.2+
* Composer
* MySQL
* Node.js and NPM
* Git

Check your installed versions:

```bash
php -v
composer -V
node -v
npm -v
```

---

### 1. Clone the Repository

```bash
git clone https://github.com/YOUR-USERNAME/InvoiceQuotationSystem.git
```

Navigate into the project:

```bash
cd InvoiceQuotationSystem
```

---

### 2. Install PHP Dependencies

```bash
composer install
```

---

### 3. Install Frontend Dependencies

```bash
npm install
```

---

### 4. Configure Environment

Copy the example environment file:

```bash
cp .env.example .env
```

On Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

Generate the Laravel application key:

```bash
php artisan key:generate
```

---

### 5. Configure Database

Update `.env` with your database configuration.

Example:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=invoice_quotation_system
DB_USERNAME=root
DB_PASSWORD=
```

Create the database before running migrations.

---

### 6. Run Migrations

```bash
php artisan migrate
```

If seeders are configured:

```bash
php artisan db:seed
```

Or:

```bash
php artisan migrate --seed
```

---

### 7. Build Frontend Assets

For development:

```bash
npm run dev
```

For production:

```bash
npm run build
```

---

### 8. Start Laravel

```bash
php artisan serve
```

The application will normally be available at:

```text
http://127.0.0.1:8000
```

---

## Development URLs

### Public Homepage

```text
http://localhost:8000/
```

### Exhibitor Login

```text
http://localhost:8000/login
```

### Exhibitor Dashboard

```text
http://localhost:8000/customer/dashboard
```

### MOSRAC Admin Login

```text
http://localhost:8000/admin/mosrac
```

### Admin Dashboard

```text
http://localhost:8000/dashboard
```

---

## Security

The system implements role-based access control and server-side authorization.

Important security principles include:

* Separate administrator and exhibitor authentication
* Role-based authorization
* Server-side ownership validation
* Exhibitor data isolation
* CSRF protection
* Password hashing
* Protected administrative routes
* Protected payment operations
* Protected invoice access
* Protected quotation confirmation
* Validation of quotation and booking ownership

Exhibitors cannot access or modify another exhibitor's records.

---

## Business Rules

### Quotation Approval

A quotation submitted by an exhibitor is **not automatically accepted**.

```text
Submitted
    ↓
Pending Admin Approval
    ↓
Admin Approves
    ↓
Exhibitor Confirms
    ↓
Booking Created
```

### Booking Creation

A booking cannot be created from a quotation that has not been approved by an administrator.

### Events

Only MOSRAC administrators create and manage exhibition events.

### Exhibitors

Exhibitors can only interact with events made available by MOSRAC and can only access their own records.

### Payments

Payments submitted by exhibitors require administrator verification before they are considered verified.

---

## Project Structure

Important Laravel directories include:

```text
app/
├── Http/
│   ├── Controllers/
│   └── Middleware/
├── Models/
│
database/
├── migrations/
└── seeders/
│
resources/
├── views/
└── ...
│
routes/
└── web.php
│
public/
│
storage/
│
.env.example
composer.json
package.json
```

---

## Future Improvements

Potential future enhancements include:

* Online payment gateway integration
* QR-code based attendee check-in
* Automated email notifications
* SMS notifications
* Advanced exhibition analytics
* Real-time event capacity monitoring
* Interactive exhibition floor maps
* Automated invoice reminders
* Advanced reporting and exports
* Mobile application for exhibitors
* QR-code badge scanning
* Integration with government/organizational systems

---

## Contributing

Contributions, suggestions and improvements are welcome.

To contribute:

```bash
git clone <repository-url>
cd InvoiceQuotationSystem
composer install
php artisan migrate
php artisan db:seed
php artisan serve
```

Create a feature branch:

```bash
git checkout -b feature/your-feature
```

Make your changes, test them and submit a pull request.

---

## License

This project is intended for exhibition and event management purposes.

Add the appropriate project license here if this repository is intended to be open source.

---

## Project Status

**Development**

The system is actively being developed and refined.

Current core functionality includes:

* Exhibition management
* Exhibitor management
* Multi-step quotation requests
* Quotation approval workflow
* Booking management
* Invoice management
* Payment management
* Attendee management
* Badge management
* Role-based dashboards
* Administrator management
* Exhibitor portal

---

## Author

**Tatenda Ainos Junior Makura**

Software Engineering
Harare Institute of Technology

---

## Acknowledgements

Developed as an exhibition management solution for the **Ministry of Sport, Recreation, Arts and Culture (MOSRAC)**.
