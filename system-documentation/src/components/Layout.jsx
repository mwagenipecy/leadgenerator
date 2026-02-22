import { useState, useMemo } from 'react'
import { Link, useLocation, useNavigate } from 'react-router-dom'

const navSections = [
  {
    title: 'Introduction',
    items: [
      { path: '/', label: 'Home' },
      { path: '/getting-started', label: 'Getting Started' },
      { path: '/user-roles', label: 'User Roles' },
      { path: '/authentication', label: 'Authentication' },
      { path: '/navigation', label: 'Navigation' },
    ],
  },
  {
    title: 'Borrower',
    items: [
      { path: '/borrower/dashboard', label: 'Dashboard' },
      { path: '/borrower/profile', label: 'User Profile' },
      { path: '/borrower/loan-application', label: 'Loan Application' },
      { path: '/borrower/self-services', label: 'Self Services' },
      { path: '/borrower/tra-verifications', label: 'TRA Verifications' },
      { path: '/borrower/credit-report', label: 'Credit Report' },
      { path: '/borrower/credit-score', label: 'Credit Score' },
    ],
  },
  {
    title: 'Lender',
    items: [
      { path: '/lender/dashboard', label: 'Dashboard' },
      { path: '/lender/lead-management', label: 'Lead Management' },
      { path: '/lender/reports', label: 'Reports' },
      { path: '/lender/loan-products', label: 'Loan Products' },
      { path: '/lender/notifications', label: 'Notifications' },
      { path: '/lender/api-integration', label: 'API / Integration' },
      { path: '/lender/webhook-connect', label: 'Webhook – Connect (PHP, Java, Node, Python)' },
    ],
  },
  {
    title: 'Super Admin',
    items: [
      { path: '/admin/overview', label: 'Overview' },
      { path: '/admin/user-management', label: 'User Management' },
      { path: '/admin/company-verification', label: 'Company Verification' },
      { path: '/admin/lender-management', label: 'Lender Management' },
      { path: '/admin/system-settings', label: 'System Settings' },
      { path: '/admin/billing', label: 'Billing' },
      { path: '/admin/content-management', label: 'Content Management' },
    ],
  },
]

const allNavItems = navSections.flatMap((section) =>
  section.items.map((item) => ({ ...item, sectionTitle: section.title }))
)

const VERSIONS = [
  { value: 'v1', label: 'Fanikisha Marketplace v1' },
  { value: 'v2', label: 'Fanikisha Marketplace v2' },
]

function NavLinks({ location, onNavigate }) {
  return (
    <>
      {navSections.map((section) => (
        <div key={section.title}>
          <h3 className="mb-2 text-xs font-semibold uppercase tracking-wider text-slate-500">
            {section.title}
          </h3>
          <ul className="space-y-0.5">
            {section.items.map((item) => (
              <li key={item.path}>
                <Link
                  to={item.path}
                  onClick={onNavigate}
                  className={`block rounded-lg px-3 py-2 text-sm transition-colors ${
                    location.pathname === item.path
                      ? 'bg-brand-red text-white font-medium'
                      : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900'
                  }`}
                >
                  {item.label}
                </Link>
              </li>
            ))}
          </ul>
        </div>
      ))}
    </>
  )
}

