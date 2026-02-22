import StepCard from '../../components/StepCard'

export default function CompanyVerification() {
  return (
    <article>
      <h1 className="mb-2 text-3xl font-bold text-slate-900">Super Admin – Company Verification</h1>
      <p className="mb-8 text-slate-600">
        Company Verification is where you review and approve or reject company (e.g. lender) registrations that require admin approval.
      </p>

      <StepCard step={1} title="Open Company Verification">
        In the left sidebar, click <strong>Company Verification</strong>. You may see a badge with the number of pending companies. The list of companies awaiting verification opens.
      </StepCard>

      <StepCard step={2} title="View the list">
        The list shows companies that registered and are in a “pending” or similar status. You can see company name, contact, registration date, and status.
      </StepCard>

      <StepCard step={3} title="Open a company">
        Click a company to open its detail page. Review the submitted information: company details, documents (e.g. registration, KYC), and the user who registered.
      </StepCard>

      <StepCard step={4} title="Approve or reject">
        After review, use <strong>Approve</strong> to grant the company full access, or <strong>Reject</strong> with an optional reason. The company (and its users) will be notified and their status updated. Approved companies can then use lender or company features as configured.
      </StepCard>
    </article>
  )
}
