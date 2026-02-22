import { Link } from 'react-router-dom'
import StepCard from '../../components/StepCard'

export default function BorrowerDashboard() {
  return (
    <article>
      <h1 className="mb-2 text-3xl font-bold text-slate-900">Borrower – Dashboard</h1>
      <p className="mb-8 text-slate-600">
        After login as a borrower, the dashboard is your home. It shows a summary and quick access to your applications and profile.
      </p>

      <StepCard step={1} title="Open the dashboard">
        After OTP and any NIDA verification, you are taken to the <strong>Dashboard</strong>. You can also click <strong>Dashboard</strong> in the left sidebar anytime.
      </StepCard>

      <StepCard step={2} title="What you see">
        The borrower dashboard shows an overview such as: number of applications, status summaries, and shortcuts to start a new application or view existing ones.
      </StepCard>

      <StepCard step={3} title="Next actions">
        <ul className="list-inside list-disc space-y-1">
          <li>To apply for a loan: use the dashboard link to start an application, or go to <strong>Loan Applications</strong> in the sidebar (see <Link to="/borrower/loan-application" className="text-brand-red hover:underline">Loan Application</Link>).</li>
          <li>To update your details: go to <strong>User Profile</strong> in the sidebar (see <Link to="/borrower/profile" className="text-brand-red hover:underline">User Profile</Link>).</li>
          <li>To use verifications (TIN, license, vehicle, credit): open <strong>Self Services</strong> in the sidebar (see <Link to="/borrower/self-services" className="text-brand-red hover:underline">Self Services</Link>).</li>
        </ul>
      </StepCard>
    </article>
  )
}
