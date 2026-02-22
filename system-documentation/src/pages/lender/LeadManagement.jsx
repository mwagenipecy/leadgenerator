import { Link } from 'react-router-dom'
import StepCard from '../../components/StepCard'

export default function LeadManagement() {
  return (
    <article>
      <h1 className="mb-2 text-3xl font-bold text-slate-900">Lender – Lead Management</h1>
      <p className="mb-4 text-slate-600">
        Lead Management is where you see and process <strong>loan applications (leads)</strong> sent to your institution. Every application a borrower submits and assigns to you appears here. You review the applicant’s details, documents, and financial information, then approve, reject, or request more information so you can convert leads into disbursed loans.
      </p>
      <p className="mb-8 text-slate-600">
        The list is scoped to your lender account: you only see applications that were directed to your loan products. Use filters and search to find specific leads by status, date, amount, or applicant name. For aggregated analytics, use <strong>Reports</strong>; for receiving leads automatically in your own system, see <strong>API / Integration</strong>.
      </p>

      <StepCard step={1} title="Open Lead Management">
        In the left sidebar, click <strong>Lead Management</strong>. You may see a badge with the number of applications. The application list (or “Application list”) opens, showing all leads assigned to you.
      </StepCard>

      <StepCard step={2} title="View the list">
        The list shows applications with columns such as: <strong>applicant name</strong>, <strong>requested amount</strong>, <strong>tenure</strong>, <strong>status</strong> (e.g. new, in progress, approved, rejected), and <strong>date submitted</strong>. Use filters or search if available (e.g. by status, date range, amount) to find specific leads. Sort by date or status to prioritise new or pending items.
      </StepCard>

      <StepCard step={3} title="Open an application">
        Click a row or the <strong>View</strong> action to open the full application. You will see all submitted details: <strong>loan details</strong> (amount, tenure, purpose), <strong>personal information</strong> (name, ID, contact), <strong>address</strong>, <strong>employment and income</strong>, <strong>emergency contact</strong>, and <strong>uploaded documents</strong> (e.g. national ID, proof of income). Use this to assess eligibility and risk before deciding.
      </StepCard>

      <StepCard step={4} title="Book a lead">
        When you <strong>book a lead</strong>, that application is <strong>assigned to your institution</strong> and <strong>disappears from other lenders’</strong> lists. Only you can see and work on it. This gives you exclusive time to review and respond. Book leads you intend to process so they don’t stay in the shared pool.
      </StepCard>

      <StepCard step={5} title="If the request stays idle – it returns to the applicant">
        If a <strong>booked lead stays idle for too long</strong> (e.g. no action from your side within the configured time), the system <strong>returns the lead to the applicant pool</strong>. It will appear again for other lenders (or for the applicant to reassign), so the applicant can get <strong>quicker responses</strong>. Respond to booked leads promptly to avoid losing them and to give applicants a good experience.
      </StepCard>

      <StepCard step={6} title="Take action">
        From the application view you can: <strong>Approve</strong> (proceed to disbursement or next step), <strong>Reject</strong> (with optional reason), or update status (e.g. pending documents, under review). Add notes or comments if the system supports it. Changes are saved and may update the borrower’s view and feed into Reports. Ensure you comply with your internal policies and turnaround times when responding to leads.
      </StepCard>

      <StepCard step={7} title="Reports and integrations">
        For aggregated views and booking reports, use <strong>Reports</strong> in the sidebar (see <Link to="/lender/reports" className="text-brand-red hover:underline">Reports</Link>). To receive new leads automatically in your CRM or core banking system, configure a webhook in <strong>Integrations</strong> and refer to <Link to="/lender/api-integration" className="text-brand-red hover:underline">API / Integration</Link> for request and response examples.
      </StepCard>

      <div className="mt-8 rounded-lg border-l-4 border-brand-red bg-red-50/50 p-4 text-slate-700">
        <p className="text-sm">
          <strong className="text-brand-red">Summary:</strong> When you book a lead, it is yours only and disappears from other lenders. If you don’t act in time, the lead goes back to the pool so the applicant can get quicker responses from others.
        </p>
      </div>
    </article>
  )
}
