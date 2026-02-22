import { Link } from 'react-router-dom'
import StepCard from '../components/StepCard'

export default function GettingStarted() {
  return (
    <article>
      <h1 className="mb-2 text-3xl font-bold text-slate-900">Getting Started</h1>
      <p className="mb-8 text-slate-600">
        How to access the system and what you need before you begin.
      </p>

      <StepCard step={1} title="Open the application">
        <p className="mb-2">Open the Fanikisha application in your browser (e.g. the URL provided by your organisation).</p>
        <p className="mb-0">The landing page shows the main marketing content, hero slider, and options to log in or register.</p>
      </StepCard>

      <StepCard step={2} title="Choose Login or Register">
        <p className="mb-2">If you already have an account, use <strong>Login</strong> (email or phone + password).</p>
        <p className="mb-0">If you are new, use <strong>Register</strong> to create an individual account or a company account. See <Link to="/authentication" className="text-brand-red hover:underline">Authentication</Link> for the full flow.</p>
      </StepCard>

      <StepCard step={3} title="Complete verification">
        <p className="mb-2">After login you will be asked to enter an OTP sent to your email. Then, if required, complete NIDA (identity) verification.</p>
        <p className="mb-0">Your role (Borrower, Lender, or Super Admin) determines which menu items and pages you see. See <Link to="/user-roles" className="text-brand-red hover:underline">User Roles</Link>.</p>
      </StepCard>

      <StepCard step={4} title="Use the sidebar to navigate">
        <p className="mb-0">Once logged in, the left sidebar shows the menu for your role. Use it to go to Dashboard, Loan Applications, Reports, Settings, etc. Details are in <Link to="/navigation" className="text-brand-red hover:underline">Navigation</Link>.</p>
      </StepCard>
    </article>
  )
}
