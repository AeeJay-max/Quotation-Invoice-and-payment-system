<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html">
    <meta name="_token" content="{{csrf_token()}}">

    <!-- Tell the browser to be responsive to screen width -->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <style type="text/css">
        #page-wrap {
            width: 700px;
            margin: 0 auto;
            padding-top: 50px;
        }
        table {
            border-spacing: 0;
            border-collapse: collapse;
            font-size: 12px;
        }
        td,
        th {
            padding: 0;
        }
        @media print {
            * {
                color: #000 !important;
                text-shadow: none !important;
                background: transparent !important;
                box-shadow: none !important;
            }
            a,
            a:visited {
                text-decoration: underline;
            }
            a[href]:after {
                content: " (" attr(href) ")";
            }
            abbr[title]:after {
                content: " (" attr(title) ")";
            }
            a[href^="javascript:"]:after,
            a[href^="#"]:after {
                content: "";
            }
            pre,
            blockquote {
                border: 1px solid #999;

                page-break-inside: avoid;
            }
            thead {
                display: table-header-group;
            }
            tr,
            img {
                page-break-inside: avoid;
            }
            img {
                max-width: 100% !important;
            }
            p,
            h2,
            h3 {
                orphans: 3;
                widows: 3;
            }
            h2,
            h3 {
                page-break-after: avoid;
            }
            select {
                background: #fff !important;
            }
            .navbar {
                display: none;
            }
            .table td,
            .table th {
                background-color: #fff !important;
            }
            .btn > .caret,
            .dropup > .btn > .caret {
                border-top-color: #000 !important;
            }
            .label {
                border: 1px solid #000;
            }
            .table {
                border-collapse: collapse !important;
            }
            .table-bordered th,
            .table-bordered td {
                border: 1px solid #ddd !important;
            }
        }
        table {
            max-width: 100%;
            background-color: transparent;
            font-size: 12px;
        }
        th {
            text-align: left;
        }
        .table {
            width: 100%;
            margin-bottom: 10px;
        }
        .head {
            border-top: 0px solid #e2e7eb;
            border-bottom: 0px solid #e2e7eb;
        }
        .table > thead > tr > th,
        .table > tbody > tr > th,
        .table > tfoot > tr > th,
        .table > thead > tr > td,
        .table > tbody > tr > td,
        .table > tfoot > tr > td {
            padding: 5px;
            line-height: 1.428571429;
            vertical-align: top;
            border-top: 1px solid #e2e7eb;
        }
        /*ini edit default value : border top 1px to 0 px*/
        .table > thead > tr > th {
            font-size: 12px;
            font-weight: 500;
            vertical-align: bottom;
            color: #242a30;
        }

        .table > caption + thead > tr:first-child > th,
        .table > colgroup + thead > tr:first-child > th,
        .table > thead:first-child > tr:first-child > th,
        .table > caption + thead > tr:first-child > td,
        .table > colgroup + thead > tr:first-child > td,
        .table > thead:first-child > tr:first-child > td {
            border-top: 0;
        }
        .table > tbody + tbody {
            border-top: 2px solid #e2e7eb;
        }
        .table .table {
            background-color: #fff;
        }
        .table-condensed > thead > tr > th,
        .table-condensed > tbody > tr > th,
        .table-condensed > tfoot > tr > th,
        .table-condensed > thead > tr > td,
        .table-condensed > tbody > tr > td,
        .table-condensed > tfoot > tr > td {
            padding: 5px;
        }
        .table-bordered {
            border: 1px solid #e2e7eb;
        }
        .table-bordered > thead > tr > th,
        .table-bordered > tbody > tr > th,
        .table-bordered > tfoot > tr > th,
        .table-bordered > thead > tr > td,
        .table-bordered > tbody > tr > td,
        .table-bordered > tfoot > tr > td {
            border: 1px solid #e2e7eb;
        }
        .table-bordered > thead > tr > th,
        .table-bordered > thead > tr > td {
            border-bottom-width: 2px;
        }
        .table-striped > tbody > tr:nth-child(odd) > td,
        .table-striped > tbody > tr:nth-child(odd) > th {
            background-color: #f0f3f5;
        }
        .no-border{
            border: hidden !important;
        }

        .no-border th{
            border-top: none !important;
        }
        .no-border td{
            border-top: none !important;
        }
        .no-border tr{
            border: hidden !important;
        }
        .company-details{
            text-align: right;
        }
        .company-details span{
            font-size: 12px;
            display: flex; align-items: center; justify-content: space-between;
        }
        .company-details strong{
            white-space: nowrap;
        }
        #page-wrap-inner{

        }

    </style>
