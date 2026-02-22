import StepCard from '../../components/StepCard'

export default function LenderManagement() {
  return (
    <article>
      <h1 className="mb-2 text-3xl font-bold text-slate-900">Super Admin – Lender Management</h1>
      <p className="mb-8 text-slate-600">
        Lender Management lets you approve, reject, suspend, reactivate, and manage lending institutions on the platform.
      </p>

      <StepCard step={1} title="Open Lender Management">
        In the left sidebar, click <strong>Lender Management</strong>. You may see a badge with pending count. The lender list opens.
      </StepCard>

      <StepCard step={2} title="View lenders">
        The list shows all lenders with status (e.g. pending, approved, suspended). You can filter or search by name, status, or date.
      </StepCard>

      <StepCard step={3} title="View a lender">
        Click a lender to open their profile/dashboard. You see their details, users, loan products, and activity summary.
      </StepCard>

      <StepCard step={4} title="Approve or reject (pending)">
        For <strong>pending</strong> lenders, use <strong>Approve</strong> to allow them to use the platform (receive leads, manage products). Use <strong>Reject</strong> to decline; you can add a reason if the system supports it.
      </StepCard>

      <StepCard step={5} title="Suspend or reactivate">
        For <strong>approved</strong> lenders, you can <strong>Suspend</strong> to temporarily disable their access. Later, use <strong>Reactivate</strong> to turn access back on. Use <strong>Add user</strong> if you need to add another user to the lender. <strong>Delete</strong> (if available) removes the lender; use with care.
      </StepCard>
    </article>
  )
}
