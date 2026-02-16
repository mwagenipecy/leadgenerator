# Lead Generator - Blade Files Inventory

This document provides a comprehensive list of all Blade template files in the system, organized by category and purpose.

## 📁 File Structure Overview

Total Blade Files: ~189 files

---

## 🎨 **LAYOUTS** (3 files)
Base layouts that wrap page content

### Main Layouts
- `resources/views/layouts/app.blade.php` - Main authenticated application layout (with sidebar, navbar, Livewire)
- `resources/views/layouts/guest.blade.php` - Guest/public layout (for login, registration)
- `resources/views/layouts/onboard.blade.php` - Onboarding flow layout

### Component Layouts
- `resources/views/components/layouts/app.blade.php` - Component version of app layout
- `resources/views/components/layouts/kyc.blade.php` - KYC verification layout

---

## 🔐 **AUTHENTICATION** (8 files)
User authentication and verification pages

- `resources/views/auth/login.blade.php` - Login page
- `resources/views/auth/register.blade.php` - Registration page
- `resources/views/auth/forgot-password.blade.php` - Password reset request
- `resources/views/auth/reset-password.blade.php` - Password reset form
- `resources/views/auth/otp.blade.php` - OTP verification page
- `resources/views/auth/confirm-password.blade.php` - Password confirmation
- `resources/views/auth/two-factor-challenge.blade.php` - Two-factor authentication
- `resources/views/auth/verify-email.blade.php` - Email verification

---

## 📧 **EMAIL TEMPLATES** (8 files)
Email notification templates

- `resources/views/emails/otp.blade.php` - OTP code email
- `resources/views/emails/reset-password.blade.php` - Password reset email
- `resources/views/emails/lender-account-created.blade.php` - Lender account creation notification
- `resources/views/emails/lender-status-change-notification.blade.php` - Lender status change notification
- `resources/views/emails/lender-status-changed.blade.php` - Alternative lender status change email
- `resources/views/emails/user-status-change-notification.blade.php` - User status change notification
- `resources/views/emails/promotion.blade.php` - Promotion/announcement email
- `resources/views/emails/team-invitation.blade.php` - Team invitation email

---

## 🏠 **PUBLIC PAGES** (4 files)
Public-facing pages accessible without authentication

- `resources/views/welcome.blade.php` - Landing/home page
- `resources/views/blog/index.blade.php` - Blog listing page
- `resources/views/blog/show.blade.php` - Individual blog post
- `resources/views/pages/terms.blade.php` - Terms and Conditions (standalone page)
- `resources/views/terms.blade.php` - Alternative terms page
- `resources/views/policy.blade.php` - Privacy policy page

---

## 🎯 **LIVEWIRE COMPONENTS** (~80 files)
Interactive Livewire components for dynamic functionality

### **Layout Components** (3 files)
- `resources/views/livewire/layout/side-bar.blade.php` - Main sidebar navigation
- `resources/views/livewire/layout/nav-bar.blade.php` - Top navigation bar
- `resources/views/livewire/layout/notification-dropdown.blade.php` - Notification dropdown

### **Admin Management** (21 files)
- `resources/views/livewire/admin/admin-dashboard.blade.php` - Admin dashboard
- `resources/views/livewire/admin/user-management.blade.php` - User management main view
- `resources/views/livewire/admin/partials/user-tab.blade.php` - User list partial
- `resources/views/livewire/admin/partials/roles-tab.blade.php` - Roles tab partial
- `resources/views/livewire/admin/partials/permissions-tab.blade.php` - Permissions tab partial
- `resources/views/livewire/admin/role-management.blade.php` - Role management
- `resources/views/livewire/admin/role-management/modals.blade.php` - Role management modals
- `resources/views/livewire/admin/permission-management.blade.php` - Permission management
- `resources/views/livewire/admin/permission-management/modals.blade.php` - Permission management modals
- `resources/views/livewire/admin/company-verification.blade.php` - Company verification list
- `resources/views/livewire/admin/company-verification-actions.blade.php` - Company verification actions
- `resources/views/livewire/admin/lender-management.blade.php` - Lender management (if exists)
- `resources/views/livewire/admin/billing-management.blade.php` - Billing management
- `resources/views/livewire/admin/settings-management.blade.php` - System settings
- `resources/views/livewire/admin/system-logs.blade.php` - System logs viewer
- `resources/views/livewire/admin/blog-management.blade.php` - Blog management
- `resources/views/livewire/admin/blog-create.blade.php` - Create blog post
- `resources/views/livewire/admin/blog-edit.blade.php` - Edit blog post
- `resources/views/livewire/admin/hero-slider-management.blade.php` - Hero slider management
- `resources/views/livewire/admin/promotion-management.blade.php` - Promotion management
- `resources/views/livewire/admin/integration-management.blade.php` - Integration management

