import { createContext, useContext, useEffect, useMemo, useState } from "react";
import { authApi } from "../services/authApi";

const AuthContext = createContext(null);

export function AuthProvider({ children }) {
  const [user, setUser] = useState(null);
  const [isLoading, setIsLoading] = useState(true);
  const [otpSessionId, setOtpSessionId] = useState(localStorage.getItem("fanikisha_borrower_otp_session"));
  const [devOtpCode, setDevOtpCode] = useState(localStorage.getItem("fanikisha_borrower_dev_otp"));

  useEffect(() => {
    const token = localStorage.getItem("fanikisha_borrower_token");
    if (!token) {
      setIsLoading(false);
      return;
    }

    authApi
      .me()
      .then((response) => setUser(response.data))
      .catch(() => {
        localStorage.removeItem("fanikisha_borrower_token");
        setUser(null);
      })
      .finally(() => setIsLoading(false));
  }, []);

  async function login(payload) {
    const response = await authApi.login(payload);
    if (response.data.otpRequired) {
      localStorage.setItem("fanikisha_borrower_otp_session", response.data.otpSessionId);
      setOtpSessionId(response.data.otpSessionId);
      if (response.data.devOtpCode) {
        localStorage.setItem("fanikisha_borrower_dev_otp", response.data.devOtpCode);
        setDevOtpCode(response.data.devOtpCode);
      }
      return { otpRequired: true };
    }

    localStorage.setItem("fanikisha_borrower_token", response.data.accessToken);
    setUser(response.data.user);
    return { otpRequired: false, user: response.data.user };
  }

  async function verifyOtp(code) {
    const session = localStorage.getItem("fanikisha_borrower_otp_session");
    if (!session) throw new Error("OTP session not found.");

    const response = await authApi.verifyOtp({ otpSessionId: session, code });
    localStorage.setItem("fanikisha_borrower_token", response.data.accessToken);
    localStorage.removeItem("fanikisha_borrower_otp_session");
    localStorage.removeItem("fanikisha_borrower_dev_otp");
    setOtpSessionId(null);
    setDevOtpCode(null);
    setUser(response.data.user);
    return response.data.user;
  }

  async function logout() {
    try {
      await authApi.logout();
    } finally {
      localStorage.removeItem("fanikisha_borrower_token");
      localStorage.removeItem("fanikisha_borrower_otp_session");
      localStorage.removeItem("fanikisha_borrower_dev_otp");
      setUser(null);
      setOtpSessionId(null);
      setDevOtpCode(null);
    }
  }

  const value = useMemo(
    () => ({
      user,
      isLoading,
      otpSessionId,
      devOtpCode,
      isAuthenticated: Boolean(user),
      login,
      verifyOtp,
      logout
    }),
    [user, isLoading, otpSessionId, devOtpCode]
  );

  return <AuthContext.Provider value={value}>{children}</AuthContext.Provider>;
}

export function useAuth() {
  const context = useContext(AuthContext);
  if (!context) {
    throw new Error("useAuth must be used within AuthProvider.");
  }
  return context;
}
