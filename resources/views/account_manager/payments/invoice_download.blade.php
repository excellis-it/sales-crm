
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Invoice - Excellis IT</title>
    <style>
        @page {
            margin: 0px;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif !important;
            font-size: 11px;
            color: #444;
            margin: 0;
            padding: 0;
            background-color: #fff;
            line-height: 1.4;
        }

        .invoice-wrapper {
            position: relative;
            min-height: 29.7cm;
            background-color: #fff;
        }

        .top-brand-bar {
            height: 12px;
            background-color: #ff9b44;
            width: 100%;
        }

        .content-main {
            padding: 40px;
        }

        .layout-table {
            width: 100%;
            border-collapse: collapse;
            border: none;
        }

        .header-logo {
            max-width: 150px;
        }

        .invoice-title-text {
            font-size: 32px;
            font-weight: 900;
            color: #2c3e50;
            margin: 0;
            text-align: right;
            text-transform: uppercase;
        }

        .invoice-no-badge {
            color: #ff9b44;
            font-size: 14px;
            font-weight: 700;
            text-align: right;
        }

        .address-box {
            padding-top: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }

        .section-label {
            font-size: 10px;
            font-weight: 800;
            color: #95a5a6;
            text-transform: uppercase;
            margin-bottom: 5px;
            display: block;
        }

        .bold-heading {
            font-size: 13px;
            font-weight: 800;
            color: #2c3e50;
            margin: 0 0 5px 0;
        }

        .address-box p {
            margin: 2px 0;
            color: #555;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        .items-table th {
            background-color: #f9f9f9;
            color: #2c3e50;
            font-weight: 800;
            text-transform: uppercase;
            padding: 12px 10px;
            text-align: left;
            font-size: 10px;
            border-bottom: 2px solid #ff9b44;
        }

        .items-table td {
            padding: 15px 10px;
            border-bottom: 1px solid #f1f1f1;
        }

        .amount-cell {
            text-align: right;
            font-weight: 700;
            color: #2c3e50;
        }

        .footer-layout-table {
            width: 100%;
            margin-top: 30px;
        }

        .footer-left {
            width: 60%;
            vertical-align: top;
            padding-right: 20px;
        }

        .footer-right {
            width: 40%;
            vertical-align: top;
        }

        .bank-info-card {
            background-color: #fffaf5;
            padding: 15px;
            border-radius: 4px;
            margin-top: 10px;
        }

        .bank-info-card strong {
            color: #ff9b44;
            font-size: 11px;
            text-transform: uppercase;
        }

        .totals-block {
            width: 100%;
        }

        .totals-row td {
            padding: 5px 0;
        }

        .totals-label {
            text-align: right;
            color: #7f8c8d;
            font-weight: 600;
            padding-right: 15px;
        }

        .totals-value {
            text-align: right;
            font-weight: 700;
            color: #2c3e50;
            width: 100px;
        }

        .grand-total-box {
            background-color: #fdfdfd;
            border-top: 2px solid #ff9b44;
            margin-top: 10px;
            padding-top: 10px;
        }

        .grand-total-label {
            font-size: 13px;
            font-weight: 800;
            color: #2c3e50;
            text-align: right;
            padding-right: 15px;
        }

        .grand-total-value {
            font-size: 18px;
            font-weight: 900;
            color: #ff9b44;
            text-align: right;
        }

        .signature-wrap {
            text-align: center;
            margin-top: 40px;
        }

        .signature-image {
            max-width: 180px;
            max-height: 60px;
            margin-bottom: 5px;
        }

        .signature-line-fixed {
            width: 100%;
            height: 1px;
            background-color: #333;
            margin-bottom: 5px;
        }

        .page-footer {
            position: absolute;
            bottom: 40px;
            left: 40px;
            right: 40px;
            text-align: center;
            border-top: 1px solid #eee;
            padding-top: 15px;
            color: #bdc3c7;
            font-size: 10px;
        }
    </style>
</head>