### **Lender Components** (3 files)
- `resources/views/livewire/lender/lender-dashboard.blade.php` - Lender dashboard
- `resources/views/livewire/lender/dashboard.blade.php` - Alternative lender dashboard
- `resources/views/livewire/lender/lender-management.blade.php` - Lender management interface

### **Borrower Components** (1 file)
- `resources/views/livewire/borrower/borrower-dashboard.blade.php` - Borrower dashboard

### **Loan Application Components** (6 files)
- `resources/views/livewire/loan-application/loan-applications-list.blade.php` - List of loan applications
- `resources/views/livewire/loan-application/application-list.blade.php` - Application listing
- `resources/views/livewire/loan-application/application-management.blade.php` - Application management
- `resources/views/livewire/loan-application/loan-application-view.blade.php` - View single application
- `resources/views/livewire/loan-application/pre-qualification.blade.php` - Pre-qualification form
- `resources/views/livewire/loan-application/complete-loan-application.blade.php` - Complete application form

### **Loan Product Components** (3 files)
- `resources/views/livewire/loan-product/loan-product-management.blade.php` - Product management
- `resources/views/livewire/loan-product/loan-product-form.blade.php` - Product form
- `resources/views/livewire/loan-product/loan-product-overview.blade.php` - Product overview

### **Lead Management Components** (13 files)
- `resources/views/livewire/leads/lead-management.blade.php` - Main lead management
- `resources/views/livewire/leads/lead-management-container.blade.php` - Lead management container
- `resources/views/livewire/leads/lead-listing.blade.php` - Lead listing
- `resources/views/livewire/leads/lead-card.blade.php` - Lead card component
- `resources/views/livewire/leads/components/lead-detail.blade.php` - Lead detail view
- `resources/views/livewire/leads/components/lead-overview.blade.php` - Lead overview
- `resources/views/livewire/leads/components/lead-personal.blade.php` - Personal information
- `resources/views/livewire/leads/components/lead-employment.blade.php` - Employment information
- `resources/views/livewire/leads/components/lead-financial.blade.php` - Financial information
- `resources/views/livewire/leads/components/lead-documents.blade.php` - Documents section
- `resources/views/livewire/leads/components/lead-credit-report.blade.php` - Credit report section
- `resources/views/livewire/leads/components/lead-timeline.blade.php` - Activity timeline
- `resources/views/livewire/leads/components/lead-card.blade.php` - Lead card (alternative)

### **Onboarding Components** (4 files)
- `resources/views/livewire/onboarding/register.blade.php` - User registration form
- `resources/views/livewire/onboarding/company-kyc.blade.php` - Company KYC form
- `resources/views/livewire/onboarding/nida-verification.blade.php` - NIDA verification
- `resources/views/livewire/onboarding/verification-method.blade.php` - Verification method selector

### **Verification Components** (5 files)
- `resources/views/livewire/verification-method-selector.blade.php` - Verification method selector
- `resources/views/livewire/phone-photo-verification.blade.php` - Phone photo verification
- `resources/views/livewire/qr-code-verification.blade.php` - QR code verification
- `resources/views/livewire/questionnaire-verification.blade.php` - Questionnaire verification
- `resources/views/livewire/license-verification.blade.php` - License verification

### **Credit Report Components** (2 files)
- `resources/views/livewire/credit-report/credit-report-search.blade.php` - Credit report search
- `resources/views/livewire/credit-info-component.blade.php` - Credit info component
- `resources/views/livewire/application-credit-info.blade.php` - Application credit info

### **TRA Check Components** (2 files)
- `resources/views/livewire/tra-check/taxpayer-details-component.blade.php` - Taxpayer details
- `resources/views/livewire/tra-check/motor-vehicle-details-component.blade.php` - Motor vehicle details

### **Other Livewire Components** (6 files)
- `resources/views/livewire/blog/blog-listing.blade.php` - Blog listing component
- `resources/views/livewire/blog/blog-detail.blade.php` - Blog detail component
- `resources/views/livewire/profile/profile-management.blade.php` - Profile management
- `resources/views/livewire/notifications/index.blade.php` - Notifications index
- `resources/views/livewire/reports/booking-reports.blade.php` - Booking reports
- `resources/views/livewire/no-permissions.blade.php` - No permissions page
- `resources/views/livewire/component/transaction-analysis-component.blade.php` - Transaction analysis
- `resources/views/livewire/dashboard/admin-dashboard.blade.php` - Admin dashboard (alternative)
- `resources/views/livewire/dashboard/lender-dashboard.blade.php` - Lender dashboard (alternative)
- `resources/views/livewire/dashboard/user-dashboard.blade.php` - User dashboard

---

## 📄 **PAGE VIEWS** (~40 files)
Traditional Blade views for specific routes

