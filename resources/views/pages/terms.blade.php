<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms and Conditions - Lead Generator</title>
    <link rel="icon" type="image/png" href="{{ asset('landing/applicationIcon.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Terms and Conditions</h1>
                <p class="text-gray-600">Last updated: {{ now()->format('F d, Y') }}</p>
            </div>

            <!-- Content -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 prose prose-lg max-w-none">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">1. Acceptance of Terms</h2>
                <p class="text-gray-700 mb-6">
                    By accessing and using this platform, you accept and agree to be bound by the terms and provision of this agreement. 
                    If you do not agree to abide by the above, please do not use this service.
                </p>

                <h2 class="text-2xl font-bold text-gray-900 mb-4">2. Use License</h2>
                <p class="text-gray-700 mb-4">
                    Permission is granted to temporarily access the materials on this platform for personal, non-commercial transitory viewing only. 
                    This is the grant of a license, not a transfer of title, and under this license you may not:
                </p>
                <ul class="list-disc pl-6 mb-6 text-gray-700">
                    <li>Modify or copy the materials</li>
                    <li>Use the materials for any commercial purpose or for any public display</li>
                    <li>Attempt to reverse engineer any software contained on the platform</li>
                    <li>Remove any copyright or other proprietary notations from the materials</li>
                </ul>

                <h2 class="text-2xl font-bold text-gray-900 mb-4">3. User Account</h2>
                <p class="text-gray-700 mb-4">
                    When you create an account with us, you must provide information that is accurate, complete, and current at all times. 
                    You are responsible for safeguarding the password and for all activities that occur under your account.
                </p>
                <p class="text-gray-700 mb-6">
                    You agree not to disclose your password to any third party and to take sole responsibility for any activities or actions 
                    under your account, whether or not you have authorized such activities or actions.
                </p>

                <h2 class="text-2xl font-bold text-gray-900 mb-4">4. Loan Applications</h2>
                <p class="text-gray-700 mb-4">
                    By submitting a loan application through this platform, you acknowledge that:
                </p>
                <ul class="list-disc pl-6 mb-6 text-gray-700">
                    <li>All information provided is true, accurate, and complete</li>
                    <li>You understand that loan approval is subject to lender verification and approval</li>
                    <li>Interest rates and terms are determined by individual lenders</li>
                    <li>We act as a platform connecting borrowers with lenders and do not guarantee loan approval</li>
                </ul>

                <h2 class="text-2xl font-bold text-gray-900 mb-4">5. Privacy Policy</h2>
                <p class="text-gray-700 mb-6">
                    Your use of this platform is also governed by our Privacy Policy. Please review our Privacy Policy to understand our practices 
                    regarding the collection and use of your personal information.
                </p>

                <h2 class="text-2xl font-bold text-gray-900 mb-4">6. Prohibited Uses</h2>
                <p class="text-gray-700 mb-4">
                    You may not use this platform:
                </p>
                <ul class="list-disc pl-6 mb-6 text-gray-700">
                    <li>In any way that violates any applicable national or international law or regulation</li>
                    <li>To transmit, or procure the sending of, any advertising or promotional material without our prior written consent</li>
                    <li>To impersonate or attempt to impersonate the company, a company employee, another user, or any other person or entity</li>
                    <li>In any way that infringes upon the rights of others, or in any way is illegal, threatening, fraudulent, or harmful</li>
                </ul>

                <h2 class="text-2xl font-bold text-gray-900 mb-4">7. Disclaimer</h2>
                <p class="text-gray-700 mb-6">
                    The materials on this platform are provided on an 'as is' basis. We make no warranties, expressed or implied, and hereby 
                    disclaim and negate all other warranties including, without limitation, implied warranties or conditions of merchantability, 
                    fitness for a particular purpose, or non-infringement of intellectual property or other violation of rights.
                </p>

                <h2 class="text-2xl font-bold text-gray-900 mb-4">8. Limitations</h2>
                <p class="text-gray-700 mb-6">
                    In no event shall we or our suppliers be liable for any damages (including, without limitation, damages for loss of data or 
                    profit, or due to business interruption) arising out of the use or inability to use the materials on this platform, even if we 
                    or an authorized representative has been notified orally or in writing of the possibility of such damage.
                </p>

                <h2 class="text-2xl font-bold text-gray-900 mb-4">9. Revisions</h2>
                <p class="text-gray-700 mb-6">
                    We may revise these terms of service at any time without notice. By using this platform, you are agreeing to be bound by the 
                    then current version of these terms of service.
                </p>

                <h2 class="text-2xl font-bold text-gray-900 mb-4">10. Contact Information</h2>
                <p class="text-gray-700 mb-6">
                    If you have any questions about these Terms and Conditions, please contact us through our support channels.
                </p>

                <div class="mt-8 pt-6 border-t border-gray-200">
                    <p class="text-sm text-gray-500">
                        By using this platform, you acknowledge that you have read, understood, and agree to be bound by these Terms and Conditions.
                    </p>
                </div>
            </div>

            <!-- Back Button -->
            <div class="mt-6">
                <a href="{{ url()->previous() ?: route('user.register') }}" 
                   class="inline-flex items-center text-red-600 hover:text-red-700 font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Back
                </a>
            </div>
        </div>
    </div>
</body>
</html>

