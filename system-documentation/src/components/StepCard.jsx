export default function StepCard({ step, title, children }) {
  return (
    <div className="mb-8 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
      <div className="mb-4 flex items-center gap-3">
        <span className="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-brand-red text-lg font-bold text-white">
          {step}
        </span>
        <h3 className="m-0 text-xl font-semibold text-slate-900">{title}</h3>
      </div>
      <div className="text-slate-600">{children}</div>
    </div>
  )
}
