import { Link } from 'react-router-dom'
import StepCard from '../../components/StepCard'

export default function LenderDashboard() {
  return (
    <article>
      <h1 className="mb-2 text-3xl font-bold text-slate-900">Lender – Dashboard</h1>
      <p className="mb-4 text-slate-600">
        The lender dashboard is your home after login. It gives an <strong>overview of leads and activity</strong> for your institution so you can see at a glance how many applications you have, their status, and where to focus next.
      </p>
      <p className="mb-8 text-slate-600">
        As a lender you receive loan applications (leads) from the platform. The dashboard summarises these so you can prioritise follow-up, track conversions, and keep your pipeline under control. Use the sidebar to go deeper into Lead Management, Reports, Loan Products, or API/Integration settings.
      </p>

      <StepCard step={1} title="Open the dashboard">
        After login as a lender, you land on the <strong>Dashboard</strong>. You can also click <strong>Dashboard</strong> in the left sidebar anytime to return to this overview.
      </StepCard>

      <StepCard step={2} title="What you see">
        The dashboard typically shows: <strong>total number of applications (leads)</strong> assigned to you; a <strong>status breakdown</strong> (e.g. new, in progress, approved, rejected, pending documents); and sometimes <strong>quick stats</strong> such as total requested amount or conversion rate. Use these to decide which leads to handle first and to report to your team.
      </StepCard>

      <StepCard step={3} title="Next actions">
        <ul className="list-inside list-disc space-y-1">
          <li>To work on leads: go to <strong>Lead Management</strong> in the sidebar (see <Link to="/lender/lead-management" className="text-brand-red hover:underline">Lead Management</Link>) to open the full list, view each application, and approve or reject.</li>
          <li>To view reports: go to <strong>Reports</strong> (see <Link to="/lender/reports" className="text-brand-red hover:underline">Reports</Link>) for booking reports and aggregated metrics.</li>
          <li>To manage products: go to <strong>Loan Products</strong> (see <Link to="/lender/loan-products" className="text-brand-red hover:underline">Loan Products</Link>) to create or edit the loan offers borrowers see and get matched to.</li>
          <li>To see notifications: go to <strong>Notifications</strong> (see <Link to="/lender/notifications" className="text-brand-red hover:underline">Notifications</Link>).</li>
          <li>To integrate with your systems: go to <strong>API / Integration</strong> (see <Link to="/lender/api-integration" className="text-brand-red hover:underline">API / Integration</Link>) and <strong>Webhook – Connect</strong> (see <Link to="/lender/webhook-connect" className="text-brand-red hover:underline">Webhook – Connect</Link>) for PHP, Java, Node, Python examples.</li>
        </ul>
      </StepCard>
    </article>
  )
}
