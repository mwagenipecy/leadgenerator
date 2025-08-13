
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Loan Application - {{ $application->application_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
            font-size: 12px;
        }
        
        .header {
            border-bottom: 3px solid #dc2626;
            padding-bottom: 20px;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .header h1 {
            color: #dc2626;
            margin: 0;
            font-size: 28px;
        }
        
        .header .subtitle {
            color: #666;
            margin: 5px 0 0 0;
            font-size: 14px;
        }
        
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 10px;
        }
        
        .status-draft { background: #f3f4f6; color: #374151; }
        .status-submitted { background: #dbeafe; color: #1e40af; }
        .status-under_review { background: #fef3c7; color: #92400e; }
        .status-approved { background: #d1fae5; color: #065f46; }
        .status-rejected { background: #fee2e2; color: #991b1b; }
        .status-disbursed { background: #e9d5ff; color: #7c2d12; }
        .status-cancelled { background: #f3f4f6; color: #6b7280; }
        
        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        
        .section h2 {
            color: #dc2626;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 5px;
            margin-bottom: 15px;
            font-size: 16px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 15px;
        }
        
        .info-item {
            margin-bottom: 10px;
        }
        
        .info-label {
            font-weight: bold;
            color: #4b5563;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .info-value {
            font-size: 13px;
            margin-top: 2px;
        }
        
        .amount-highlight {
            font-size: 20px;
            font-weight: bold;
            color: #dc2626;
        }
        
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            font-size: 10px;
            color: #6b7280;
            text-align: center;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        
        .table th,
        .table td {
            border: 1px solid #e5e7eb;
            padding: 8px;
            text-align: left;
            font-size: 11px;
        }
        
        .table th {
            background-color: #f9fafb;
            font-weight: bold;
        }
        
        @page {
            margin: 2cm;
        }
        
        .page-break {
            page-break-before: always;
        }

        .summary-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
        }

        .highlight-box {
            background: #fef2f2;
            border-left: 4px solid #dc2626;
            padding: 10px 15px;
            margin: 10px 0;
        }

        .financial-summary {
            background: #f0f9ff;
            border: 1px solid #0ea5e9;
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
        }

        .document-list {
            background: #f8fafc;
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
        }

        .progress-bar {
            width: 100%;
            height: 20px;
            background: #e5e7eb;
            border-radius: 10px;
            overflow: hidden;
            margin: 10px 0;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(to right, #dc2626, #b91c1c);
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>Loan Application</h1>
        <div class="subtitle">Application Number: {{ $application->application_number }}</div>
        <div class="subtitle">Generated on: {{ $generated_at->format('F d, Y H:i') }}</div>
        <span class="status-badge status-{{ $application->status }}">
            {{ ucwords(str_replace('_', ' ', $application->status)) }}
        </span>
    </div>

    <!-- Application Summary -->
    <div class="section">
        <h2>Application Summary</h2>
        <div class="summary-box">
            <div class="info-grid">
                <div>
                    <div class="info-item">
                        <div class="info-label">Applicant Name</div>
                        <div class="info-value">{{ $application->first_name }} {{ $application->middle_name }} {{ $application->last_name }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Application Date</div>
                        <div class="info-value">{{ $application->created_at->format('F d, Y') }}</div>
                    </div>
                    @if($application->submitted_at)
                        <div class="info-item">
                            <div class="info-label">Submission Date</div>
                            <div class="info-value">{{ $application->submitted_at->format('F d, Y') }}</div>
                        </div>
                    @endif
                </div>
                <div>
                    <div class="info-item">
                        <div class="info-label">Requested Amount</div>
                        <div class="info-value amount-highlight">TSh {{ number_format($application->requested_amount) }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Loan Period</div>
                        <div class="info-value">{{ $application->requested_tenure_months }} months</div>
                    </div>
                    @if($application->debt_to_income_ratio)
                        <div class="info-item">
                            <div class="info-label">Debt Service Ratio</div>
                            <div class="info-value">{{ number_format($application->debt_to_income_ratio, 1) }}%</div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Progress Bar -->
            @php
                $progressPercentage = match($application->status) {
                    'draft' => 20,
                    'submitted' => 40,
                    'under_review' => 60,
                    'approved' => 80,
                    'disbursed' => 100,
                    'rejected' => 100,
                    'cancelled' => 100,
                    default => 0
                };
            @endphp
            <div class="info-item">
                <div class="info-label">Application Progress</div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: {{ $progressPercentage }}%"></div>
                </div>
                <div style="text-align: center; margin-top: 5px; font-size: 10px;">{{ $progressPercentage }}% Complete</div>
            </div>
        </div>
    </div>

    <!-- Loan Details -->
    <div class="section">
        <h2>Loan Details</h2>
        <div class="info-grid">
            <div>
                <div class="info-item">
                    <div class="info-label">Requested Amount</div>
                    <div class="info-value amount-highlight">TSh {{ number_format($application->requested_amount) }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Loan Period</div>
                    <div class="info-value">{{ $application->requested_tenure_months }} months</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Purpose</div>
                    <div class="info-value">{{ $application->loan_purpose ? ucwords(str_replace('_', ' ', $application->loan_purpose)) : 'Not specified' }}</div>
                </div>
            </div>
            <div>
                @if($application->lender)
                    <div class="info-item">
                        <div class="info-label">Lender</div>
                        <div class="info-value">{{ $application->lender->company_name }}</div>
                    </div>
                @endif
                @if($application->loanProduct)
                    <div class="info-item">
                        <div class="info-label">Product</div>
                        <div class="info-value">{{ $application->loanProduct->name }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Interest Rate Range</div>
                        <div class="info-value">{{ $application->loanProduct->interest_rate_min }}% - {{ $application->loanProduct->interest_rate_max }}%</div>
                    </div>
                @endif
            </div>
        </div>

        @if($application->requested_amount && $application->requested_tenure_months)
            @php
                $interestRate = 0.15; // Default 15%
                if($application->loanProduct) {
                    $interestRate = $application->loanProduct->interest_rate_min / 100;
                }
                $monthlyRate = $interestRate / 12;
                $estimatedPayment = $application->requested_amount * 
                    ($monthlyRate * pow(1 + $monthlyRate, $application->requested_tenure_months)) / 
                    (pow(1 + $monthlyRate, $application->requested_tenure_months) - 1);
            @endphp
            <div class="highlight-box">
                <strong>Estimated Monthly Payment:</strong> TSh {{ number_format($estimatedPayment) }}<br>
                <strong>Total Amount Payable:</strong> TSh {{ number_format($estimatedPayment * $application->requested_tenure_months) }}<br>
                <strong>Interest Rate Used:</strong> {{ number_format($interestRate * 100, 1) }}%
            </div>
        @endif
    </div>

    <!-- Personal Information -->
    <div class="section">
        <h2>Personal Information</h2>
        <div class="info-grid">
            <div>
                <div class="info-item">
                    <div class="info-label">Full Name</div>
                    <div class="info-value">
                        {{ $application->first_name }} 
                        {{ $application->middle_name ? $application->middle_name . ' ' : '' }}
                        {{ $application->last_name }}
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Date of Birth</div>
                    <div class="info-value">
                        {{ $application->date_of_birth ? $application->date_of_birth->format('F d, Y') : 'Not provided' }}
                        @if($application->date_of_birth)
                            ({{ $application->date_of_birth->age }} years old)
                        @endif
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Gender</div>
                    <div class="info-value">{{ $application->gender ? ucfirst($application->gender) : 'Not specified' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Marital Status</div>
                    <div class="info-value">{{ $application->marital_status ? ucfirst($application->marital_status) : 'Not specified' }}</div>
                </div>
            </div>
            <div>
                <div class="info-item">
                    <div class="info-label">National ID</div>
                    <div class="info-value">{{ $application->national_id ?: 'Not provided' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Phone Number</div>
                    <div class="info-value">{{ $application->phone_number ?: 'Not provided' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Email Address</div>
                    <div class="info-value">{{ $application->email ?: 'Not provided' }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Address Information -->
    <div class="section">
        <h2>Address Information</h2>
        <div class="info-grid">
            <div>
                <div class="info-item">
                    <div class="info-label">Current Address</div>
                    <div class="info-value">
                        {{ $application->current_address ?: 'Not provided' }}<br>
                        {{ $application->current_city }}, {{ $application->current_region }}
                        @if($application->current_postal_code)
                            <br>{{ $application->current_postal_code }}
                        @endif
                    </div>
                </div>
                @if($application->years_at_current_address)
                    <div class="info-item">
                        <div class="info-label">Years at Current Address</div>
                        <div class="info-value">{{ $application->years_at_current_address }} years</div>
                    </div>
                @endif
            </div>
            <div>
                <div class="info-item">
                    <div class="info-label">Permanent Address</div>
                    <div class="info-value">
                        @if($application->is_permanent_same_as_current)
                            Same as current address
                        @else
                            {{ $application->permanent_address ?: 'Not provided' }}
                            @if($application->permanent_city || $application->permanent_region)
                                <br>{{ $application->permanent_city }}, {{ $application->permanent_region }}
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Page Break for Employment Information -->
    <div class="page-break"></div>

    <!-- Employment & Financial Information -->
    <div class="section">
        <h2>Employment & Financial Information</h2>
        <div class="info-grid">
            <div>
                <div class="info-item">
                    <div class="info-label">Employment Status</div>
                    <div class="info-value">{{ $application->employment_status ? ucwords(str_replace('_', ' ', $application->employment_status)) : 'Not specified' }}</div>
                </div>
                @if($application->employment_status === 'employed')
                    <div class="info-item">
                        <div class="info-label">Employer</div>
                        <div class="info-value">{{ $application->employer_name ?: 'Not provided' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Job Title</div>
                        <div class="info-value">{{ $application->job_title ?: 'Not provided' }}</div>
                    </div>
                    @if($application->employment_sector)
                        <div class="info-item">
                            <div class="info-label">Employment Sector</div>
                            <div class="info-value">{{ ucwords(str_replace('_', ' ', $application->employment_sector)) }}</div>
                        </div>
                    @endif
                    @if($application->months_with_current_employer)
                        <div class="info-item">
                            <div class="info-label">Time with Current Employer</div>
                            <div class="info-value">{{ $application->months_with_current_employer }} months</div>
                        </div>
                    @endif
                @endif
                @if($application->employment_status === 'self_employed')
                    <div class="info-item">
                        <div class="info-label">Business Name</div>
                        <div class="info-value">{{ $application->business_name ?: 'Not provided' }}</div>
                    </div>
                    @if($application->business_type)
                        <div class="info-item">
                            <div class="info-label">Business Type</div>
                            <div class="info-value">{{ ucwords(str_replace('_', ' ', $application->business_type)) }}</div>
                        </div>
                    @endif
                    @if($application->business_registration_number)
                        <div class="info-item">
                            <div class="info-label">Registration Number</div>
                            <div class="info-value">{{ $application->business_registration_number }}</div>
                        </div>
                    @endif
                    @if($application->years_in_business)
                        <div class="info-item">
                            <div class="info-label">Years in Business</div>
                            <div class="info-value">{{ $application->years_in_business }} years</div>
                        </div>
                    @endif
                @endif
            </div>
            <div>
                <div class="info-item">
                    <div class="info-label">Monthly Salary</div>
                    <div class="info-value">TSh {{ number_format($application->monthly_salary ?: 0) }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Business Income</div>
                    <div class="info-value">TSh {{ number_format($application->monthly_business_income ?: 0) }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Other Income</div>
                    <div class="info-value">TSh {{ number_format($application->other_income ?: 0) }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Total Monthly Income</div>
                    <div class="info-value">TSh {{ number_format(($application->monthly_salary ?: 0) + ($application->monthly_business_income ?: 0) + ($application->other_income ?: 0)) }}</div>
                </div>

                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            This document is generated for informational purposes only and does not constitute a legally binding agreement.
        </div>
    </body>
    </html>