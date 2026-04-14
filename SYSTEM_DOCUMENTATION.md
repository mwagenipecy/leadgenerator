# Lead Generator System Documentation

## 1) Document Control

- **System Name:** Lead Generator Platform
- **Repository:** `lead_generator`
- **Date:** 2026-04-14
- **Prepared For:** Product, Engineering, QA, DevOps, Security, and Operations
- **Purpose:** Provide end-to-end documentation for requirements, architecture, design, implementation, testing, deployment, and operations.

---

## 2) Executive Overview

This repository currently contains two application stacks:

1. **Primary product stack (`LeadGenerator/`):**
   - Three React applications (`admin`, `borrower`, `lender`)
   - One shared .NET backend API (`LeadGenerator/backend/src/LeadNet.Api`)
   - JWT + OTP-based authentication and role-based access

2. **Legacy/parallel Laravel stack (repo root):**
   - Laravel 12 + Livewire app
   - Rich route surface for onboarding, loan workflows, admin management, and content management
   - Dockerized deployment and GitHub Actions pipeline

This document treats both stacks as part of the delivered system landscape and clearly separates current responsibilities.

---

## 3) Scope Definition

### 3.1 In Scope

- User authentication with OTP verification
- Role-based access for borrower, lender, and admin
- Shared API access from separate frontends
- Activity/audit logging for auth events
- Admin content operations (Laravel side, e.g., hero slider management)
- Deployment pipeline and runtime operations

### 3.2 Out of Scope (Current Baseline)

- Fully integrated domain workflows between Laravel and .NET stacks (not evidenced as unified runtime in code)
- Distributed OTP provider integration (current .NET OTP is in-memory dev-friendly implementation)
- Automated test suite coverage baseline (limited explicit tests in inspected code)

---

## 4) Stakeholders and User Roles

- **Borrower:** Authenticates and accesses borrower-only dashboard/features
- **Lender:** Authenticates and accesses lender-only dashboard/features
- **Admin:** Authenticates and accesses admin-only dashboard/features
- **Super Admin (Laravel side):** Manages admin modules such as hero slider
- **Engineering:** Maintains backend/frontends and infra
- **QA:** Validates auth flows, role routing, and regressions
- **Ops/DevOps:** Deploys and monitors runtime services

---

## 5) System Context and High-Level Architecture

### 5.1 Logical Architecture

- **Presentation Layer**
  - React SPAs: `LeadGenerator/admin`, `LeadGenerator/borrower`, `LeadGenerator/lender`
  - Laravel Blade + Livewire UI in root app
- **Application Layer**
  - .NET API (`LeadNet.Api`) for auth endpoints
  - Laravel controllers + Livewire components for web modules
- **Domain/Service Layer**
  - .NET Application services (`AuthService`, repository/services interfaces)
  - Laravel business services/controllers
- **Data Layer**
  - PostgreSQL via EF Core for .NET (`users`, `activity_logs`)
  - Laravel DB layer/migrations (separate schema set)

### 5.2 Deployment View (Observed)

- Laravel deployment through Docker (PHP-FPM + Nginx) and GitHub Actions SSH workflow
- .NET backend + React apps currently documented for local startup; dedicated deployment pipeline not present in inspected files

---

## 6) Technology Stack

### 6.1 LeadGenerator (.NET + React)

- **Backend:** .NET 9, ASP.NET Core Web API, EF Core, Npgsql, JWT Bearer auth
- **Frontend:** React 18, React Router v6, Axios, Vite 5, Tailwind CSS
- **Security:** BCrypt password verification, JWT tokens, OTP second factor

### 6.2 Laravel Stack

- **Framework:** Laravel 12 (PHP 8.2+)
- **UI:** Blade, Livewire 3, Alpine.js, Tailwind CSS, Vite
- **Auth-related packages:** Jetstream, Passport, Sanctum
- **Ops:** Docker multi-stage build, Nginx reverse proxy, GitHub Actions deploy

---

## 7) Functional Requirements (System Requirements Specification - SRS Core)

### 7.1 Authentication and Authorization

