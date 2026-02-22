import { Link } from 'react-router-dom'
import StepCard from '../../components/StepCard'

export default function BorrowerProfile() {
  return (
    <article>
      <h1 className="mb-2 text-3xl font-bold text-slate-900">Borrower – User Profile</h1>
      <p className="mb-8 text-slate-600">
        Your profile stores information used in loan applications. Keeping it up to date helps speed up new applications.
      </p>

      <StepCard step={1} title="Open User Profile">
        In the left sidebar, click <strong>User Profile</strong>. You may also reach it via the application flow (e.g. <strong>application/profile</strong> or profile link on the loan application page).
      </StepCard>

      <StepCard step={2} title="View your information">
        The profile page shows your saved details: name, contact (phone, email), date of birth, address, employment, and other relevant fields. Some of this may be pre-filled from registration or previous applications.
      </StepCard>

      <StepCard step={3} title="Edit and save">
        Use <strong>Edit</strong> or the edit form to change any field. Fill or correct the information, then click <strong>Save</strong> or <strong>Update</strong>. Changes are saved and will be used in future loan applications.
      </StepCard>

      <StepCard step={4} title="Relation to loan applications">
        When you start a new loan application (see <Link to="/borrower/loan-application" className="text-brand-red hover:underline">Loan Application</Link>), many fields can be pre-filled from your profile. Update your profile first if your contact or employment details have changed.
      </StepCard>
    </article>
  )
}
