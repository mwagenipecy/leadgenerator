import { Link } from 'react-router-dom'

export default function AdminOverview() {
  return (
    <article>
      <h1 className="mb-2 text-3xl font-bold text-slate-900">Super Admin – Overview</h1>
      <p className="mb-8 text-slate-600">
        As Super Admin you have access to all administrative areas. This page lists them with links to detailed step-by-step guides.
      </p>

      <div className="space-y-4">
        <div className="rounded-xl border border-slate-200 bg-white p-4">
          <h2 className="mb-1 font-semibold text-slate-900">User & access</h2>
          <ul className="list-inside list-disc text-slate-600">
            <li><Link to="/admin/user-management" className="text-brand-red hover:underline">User Management</Link> – Users, roles, permissions, language.</li>
          </ul>
        </div>
        <div className="rounded-xl border border-slate-200 bg-white p-4">
          <h2 className="mb-1 font-semibold text-slate-900">Verification & lenders</h2>
          <ul className="list-inside list-disc text-slate-600">
            <li><Link to="/admin/company-verification" className="text-brand-red hover:underline">Company Verification</Link> – Approve or reject company registrations.</li>
            <li><Link to="/admin/lender-management" className="text-brand-red hover:underline">Lender Management</Link> – Approve, reject, suspend, reactivate lenders.</li>
          </ul>
        </div>
        <div className="rounded-xl border border-slate-200 bg-white p-4">
          <h2 className="mb-1 font-semibold text-slate-900">System & billing</h2>
          <ul className="list-inside list-disc text-slate-600">
            <li><Link to="/admin/system-settings" className="text-brand-red hover:underline">System Settings</Link> – Global configuration.</li>
            <li><Link to="/admin/billing" className="text-brand-red hover:underline">Billing</Link> – Billing section.</li>
          </ul>
        </div>
        <div className="rounded-xl border border-slate-200 bg-white p-4">
          <h2 className="mb-1 font-semibold text-slate-900">Content</h2>
          <ul className="list-inside list-disc text-slate-600">
            <li><Link to="/admin/content-management" className="text-brand-red hover:underline">Content Management</Link> – Loan categories, blog, hero slider, promotions.</li>
          </ul>
        </div>
      </div>
    </article>
  )
}
