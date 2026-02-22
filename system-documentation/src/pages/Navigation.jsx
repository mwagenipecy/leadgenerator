export default function Navigation() {
  return (
    <article className="navigation-page">
      <h1 className="mb-2 text-3xl font-bold text-slate-900">Navigation</h1>
      <p className="mb-8 text-slate-600">
        The main navigation is the <strong className="text-brand-red">left sidebar</strong>. It is visible after login and changes by role. You can collapse it to show only icons. Below is an explanation of every menu item so you can find what you need quickly.
      </p>

      {/* Common for all roles */}
      <section className="mb-10">
        <h2 className="mb-4 text-xl font-semibold text-brand-red">Common for all roles</h2>
        <p className="mb-4 text-slate-600">
          These items appear for every logged-in user (Borrower, Lender, or Super Admin).
        </p>
        <ul className="space-y-4 text-slate-600">
          <li className="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
            <span className="font-semibold text-brand-red">Dashboard</span>
            <p className="mt-1 text-sm">The first page you see after login. It shows a summary and quick links based on your role—for example, number of applications, recent activity, or shortcuts to start a new application or open Lead Management. Use it as your home base to decide what to do next.</p>
          </li>
          <li className="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
            <span className="font-semibold text-brand-red">Blog</span>
            <p className="mt-1 text-sm">Opens the public blog listing. You can read articles and updates without leaving the app. The link appears in the sidebar when you are logged in.</p>
          </li>
          <li className="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
            <span className="font-semibold text-brand-red">Logout</span>
            <p className="mt-1 text-sm">A button at the bottom of the sidebar. Click it to sign out of your account safely. You will be returned to the landing page and must log in again to access the system.</p>
          </li>
        </ul>
      </section>

      {/* Borrower menu */}
      <section className="mb-10">
        <h2 className="mb-4 text-xl font-semibold text-brand-red">Borrower menu</h2>
        <p className="mb-4 text-slate-600">
          If you are a borrower (individual applying for a loan), you will see these items in the sidebar.
        </p>
        <ul className="space-y-4 text-slate-600">
          <li className="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
            <span className="font-semibold text-brand-red">User Profile</span>
            <p className="mt-1 text-sm">View and update your personal details—name, contact, address, employment—used in loan applications. Keeping this up to date speeds up new applications because the form can be pre-filled from your profile.</p>
          </li>
          <li className="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
            <span className="font-semibold text-brand-red">Loan Applications</span>
            <p className="mt-1 text-sm">Start a new loan application or see the list of your existing applications (drafts and submitted). From here you go through pre-qualification and the multi-step form (loan details, personal info, address, employment, emergency contact, documents), then submit or save as draft.</p>
          </li>
          <li className="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
            <span className="font-semibold text-brand-red">Self Services</span>
            <p className="mt-1 text-sm">An expandable submenu with: <strong className="text-brand-red">Verify TIN</strong> (taxpayer number), <strong className="text-brand-red">Verify License</strong> (driving licence), <strong className="text-brand-red">Verify Vehicle</strong> (motor vehicle), and <strong className="text-brand-red">Credit Report</strong>. Use these to verify your details or get your credit report before or during the loan process.</p>
          </li>
          <li className="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
            <span className="font-semibold text-brand-red">TRA Verifications</span>
            <p className="mt-1 text-sm">Full step-by-step guide for TRA (Tanzania Revenue Authority) checks: TIN, driving licence, and motor vehicle verification. The system is integrated with TRA so you can complete these from within Fanikisha.</p>
          </li>
          <li className="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
            <span className="font-semibold text-brand-red">Credit Report</span>
            <p className="mt-1 text-sm">Request and view your credit report from the integrated credit bureau. Useful to see your standing before applying for a loan and to correct any errors. Lenders may also pull your credit when assessing your application.</p>
          </li>
          <li className="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
            <span className="font-semibold text-brand-red">Credit Score</span>
            <p className="mt-1 text-sm">What your credit score means, score number ranges (e.g. 300–579 Poor to 800–850 Excellent), and a simple “game” of levels showing how to improve your score (pay on time, low utilization, fix errors, etc.).</p>
          </li>
        </ul>
      </section>

      {/* Lender menu */}
      <section className="mb-10">
        <h2 className="mb-4 text-xl font-semibold text-brand-red">Lender menu</h2>
        <p className="mb-4 text-slate-600">
          If you are a lender (institution receiving loan applications), you will see these items.
        </p>
        <ul className="space-y-4 text-slate-600">
          <li className="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
            <span className="font-semibold text-brand-red">Dashboard</span>
            <p className="mt-1 text-sm">Overview of leads and activity for your institution: number of applications, status breakdown (new, in progress, approved, rejected), and quick stats. Use it to prioritise which leads to work on and to report to your team.</p>
          </li>
          <li className="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
            <span className="font-semibold text-brand-red">Lead Management</span>
            <p className="mt-1 text-sm">The list of all loan applications (leads) sent to your institution. Open any application to see full details and documents, then approve, reject, or update status. This is where you convert leads into decisions and disbursements.</p>
          </li>
          <li className="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
            <span className="font-semibold text-brand-red">Reports</span>
            <p className="mt-1 text-sm">Booking reports and other aggregated data for your institution. Filter by date, status, or product; view totals and trends; export to CSV/Excel if supported. Use for internal reporting and planning.</p>
          </li>
          <li className="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
            <span className="font-semibold text-brand-red">Loan Products</span>
            <p className="mt-1 text-sm">Create and manage the loan products you offer (e.g. personal loan, salary advance). Set amount range, tenure, eligibility (e.g. min income, DSR). Borrowers are matched to these during pre-qualification; only matching products receive leads.</p>
          </li>
          <li className="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
            <span className="font-semibold text-brand-red">Notifications</span>
            <p className="mt-1 text-sm">In-app list of alerts—e.g. new lead received, application status updated. The system is integrated with email so you can also get important alerts by email. Open a notification to go to the related application.</p>
          </li>
          <li className="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
            <span className="font-semibold text-brand-red">API / Integration</span>
            <p className="mt-1 text-sm">Documentation for the Transaction Analysis API, webhook payloads, and internal integrations (email, NIDA, TRA, credit bureau). Configure your webhook URL and authentication in the Integrations page in the main app.</p>
          </li>
          <li className="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
            <span className="font-semibold text-brand-red">Webhook – Connect (PHP, Java, Node, Python)</span>
            <p className="mt-1 text-sm">Step-by-step and code examples for receiving Fanikisha webhooks in your backend: PHP, Java (Spring Boot), Node.js (Express), and Python (Flask). Use this when building the endpoint that receives new leads from the platform.</p>
          </li>
        </ul>
      </section>

      {/* Super Admin menu */}
      <section className="mb-10">
        <h2 className="mb-4 text-xl font-semibold text-brand-red">Super Admin menu</h2>
        <p className="mb-4 text-slate-600">
          If you are a Super Admin, you see the full set of menus below. You manage users, companies, lenders, settings, and content.
        </p>
        <ul className="space-y-4 text-slate-600">
          <li className="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
            <span className="font-semibold text-brand-red">Reports</span>
            <p className="mt-1 text-sm">System-wide booking and other reports. View aggregated data across all lenders and applications for oversight and reporting.</p>
          </li>
          <li className="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
            <span className="font-semibold text-brand-red">Admin Manager</span>
            <p className="mt-1 text-sm">Expandable submenu: <strong className="text-brand-red">User Management</strong> (list and edit users), <strong className="text-brand-red">Roles</strong> and <strong className="text-brand-red">Permissions</strong> (define what each role can do), <strong className="text-brand-red">Language Management</strong> (translations and supported languages). Use these to control access and localization.</p>
          </li>
          <li className="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
            <span className="font-semibold text-brand-red">Company Verification</span>
            <p className="mt-1 text-sm">Review companies that registered and are pending verification. View submitted documents and details, then approve or reject. Approved companies get full access (e.g. as lenders or verified businesses).</p>
          </li>
          <li className="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
            <span className="font-semibold text-brand-red">Lender Management</span>
            <p className="mt-1 text-sm">List of all lending institutions. Approve or reject pending lenders; suspend or reactivate approved ones; add users to a lender; delete if needed. Use this to control which institutions can receive leads on the platform.</p>
          </li>
          <li className="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
            <span className="font-semibold text-brand-red">System Settings</span>
            <p className="mt-1 text-sm">Global configuration: site name, security options, notification settings, feature toggles. Changes apply across the whole system. Only Super Admins can access this.</p>
          </li>
          <li className="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
            <span className="font-semibold text-brand-red">Billing</span>
            <p className="mt-1 text-sm">Billing and subscription section. View plan, usage, invoices, payment methods. Update payment details or download invoices as needed.</p>
          </li>
          <li className="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
            <span className="font-semibold text-brand-red">System Logs</span>
            <p className="mt-1 text-sm">View system logs for troubleshooting and auditing. Useful to trace errors or user actions when investigating an issue.</p>
          </li>
          <li className="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
            <span className="font-semibold text-brand-red">Loan Categories</span>
            <p className="mt-1 text-sm">Manage the categories used to classify loan products (e.g. Personal, Business). Create, edit, or disable categories. Lenders choose a category when creating a product.</p>
          </li>
          <li className="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
            <span className="font-semibold text-brand-red">Blog Management</span>
            <p className="mt-1 text-sm">Create, edit, and publish blog posts. Set title, content, excerpt, featured image, and publish status. The posts appear on the public Blog page.</p>
          </li>
          <li className="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
            <span className="font-semibold text-brand-red">Hero Slider</span>
            <p className="mt-1 text-sm">Manage the slides on the landing page hero section. Add, edit, or reorder slides (image, title, link). Active slides are shown to visitors on the home page.</p>
          </li>
          <li className="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
            <span className="font-semibold text-brand-red">Promotions</span>
            <p className="mt-1 text-sm">Manage promotions or banners. Set title, content, image, link, and start/end dates. Promotions are shown on the site according to their schedule.</p>
          </li>
          <li className="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
            <span className="font-semibold text-brand-red">Integrations</span>
            <p className="mt-1 text-sm">Webhook and API integration settings. Configure webhook URLs, authentication, and field mappings so the platform can send leads and events to external systems.</p>
          </li>
        </ul>
      </section>

      <div className="rounded-lg border-l-4 border-brand-red bg-red-50/50 p-4 text-slate-700">
        <p className="text-sm">
          <strong className="text-brand-red">Tip:</strong> The sidebar can be collapsed to icon-only if the toggle is available. The current page is highlighted in <strong className="text-brand-red">red</strong>. Use the sidebar to move between sections without using the browser back button.
        </p>
      </div>
    </article>
  )
}
