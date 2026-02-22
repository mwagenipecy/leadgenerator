import { Link } from 'react-router-dom'
import StepCard from '../../components/StepCard'

export default function CreditReport() {
  return (
    <article>
      <h1 className="mb-2 text-3xl font-bold text-slate-900">Credit Report</h1>
      <p className="mb-8 text-slate-600">
        The <strong>Credit Report</strong> self-service lets you request and view your credit report (or a summary) from within Fanikisha. Lenders may use credit information to assess your application; having access to your own report helps you understand your standing and correct any errors before applying. To understand what your <strong>credit score</strong> means, see score ranges, and learn how to improve it (with a simple “game” of levels), go to <Link to="/borrower/credit-score" className="text-brand-red hover:underline">Credit Score</Link>.
      </p>

      <StepCard step={1} title="Open Credit Report">
        In the sidebar, expand <strong>Self Services</strong> and click <strong>Credit Report</strong>. The credit report page opens.
      </StepCard>

      <StepCard step={2} title="Consent and identification">
        If this is your first time, you may need to give <strong>consent</strong> for the platform to request your report from the credit bureau. Enter any required identification (e.g. national ID, phone, or account details) as shown on the page.
      </StepCard>

      <StepCard step={3} title="Request your report">
        Click <strong>Request report</strong> or <strong>Get my report</strong>. The system will call the integrated credit bureau and fetch your report. This may take a few seconds; do not refresh the page.
      </StepCard>

      <StepCard step={4} title="View the report">
        Once ready, your <strong>credit report</strong> (or a summary) is shown on the page. You may see: score, accounts, payment history, and any negative items. You can download or print if the page offers that option. Use this information to improve your profile or dispute errors with the bureau if needed.
      </StepCard>

      <StepCard step={5} title="Using the report when applying">
        When you apply for a loan, lenders may pull your credit information themselves via the platform’s integration. Your self-service report is for your own reference; it does not replace the lender’s own credit check but helps you prepare and correct issues in advance.
      </StepCard>

      <p className="mt-8 rounded-lg border border-slate-200 bg-slate-50 p-4 text-sm text-slate-600">
        Fanikisha has integrated with the <strong>credit bureau</strong> (e.g. Credit Info) for credit report requests. The feature is available to <strong>borrowers</strong> from the Self Services menu. If the request fails, check your identification details and try again, or contact support.
      </p>
    </article>
  )
}