### **Admin Pages** (3 files)
- `resources/views/pages/admin/loan-categories/index.blade.php` - Loan categories list
- `resources/views/pages/admin/loan-categories/create.blade.php` - Create loan category
- `resources/views/pages/admin/loan-categories/edit.blade.php` - Edit loan category

### **User Management Pages** (3 files)
- `resources/views/pages/user-management/index.blade.php` - User management index
- `resources/views/pages/user-management/roles.blade.php` - Roles management page
- `resources/views/pages/user-management/permissions.blade.php` - Permissions management page

### **Company Verification Pages** (2 files)
- `resources/views/pages/company-verification/index.blade.php` - Company verification list
- `resources/views/pages/company-verification/show.blade.php` - Company verification detail

### **Loan Application Pages** (7 files)
- `resources/views/pages/application/application-list.blade.php` - Application list
- `resources/views/pages/application/application-view.blade.php` - Application view
- `resources/views/pages/application/view-application.blade.php` - View application (alternative)
- `resources/views/pages/application/create-application.blade.php` - Create application
- `resources/views/pages/application/loan-application.blade.php` - Loan application form
- `resources/views/pages/application/completed-applications.blade.php` - Completed applications
- `resources/views/pages/application/profile.blade.php` - Application profile

### **Loan Product Pages** (4 files)
- `resources/views/pages/loanProduct/index.blade.php` - Loan products list
- `resources/views/pages/loanProduct/create.blade.php` - Create loan product
- `resources/views/pages/loanProduct/edit.blade.php` - Edit loan product
- `resources/views/pages/loanProduct/show.blade.php` - Show loan product

### **Lender Management Pages** (2 files)
- `resources/views/pages/lenderManagement/index.blade.php` - Lender list
- `resources/views/pages/lenderManagement/viewLender.blade.php` - View lender details

### **Onboarding Pages** (6 files)
- `resources/views/pages/onboarding/register.blade.php` - Registration page
- `resources/views/pages/onboarding/register-company.blade.php` - Company registration
- `resources/views/pages/onboarding/verification-method.blade.php` - Verification method selection
- `resources/views/pages/onboarding/phone-verification.blade.php` - Phone verification
- `resources/views/pages/onboarding/qr-code.blade.php` - QR code verification page
- `resources/views/pages/onboarding/questionnaire.blade.php` - Questionnaire verification

### **Verification Pages** (4 files)
- `resources/views/pages/verification/credit-report.blade.php` - Credit report verification
- `resources/views/pages/verification/taxpayer.blade.php` - Taxpayer verification
- `resources/views/pages/verification/motor-vehicle.blade.php` - Motor vehicle verification
- `resources/views/pages/verification/lincense.blade.php` - License verification

### **User Pages** (2 files)
- `resources/views/pages/user/profile.blade.php` - User profile
- `resources/views/pages/user/setting.blade.php` - User settings

### **Other Pages** (4 files)
- `resources/views/pages/billing/billing-section.blade.php` - Billing section
- `resources/views/pages/integrations/webhook-integration.blade.php` - Webhook integration
- `resources/views/pages/system-logs.blade.php` - System logs page
- `resources/views/pages/system-settings/index.blade.php` - System settings

### **PDF/Export Views** (1 file)
- `resources/views/loan-applications/pdf/[pdf-template].blade.php` - PDF template for loan applications

---

## 🧩 **COMPONENTS** (~30 files)
Reusable Blade components

### **Form Components**
- `resources/views/components/input.blade.php` - Input field
- `resources/views/components/label.blade.php` - Form label
- `resources/views/components/checkbox.blade.php` - Checkbox input
- `resources/views/components/button.blade.php` - Button component
- `resources/views/components/danger-button.blade.php` - Danger button
- `resources/views/components/secondary-button.blade.php` - Secondary button
- `resources/views/components/input-error.blade.php` - Error message display
- `resources/views/components/validation-errors.blade.php` - Validation errors display

### **Navigation Components**
- `resources/views/components/nav-link.blade.php` - Navigation link
- `resources/views/components/responsive-nav-link.blade.php` - Responsive nav link
- `resources/views/components/dropdown.blade.php` - Dropdown menu
- `resources/views/components/dropdown-link.blade.php` - Dropdown link

### **Modal/Dialog Components**
- `resources/views/components/modal.blade.php` - Modal component
- `resources/views/components/dialog-modal.blade.php` - Dialog modal
- `resources/views/components/confirmation-modal.blade.php` - Confirmation modal

### **Section Components**
- `resources/views/components/action-section.blade.php` - Action section wrapper
- `resources/views/components/form-section.blade.php` - Form section wrapper
- `resources/views/components/section-title.blade.php` - Section title
- `resources/views/components/section-border.blade.php` - Section border divider
- `resources/views/components/action-message.blade.php` - Action message display

