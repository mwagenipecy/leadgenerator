export function Button({ children, className = "", ...props }) {
  return (
    <button
      className={`w-full bg-brandRed text-white py-3 rounded-lg font-semibold hover:bg-brandDarkRed disabled:opacity-60 ${className}`}
      {...props}
    >
      {children}
    </button>
  );
}