</head>
<body>
<div id="page-wrap">
    <div id="page-wrap-inner">
        {{-- ══ MINISTRY OFFICIAL HEADER ══ --}}
        <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:0;">
            <tr>
                <td width="18%" style="vertical-align:middle; padding-right:12px;">
                    <img src="{{ public_path($settings['logo'] ?? 'assets/files/ministry-logo.png') }}"
                         alt="Ministry Logo"
                         style="width:100px; height:auto; display:block;">
                </td>
                <td width="82%" style="vertical-align:middle;">
                    <div style="font-size:15px; font-weight:700; color:#1a5c1a; text-transform:uppercase; letter-spacing:0.5px; line-height:1.3;">
                        {{ $settings['app_name'] ?? 'Ministry of Sports, Recreation, Arts and Culture' }}
                    </div>
                    <div style="font-size:11px; color:#444; margin-top:4px; line-height:1.7;">
                        {{ $settings['app_address'] ?? 'Chinengundu Mashayamombe Building 95, Cnr N. Mandela & S. V. Muzenda Street, Harare' }}<br>
                        {{ $settings['app_postal_address'] ?? 'P.O. Box HR 480 Harare' }}<br>
                        <strong>Email:</strong> {{ $settings['app_email'] ?? 'minofsportandarts@gmail.com' }}
                        &nbsp;|&nbsp;
                        <strong>Tel:</strong> {{ $settings['app_phone'] ?? '+263242708345' }}
                    </div>
                </td>
            </tr>
        </table>
        <table width="100%" cellpadding="0" cellspacing="0" style="margin:0;">
            <tr>
                <td style="padding:0;">
                    <div style="height:3px; background:#1a5c1a; margin-bottom:3px;"></div>
                    <div style="height:1px; background:#ccc; margin-bottom:12px;"></div>
                </td>
            </tr>
        </table>

        <table width="100%">
            <tr>
                <td width="50%">
                    <h2>Invoice To:</h2>
                    <address>
                        <strong>{{ $invoice->client->name }}</strong><br>
                        {{ $invoice->client->company_name}}<br>
                        {{ $invoice->client->address}}<br>
                        {{ $invoice->client->phone }}<br>
                        {{ $invoice->client->email }}<br>
                    </address>
                </td>
                <td width="50%">
                    <h2>Invoice Info</h2>
                    <table class="table table-striped table-bordered" width="100%">
                        <tbody>
                        <tr>
                            <th><b>Invoice No</b></th>
                            <td>#{{ $invoice->id }}</td>
                        </tr>
                        <tr>
                            <th><b>Payment Type:</b></th>
                            <td>{{ $invoice->paymentType->name }}</td>
                        </tr>
                        <tr>
                            <th><b>Payment Currency:</b></th>
                            <td>{{ $invoice->paymentCurrency->name }}</td>
                        </tr>
                        <tr>
                            <th><b>Created Date:</b></th>
                            <td>#{{ Carbon\Carbon::parse($invoice->create_date)->format('jS F Y ') }}</td>
                        </tr>
                        <tr>
                            <th><b>Due Date:</b></th>
                            <td>#{{ Carbon\Carbon::parse($invoice->due_date)->format('jS F Y ') }}</td>
                        </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
        <br/><br/>

        <table class="table table-striped table-bordered">
            <thead>
            <tr>
                <th width="60%" data-title="Description">Description</th>
                <th width="15%" data-title="unit Price">Unit Price</th>
                <th width="10%" data-title="Qty.">Qty.</th>
                <th width="15%" data-title="Subtotal">Subtotal</th>
            </tr>
            </thead>
            <tbody>
            @foreach($invoice->items as $item)
                <tr>
                    <td data-title="Description" class="table-name">{{$item->description}}</td>
                    <td data-title="Unit Price" class="table-price">{{ number_format($item->unit_price, 2)}}</td>
                    <td data-title="Quantity" class="table-qty">{{$item->quantity}}</td>
                    <td data-title="Subtotal"
                        class="table-total text-right">{{ number_format($item->quantity * $item->unit_price, 2)}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        @php
            // Determine stamp from verified amounts passed from controller
            $pdfVerifiedPaid   = $verified_paid ?? 0;
            $pdfOutstanding    = $outstanding_balance ?? floatval($invoice->total ?? 0);
            $pdfGrandTotal     = ($invoice->vat / 100 + 1) * ($total_price - $invoice->discount);
            $isCancelled       = $invoice->payment_status == 3;

            if ($isCancelled) {
                $pdfStamp = 'cancelled';
            } elseif ($pdfVerifiedPaid <= 0) {
                $pdfStamp = 'unpaid';
            } elseif ($pdfOutstanding > 0) {
                $pdfStamp = 'partially_paid';
            } else {
                $pdfStamp = 'paid';
            }
        @endphp
        <table width="100%">
            <tbody>
            <tr>
                 <td width="40%" style="vertical-align:middle;">
                    @if($pdfStamp === 'paid')
                        <div style="display:inline-block; border:4px solid #28a745; border-radius:6px; padding:6px 16px; margin:16px 0;">
                            <span style="color:#28a745; font-size:20px; font-weight:900; letter-spacing:2px; text-transform:uppercase;">✔ PAID</span>
                        </div>
                    @elseif($pdfStamp === 'partially_paid')
                        <div style="display:inline-block; border:4px solid #fd7e14; border-radius:6px; padding:6px 14px; margin:16px 0;">
                            <span style="color:#fd7e14; font-size:15px; font-weight:900; letter-spacing:1px; text-transform:uppercase;">PARTIALLY PAID</span>
                        </div>
                    @elseif($pdfStamp === 'cancelled')
                        <img style="margin:16px 0;" src="{{ public_path('assets/invoice/img/canceled.png') }}" alt="cancelled" width="180" height="70">
                    @else
                        <img style="margin:16px 0;" src="{{ public_path('assets/invoice/img/unpaid.png') }}" alt="unpaid" width="180" height="70">
                    @endif
                </td>
                <td width="40%" style="float: right;">
                    <table class="table table-striped no-border">
                        <tbody>
                        <tr>
                            <th class="table-label">Sub Total</th>
                            <td width="30%" class="table-amount text-right">{{ number_format($total_price, 2)}}</td>
                        </tr>
                        <tr>
                            <th class="table-label">Vat %</th>
                            <td width="30%" class="table-amount text-right">{{ number_format($invoice->vat, 2)}}</td>
                        </tr>
                        <tr>
                            <th class="table-label">Discount</th>
                            <td width="30%" class="table-amount text-right">{{ number_format($invoice->discount, 2)}}</td>
                        </tr>
                        <tr style="font-weight:bold; background:#eee;">
                            <th class="table-label">Grand Total</th>
                            <td width="30%" class="table-amount text-right">{{ number_format($pdfGrandTotal, 2) }}</td>
                        </tr>
                        @if($pdfVerifiedPaid > 0)
                        <tr style="color:#28a745; font-weight:bold;">
                            <th class="table-label">Total Verified Paid</th>
                            <td width="30%" class="table-amount text-right">{{ number_format($pdfVerifiedPaid, 2) }}</td>
                        </tr>
                        <tr style="font-weight:bold; color:{{ $pdfOutstanding > 0 ? '#c0392b' : '#27ae60' }};">
                            <th class="table-label">Outstanding Balance</th>
                            <td width="30%" class="table-amount text-right">{{ number_format($pdfOutstanding, 2) }}</td>
                        </tr>
                        @endif
                        <tr>
                            <th class="table-label">Status</th>
                            <td width="30%" class="table-amount text-right" style="font-weight:bold; text-transform:uppercase;">
                                @if($pdfStamp==='paid') PAID
                                @elseif($pdfStamp==='partially_paid') PARTIALLY PAID
                                @elseif($pdfStamp==='cancelled') CANCELLED
                                @else UNPAID
                                @endif
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            </tbody>
        </table>
        <table>
            <tr>
                <td>
                    @if($invoice->note)
                        <b>Note:</b>
                        <p class="text-muted well well-sm no-shadow">
                            {{ $invoice->note }}
                        </p>
                    @endif
                </td>
            </tr>
            <tr>
                <td width="100%">
                    @if($invoice->terms_condition)
                        <b>Terms &amp; Conditions:</b>
                        <p class="text-muted well well-sm no-shadow" style="">
                            {{ $invoice->terms_condition }}
                        </p>
                    @endif
                </td>
            </tr>
        </table>
        <br>

        {{-- ══ OFFICIAL BANKING DETAILS ══ --}}
        <table width="100%" style="border:1px solid #ddd; border-radius:4px; background:#f9f9f9;">
            <tr>
                <td colspan="2" style="padding:6px 10px; background:#1a5c1a;">
                    <span style="color:#fff; font-weight:700; font-size:13px; text-transform:uppercase; letter-spacing:0.5px;">
                        Payment Instructions — Official Banking Details
                    </span>
                </td>
            </tr>
            <tr>
                <td width="50%" style="padding:8px 12px; vertical-align:top;">
                    <table width="100%">
                        <tr><th style="padding:4px 0; color:#555; white-space:nowrap;">Account Name:</th>
                            <td style="padding:4px 6px; font-weight:600;">Sports and Recreation</td></tr>
                        <tr><th style="padding:4px 0; color:#555; white-space:nowrap;">Bank:</th>
                            <td style="padding:4px 6px; font-weight:600;">EmpowerBank</td></tr>
                        <tr><th style="padding:4px 0; color:#555; white-space:nowrap;">Account Number:</th>
                            <td style="padding:4px 6px; font-weight:700; color:#1a5c1a;">953869211833</td></tr>
                        <tr><th style="padding:4px 0; color:#555; white-space:nowrap;">Account Type:</th>
                            <td style="padding:4px 6px;">Corporate Nostro FCA (Domestic) USD</td></tr>
                        <tr><th style="padding:4px 0; color:#555; white-space:nowrap;">Currency:</th>
                            <td style="padding:4px 6px; font-weight:600;">USD</td></tr>
                    </table>
                </td>
                <td width="50%" style="padding:8px 12px; vertical-align:top; border-left:1px solid #ddd;">
                    <p style="font-size:11px; color:#555; margin:0 0 6px 0;">
                        <strong>Payment Reference:</strong> Please use your Invoice No. <strong>#{{ $invoice->id }}</strong> as the payment reference.
                    </p>
                    <p style="font-size:11px; color:#555; margin:0;">
                        After making payment, submit your proof of payment through the Exhibitor Portal or email to
                        <strong>{{ $settings['app_email'] ?? 'minofsportandarts@gmail.com' }}</strong>.
                        Payments are only confirmed once verified by the Ministry Finance team.
                    </p>
                </td>
            </tr>
        </table>

        <br>
        <table width="100%">
            <tbody>
            <tr>
                <td width="30%">
                    <hr>
                    Client Signature
                </td>
                <td width="40%">
                </td>
                <td width="30%">
                    <hr>
                    Authorised Signature
                </td>
            </tr>
            </tbody>
        </table>
        <p style="text-align: center;font-style: italic"><small>{{ $settings['app_moto'] ?? '' }}</small></p>
    </div>
</div>
</body>
</html>
</html>
