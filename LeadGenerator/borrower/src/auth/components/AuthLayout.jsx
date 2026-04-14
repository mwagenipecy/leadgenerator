export function AuthLayout({ children }) {
  return (
    <div className="h-screen flex overflow-hidden font-inter">
      <section
        className="hidden lg:flex lg:w-1/2 relative overflow-hidden h-screen"
        style={{
          backgroundImage: "url('/landing/register-login.jpg')",
          backgroundSize: "cover",
          backgroundPosition: "center",
          backgroundRepeat: "no-repeat"
        }}
      >
        <div className="absolute bottom-0 left-0 right-0 h-1/4 bg-gradient-to-t from-brandRed/90 via-brandRed/60 to-transparent" />
        <div className="absolute bottom-0 right-0 z-20 p-6 pr-8 max-w-sm">
          <div className="bg-gradient-to-t from-brandRed via-brandRed/95 to-brandRed/80 rounded-lg p-5 backdrop-blur-sm text-white">
            <h2 className="text-xl md:text-2xl font-bold font-poppins mb-3 leading-tight">Connect. Grow. Succeed.</h2>
            <p className="text-sm leading-snug">Get matched with trusted lenders and secure the funding you need.</p>
          </div>
        </div>
      </section>

      <section className="w-full lg:w-1/2 h-screen overflow-y-auto bg-white">
        <div className="flex items-center justify-center min-h-full p-6 sm:p-8 lg:p-12">
          <div className="w-full max-w-lg py-8">{children}</div>
        </div>
      </section>
    </div>
  );
}
