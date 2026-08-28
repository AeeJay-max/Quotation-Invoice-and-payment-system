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

        .company-details {
            text-align: right;
        }

        .company-details span {
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .company-details strong {
            white-space: nowrap;
        }

        #page-wrap-inner {

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
        <div style="border-top:3px solid #1a5c1a; border-bottom:1px solid #ccc; margin:8px 0 12px 0;"></div>

        {{-- ══ DOCUMENT TITLE & STATUS ══ --}}
        @php
            $statusLabel = match(strtolower($quotation->status ?? 'pending')) {
                'approved' => 'APPROVED BY ADMIN',
                'rejected' => 'REJECTED',
                'accepted' => 'CONFIRMED BY EXHIBITOR',
                default    => 'PENDING ADMIN REVIEW',
            };
        @endphp
        <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:10px;">
            <tr>
                <td>
                    <div style="font-size:18px; font-weight:700; text-transform:uppercase; letter-spacing:1px; color:#1a5c1a;">EXHIBITION QUOTATION</div>
                    <div style="font-size:11px; color:#555; margin-top:3px;">
                        <strong>Quotation No:</strong> {{ $quotation->quotation_number ?? '#'.$quotation->id }}
                        &nbsp;&nbsp;|&nbsp;&nbsp;
                        <strong>Date:</strong> {{ $quotation->create_date ? \Carbon\Carbon::parse($quotation->create_date)->format('d F Y') : 'N/A' }}
                        &nbsp;&nbsp;|&nbsp;&nbsp;
                        <strong>Status:</strong> {{ $statusLabel }}
                    </div>
                </td>
            </tr>
        </table>
        <div style="border-top:1px solid #ccc; margin-bottom:10px;"></div>

        <table width="100%" cellpadding="4" cellspacing="0">
            <tr>
                <td width="50%" style="vertical-align:top;">
                    <div style="font-size:11px; font-weight:700; color:#1a5c1a; border-bottom:2px solid #1a5c1a; padding-bottom:3px; margin-bottom:5px; text-transform:uppercase;">Quotation To:</div>
                    <div style="font-size:11px; line-height:1.7;">
                        <strong>{{ $quotation->client->name ?? 'N/A' }}</strong><br>
                        {{ $quotation->client->company_name ?? '' }}<br>
                        {{ $quotation->client->address ?? '' }}<br>
                        @if($quotation->client->phone ?? false)<strong>Tel:</strong> {{ $quotation->client->phone }}<br>@endif
                        @if($quotation->client->email ?? false)<strong>Email:</strong> {{ $quotation->client->email }}<br>@endif
                    </div>
                </td>
                <td width="50%" style="vertical-align:top; padding-left:15px;">
                    <div style="font-size:11px; font-weight:700; color:#1a5c1a; border-bottom:2px solid #1a5c1a; padding-bottom:3px; margin-bottom:5px; text-transform:uppercase;">Quotation Details:</div>
                    <table class="table table-bordered" width="100%" style="font-size:11px;">
                        <tbody>
                            <tr>
                                <th style="background:#f5f5f5; width:45%;">Quotation No:</th>
                                <td><strong>{{ $quotation->quotation_number ?? '#'.$quotation->id }}</strong></td>
                            </tr>
                            <tr>
                                <th style="background:#f5f5f5;">Date Issued:</th>
                                <td>{{ $quotation->create_date ? \Carbon\Carbon::parse($quotation->create_date)->format('d M Y') : 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th style="background:#f5f5f5;">Payment Type:</th>
                                <td>{{ $quotation->paymentType->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th style="background:#f5f5f5;">Currency:</th>
                                <td>{{ $quotation->paymentCurrency->name ?? 'USD' }}</td>
                            </tr>
                            <tr>
                                <th style="background:#f5f5f5;">Status:</th>
                                <td><strong>{{ $statusLabel }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
        <br>



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
            @foreach($quotation->items as $item)
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
        <table width="100%">
            <tbody>
            <tr>
                <td width="40%">

                </td>
                <td width="40%" style="float: right;">
                    <table class="table table-bordered">
                        <tfoot>
                        <tr>
                            <th class="table-label">Sub Total</th>
                            <td width="30%" class="table-amount text-right">{{ number_format($total_price, 2)}}</td>
                        </tr>
                        <tr>
                            <th class="table-label">Vat %</th>
                            <td width="30%" class="table-amount text-right">{{ number_format($quotation->vat, 2)}}</td>
                        </tr>
                        <tr>
                            <th class="table-label">Discount</th>
                            <td class="table-amount text-right">{{ number_format($quotation->discount, 2)}}</td>
                        </tr>
                        {{--                        <tr>--}}
                        {{--                            <th class="table-label">Paid Amount</th>--}}
                        {{--                            <td class="table-amount text-right">{{ $quotation->currency->currency_symbol ? $quotation->currency->currency_symbol : isite()->siteCurrencySymbol() }}{{ number_format($quotation->payments->sum('paid_amount'),2)}}</td>--}}
                        {{--                        </tr>--}}
                        {{--                        <tr>--}}
                        {{--                            <th class="table-label">Due Amount</th>--}}
                        {{--                            <td class="table-amount text-right">{{ $quotation->currency->currency_symbol ? $quotation->currency->currency_symbol : isite()->siteCurrencySymbol() }}{{ number_format($quotation->grand_total - $quotation->payments->sum('paid_amount'), 2)}}</td>--}}
                        {{--                        </tr>--}}
                        <tr>
                            <th class="table-label">Grand Total</th>
                            <td class="table-amount text-right">{{ number_format($total_price, 2) }}</td>
                        </tr>
                        </tfoot>
                    </table>
                </td>
            </tr>
            </tbody>
        </table>
        <table>
            <tr>
                <td>
                    @if($quotation->note)
                        <b>Note:</b>
                        <p class="text-muted well well-sm no-shadow">
                            {{ $quotation->note }}
                        </p>
                    @endif
                </td>
            </tr>
            <tr>
                <td width="100%">
                    @if($quotation->terms_condition)
                        <b>Terms & Conditions:</b>
                        <p class="text-muted well well-sm no-shadow" style="">
                            {{ $quotation->terms_condition }}
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
                        <strong>Payment Reference:</strong> Please use your Quotation No.
                        <strong>{{ $quotation->quotation_number ?? '#'.$quotation->id }}</strong> as your payment reference.
                    </p>
                    <p style="font-size:11px; color:#555; margin:0;">
                        After making payment, submit your proof of payment through the Exhibitor Portal or email
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