### **Authentication Components**
- `resources/views/components/authentication-card.blade.php` - Auth card wrapper
- `resources/views/components/authentication-card-logo.blade.php` - Auth card logo
- `resources/views/components/confirms-password.blade.php` - Password confirmation

### **Branding Components**
- `resources/views/components/application-logo.blade.php` - Application logo
- `resources/views/components/application-mark.blade.php` - Application mark

### **Other Components**
- `resources/views/components/banner.blade.php` - Banner component
- `resources/views/components/welcome.blade.php` - Welcome component
- `resources/views/components/switchable-team.blade.php` - Team switcher

---

## 📱 **API VIEWS** (2 files)
API token management views

- `resources/views/api/index.blade.php` - API token management
- `resources/views/api/api-token-manager.blade.php` - API token manager component

---

## 👤 **PROFILE PAGES** (5 files)
User profile management

- `resources/views/profile/show.blade.php` - Profile show page
- `resources/views/profile/update-profile-information-form.blade.php` - Update profile form
- `resources/views/profile/update-password-form.blade.php` - Update password form
- `resources/views/profile/two-factor-authentication-form.blade.php` - 2FA form
- `resources/views/profile/delete-user-form.blade.php` - Delete user form
- `resources/views/profile/logout-other-browser-sessions-form.blade.php` - Logout sessions form

---

## 🗂️ **MISC/LEGACY FILES** (5 files)
Old or test files

- `resources/views/dashboard.blade.php` - Main dashboard
- `resources/views/dashboard2.blade.php` - Dashboard variant 2
- `resources/views/dashboard3.blade.php` - Dashboard variant 3
- `resources/views/login.blade.php` - Alternative login
- `resources/views/login2.blade.php` - Alternative login 2
- `resources/views/demo.blade.php` - Demo page
- `resources/views/demo2.blade.php` - Demo page 2
- `resources/views/dome3.blade.php` - Demo page 3
- `resources/views/nida.blade.php` - NIDA page
- `resources/views/navigation-menu.blade.php` - Navigation menu
- `resources/views/onboarding.blade.php` - Onboarding page

---

## 📊 **Summary by Category**

| Category | Count | Description |
|----------|-------|-------------|
| **Layouts** | 3 | Base page layouts |
| **Authentication** | 8 | Login, register, OTP, password reset |
| **Email Templates** | 8 | Email notifications |
| **Public Pages** | 4 | Landing, blog, terms |
| **Livewire Components** | ~80 | Interactive components |
| **Page Views** | ~40 | Traditional route views |
| **Components** | ~30 | Reusable UI components |
| **API Views** | 2 | API management |
| **Profile Pages** | 5 | User profile management |
| **Misc/Legacy** | 5+ | Test/old files |
| **TOTAL** | **~189** | All Blade files |

---

## 🎯 **Key Features by Blade Files**

### **Admin Features**
- User Management (CRUD, status changes, password confirmation)
- Role & Permission Management
- Company Verification
- Lender Management (suspend/reactivate)
- Loan Categories Management
- System Logs Viewer
- Blog Management
- Hero Slider Management
- Promotion Management
- Billing Management
- Settings Management
- Integration Management

### **Lender Features**
- Lender Dashboard
- Lead Management
- Loan Application Review
- Loan Product Management

### **Borrower Features**
- Borrower Dashboard
- Loan Application Creation
- Application Status Tracking
- Profile Management

### **Onboarding Features**
- User Registration
- Company Registration
- NIDA Verification
- Phone/Photo Verification
- QR Code Verification
- Questionnaire Verification
- Company KYC

### **Verification Features**
- Credit Report Search
- TRA Taxpayer Check
- Motor Vehicle Check
- License Verification

### **System Features**
- Notifications System
- Reports (Booking Reports)
- System Logging
- Email Notifications

---

## 📝 **Notes**

1. **Livewire Integration**: Most interactive features use Livewire components for real-time updates
2. **Layout System**: Uses component-based layouts (`<x-app-layout>`, `<x-guest-layout>`)
3. **Responsive Design**: All views use Tailwind CSS for responsive design
4. **Authentication Flow**: Multi-step authentication with OTP verification
5. **Role-Based Access**: Different dashboards and views for Admin, Lender, and Borrower roles
6. **Email System**: Comprehensive email notification system for status changes
7. **System Logging**: All critical actions are logged and viewable in system logs

---

## 🔍 **File Naming Conventions**

- **Livewire Components**: `kebab-case.blade.php` in `livewire/` directory
- **Page Views**: `kebab-case.blade.php` in `pages/` directory
- **Components**: `kebab-case.blade.php` in `components/` directory
- **Layouts**: `kebab-case.blade.php` in `layouts/` directory
- **Emails**: `kebab-case.blade.php` in `emails/` directory

---

*Last Updated: Generated from codebase analysis*
*Total Files: ~189 Blade templates*

