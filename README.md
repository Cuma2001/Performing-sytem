# Performing System

## Overview

`Performing System` is a Laravel-based performance management application for stores, regions, employees, users, and KPI tracking. It includes authentication, role-based dashboards, target uploads, reporting, and user management.

## Key Features

- User authentication and registration
- Password reset and profile management
- Role-based dashboards for CEO/HR, supervisors, and salespeople
- Store and region management
- KPI creation, distribution view, and upload workflow
- Employee CRUD management
- Utilities for master target upload, history, retry, delete, and stats
- User administration with lock/unlock, status toggle, reset password, bulk delete, and export
- Report overview pages

## Tech Stack

- PHP 8.2+
- Laravel 12
- Tailwind CSS + Vite
- `barryvdh/laravel-dompdf` for PDF generation
- `maatwebsite/excel` for Excel import/export
- `spatie/laravel-permission` for role and permission management

## Installation

<h2 style="color:red;text-color:red;background-color:black;padding:20px;">Commands for clonning project</h2>
<ul>
<ol>Run `git clone 'link projer github'</ol>
<ol>Run composer install.</ol>
<ol>Run cp .env.example .env or copy .env.example .env.</ol>
<ol>Run php artisan key:generate.</ol>
<ol>Run php artisan migrate.</ol>
<ol>Run php artisan db:seed.</ol>
<ol>Run php artisan serve.</ol>
<ol>Go to link localhost:8000 OR 127.0.0.1:8000.</ol>
   ```

## Configuration

- Copy `.env.example` to `.env`
- Configure database connection settings in `.env`
- Configure mail settings for password reset and email verification

## Authentication

The system provides the following auth flows:

- Registration: `GET /register`, `POST /register`
- Login: `GET /login`, `POST /login`
- Logout: `POST /logout`
- Password reset: `GET /forgot-password`, `POST /password/email`, `GET /password/reset/{token}`, `POST /password/reset`
- Email verification and password confirmation
- Profile edit: `GET /profile`, `PATCH /profile`, `DELETE /profile`

## Dashboard

Authenticated users can access:

- `/dashboard`
- `/dashboard/ceo-hr`
- `/dashboard/supervisor`
- `/dashboard/salesperson`

These routes are handled by `DashboardController` and show role-specific views and summaries.

## Main Modules

### Stores

- Resource controller: `StoreController`
- Routes: `/stores`
- Supports CRUD for store entities

### Regions

- Resource controller: `RegionController`
- Routes: `/region` and `/regions`
- Supports CRUD for regions and region status toggling

### Users

- Resource controller: `UserController`
- Routes: `/users`
- Additional actions:
  - `PATCH /users/{user}/toggle-status`
  - `PATCH /users/{user}/lock`
  - `PATCH /users/{user}/unlock`
  - `PATCH /users/{user}/reset-password`
  - `POST /users/bulk-delete`
  - `GET /users/export`

### KPIs

- Resource controller: `KPIController`
- Routes: `/kpis`
- Additional KPI operations:
  - `GET /kpi-distribution`
  - `GET /kpi-upload`

### Employees

- Resource controller: `EmployeeController`
- Routes: `/employees`

### Reports

- Controller: `ReportController`
- Route: `/reports`

### Utilities / Target Uploads

- Controller: `StoreTargetUploadController`
- Routes under `/utilities`
- Actions include:
  - `/utilities` (utilities index)
  - `/utilities/master-upload`
  - `/utilities/master-process`
  - `/utilities/master-history`
  - `/utilities/history`
  - `/utilities/history/{id}`
  - `/utilities/template`
  - `/utilities/retry/{id}`
  - `/utilities/delete/{id}`
  - `/utilities/stats`

## Database Models

The system includes models for the following main entities:

- `User`
- `Region`
- `Store`
- `Employee`
- `KPI`
- `StoreTargetUpload`
- `StoreTargets`
- `StoreTargetSummary`
- `CompanyTarget`
- `MtnTarget`
- `SupervisorTarget`
- `StorePerformance`
- `AuditLog`

## Useful Commands

- Start dev server: `php artisan serve`
- Run tests: `php artisan test`
- Clear config cache: `php artisan config:clear`
- Run migrations: `php artisan migrate`
- Seed database: `php artisan db:seed`

## Notes

- Most routes are protected by the `auth` middleware.
- The application uses Laravel resource controllers for standard CRUD workflows.
- User and utility modules include additional custom actions beyond standard resource routes.
