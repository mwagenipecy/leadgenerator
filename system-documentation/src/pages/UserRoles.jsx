import { Link } from 'react-router-dom'

export default function UserRoles() {
  return (
    <article>
      <h1 className="mb-2 text-3xl font-bold text-slate-900">User Roles</h1>
      <p className="mb-8 text-slate-600">
        The system has three main roles. Your menu and capabilities depend on your role.
      </p>

      <section className="mb-10 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 className="mb-2 text-xl font-semibold text-slate-900">Borrower</h2>
        <p className="mb-4 text-slate-600">
          Individuals who apply for loans. Borrowers can manage their profile, submit and track loan applications, and use self-service verifications (TIN, license, vehicle, credit report).
        </p>
        <Link to="/borrower/dashboard" className="text-brand-red font-medium hover:underline">Borrower documentation →</Link>
      </section>

      <section className="mb-10 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 className="mb-2 text-xl font-semibold text-slate-900">Lender</h2>
        <p className="mb-4 text-slate-600">
          Lending institutions that receive and process loan applications (leads). Lenders see Lead Management, Reports, and Loan Products. They can approve, reject, or manage applications assigned to them.
        </p>
        <Link to="/lender/dashboard" className="text-brand-red font-medium hover:underline">Lender documentation →</Link>
      </section>

      <section className="mb-10 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 className="mb-2 text-xl font-semibold text-slate-900">Super Admin</h2>
        <p className="mb-4 text-slate-600">
          System administrators. They manage users, roles, permissions, company verification, lenders, loan categories, system settings, billing, blog, hero slider, and promotions.
        </p>
        <Link to="/admin/overview" className="text-brand-red font-medium hover:underline">Super Admin documentation →</Link>
      </section>
    </article>
  )
}
