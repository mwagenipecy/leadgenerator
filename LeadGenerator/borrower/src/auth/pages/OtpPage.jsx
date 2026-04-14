import { useState } from "react";
import { useNavigate } from "react-router-dom";
import { AuthLayout } from "../components/AuthLayout";
import { useAuth } from "../context/AuthContext";
import { Button } from "../../shared/components/Button";
import { TextInput } from "../../shared/components/TextInput";
import { ErrorToast } from "../../shared/components/ErrorToast";

export function OtpPage() {
  const navigate = useNavigate();
  const { verifyOtp, devOtpCode } = useAuth();
  const [code, setCode] = useState("");
  const [error, setError] = useState("");
  const [isSubmitting, setIsSubmitting] = useState(false);

  async function onSubmit(event) {
    event.preventDefault();
    setError("");
    setIsSubmitting(true);
    try {
      const user = await verifyOtp(code);
      if (user.role === "admin") navigate("/admin/dashboard");
      else if (user.role === "lender") navigate("/lender/dashboard");
      else navigate("/borrower/dashboard");
    } catch {
      setError("Invalid OTP code.");
    } finally {
      setIsSubmitting(false);
    }
  }

  return (
    <AuthLayout>
      <ErrorToast message={error} onClose={() => setError("")} />
      <div className="text-center mb-8">
        <div className="flex justify-center mb-6">
          <img src="/landing/redlogo.png" alt="Fanikisha Market place Logo" className="h-16 w-auto" />
        </div>
        <h2 className="text-2xl lg:text-3xl font-bold text-gray-900 mb-2">OTP Verification</h2>
        <p className="text-gray-600">Enter the 6-digit code sent to your account.</p>
      </div>

      <div className="bg-white rounded-2xl p-8 border border-gray-100">
        <form onSubmit={onSubmit} className="space-y-4">
          <TextInput label="OTP Code" name="code" value={code} onChange={(e) => setCode(e.target.value)} required />
          {devOtpCode ? <p className="text-xs text-gray-500">Dev OTP: {devOtpCode}</p> : null}
          <Button type="submit" disabled={isSubmitting}>{isSubmitting ? "Verifying..." : "Verify OTP"}</Button>
        </form>
      </div>
    </AuthLayout>
  );
}
