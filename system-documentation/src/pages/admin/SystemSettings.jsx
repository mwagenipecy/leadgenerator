import StepCard from '../../components/StepCard'

export default function SystemSettings() {
  return (
    <article>
      <h1 className="mb-2 text-3xl font-bold text-slate-900">Super Admin – System Settings</h1>
      <p className="mb-8 text-slate-600">
        System Settings hold global configuration options for the application. Only Super Admins can access this page.
      </p>

      <StepCard step={1} title="Open System Settings">
        In the left sidebar, click <strong>Settings</strong> (under the system section). The System Settings page opens.
      </StepCard>

      <StepCard step={2} title="Review sections">
        The page is organised into sections (e.g. general, security, notifications, integrations). Each section contains options relevant to that area. Common options include: site name, default currency, session timeout, email/SMS settings, and feature toggles.
      </StepCard>

      <StepCard step={3} title="Edit and save">
        Change any setting as needed. Use <strong>Save</strong> or <strong>Update</strong> at the section or page level. Changes take effect immediately or after the next reload, depending on the setting. Be careful with security-related options.
      </StepCard>
    </article>
  )
}
