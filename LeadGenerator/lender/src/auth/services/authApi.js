import axios from "axios";

const authClient = axios.create({
  baseURL: import.meta.env.VITE_API_URL || "http://localhost:5000/api"
});

authClient.interceptors.request.use((config) => {
  const token = localStorage.getItem("fanikisha_lender_token");
  if (token) config.headers.Authorization = `Bearer ${token}`;
  return config;
});

export const authApi = {
  login: (payload) => authClient.post("/auth/login", payload),
  verifyOtp: (payload) => authClient.post("/auth/otp/verify", payload),
  me: () => authClient.get("/auth/me"),
  logout: () => authClient.post("/auth/logout")
};