- FR-001: System shall accept login using email or phone and password.
- FR-002: System shall enforce OTP verification before issuing access token/session.
- FR-003: System shall issue JWT access token after successful OTP verification (.NET stack).
- FR-004: System shall expose authenticated `me` endpoint for current user context.
- FR-005: System shall enforce role-based route access (borrower/lender/admin).
- FR-006: System shall provide logout endpoint and clear active client auth state.

### 7.2 Audit and Activity Logging

- FR-007: System shall record login success/failure and OTP verification outcomes.
- FR-008: System shall capture IP address, user agent, and metadata for auth events.

### 7.3 UI and Routing

- FR-009: Each SPA shall expose `login`, `otp`, and protected dashboard routes.
- FR-010: Unauthorized role access shall redirect to unauthorized page.
- FR-011: Laravel app shall provide route-driven modules for onboarding, loan management, and admin operations.

### 7.4 Content and Admin Operations (Laravel)

- FR-012: Admin/super-admin shall manage hero slider media (create/update/delete/activate/order).
- FR-013: Uploaded hero images shall be validated and optimized before storage.

---

## 8) Non-Functional Requirements (NFR)

- NFR-001: API auth endpoints should respond in < 500ms under normal load (excluding network latency).
- NFR-002: OTP code validity should be time-boxed (5 minutes in .NET implementation).
- NFR-003: JWT expiry should be bounded (12 hours in .NET implementation).
- NFR-004: Audit logs should be persisted for security/compliance traceability.
- NFR-005: System should run in containerized production topology (Laravel verified).
- NFR-006: Secrets must not be hardcoded in production configuration.
- NFR-007: Role checks must be enforced both server-side and client-side.
- NFR-008: Upload handling must validate file type/size and reject malformed images.

---

## 9) Detailed Design - LeadGenerator (.NET + React)

### 9.1 Backend Layered Design

- **API Layer:** `LeadNet.Api`
  - `AuthController` endpoints: login, otp verify, me, logout
- **Application Layer:** `LeadNet.Application`
  - `IAuthService` + `AuthService`
  - Service interfaces for password/JWT/OTP/user repository/activity logs
- **Infrastructure Layer:** `LeadNet.Infrastructure`
  - EF Core `AppDbContext`
  - `UserRepository`, `PasswordService`, `JwtTokenService`, `OtpService`, `ActivityLogService`
- **Domain Layer:** `LeadNet.Domain`
  - `User`, `ActivityLog`
- **Contracts Layer:** `LeadNet.Contracts`
  - Request/response DTOs for auth API

### 9.2 Authentication Sequence (.NET)

1. Client sends login payload (`login`, `password`) to `/api/auth/login`.
2. API validates user existence/active status and password hash.
3. Server creates OTP session and returns `otpRequired=true`, `otpSessionId`, and dev OTP code.
4. Client submits OTP code to `/api/auth/otp/verify`.
5. Server verifies session and code, issues JWT, returns user payload.
6. Client stores JWT, uses it for authenticated endpoints.
7. `GET /api/auth/me` resolves identity from JWT claims.

### 9.3 Data Model (.NET)

- **users**
  - `id (Guid)`, `name`, `email`, `phone`, `password`, `role`, `is_active`
- **activity_logs**
  - `id`, `user_id`, `event_type`, `email_or_phone`, `role`, `realm`, `ip_address`, `user_agent`, `metadata`, `occurred_at_utc`

### 9.4 Frontend Design (All 3 SPAs)

- Shared auth architecture per app:
  - `AuthContext` handles login, OTP verification, logout, bootstrap via `/auth/me`
  - `authApi` uses Axios client with `VITE_API_URL` fallback to `http://localhost:5000/api`
  - Local storage token/session keys are app-specific per role app
- Route protection:
  - `ProtectedRoute` checks authenticated state
  - `RoleGuard` enforces role whitelist

---

## 10) Detailed Design - Laravel Stack

### 10.1 Application Characteristics

- Monolithic web app with extensive route-driven modules
- Auth + OTP gating in web flow
- Livewire admin modules for content and operations
- Multiple domains: onboarding, lender management, applications, reports, settings, verification

