import { Link } from 'react-router-dom'

export default function Home() {
  return (
    <article>
      <h1 className="mb-2 text-3xl font-bold text-slate-900">Fanikisha</h1>
      <p className="mb-8 text-lg text-slate-600">
        System documentation for Fanikisha. Use the sidebar or search to jump to any section.
      </p>

      <section className="mb-10">
        <h2 className="mb-4 text-xl font-semibold text-slate-900">How Fanikisha works</h2>
        <p className="mb-4 text-slate-600">
          <strong>Fanikisha</strong> connects borrowers who need loans with lenders who provide them. Borrowers apply for loans on the platform; Fanikisha matches them to suitable loan products and sends those applications (leads) to the right lenders. Lenders then review, approve, or reject leads and can integrate with their own systems via webhooks and APIs.
        </p>
        <p className="mb-4 text-slate-600">
          <strong>Borrowers</strong> register or log in, complete identity verification (and for companies, NIDA and document checks where applicable), then go through pre-qualification and a multi-step loan application. They can also use self-service tools: TRA verifications (TIN, licence, motor vehicle) and credit report. <strong>Lenders</strong> receive leads in the lead management area, manage loan products, view reports, and configure webhooks so new leads are sent to their CRM or core banking system. <strong>Super Admins</strong> manage users, company and lender verification, settings, and content. The system is integrated with email (e.g. OTP, notifications), TRA services (taxpayer, licence, vehicle), credit reporting, and NIDA where applicable—so verification and communication run through these integrated services without users having to leave the platform.
        </p>
      </section>

      <div className="grid gap-6 sm:grid-cols-2">
        <Link
          to="/getting-started"
          className="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:border-brand-red hover:shadow-md"
        >
          <h2 className="mb-2 text-lg font-semibold text-brand-red group-hover:underline">Getting Started</h2>
          <p className="text-sm text-slate-600">Overview of the system, access, and first steps.</p>
        </Link>
        <Link
          to="/user-roles"
          className="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:border-brand-red hover:shadow-md"
        >
          <h2 className="mb-2 text-lg font-semibold text-brand-red group-hover:underline">User Roles</h2>
          <p className="text-sm text-slate-600">Borrower, Lender, and Super Admin roles and permissions.</p>
        </Link>
        <Link
          to="/authentication"
          className="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:border-brand-red hover:shadow-md"
        >
          <h2 className="mb-2 text-lg font-semibold text-brand-red group-hover:underline">Authentication</h2>
          <p className="text-sm text-slate-600">Login, OTP verification, and registration flows.</p>
        </Link>
        <Link
          to="/navigation"
          className="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:border-brand-red hover:shadow-md"
        >
          <h2 className="mb-2 text-lg font-semibold text-brand-red group-hover:underline">Navigation</h2>
          <p className="text-sm text-slate-600">Sidebar menu and how to move around the system.</p>
        </Link>
      </div>

      <section className="mt-12">
        <h2 className="mb-4 text-xl font-semibold text-slate-900">By role</h2>
        <ul className="space-y-2 text-slate-600">
          <li>
            <Link to="/borrower/dashboard" className="text-brand-red hover:underline">Borrower</Link> – Dashboard, profile, loan applications, self-services, TRA verifications (TIN, licence, vehicle), credit report.
          </li>
          <li>
            <Link to="/lender/dashboard" className="text-brand-red hover:underline">Lender</Link> – Dashboard, lead management, reports, loan products, notifications, API / Integration, webhook connect.
          </li>
          <li>
            <Link to="/admin/overview" className="text-brand-red hover:underline">Super Admin</Link> – User management, company verification, lenders, settings, billing, content.
          </li>
        </ul>
      </section>
    </article>
  )
}
