import { useEffect } from "react";

export function ErrorToast({ message, onClose, durationMs = 3500 }) {
  useEffect(() => {
    if (!message) return undefined;
    const timer = setTimeout(() => onClose?.(), durationMs);
    return () => clearTimeout(timer);
  }, [message, onClose, durationMs]);

  if (!message) return null;

  return (
    <div className="fixed top-4 right-4 z-50 max-w-sm rounded-lg bg-red-600 px-4 py-3 text-sm text-white shadow-lg">
      {message}
    </div>
  );
}