### 10.2 Example Module: Hero Slider Management

- Livewire component: `App\Livewire\Admin\HeroSliderManagement`
- Supports:
  - image upload validation (mime/size/dimensions)
  - safe image verification
  - image optimization/resizing and conversion (prefers WebP)
  - CRUD + ordering + active status
- Authorization guard in `mount`: requires authenticated `super_admin`

### 10.3 Deployment Architecture (Laravel)

- Multi-stage Docker build:
  - Stage 1 builds frontend assets via Vite
  - Stage 2 runs PHP-FPM app with Composer install
- Nginx routes to PHP-FPM container on port 9000
- CI workflow deploys over SSH, resets branch, builds containers, runs migrations/seeding, and caches config/routes/views

---

## 11) API Documentation Baseline (.NET Auth API)

### 11.1 `POST /api/auth/login`

- **Request:** `{ "login": "email-or-phone", "password": "..." }`
- **Success:** `200 OK` with `otpRequired=true`, `otpSessionId`, optional `devOtpCode`
- **Failure:** `401 Unauthorized` with invalid credentials message

### 11.2 `POST /api/auth/otp/verify`

- **Request:** `{ "otpSessionId": "...", "code": "123456" }`
- **Success:** `200 OK` with `accessToken`, `user`, `otpRequired=false`
- **Failure:** `401 Unauthorized` when code/session invalid

### 11.3 `GET /api/auth/me`

- **Auth:** Bearer token required
- **Success:** current user DTO from claims

### 11.4 `POST /api/auth/logout`

- **Auth:** Bearer token required
- **Success:** logs logout event and returns confirmation message

---

## 12) Security Architecture

### 12.1 Implemented Controls

- Password hash verification (`BCrypt.Net`)
- JWT bearer validation (issuer, audience, signing key, lifetime)
- OTP challenge before token issuance
- Role-based route guard in frontend
- Activity logging for auth lifecycle events
- File upload validation and image sanitization in Laravel admin module

### 12.2 Identified Security Gaps / Improvement Requirements

- SR-001: Remove default/hardcoded JWT and DB credentials from committed config for production.
- SR-002: Replace in-memory OTP store with distributed/persistent provider (Redis/DB/SMS/Email provider integration).
- SR-003: Ensure OTP code is never returned to production client payload.
- SR-004: Add brute-force/rate limiting on login and OTP verification endpoints.
- SR-005: Add centralized secrets management (environment vault/KMS).
- SR-006: Add security headers, CORS policy hardening, and request validation middleware in .NET API.

---

## 13) Operational Documentation

### 13.1 Local Development Setup

#### .NET + React stack

1. Run backend:
   - `cd LeadGenerator/backend/src/LeadNet.Api`
   - `dotnet restore`
   - `dotnet run`
2. Run each frontend as needed:
   - `cd LeadGenerator/<admin|borrower|lender>`
   - `npm install`
   - `npm run dev`

#### Laravel stack

- Install PHP/Composer + Node dependencies and run app/Vite, or use Docker workflow in repo.

### 13.2 Configuration Matrix

- **.NET API**
  - `ConnectionStrings:DefaultConnection`
  - `Jwt:Key`, `Jwt:Issuer`, `Jwt:Audience`
- **React Apps**
  - `VITE_API_URL`
- **Laravel**
  - `.env` values for DB/mail/queue/session/app secrets

### 13.3 Deployment and Release

- Laravel production deployment path is automated in `.github/workflows/deploy.yml`.
- Deployment includes build, container restart, migration/seed, and cache warm-up.
- Release checklist should require:
  - smoke tests (login/otp/me/logout),
  - migration verification,
  - rollback readiness.

---

## 14) Testing Documentation

### 14.1 Minimum Test Strategy

- **Unit Tests**
  - Auth service logic (credential validation, OTP branch, token branch)
  - Password verification and token service claims/expiry
- **Integration Tests**
  - Auth endpoints with DB fixtures
  - Activity log write assertions