<body>
    <div class="invoice-wrapper">
        <div class="top-brand-bar"></div>

        <div class="content-main">
            @php
                $currency = '$';
                if ($milestone_detail->project_id) {
                    $currency = $milestone_detail->project->currency ?? '$';
                } elseif ($milestone_detail->bdm_project_id) {
                    $currency = $milestone_detail->bdmProject->currency ?? '$';
                } elseif ($milestone_detail->tender_project_id) {
                    $currency = 'INR';
                }
            @endphp

            @if($milestone_detail)
            <table class="layout-table">
                <tr>
                    <td style="width: 50%;">
                        <img class="header-logo" src="https://webexstudio.com/crm/admin_assets/img/logopns.png" alt="Excellis IT">
                    </td>
                    <td style="width: 50%;" class="text-right">
                        <h1 class="invoice-title-text" style="text-align: right;">INVOICE</h1>
                        <div class="invoice-no-badge" style="text-align: right;">#EXC-{{ date('y') }}-{{ str_pad($milestone_detail->id, 4, '0', STR_PAD_LEFT) }}</div>
                    </td>
                </tr>
            </table>

            <div class="address-box">
                <table class="layout-table">
                    <tr>
                        <td style="width: 35%;">
                            <span class="section-label">Sender</span>
                            <p class="bold-heading">Excellis IT Private Ltd.</p>
                            <p>Merlin Infinite, 9th Floor, Unit 907</p>
                            <p>Sector V, Bidhannagar, Kolkata</p>
                            <p>WB 700091, India</p>
                            <p>GSTIN: 19AAFCE5666G1ZC</p>
                        </td>
                        <td style="width: 35%;">
                            <span class="section-label">Recipient</span>
                            <p class="bold-heading">
                                {{ $milestone_detail->project->business_name ?? ($milestone_detail->bdmProject->business_name ?? ($milestone_detail->tenderProject->tender_name ?? 'Client Name')) }}
                            </p>
                            <p>{{ $milestone_detail->project->client_email ?? ($milestone_detail->bdmProject->client_email ?? ($milestone_detail->tenderProject->email ?? '')) }}</p>
                            <p>{{ $milestone_detail->project->client_phone ?? ($milestone_detail->bdmProject->client_phone ?? ($milestone_detail->tenderProject->phone ?? '')) }}</p>
                            @php
                                $client_address = $milestone_detail->project->address ?? ($milestone_detail->bdmProject->address ?? ($milestone_detail->tenderProject->address ?? ''));
                            @endphp
                            @if($client_address)
                                <p>{{ $client_address }}</p>
                            @endif
                        </td>
                        <td style="width: 30%;">
                            <span class="section-label">Metadata</span>
                            <p><strong>Issued:</strong> {{ date('d M, Y') }}</p>
                            <p><strong>Due by:</strong> {{ date('d M, Y', strtotime($milestone_detail->payment_date)) }}</p>
                            <p><strong>Status:</strong> <span style="color: #27ae60; font-weight: 700;">Paid</span></p>
                        </td>
                    </tr>
                </table>
            </div>

            <table class="items-table">
                <thead>
                    <tr>
                        <th width="5%" style="text-align: center;">#</th>
                        <th width="45%">Description & Scope</th>
                        <th width="20%">Milestone</th>
                        <th width="15%">Method</th>
                        <th width="15%" style="text-align: right;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="text-align: center; color: #95a5a6;">01</td>
                        <td>
                            <div class="bold-heading" style="margin-bottom: 3px;">{{ $milestone_detail->project->business_name ?? ($milestone_detail->tenderProject->tender_name ?? $milestone_detail->bdmProject->business_name ?? 'Project Name') }}</div>
                            <span style="font-size: 9px; color: #999;">Reference ID: #MT-{{ $milestone_detail->id }}</span>
                        </td>
                        <td>{{ $milestone_detail->milestone_name }}</td>
                        <td>{{ $milestone_detail->payment_mode }}</td>
                        <td class="amount-cell" style="text-align: right;">
                            {{ $currency }} {{ number_format($milestone_detail->milestone_value, 2) }}
                        </td>
                    </tr>
                </tbody>
            </table>

            <table class="footer-layout-table">
                <tr>
                    <td class="footer-left">
                        <span class="section-label">Payment Information</span>
                        <div class="bank-info-card">
                            <strong>Bank Transfer Details</strong>
                            <div style="margin-top: 5px; color: #555;">
                                <p style="margin: 2px 0;"><strong>A/C Name:</strong> Excellis IT Pvt Ltd</p>
                                <p style="margin: 2px 0;"><strong>Bank:</strong> ICICI BANK (Acc: 193405000720)</p>
                                <p style="margin: 2px 0;"><strong>IFSC:</strong> ICIC0001934 | <strong>Swift:</strong> ICICINBBCTS</p>
                                <p style="margin: 2px 0;"><strong>Branch:</strong> New Town, Kolkata, WB 700156</p>
                            </div>
                        </div>
                    </td>
                    <td class="footer-right">
                        <table class="totals-block">
                            <tr class="totals-row">
                                <td class="totals-label" style="text-align: right;">Sub Total</td>
                                <td class="totals-value" style="text-align: right;">{{ $currency }} {{ number_format($milestone_detail->milestone_value, 2) }}</td>
                            </tr>
                            <tr class="totals-row">
                                <td class="totals-label" style="text-align: right;">Tax (0.00%)</td>
                                <td class="totals-value" style="text-align: right;">{{ $currency }} 0.00</td>
                            </tr>
                            <tr>
                                <td colspan="2" class="grand-total-box">
                                    <table style="width: 100%;">
                                        <tr>
                                            <td class="grand-total-label" style="text-align: right;">Grand Total</td>
                                            <td class="grand-total-value" style="text-align: right;">{{ $currency }} {{ number_format($milestone_detail->milestone_value, 2) }}</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>

                        <div class="signature-wrap">
                            <img class="signature-image" src="https://webexstudio.com/crm/admin_assets/img/sign.png" alt="Signature">
                            <div class="signature-line-fixed"></div>
                            <div style="font-weight: 800; color: #2c3e50;">Zulfiquar Ali</div>
                            <div style="font-size: 9px; color: #bdc3c7; text-transform: uppercase;">Authorized Signatory</div>
                        </div>
                    </td>
                </tr>
            </table>
            @else
            <div style="padding: 100px; text-align: center;">
                <h3>No Invoice Data Available</h3>
            </div>
            @endif

            <div class="page-footer">
                <div style="font-weight: 700; color: #7f8c8d;">www.excellisit.com</div>
                <div style="margin-top: 3px;">Thank you for your choice. For support, email info@excellisit.com</div>
            </div>
        </div>
    </div>
</body>
</html>