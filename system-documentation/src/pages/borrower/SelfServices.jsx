import StepCard from '../../components/StepCard'

export default function SelfServices() {
  return (
    <article>
      <h1 className="mb-2 text-3xl font-bold text-slate-900">Borrower – Self Services</h1>
      <p className="mb-8 text-slate-600">
        Self Services are verification tools available from the sidebar: TIN (Taxpayer), License, Motor Vehicle, and Credit Report. Each has its own page and steps.
      </p>

      <h2 className="mb-4 text-xl font-semibold text-slate-900">Verify TIN (Taxpayer Identification Number)</h2>
      <StepCard step={1} title="Open TIN verification">
        In the sidebar, expand <strong>Self Services</strong> and click <strong>Verify TIN Number</strong> (or the equivalent label). You are taken to the taxpayer verification page.
      </StepCard>
      <StepCard step={2} title="Enter details and verify">
        Enter the required information (e.g. TIN or related details as shown on the page). Submit to run the verification. Follow any on-screen instructions and check the result.
      </StepCard>

      <h2 className="mb-4 mt-10 text-xl font-semibold text-slate-900">Verify License</h2>
      <StepCard step={3} title="Open license verification">
        Under <strong>Self Services</strong>, click <strong>Verify License</strong>. The license verification page opens.
      </StepCard>
      <StepCard step={4} title="Enter license details">
        Enter the license number or other requested details. Submit to verify. Review the result shown on the page.
      </StepCard>

      <h2 className="mb-4 mt-10 text-xl font-semibold text-slate-900">Verify Motor Vehicle</h2>
      <StepCard step={5} title="Open vehicle verification">
        Under <strong>Self Services</strong>, click <strong>Verify Vehicle</strong>. The motor vehicle verification page opens.
      </StepCard>
      <StepCard step={6} title="Enter vehicle details">
        Enter the vehicle registration or other required information. Submit to verify and view the result.
      </StepCard>

      <h2 className="mb-4 mt-10 text-xl font-semibold text-slate-900">Credit Report</h2>
      <StepCard step={7} title="Open credit report">
        Under <strong>Self Services</strong>, click <strong>Credit Report</strong>. The credit report page opens.
      </StepCard>
      <StepCard step={8} title="Request or view report">
        Follow the instructions to request or view your credit report. You may need to provide identification or consent. The report (or summary) is shown on the page once available.
      </StepCard>
    </article>
  )
}
