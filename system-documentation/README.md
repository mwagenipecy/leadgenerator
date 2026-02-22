# Fanikisha – System Documentation

This is a **standalone documentation site** for the Fanikisha system. It is built with **React**, **React Router**, and **Tailwind CSS**, and provides step-by-step guides so anyone can read and understand how to navigate and use the system.

## Project folder

All documentation app files live in this folder: `system-documentation/`.

## Run locally

1. **Install dependencies**

   ```bash
   cd system-documentation
   npm install
   ```

2. **Start the dev server**

   ```bash
   npm run dev
   ```

   Then open the URL shown in the terminal (e.g. `http://localhost:5173`).

3. **Build for production**

   ```bash
   npm run build
   ```

   Output is in `dist/`. You can serve it with any static host (e.g. `npm run preview` to test the build locally).

## What’s inside

- **Introduction** – Home, Getting Started, User Roles, Authentication, Navigation  
- **Borrower** – Dashboard, User Profile, Loan Application (pre-qualification + 6 steps), Self Services (TIN, License, Vehicle, Credit Report)  
- **Lender** – Dashboard, Lead Management, Reports, Loan Products  
- **Super Admin** – Overview, User Management, Company Verification, Lender Management, System Settings, Billing, Content Management (Loan Categories, Blog, Hero Slider, Promotions)

Use the **sidebar** on the left to jump to any section. Each page explains usage step by step so users can follow along and know how to navigate the system.