export default function Layout({ children }) {
  const location = useLocation()
  const navigate = useNavigate()
  const [sidebarOpen, setSidebarOpen] = useState(false)
  const [searchQuery, setSearchQuery] = useState('')
  const [searchFocused, setSearchFocused] = useState(false)
  const [version, setVersion] = useState('v1')

  const searchResults = useMemo(() => {
    const q = searchQuery.trim().toLowerCase()
    if (!q) return []
    return allNavItems.filter(
      (item) =>
        item.label.toLowerCase().includes(q) ||
        item.path.toLowerCase().includes(q) ||
        item.sectionTitle.toLowerCase().includes(q)
    )
  }, [searchQuery])

  const openResult = (path) => {
    navigate(path)
    setSearchQuery('')
    setSearchFocused(false)
    setSidebarOpen(false)
  }

  return (
    <div className="min-h-screen bg-slate-50 text-slate-900">
      <header className="sticky top-0 z-40 border-b border-slate-200 bg-white/95 backdrop-blur">
        <div className="mx-auto flex min-h-14 max-w-7xl flex-wrap items-center gap-2 py-2 px-3 sm:gap-3 sm:px-6 lg:flex-nowrap lg:py-0 lg:px-8">
          {/* Mobile menu button */}
          <button
            type="button"
            onClick={() => setSidebarOpen(true)}
            className="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg text-slate-600 hover:bg-slate-100 lg:hidden"
            aria-label="Open menu"
          >
            <svg className="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>

          <Link
            to="/"
            className="flex shrink-0 items-center gap-2 font-display text-lg font-bold text-brand-red sm:gap-3"
          >
            <img src="/redlogo.png" alt="Docs" className="h-7 w-auto sm:h-9" />
            <span className="rounded-lg bg-brand-red px-2 py-0.5 text-white text-xs sm:text-sm">
              Docs
            </span>
          </Link>

          {/* Search - grows but doesn't overflow */}
          <div className="relative min-w-0 flex-1 basis-0 sm:basis-auto sm:max-w-md lg:mx-auto">
            <div className="relative">
              <span className="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                <svg className="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
              </span>
              <input
                type="search"
                value={searchQuery}
                onChange={(e) => setSearchQuery(e.target.value)}
                onFocus={() => setSearchFocused(true)}
                onBlur={() => setTimeout(() => setSearchFocused(false), 180)}
                placeholder="Search documentation..."
                className="w-full rounded-lg border border-slate-200 bg-slate-50 py-2 pl-9 pr-3 text-sm text-slate-900 placeholder-slate-400 focus:border-brand-red focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-red/20"
                aria-label="Search documentation"
              />
            </div>
            {searchFocused && searchResults.length > 0 && (
              <div className="absolute left-0 right-0 top-full z-50 mt-1 max-h-72 overflow-auto rounded-lg border border-slate-200 bg-white py-1 shadow-lg">
                {searchResults.map((item) => (
                  <button
                    key={item.path}
                    type="button"
                    onClick={() => openResult(item.path)}
                    className="flex w-full flex-col items-start px-3 py-2 text-left text-sm hover:bg-slate-50"
                  >
                    <span className="font-medium text-slate-900">{item.label}</span>
                    <span className="text-xs text-slate-500">{item.sectionTitle}</span>
                  </button>
                ))}
              </div>
            )}
            {searchFocused && searchQuery.trim() && searchResults.length === 0 && (
              <div className="absolute left-0 right-0 top-full z-50 mt-1 rounded-lg border border-slate-200 bg-white px-3 py-4 text-sm text-slate-500 shadow-lg">
                No results for “{searchQuery}”
              </div>
            )}
          </div>

          {/* Version dropdown - right */}
          <div className="flex shrink-0 items-center self-center">
            <label htmlFor="version-select" className="sr-only">
              Version
            </label>
            <select
              id="version-select"
              value={version}
              onChange={(e) => setVersion(e.target.value)}
              className="min-w-0 max-w-[120px] rounded-lg border border-slate-200 bg-white px-2 py-1.5 text-xs font-medium text-slate-700 sm:max-w-none sm:min-w-[140px] sm:px-3 sm:py-2 sm:text-sm focus:border-brand-red focus:outline-none focus:ring-2 focus:ring-brand-red/20"
            >
              {VERSIONS.map((v) => (
                <option key={v.value} value={v.value}>
                  {v.label}
                </option>
              ))}
            </select>
          </div>
        </div>
      </header>

      <div className="mx-auto flex max-w-7xl gap-8 px-4 py-6 sm:px-6 lg:px-8">
        {/* Desktop sidebar */}
        <aside className="hidden w-64 shrink-0 lg:block">
          <nav className="sticky top-24 space-y-6">
            <NavLinks location={location} />
          </nav>
        </aside>

        {/* Mobile sidebar overlay */}
        {sidebarOpen && (
          <div
            className="fixed inset-0 z-50 bg-slate-900/50 lg:hidden"
            aria-hidden
            onClick={() => setSidebarOpen(false)}
          />
        )}
        <aside
          className={`fixed inset-y-0 left-0 z-50 flex h-full max-h-[100dvh] w-72 max-w-[85vw] flex-col transform border-r border-slate-200 bg-white shadow-xl transition-transform duration-200 ease-out lg:hidden ${
            sidebarOpen ? 'translate-x-0' : '-translate-x-full'
          }`}
        >
          <div className="flex shrink-0 items-center justify-between border-b border-slate-200 p-4">
            <span className="font-semibold text-slate-900">Menu</span>
            <button
              type="button"
              onClick={() => setSidebarOpen(false)}
              className="rounded-lg p-2 text-slate-500 hover:bg-slate-100"
              aria-label="Close menu"
            >
              <svg className="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
          <nav className="min-h-0 flex-1 space-y-6 overflow-y-auto overscroll-contain p-4 pb-6">
            <NavLinks location={location} onNavigate={() => setSidebarOpen(false)} />
          </nav>
        </aside>

        <main className="min-w-0 flex-1">
          <div className="prose prose-slate max-w-none prose-headings:font-display prose-a:text-brand-red prose-a:no-underline hover:prose-a:underline">
            {children}
          </div>
        </main>
      </div>
    </div>
  )
}
