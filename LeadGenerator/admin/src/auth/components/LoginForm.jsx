import { useState } from "react";
import { useNavigate } from "react-router-dom";
import { useAuth } from "../context/AuthContext";
import { Button } from "../../shared/components/Button";
import { TextInput } from "../../shared/components/TextInput";
import { ErrorToast } from "../../shared/components/ErrorToast";

export function LoginForm() {
  const navigate = useNavigate();
  const { login } = useAuth();
  const [form, setForm] = useState({ login: "", password: "" });
  const [error, setError] = useState("");
  const [isSubmitting, setIsSubmitting] = useState(false);

  function onChange(event) {
    setForm((prev) => ({ ...prev, [event.target.name]: event.target.value }));
  }

  async function onSubmit(event) {
    event.preventDefault();
    setError("");
    setIsSubmitting(true);
    try {
      const result = await login(form);
      if (result.otpRequired) navigate("/otp");
    } catch {
      setError("Invalid login credentials.");
    } finally {
      setIsSubmitting(false);
    }
  }

  return (
    <>
      <ErrorToast message={error} onClose={() => setError("")} />
      <div className="text-center mb-8">
        <div className="flex justify-center mb-6">
          <img src="/landing/redlogo.png" alt="Fanikisha Market place Logo" className="h-16 w-auto" />
        </div>
        <h2 className="text-2xl lg:text-3xl font-bold text-gray-900 mb-2">Welcome</h2>
        <p className="text-gray-600">Fanikisha admin login</p>
      </div>

      <div className="bg-white rounded-2xl p-8 border border-gray-100">
        <form onSubmit={onSubmit} className="space-y-4">
          <TextInput label="Email / Phone" name="login" value={form.login} onChange={onChange} required />
          <TextInput label="Password" name="password" type="password" value={form.password} onChange={onChange} required />
          <Button type="submit" disabled={isSubmitting}>{isSubmitting ? "Loading..." : "Login"}</Button>
        </form>
      </div>

      <div className="mt-6 text-center text-sm text-gray-500">
        <span>Your data is protected with 256-bit SSL encryption</span>
      </div>
      <div className="mt-4 text-center text-xs text-gray-400">Powered by Fanikisha</div>
    </>
  );
}
