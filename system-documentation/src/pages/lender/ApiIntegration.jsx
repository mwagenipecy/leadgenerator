import { Link } from 'react-router-dom'
import StepCard from '../../components/StepCard'

export default function ApiIntegration() {
  return (
    <article>
      <h1 className="mb-2 text-3xl font-bold text-slate-900">Lender – API / Integration</h1>
      <p className="mb-8 text-slate-600">
        Lenders and admins can use the <strong>Webhook Integration</strong> page (sidebar → Integrations) to configure how the platform sends data to your systems. Below: <strong>internal integrations</strong> (what Fanikisha has integrated with), the <strong>Transaction Analysis API</strong>, and the <strong>webhook</strong> payload. For how to <strong>connect and receive</strong> webhooks in PHP, Java, Node.js, and Python, see <Link to="/lender/webhook-connect" className="text-brand-red hover:underline">Webhook – Connect (PHP, Java, Node, Python)</Link>.
      </p>

      <h2 className="mb-4 text-xl font-semibold text-slate-900">Internal integrations</h2>
      <p className="mb-4 text-slate-600">
        Fanikisha has integrated with the following services. These are used inside the system; no implementation detail is exposed to end users.
      </p>
      <ul className="mb-8 list-inside list-disc space-y-1 text-slate-600">
        <li><strong>Email</strong> – Integrated for OTP delivery, password reset, and notifications (e.g. new lead alerts, status updates) to borrowers and lenders.</li>
        <li><strong>NIDA</strong> – Integrated for identity verification (e.g. for company registration and certain user flows in Tanzania).</li>
        <li><strong>TRA (Tanzania Revenue Authority)</strong> – Integrated for TIN (taxpayer) verification, driving licence verification, and motor vehicle verification in the Self Services section.</li>
        <li><strong>Credit bureau (e.g. Credit Info)</strong> – Integrated for credit report requests and lender credit checks.</li>
      </ul>

      <h2 className="mb-4 text-xl font-semibold text-slate-900">1. Transaction Analysis API (POST)</h2>
      <p className="mb-4 text-slate-600">
        The platform can receive transaction analysis data via the API. Replace <code className="rounded bg-slate-100 px-1.5 py-0.5 text-sm">BASE_URL</code> with your actual application URL (e.g. <code className="rounded bg-slate-100 px-1.5 py-0.5 text-sm">https://your-domain.com</code>).
      </p>

      <StepCard step={1} title="Endpoint">
        <p className="mb-2"><strong>POST</strong> <code className="rounded bg-slate-100 px-1.5 py-0.5 text-sm">BASE_URL/api/transaction-analysis</code></p>
        <p className="mb-0">Content-Type: <code className="rounded bg-slate-100 px-1.5 py-0.5 text-sm">application/json</code></p>
      </StepCard>

      <div className="mb-6">
        <h3 className="mb-2 font-semibold text-slate-900">Request body (example)</h3>
        <pre className="overflow-x-auto rounded-xl border border-slate-200 bg-slate-900 p-4 text-sm text-slate-100">
{`{
  "profile": {
    "account": "1234567890"
  },
  "1d_analysis": {
    "total_inflow": 500000,
    "total_outflow": 300000,
    "net_flow": 200000
  },
  "2d_analysis": {
    "average_balance": 150000,
    "transaction_count": 45
  },
  "3d_analysis": {
    "income_stability_score": 0.85,
    "spending_pattern": "consistent"
  },
  "affordability_scores": {
    "overall": 72,
    "income_adequacy": 80,
    "debt_burden": 65
  }
}`}
        </pre>
      </div>

      <div className="mb-8">
        <h3 className="mb-2 font-semibold text-slate-900">Response – success (201)</h3>
        <pre className="overflow-x-auto rounded-xl border border-slate-200 bg-slate-900 p-4 text-sm text-slate-100">
{`{
  "status": "success",
  "message": "Transaction analysis saved successfully",
  "data": {
    "id": 1,
    "account_number": "1234567890",
    "created_at": "2025-02-23T10:00:00.000000Z"
  }
}`}
        </pre>
      </div>

      <div className="mb-8">
        <h3 className="mb-2 font-semibold text-slate-900">Response – validation error (422)</h3>
        <pre className="overflow-x-auto rounded-xl border border-slate-200 bg-slate-900 p-4 text-sm text-slate-100">
{`{
  "status": "error",
  "message": "Validation failed",
  "errors": {
    "profile.account": ["The profile.account field is required."],
    "1d_analysis": ["The 1d_analysis field is required."]
  }
}`}
        </pre>
      </div>

      <div className="mb-8">
        <h3 className="mb-2 font-semibold text-slate-900">Response – server error (500)</h3>
        <pre className="overflow-x-auto rounded-xl border border-slate-200 bg-slate-900 p-4 text-sm text-slate-100">
{`{
  "status": "error",
  "message": "Failed to process transaction analysis",
  "error": "Internal server error"
}`}
        </pre>
      </div>

      <h2 className="mb-4 mt-10 text-xl font-semibold text-slate-900">2. Webhook – lead/application payload (example)</h2>
      <p className="mb-4 text-slate-600">
        When you configure a webhook URL in <strong>Integrations</strong>, the platform may send new leads (loan applications) to your endpoint. Below is an example payload structure you might receive. Exact field names can vary; check your Webhook Integration settings and field mappings.
      </p>

      <StepCard step={2} title="Outgoing webhook (platform → your system)">
        <p className="mb-2">Your configured URL receives a <strong>POST</strong> request with a JSON body. Example structure:</p>
        <pre className="mt-2 overflow-x-auto rounded-xl border border-slate-200 bg-slate-900 p-4 text-sm text-slate-100">
{`{
  "event": "application.submitted",
  "application_id": 42,
  "lender_id": 5,
  "submitted_at": "2025-02-23T10:00:00Z",
  "applicant": {
    "first_name": "Jane",
    "last_name": "Doe",
    "email": "jane@example.com",
    "phone": "+255700000000",
    "national_id": "19900101-12345-67890-1"
  },
  "loan": {
    "requested_amount": 5000000,
    "tenure_months": 12,
    "purpose": "Personal"
  },
  "documents": [
    { "type": "national_id", "url": "https://..." },
    { "type": "proof_of_income", "url": "https://..." }
  ]
}`}
        </pre>
      </StepCard>

      <div className="mb-6">
        <h3 className="mb-2 font-semibold text-slate-900">Your endpoint response (recommended)</h3>
        <p className="mb-2 text-slate-600">Respond with <strong>200 OK</strong> (or <strong>2xx</strong>) and optionally a JSON body so the platform knows the webhook was received:</p>
        <pre className="overflow-x-auto rounded-xl border border-slate-200 bg-slate-900 p-4 text-sm text-slate-100">
{`{
  "received": true,
  "message": "Lead received",
  "your_reference_id": "optional-id"
}`}
        </pre>
      </div>

      <StepCard step={3} title="Configure in the system">
        Go to <strong>Integrations</strong> (or Webhook Integration) in the sidebar. Add your API name, webhook URL, and authentication (e.g. API key header). Map platform fields to your target fields if the UI supports it. Save and test with a sample lead or transaction to confirm request/response format.
      </StepCard>

      <p className="mt-8 rounded-lg border border-slate-200 bg-slate-50 p-4 text-slate-600">
        To see how to <strong>receive and handle</strong> this webhook in your own backend, see <Link to="/lender/webhook-connect" className="font-medium text-brand-red hover:underline">Webhook – Connect (PHP, Java, Node, Python)</Link> for example code in PHP, Java, Node.js, and Python.
      </p>
    </article>
  )
}
