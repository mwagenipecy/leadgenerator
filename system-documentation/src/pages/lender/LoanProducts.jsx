import StepCard from '../../components/StepCard'

export default function LoanProducts() {
  return (
    <article>
      <h1 className="mb-2 text-3xl font-bold text-slate-900">Lender – Loan Products</h1>
      <p className="mb-4 text-slate-600">
        <strong>Loan Products</strong> define the loan offers your institution provides on the platform. Borrowers are matched to these products during <strong>pre-qualification</strong> and when they submit an application. Each product has criteria (e.g. min/max amount, tenure, interest, eligibility rules such as minimum income or maximum DSR). Only products that match the borrower’s profile and request are shown to them, so configuring products correctly is essential to receive relevant leads.
      </p>
      <p className="mb-8 text-slate-600">
        You can create multiple products (e.g. personal loan, salary advance, business loan) with different terms. Active products appear in the borrower’s pre-qualification results when they qualify. Editing a product affects new applications; existing applications usually keep the product terms that were in place at the time of submission. Use the list to see all your products and their status (active, inactive, or draft).
      </p>

      <StepCard step={1} title="Open Loan Products">
        In the left sidebar, click <strong>Loan Products</strong>. You see the list of your existing products with information such as: product name, amount range, tenure, status (active/inactive), and possibly creation or last-update date. From here you can create a new product or edit an existing one.
      </StepCard>

      <StepCard step={2} title="Create a new product">
        Click <strong>Create</strong> or <strong>Add product</strong>. You are taken to the product form. Fill in: <strong>product name</strong>, <strong>min and max amount</strong>, <strong>tenure options</strong> (e.g. 6, 12, 24 months), <strong>interest or pricing details</strong>, and <strong>eligibility criteria</strong> (e.g. minimum monthly income, maximum debt-service ratio). Some systems allow category, description, and document requirements. Save the product; it may need to be set to “active” before it appears to borrowers. Ensure the criteria align with your risk appetite so you receive quality leads.
      </StepCard>

      <StepCard step={3} title="Edit a product">
        From the list, click <strong>Edit</strong> on a product. Update the fields as needed (amount range, tenure, criteria, or pricing) and save. Changes apply to <strong>new</strong> applications; existing applications may keep the previous product terms depending on system behaviour. If you want to stop receiving new leads for a product, set it to inactive or pause it instead of deleting, so historical data remains clear.
      </StepCard>

      <StepCard step={4} title="View product details">
        Click <strong>View</strong> or the product name to see full details in read-only form. Use this to verify settings before borrowers see the product in pre-qualification, and to ensure your eligibility rules (income, DSR, etc.) are correct so matching leads are relevant to your institution.
      </StepCard>
    </article>
  )
}
