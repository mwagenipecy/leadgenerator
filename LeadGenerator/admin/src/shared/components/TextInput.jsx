export function TextInput({ label, ...props }) {
  return (
    <label className="block">
      <span className="block text-sm text-gray-700 mb-2">{label}</span>
      <input
        className="block w-full border border-gray-300 rounded-lg px-3 py-3 focus:outline-none focus:ring-2 focus:ring-brandRed"
        {...props}
      />
    </label>
  );
}
