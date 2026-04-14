import { Navigate, Route, Routes } from "react-router-dom";
import { LoginPage } from "../auth/pages/LoginPage";
import { OtpPage } from "../auth/pages/OtpPage";
import { ProtectedRoute } from "../auth/routes/ProtectedRoute";
import { RoleGuard } from "../auth/routes/RoleGuard";
import { LenderDashboardPage } from "../features/dashboard/pages/LenderDashboardPage";
import { UnauthorizedPage } from "../features/dashboard/pages/UnauthorizedPage";

export function AppRouter() {
  return (
    <Routes>
      <Route path="/login" element={<LoginPage />} />
      <Route path="/otp" element={<OtpPage />} />
      <Route element={<ProtectedRoute />}>
        <Route path="/unauthorized" element={<UnauthorizedPage />} />
        <Route element={<RoleGuard allowedRoles={["lender"]} />}>
          <Route path="/lender/dashboard" element={<LenderDashboardPage />} />
        </Route>
      </Route>
      <Route path="*" element={<Navigate to="/login" replace />} />
    </Routes>
  );
}
