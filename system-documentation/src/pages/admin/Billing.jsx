import StepCard from '../../components/StepCard'

export default function Billing() {
  return (
    <article>
      <h1 className="mb-2 text-3xl font-bold text-slate-900">Super Admin – Billing</h1>
      <p className="mb-8 text-slate-600">
        The Billing section is used to manage billing, subscriptions, or payments related to the platform.
      </p>

      <StepCard step={1} title="Open Billing">
        In the left sidebar, click <strong>Billing</strong>. The Billing section opens.
      </StepCard>

      <StepCard step={2} title="What you can do">
        Depending on configuration, you may see: current plan, usage, invoices, payment methods, or billing for lenders. Use the tabs or links on the page to view history, download invoices, or update payment details.
      </StepCard>

      <StepCard step={3} title="Take action">
        Follow on-screen instructions to pay an invoice, update a payment method, or contact support. Any critical messages (e.g. payment failed) are usually shown at the top of the page.
      </StepCard>
    </article>
  )
}
