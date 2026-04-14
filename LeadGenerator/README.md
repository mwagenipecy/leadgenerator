## LeadGenerator Multi-Frontend Setup

Three separate React frontends use one shared .NET backend.

### Projects

- `borrower` -> borrower login and borrower routes
- `lender` -> lender login and lender routes
- `admin` -> admin login and admin routes
- `backend/src/LeadNet.Api` -> shared API for all frontends

### Frontend standard structure (same in all three apps)

- `src/auth/components`
- `src/auth/context`
- `src/auth/pages`
- `src/auth/routes`
- `src/auth/services`
- `src/shared/components`
- `src/features`
- `src/router`

### Run

Backend:

1. `cd LeadGenerator/backend/src/LeadNet.Api`
2. `dotnet restore`
3. `dotnet run`

Borrower frontend:

1. `cd LeadGenerator/borrower`
2. `npm install`
3. `npm run dev`

Lender frontend:

1. `cd LeadGenerator/lender`
2. `npm install`
3. `npm run dev`

Admin frontend:

1. `cd LeadGenerator/admin`
2. `npm install`
3. `npm run dev`

API base URL defaults to `http://localhost:5000/api`.
