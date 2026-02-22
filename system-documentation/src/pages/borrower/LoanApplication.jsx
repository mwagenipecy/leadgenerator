import StepCard from '../../components/StepCard'

export default function LoanApplication() {
  return (
    <article>
      <h1 className="mb-2 text-3xl font-bold text-slate-900">Borrower – Loan Application (step by step)</h1>
      <p className="mb-8 text-slate-600">
        When you apply for a loan, <strong className="text-brand-red">your information is already there</strong>: after NIDA verification, Fanikisha fills your profile with verified data. You only need to <strong className="text-brand-red">complete a few remaining fields</strong>, then <strong className="text-brand-red">select the lender(s)</strong> to send your request to, upload documents, and finally <strong className="text-brand-red">preview and submit</strong>. You can save as draft and come back later.
      </p>

      <h2 className="mb-4 text-xl font-semibold text-brand-red">Pre-qualification</h2>
      <StepCard step={1} title="Start a new application">
        Go to <strong>Loan Applications</strong> in the sidebar, then click <strong>Start new application</strong> or <strong>Apply for a loan</strong>. You will see the pre-qualification screen.
      </StepCard>
      <StepCard step={2} title="Enter pre-qualification details">
        Enter: <strong>Loan amount</strong>, <strong>Monthly income</strong>, <strong>Existing loan payments</strong>, and <strong>Tenure (months)</strong>. The system calculates your Debt Service Ratio (DSR) and shows whether you meet the minimum requirement to proceed. Click <strong>Next</strong> when done.
      </StepCard>

      <h2 className="mb-4 mt-10 text-xl font-semibold text-brand-red">Your information (from profile)</h2>
      <StepCard step={3} title="User information is already there">
        After <strong>NIDA verification</strong>, your verified details are stored in <strong>Your profile</strong>. When you apply for a loan, the form is <strong>pre-populated</strong> with your name, national ID, contact, and other data from your profile—so you don’t have to type everything again.
      </StepCard>
      <StepCard step={4} title="Finish the few remaining fields">
        You are only required to <strong>complete the few data</strong> that are missing or that you want to update (e.g. loan purpose, current address, employment details, emergency contact). Review the pre-filled fields and fill or correct any empty or outdated ones. Click <strong>Next</strong> to continue.
      </StepCard>

      <h2 className="mb-4 mt-10 text-xl font-semibold text-brand-red">Select lender and send request</h2>
      <StepCard step={5} title="Select lender(s) where you can send your request">
        You will see a list of <strong>matching loan products</strong> from different lenders. Choose the <strong>lender(s)</strong> you want to send your application to (you can select one or more). This is where you decide who will receive your loan request. When ready, click <strong>Next</strong>.
      </StepCard>

      <h2 className="mb-4 mt-10 text-xl font-semibold text-brand-red">Application, documents, then preview and submit</h2>
      <StepCard step={6} title="See your application and submit documents">
        On the next screen you will see <strong>your application summary</strong>. Here you must <strong>upload the required documents</strong> (e.g. national ID, proof of income, proof of address) as indicated. Each document type shows accepted formats and size limits. Upload all required documents, then click <strong>Next</strong>.
      </StepCard>
      <StepCard step={7} title="Preview and submit">
        The last step is <strong>Preview and submit</strong>. Review your full application—loan details, your information, selected lender(s), and uploaded documents. If everything is correct, click <strong>Submit</strong> to send your application to the selected lender(s). You can also click <strong>Save as draft</strong> to finish later.
      </StepCard>

      <StepCard step={8} title="After submission">
        Once you submit, you will see a confirmation. Your application is sent to the lender(s) you selected. You can return to <strong>Loan Applications</strong> to see the list and status of your applications. Drafts can be edited and submitted later. Lenders will review and respond; check your notifications and email for updates.
      </StepCard>
    </article>
  )
}