- **E2E Tests**
  - Per-role login + OTP + protected route navigation
  - Unauthorized role redirection
  - Logout and session cleanup
- **Laravel Module Tests**
  - Hero slider upload/update/delete and role access controls

### 14.2 Acceptance Criteria (Core)

- AC-001: Valid login must require OTP before protected access.
- AC-002: Invalid credentials/OTP must be rejected with no token issuance.
- AC-003: Role-restricted routes must block unauthorized users.
- AC-004: Auth events must be present in activity logs.

---

## 15) Data Governance and Compliance Notes

- Personal data processed: email, phone, authentication metadata (IP/user agent), activity traces.
- Required controls:
  - retention policy for activity logs,
  - audit access restrictions,
  - encryption at rest/in transit,
  - backup and restore procedures,
  - incident response runbook.

---

## 16) Risks and Mitigation Plan

- **R-001:** Dual-stack complexity (Laravel + .NET/React) may create ownership ambiguity.
  - **Mitigation:** Define system boundary and migration roadmap.
- **R-002:** In-memory OTP is not horizontally scalable.
  - **Mitigation:** Move OTP session state to Redis/DB.
- **R-003:** Config secrets exposure risk in repository defaults.
  - **Mitigation:** enforce env-only secrets and secret scanning.
- **R-004:** Mixed framework/runtime versions can increase maintenance overhead.
  - **Mitigation:** version policy and scheduled dependency updates.

---

## 17) Required Companion Documents (Templates Included)

Use this section to generate formal deliverables quickly.

### 17.1 System Requirements Specification (SRS)

Template sections:

1. Introduction and scope
2. Business context
3. Functional requirements (FR-xxx)
4. Non-functional requirements (NFR-xxx)
5. External interface requirements
6. Data requirements
7. Acceptance criteria and traceability matrix

### 17.2 Software Architecture Document (SAD)

Template sections:

1. Architectural goals and constraints
2. Context diagram
3. Container and component views
4. Data flow and sequence diagrams
5. Security architecture
6. Availability/scalability strategy
7. Technology decisions and rationale (ADR references)

### 17.3 Low-Level Design (LLD)

Template sections:

1. Module-by-module design
2. Class/interface contracts
3. API schemas
4. Error handling and retry strategy
5. Pseudocode/state diagrams for critical flows

### 17.4 Test Plan and Test Cases

Template sections:

1. Test scope and environments
2. Functional test cases by FR
3. Security tests
4. Performance tests
5. Regression suite
6. Exit criteria

### 17.5 Operations Runbook

Template sections:

1. Startup/shutdown procedures
2. Deploy/rollback steps
3. Monitoring and alert thresholds
4. Incident triage playbooks
5. Backup/restore procedure

### 17.6 Security and Compliance Document

Template sections:

1. Threat model
2. Authentication and authorization controls
3. Data classification and retention
4. Vulnerability management process
5. Audit requirements

---

## 18) Traceability Matrix (Starter)

| Requirement ID | Design Component | Test Case ID | Status |
|---|---|---|---|
| FR-001 | AuthController + AuthService | TC-AUTH-001 | Draft |
| FR-002 | OtpService + OTP UI flow | TC-AUTH-002 | Draft |
| FR-005 | RoleGuard/ProtectedRoute | TC-AUTHZ-001 | Draft |
| FR-007 | ActivityLogService | TC-AUDIT-001 | Draft |
| FR-012 | HeroSliderManagement | TC-ADMIN-001 | Draft |

---

## 19) Roadmap Recommendations

1. Decide target strategic platform boundary (Laravel-centric vs .NET/React-centric vs coexistence contract).
2. Introduce shared API gateway and unified identity policy if both stacks remain active.
3. Add CI quality gates (tests, lint, security scans) before deployment.
4. Replace development OTP behavior with production-grade provider.
5. Produce ADRs for major architecture decisions and keep them versioned with code.

---

## 20) Revision History

| Version | Date | Author | Notes |
|---|---|---|---|
| 1.0 | 2026-04-14 | AI Assistant | Initial end-to-end baseline documentation from current repository implementation. |

