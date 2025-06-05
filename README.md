# 🏫 Sports Academy Management System (Multi-Tenant)

A Laravel-based multi-tenant sports academy management application with role-based access control, PostgreSQL database, and Tailwind CSS UI.

## 🚀 Features

- 🔐 **Role-Based Access**
  - **Admin**: Full access to all academies, users, transactions, and reports.
  - **Manager**: Access to their own academy's students, employees, sports, expenses, and financial records.
  - **Employee (Coach)**: Take attendance for their assigned sport, view salary deposit history.
  - **Student**: View own attendance and fee deposit transactions.

- 🏢 **Multi-Tenant Academy Structure**
  - Each academy manages its own data (students, employees, sports, expenses, etc.)
  - Admin can create and manage multiple academies.

- 📊 **Dashboard Overview**
  - Fee collection summary
  - Salary paid summary
  - Expense tracking
  - Net revenue overview
  - All with academy-wise breakdowns

- 👨‍🎓 **Student Management**
  - List, search, and filter students
  - Fee transaction history per student
  - Attendance record view

- 🧑‍🏫 **Employee Management**
  - Coach listing and profiles
  - Salary transaction history
  - Attendance management for their sport

- 💸 **Financial System**
  - Unified `transactions` table
  - Track:
    - Fee deposits (from students)
    - Salary deposits (to employees)
    - Expenses (for academy)

- 🧾 **Expense Page**
  - Add and manage academy expenses
  - Filter by academy and date

- 📅 **Attendance System**
  - Daily attendance for students by sport
  - Taken by employees (coaches)
  - Filter by sport, academy, student, and date
  - One attendance entry per day per student

- 🏅 **Sport Management**
  - List of sports per academy
  - Add, edit, and filter sports

## 🛠️ Tech Stack

- **Framework**: Laravel
- **UI**: Tailwind CSS
- **Database**: PostgreSQL
- **Auth**: Laravel Breeze / Sanctum
- **Local Development**: Laravel Herd (Mac/Windows)

## ⚙️ Installation Guide (Using Laravel Herd)

```bash
# 1. Clone the repository
git clone https://github.com/Vishalcd/academy-multi-tenant-system.git
cd academy-management

# 2. Install PHP dependencies
composer install

# 3. Install JavaScript dependencies
npm install && npm run build

# 4. Setup environment file
cp .env.example .env

# 5. Generate application key
php artisan key:generate

# 6. Update your .env file with PostgreSQL DB credentials

# 7. Run migrations and seeders
php artisan migrate --seed

# 8. Open the project in Laravel Herd (it will automatically serve the project)
#    URL typically: http://academy-management.test
