import StepCard from '../components/StepCard'

export default function Authentication() {
  return (
    <article>
      <h1 className="mb-2 text-3xl font-bold text-slate-900">Authentication</h1>
      <p className="mb-8 text-slate-600">
        How to log in, verify with OTP, and register as an <strong>individual</strong> or as a <strong>company</strong>. Verification and timing differ by user type and location (e.g. Tanzania companies use NIDA and have a 3 working-day verification period).
      </p>

      <h2 className="mb-4 text-xl font-semibold text-slate-900">Login (all users)</h2>
      <StepCard step={1} title="Go to Login">
        From the landing page, click the <strong>Login</strong> button or go to the login URL (e.g. <code className="rounded bg-slate-100 px-1.5 py-0.5 text-sm">/login</code>).
      </StepCard>
      <StepCard step={2} title="Enter credentials">
        Enter your <strong>email or phone number</strong> in the login field and your <strong>password</strong>. Optionally check “Remember me” to stay logged in.
      </StepCard>
      <StepCard step={3} title="Submit and OTP">
        Click the login button. If credentials are correct, you are redirected to the <strong>OTP verification</strong> step (you are temporarily logged out until OTP is verified). An OTP is sent to your email.
      </StepCard>
      <StepCard step={4} title="Enter OTP and continue">
        Type the code from the email into the OTP field and submit. If you did not receive it, use <strong>Resend code</strong>. After successful OTP verification, you are logged in. If your account type requires NIDA or document verification, you will be guided to complete it before full access.
      </StepCard>

      <h2 className="mb-4 mt-10 text-xl font-semibold text-slate-900">Registration – Individual (normal individual)</h2>
      <p className="mb-4 text-slate-600">
        For a <strong>normal individual</strong> (personal account, not a company), the flow is straightforward: register, verify email/OTP, and complete identity verification if required. No company documents or 3-day verification wait apply.
      </p>
      <StepCard step={5} title="Choose individual registration">
        From the landing or login area, go to <strong>Register</strong>. Select <strong>Individual</strong> (or “Register as individual”).
      </StepCard>
      <StepCard step={6} title="Fill individual form">
        Enter your details: full name, email, phone number, password, and any other required fields. Submit the form.
      </StepCard>
      <StepCard step={7} title="Verify and access">
        Complete <strong>OTP verification</strong> sent to your email. If the system requires identity verification (e.g. NIDA for certain flows), follow the on-screen steps. Once verified, you can access the dashboard and use the system as a borrower. There is no “pending verification” period for individuals; access is granted after OTP (and any immediate verification step) is done.
      </StepCard>

      <h2 className="mb-4 mt-10 text-xl font-semibold text-slate-900">Registration – Company from Tanzania (NIDA + documents, 3 working days)</h2>
      <p className="mb-4 text-slate-600">
        If the user is a <strong>company from Tanzania</strong>, the system verifies identity via <strong>NIDA</strong> and requires document submission. The company account stays in <strong>pending verification</strong> and is <strong>verified after 3 working days</strong> (by admin).
      </p>
      <StepCard step={8} title="Choose company registration (Tanzania)">
        From <strong>Register</strong>, select <strong>Company</strong> and ensure the company is identified as from Tanzania (e.g. country or region selection).
      </StepCard>
      <StepCard step={9} title="Complete NIDA verification">
        Complete <strong>NIDA verification</strong> as prompted (identity verification for the representative or company). Follow the NIDA flow on the platform (e.g. link or steps shown after registration/OTP).
      </StepCard>
      <StepCard step={10} title="Submit required documents">
        Upload and submit all required <strong>company documents</strong> (e.g. registration certificate, incorporation documents, KYC) as indicated on the company KYC or verification page.
      </StepCard>
      <StepCard step={11} title="Pending verification – 3 working days">
        After NIDA and document submission, the company status is <strong>Pending verification</strong>. The account will be <strong>verified after 3 working days</strong>. An administrator reviews the submission and approves or rejects. Once approved, the company gets full access (e.g. as lender or verified business). If not approved within or after that period, check with support or resubmit if requested.
      </StepCard>

      <h2 className="mb-4 mt-10 text-xl font-semibold text-slate-900">Registration – Company not from Tanzania (documents, verification within that time)</h2>
      <p className="mb-4 text-slate-600">
        For a <strong>company not from Tanzania</strong>, NIDA is not used. The company <strong>submits documents</strong> and verification is completed <strong>within the stated time</strong> (as shown in the system or communicated by admin), without the 3 working-day Tanzania rule.
      </p>
      <StepCard step={12} title="Choose company registration (non-Tanzania)">
        From <strong>Register</strong>, select <strong>Company</strong> and enter the company’s country/region (non-Tanzania).
      </StepCard>
      <StepCard step={13} title="Submit required documents">
        Upload and submit all required <strong>company documents</strong> (e.g. registration, incorporation, address, authorised signatories) on the company KYC or verification page.
      </StepCard>
      <StepCard step={14} title="Verification within stated time">
        The account goes to <strong>pending verification</strong>. Verification is completed <strong>within the time frame</strong> indicated in the system (or by support). There is no NIDA step. Once the admin verifies the documents, the company receives full access. If documents are incomplete or invalid, you may be asked to resubmit.
      </StepCard>
    </article>
  )
}
