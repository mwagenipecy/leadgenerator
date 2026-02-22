import StepCard from '../../components/StepCard'

export default function UserManagement() {
  return (
    <article>
      <h1 className="mb-2 text-3xl font-bold text-slate-900">Super Admin – User Management</h1>
      <p className="mb-8 text-slate-600">
        User Management includes users list, roles, permissions, and language management. All are under the <strong>Admin Manager</strong> section in the sidebar.
      </p>

      <h2 className="mb-4 text-xl font-semibold text-slate-900">User Management</h2>
      <StepCard step={1} title="Open User Management">
        In the sidebar, open <strong>Admin Manager</strong> and click <strong>User Management</strong>. You see a list of system users.
      </StepCard>
      <StepCard step={2} title="Manage users">
        From the list you can search, filter by role or status, and open a user to view or edit details (e.g. name, email, phone, role). You can disable or enable accounts as the system allows.
      </StepCard>

      <h2 className="mb-4 mt-10 text-xl font-semibold text-slate-900">Roles</h2>
      <StepCard step={3} title="Open Roles">
        Under <strong>Admin Manager</strong>, click <strong>Roles</strong>. You see the list of roles (e.g. super_admin, lender, borrower).
      </StepCard>
      <StepCard step={4} title="Edit roles">
        Create or edit roles and assign permissions. Changes affect what users in that role can do in the system.
      </StepCard>

      <h2 className="mb-4 mt-10 text-xl font-semibold text-slate-900">Permissions</h2>
      <StepCard step={5} title="Open Permissions">
        Under <strong>Admin Manager</strong>, click <strong>Permissions</strong>. You see the list of permissions (e.g. view reports, manage lenders).
      </StepCard>
      <StepCard step={6} title="Assign permissions">
        Permissions are typically assigned to roles. Configure which permissions each role has so that access is correct for borrowers, lenders, and admins.
      </StepCard>

      <h2 className="mb-4 mt-10 text-xl font-semibold text-slate-900">Language Management</h2>
      <StepCard step={7} title="Open Language Management">
        Under <strong>Admin Manager</strong>, click <strong>Language Management</strong>. You can manage supported languages and translation strings for the application.
      </StepCard>
    </article>
  )
}
