import StepCard from '../../components/StepCard'

export default function Notifications() {
  return (
    <article>
      <h1 className="mb-2 text-3xl font-bold text-slate-900">Lender – Notifications</h1>
      <p className="mb-8 text-slate-600">
        The <strong>Notifications</strong> section shows alerts and messages for your lender account. You see new lead alerts, status updates, and other important notices so you can respond to applications quickly.
      </p>

      <StepCard step={1} title="Open Notifications">
        In the left sidebar, click <strong>Notifications</strong>. The notifications list or inbox opens. You may also see a notification icon in the header with a badge for unread count.
      </StepCard>

      <StepCard step={2} title="What you see">
        The list shows notifications such as: <strong>New application received</strong> (new lead assigned to you), <strong>Application status updated</strong> (e.g. by borrower or system), <strong>Document uploaded</strong>, and system announcements. Each item usually has a title, short message, and date. Unread items are highlighted.
      </StepCard>

      <StepCard step={3} title="Open a notification">
        Click a notification to open it or to go to the related application. Mark as read when done. Use filters (e.g. unread only, by type or date) if the page supports them.
      </StepCard>

      <StepCard step={4} title="Email and in-app">
        The platform has integrated with <strong>email</strong> for notifications. You may receive important alerts by email as well as in the Notifications page. Ensure your email address is correct in your profile so you do not miss time-sensitive lead alerts.
      </StepCard>
    </article>
  )
}
