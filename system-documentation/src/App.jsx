import { Routes, Route } from 'react-router-dom'
import Layout from './components/Layout'
import Home from './pages/Home'
import GettingStarted from './pages/GettingStarted'
import UserRoles from './pages/UserRoles'
import Authentication from './pages/Authentication'
import Navigation from './pages/Navigation'
import BorrowerDashboard from './pages/borrower/BorrowerDashboard'
import BorrowerProfile from './pages/borrower/BorrowerProfile'
import LoanApplication from './pages/borrower/LoanApplication'
import SelfServices from './pages/borrower/SelfServices'
import TRAVerifications from './pages/borrower/TRAVerifications'
import CreditReport from './pages/borrower/CreditReport'
import CreditScore from './pages/borrower/CreditScore'
import LenderDashboard from './pages/lender/LenderDashboard'
import LeadManagement from './pages/lender/LeadManagement'
import LenderReports from './pages/lender/LenderReports'
import LoanProducts from './pages/lender/LoanProducts'
import ApiIntegration from './pages/lender/ApiIntegration'
import Notifications from './pages/lender/Notifications'
import WebhookConnect from './pages/lender/WebhookConnect'
import AdminOverview from './pages/admin/AdminOverview'
import UserManagement from './pages/admin/UserManagement'
import CompanyVerification from './pages/admin/CompanyVerification'
import LenderManagement from './pages/admin/LenderManagement'
import SystemSettings from './pages/admin/SystemSettings'
import Billing from './pages/admin/Billing'
import ContentManagement from './pages/admin/ContentManagement'

function App() {
  return (
    <Layout>
      <Routes>
        <Route path="/" element={<Home />} />
        <Route path="/getting-started" element={<GettingStarted />} />
        <Route path="/user-roles" element={<UserRoles />} />
        <Route path="/authentication" element={<Authentication />} />
        <Route path="/navigation" element={<Navigation />} />
        <Route path="/borrower/dashboard" element={<BorrowerDashboard />} />
        <Route path="/borrower/profile" element={<BorrowerProfile />} />
        <Route path="/borrower/loan-application" element={<LoanApplication />} />
        <Route path="/borrower/self-services" element={<SelfServices />} />
        <Route path="/borrower/tra-verifications" element={<TRAVerifications />} />
        <Route path="/borrower/credit-report" element={<CreditReport />} />
        <Route path="/borrower/credit-score" element={<CreditScore />} />
        <Route path="/lender/dashboard" element={<LenderDashboard />} />
        <Route path="/lender/lead-management" element={<LeadManagement />} />
        <Route path="/lender/reports" element={<LenderReports />} />
        <Route path="/lender/loan-products" element={<LoanProducts />} />
        <Route path="/lender/notifications" element={<Notifications />} />
        <Route path="/lender/api-integration" element={<ApiIntegration />} />
        <Route path="/lender/webhook-connect" element={<WebhookConnect />} />
        <Route path="/admin/overview" element={<AdminOverview />} />
        <Route path="/admin/user-management" element={<UserManagement />} />
        <Route path="/admin/company-verification" element={<CompanyVerification />} />
        <Route path="/admin/lender-management" element={<LenderManagement />} />
        <Route path="/admin/system-settings" element={<SystemSettings />} />
        <Route path="/admin/billing" element={<Billing />} />
        <Route path="/admin/content-management" element={<ContentManagement />} />
      </Routes>
    </Layout>
  )
}

export default App
