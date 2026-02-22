import StepCard from '../../components/StepCard'

export default function TRAVerifications() {
  return (
    <article>
      <h1 className="mb-2 text-3xl font-bold text-slate-900">TRA Verifications</h1>
      <p className="mb-8 text-slate-600">
        TRA (Tanzania Revenue Authority) verifications are available under <strong>Self Services</strong> in the sidebar. You can verify your <strong>Taxpayer Identification Number (TIN)</strong>, <strong>driving licence</strong>, and <strong>motor vehicle</strong> registration. These help lenders and the platform confirm your identity and documents during the loan process.
      </p>

      <h2 className="mb-4 text-xl font-semibold text-slate-900">Verify TIN (Taxpayer Identification Number)</h2>
      <StepCard step={1} title="Open TIN verification">
        In the sidebar, expand <strong>Self Services</strong> and click <strong>Verify TIN Number</strong> (or the equivalent label). You are taken to the taxpayer verification page.
      </StepCard>
      <StepCard step={2} title="Enter your TIN">
        Enter your <strong>TIN</strong> and any other required details (e.g. full name, ID type) as shown on the page. The system will validate the TIN against TRA records.
      </StepCard>
      <StepCard step={3} title="Submit and view result">
        Click <strong>Verify</strong> or <strong>Submit</strong>. The result is shown on the same page: valid TIN details or an error message if the TIN is invalid or not found. You can use this result when applying for loans or when asked to prove your taxpayer status.
      </StepCard>

      <h2 className="mb-4 mt-10 text-xl font-semibold text-slate-900">Verify driving licence</h2>
      <StepCard step={4} title="Open licence verification">
        Under <strong>Self Services</strong>, click <strong>Verify License</strong>. The licence verification page opens.
      </StepCard>
      <StepCard step={5} title="Enter licence details">
        Enter your <strong>driving licence number</strong> (and any other requested fields, e.g. licence type or ID number). The system verifies the licence with TRA (or the relevant authority).
      </StepCard>
      <StepCard step={6} title="View verification result">
        Submit the form. The page shows whether the licence is valid, the holder’s name, expiry date (if returned), and any other details the integration provides. Use this when a lender or product requires licence verification.
      </StepCard>

      <h2 className="mb-4 mt-10 text-xl font-semibold text-slate-900">Verify motor vehicle</h2>
      <StepCard step={7} title="Open vehicle verification">
        Under <strong>Self Services</strong>, click <strong>Verify Vehicle</strong>. The motor vehicle verification page opens.
      </StepCard>
      <StepCard step={8} title="Enter vehicle details">
        Enter the <strong>vehicle registration number</strong> or other required information (e.g. chassis number) as indicated. The system checks the vehicle against TRA / transport authority records.
      </StepCard>
      <StepCard step={9} title="View result">
        Submit to verify. The result shows whether the vehicle is found, registration details, and any status (e.g. valid, expired). Useful when you are using the vehicle as collateral or when the lender requires vehicle verification.
      </StepCard>

      <p className="mt-8 rounded-lg border border-slate-200 bg-slate-50 p-4 text-sm text-slate-600">
        All TRA verification pages are available to <strong>borrowers</strong> from the Self Services menu. The platform has integrated with TRA for these checks; you do not need to leave Fanikisha to perform them. If a verification fails, check the details you entered or try again later; contact support if the issue continues.
      </p>
    </article>
  )
}
