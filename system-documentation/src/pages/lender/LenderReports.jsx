import StepCard from '../../components/StepCard'

export default function LenderReports() {
  return (
    <article>
      <h1 className="mb-2 text-3xl font-bold text-slate-900">Lender – Reports</h1>
      <p className="mb-4 text-slate-600">
        Reports give you <strong>aggregated data</strong> on applications and bookings for your institution. Instead of looking at leads one by one in Lead Management, you use Reports to see totals, trends, conversion rates, and performance over time. This helps with planning, forecasting, and reporting to management or regulators.
      </p>
      <p className="mb-8 text-slate-600">
        The main report type available to lenders is <strong>Booking Reports</strong>. You can filter by date range, status, product, or other dimensions (depending on what the system supports) and optionally export the data for use in spreadsheets or BI tools.
      </p>

      <StepCard step={1} title="Open Reports">
        In the left sidebar, click <strong>Reports</strong>. You are taken to the reports section (e.g. Booking Reports). Ensure you have the right date range and filters set so the numbers match your expectations.
      </StepCard>

      <StepCard step={2} title="Booking reports">
        The <strong>Booking Reports</strong> page shows metrics such as: number of applications received, total requested amount, breakdown by status (new, approved, rejected, pending), and possibly conversion rates or trends over time. Use the date picker or filters to narrow the period (e.g. this month, last quarter). The report is scoped to your lender account, so you only see your own leads and bookings.
      </StepCard>

      <StepCard step={3} title="Export or drill down">
        If the page supports it, you can <strong>export</strong> (e.g. CSV or Excel) to analyse data offline or in another tool. Some reports let you click into a row or segment to see the underlying applications. Use these reports for internal tracking, board reporting, and decision-making. If you need custom or additional reports, contact your system administrator.
      </StepCard>
    </article>
  )
}
