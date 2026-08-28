@extends('layout')

@section('title', 'View Invoice')
@section('invoice-show', 'menu-open')

@section('content')
    <style type="text/css">
        #page-wrap {
            width: 700px;
            margin: 0 auto;
            padding-top: 50px;
        }

        #page-wrap-inner {
            border: 1px solid lightblue;
        }

        .center-justified {
            text-align: justify;
            margin: 0 auto;
            width: 30em;
        }

        /*ini starts here*/
        .list-group {
            padding-left: 0;
            margin-bottom: 15px;
            width: auto;
        }

        .list-group-item {
            position: relative;
            display: block;
            padding: 7.5px 10px;
            margin-bottom: -1px;
            background-color: #fff;
            border: 1px solid #ddd;
            /*margin: 2px;*/
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
                content: " ("attr(href) ")";
            }

            abbr[title]:after {
                content: " ("attr(title) ")";
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

            .btn>.caret,
            .dropup>.btn>.caret {
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

        .table>thead>tr>th,
        .table>tbody>tr>th,
        .table>tfoot>tr>th,
        .table>thead>tr>td,
        .table>tbody>tr>td,
        .table>tfoot>tr>td {
            padding: 5px;
            line-height: 1.428571429;
            vertical-align: top;
            border-top: 1px solid #e2e7eb;
        }

        /*ini edit default value : border top 1px to 0 px*/
        .table>thead>tr>th {
            font-size: 12px;
            font-weight: 500;
            vertical-align: bottom;
            color: #242a30;
        }

        .table>caption+thead>tr:first-child>th,
        .table>colgroup+thead>tr:first-child>th,
        .table>thead:first-child>tr:first-child>th,
        .table>caption+thead>tr:first-child>td,
        .table>colgroup+thead>tr:first-child>td,
        .table>thead:first-child>tr:first-child>td {
            border-top: 0;
        }

        .table>tbody+tbody {
            border-top: 2px solid #e2e7eb;
        }

        .table .table {
            background-color: #fff;
        }

        .table-condensed>thead>tr>th,
        .table-condensed>tbody>tr>th,
        .table-condensed>tfoot>tr>th,
        .table-condensed>thead>tr>td,
        .table-condensed>tbody>tr>td,
        .table-condensed>tfoot>tr>td {
            padding: 5px;
        }

        .table-bordered {
            border: 1px solid #e2e7eb;
        }

        .table-bordered>thead>tr>th,
        .table-bordered>tbody>tr>th,
        .table-bordered>tfoot>tr>th,
        .table-bordered>thead>tr>td,
        .table-bordered>tbody>tr>td,
        .table-bordered>tfoot>tr>td {
            border: 1px solid #e2e7eb;
        }

        .table-bordered>thead>tr>th,
        .table-bordered>thead>tr>td {
            border-bottom-width: 2px;
        }

        .table-striped>tbody>tr:nth-child(odd)>td,
        .table-striped>tbody>tr:nth-child(odd)>th {
            background-color: #f0f3f5;
        }

        .panel-title {
            margin-top: 0;
            margin-bottom: 0;
            font-size: 16px;
            color: #fff;
            padding: 0;
        }

        .panel-title>a {
            color: #707478;
            text-decoration: none;
        }

        a {
            background: transparent;
            color: #707478;
            text-decoration: none;
        }

        strong {
            color: #707478;
        }

        .total {
            float: left;
            color: #232A3F;
            margin-left: 80px;
            font-weight: 200;
        }

        .lead {
            font-size: 16px;
        }

        address {
            font-size: 14px;
        }

        .no-border {
            border-top: hidden !important;
        }

        .no-border th {
            border-top: hidden !important;
        }

        .no-border td {
            border-top: hidden !important;
        }

        .no-border tr {
            border-top: hidden !important;
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
    <div class="content-wrapper">
        <div class="container">
            <div class="row">
                <div class="col col-xs-5 text-center">
                    <h1 class="panel-title">Invoice View</h1>
                </div>
                <div class="col-lg-12">

                </div>
            </div>

            <div id="page-wrap">
                <div class="row">
                    <div class="col-lg-12">
                        @if (session('error'))
                            <div id="error_m" class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        @if (session('success'))
                            <div id="success_m" class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="container">
                    <div class="row">
                        <div class="box-tools py-1">
                            <a href="/invoice/edit/{{ $invoice->id }}">
                                <button type="button" class="btn btn-flat margin-bottom pull-right bg-blue"
                                    data-toggle="tooltip" title="{{ trans('Invoice::invoice.optional_title.edit') }}">
                                    <i class="fa fa-pencil"></i> Edit
                                </button>
                            </a>
                            <button type="button" class="btn btn-flat margin-bottom bg-olive pull-right" data-toggle="modal"
                                data-target="#sendEmail" title="Send Email">
                                <i class="fa fa-envelope"></i> Send Email
                            </button>

                            <a href="/invoice/print/{{ $invoice->id }}" target="_blank"
                                class="btn btn-flat margin-bottom bg-maroon pull-right" title="Print Preview">
                                <i class="fa fa-envelope"></i> Print</a>

                            <button type="button" class="btn btn-flat margin-bottom btn-success pull-right"
                                data-toggle="modal" data-target="#myModal" title="Payment">
                                <i class="fa fa-credit-card"></i> Submit Payment
                            </button>

                        </div>
                    </div>
                </div>
                <div id="page-wrap-inner">
                    <table width="100%">
                        <tr>
                            <td width="40%">
                                <img style="width: 150px;height: auto" src="{{ asset($settings['logo'] ?? '') }}" class="">
                            </td>
                            <td width="60%" class="company-details">
                                <span style="font-size: 20px;"><strong>{{ $settings['app_name'] ?? '' }}</strong></span>
                                <span><strong>Address:  </strong>{{ $settings['app_address'] ?? '' }}</span>
                                <span>
                                    <strong>Email(s):  </strong>
                                    {!! implode('<br>', explode(',', $settings['app_email'])) !!}
                                </span>
                               <span> <strong>Phone Number(s):  </strong>{!! implode('<br>', explode(',', $settings['app_phone'])) !!}</span>
                            </td>
                        </tr>
                    </table>


                    <hr>
                    <table width="100%">
                        <tr>
                            <td width="50%">
                                <h2>Invoice To:</h2>
                                <address>
                                    <strong>{{ $invoice->client->name }}</strong><br>
                                    {{ $invoice->client->company_name }}<br>
                                    {{ $invoice->client->address }}<br>
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
                                            <td>#{{ Carbon\Carbon::parse($invoice->create_date)->format('jS F Y ') }}
                                            </td>
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
                    <br /><br />

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
                            @foreach ($invoice->items as $item)
                                <tr>
                                    <td data-title="Description" class="table-name">{{ $item->description }}</td>
                                    <td data-title="Unit Price" class="table-price">
                                        {{ number_format($item->unit_price, 2) }}</td>
                                    <td data-title="Quantity" class="table-qty">{{ $item->quantity }}</td>
                                    <td data-title="Subtotal" class="table-total text-right">
                                        {{ number_format($item->quantity * $item->unit_price, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <table width="100%">
                        <tbody>
                            <tr>
                                <td width="40%">
                                    @php
                                        // Determine stamp from VERIFIED payments (authoritative)
                                        $stampVerifiedPaid = $verified_paid ?? 0;
                                        $stampOutstanding  = $outstanding_balance ?? floatval($invoice->total ?? 0);
                                        $invoiceGrandTotal = ($invoice->vat / 100 + 1) * ($total_price - $invoice->discount);

                                        // Explicit cancellation overrides all
                                        $isCancelled = $invoice->payment_status == 3;

                                        if ($isCancelled) {
                                            $stampKey = 'cancelled';
                                        } elseif ($stampVerifiedPaid <= 0) {
                                            $stampKey = 'unpaid';
                                        } elseif ($stampOutstanding > 0) {
                                            $stampKey = 'partially_paid';
                                        } else {
                                            $stampKey = 'paid';
                                        }
                                    @endphp

                                    @if($stampKey === 'paid')
                                        <div style="display:inline-block; border:4px solid #28a745; border-radius:8px; padding:6px 18px; margin:20px 0 20px 20px;">
                                            <span style="color:#28a745; font-size:22px; font-weight:900; letter-spacing:2px; text-transform:uppercase;">✔ PAID</span>
                                        </div>
                                    @elseif($stampKey === 'partially_paid')
                                        <div style="display:inline-block; border:4px solid #fd7e14; border-radius:8px; padding:6px 18px; margin:20px 0 20px 20px;">
                                            <span style="color:#fd7e14; font-size:16px; font-weight:900; letter-spacing:1px; text-transform:uppercase;">PARTIALLY PAID</span>
                                        </div>
                                    @elseif($stampKey === 'cancelled')
                                        <img style="margin:20px 0 20px 20px;"
                                            src="{{ asset('assets/invoice/img/canceled.png') }}" alt="cancelled"
                                            width="200" height="80">
                                    @else
                                        <img style="margin:20px 0 20px 20px;"
                                            src="{{ asset('assets/invoice/img/unpaid.png') }}" alt="unpaid"
                                            width="200" height="80">
                                    @endif
                                </td>
                                <td width="40%" style="float: right;">
                                    <table class="table table-bordered">
                                        <tfoot>
                                            <tr>
                                                <th class="table-label">Sub Total</th>
                                                <td width="30%" class="table-amount text-right">
                                                    {{ number_format($total_price, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <th class="table-label">Vat%</th>
                                                <td width="30%" class="table-amount text-right">
                                                    {{ number_format($invoice->vat, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <th class="table-label">Discount</th>
                                                <td class="table-amount text-right">
                                                    {{ number_format($invoice->discount, 2) }}</td>
                                            </tr>
                                            <tr class="table-dark">
                                                <th class="table-label font-weight-bold">Grand Total</th>
                                                <td class="table-amount text-right font-weight-bold">{{ number_format($invoiceGrandTotal, 2) }}
                                                </td>
                                            </tr>
                                            @if($stampVerifiedPaid > 0)
                                            <tr class="table-success">
                                                <th class="table-label text-success">Total Verified Paid</th>
                                                <td class="table-amount text-right text-success font-weight-bold">
                                                    {{ number_format($stampVerifiedPaid, 2) }}
                                                </td>
                                            </tr>
                                            <tr class="{{ $stampOutstanding <= 0 ? 'table-success' : 'table-warning' }}">
                                                <th class="table-label {{ $stampOutstanding <= 0 ? 'text-success' : 'text-danger' }} font-weight-bold">Outstanding Balance</th>
                                                <td class="table-amount text-right font-weight-bold {{ $stampOutstanding <= 0 ? 'text-success' : 'text-danger' }}">
                                                    {{ number_format($stampOutstanding, 2) }}
                                                    @if($stampOutstanding <= 0)
                                                        <span class="badge badge-success ml-1">PAID IN FULL</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <th class="table-label">Payment Status</th>
                                                <td class="table-amount text-right">
                                                    @if($stampKey === 'paid')
                                                        <span class="badge badge-success px-2 py-1" style="font-size:0.85rem;">PAID</span>
                                                    @elseif($stampKey === 'partially_paid')
                                                        <span class="badge badge-warning px-2 py-1 text-dark" style="font-size:0.85rem;">PARTIALLY PAID</span>
                                                    @elseif($stampKey === 'cancelled')
                                                        <span class="badge badge-secondary px-2 py-1" style="font-size:0.85rem;">CANCELLED</span>
                                                    @else
                                                        <span class="badge badge-danger px-2 py-1" style="font-size:0.85rem;">UNPAID</span>
                                                    @endif
                                                </td>
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
                                @if ($invoice->note)
                                    <b>Note:</b>
                                    <p class="text-muted well well-sm no-shadow">
                                        {{ $invoice->note }}
                                    </p>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td width="100%">
                                @if ($invoice->terms_condition)
                                    <b>Terms & Conditions:</b>
                                    <p class="text-muted well well-sm no-shadow" style="">
                                        {{ $invoice->terms_condition }}
                                    </p>
                                @endif
                            </td>
                        </tr>
                    </table>
                    <br>
                    {{-- ══ OFFICIAL BANKING DETAILS ══ --}}
                    <div style="border:1px solid #ddd; border-radius:4px; overflow:hidden; margin-bottom:16px;">
                        <div style="background:#1a5c1a; padding:8px 14px;">
                            <strong style="color:#fff; font-size:13px; text-transform:uppercase; letter-spacing:0.5px;">
                                Payment Instructions — Official Banking Details
                            </strong>
                        </div>
                        <div style="display:flex; padding:12px;">
                            <div style="flex:1; padding-right:16px;">
                                <table class="table table-sm mb-0" style="font-size:13px;">
                                    <tr><th style="width:45%; padding:4px 0; color:#555;">Account Name</th>
                                        <td style="padding:4px 6px; font-weight:600;">Sports and Recreation</td></tr>
                                    <tr><th style="padding:4px 0; color:#555;">Bank</th>
                                        <td style="padding:4px 6px; font-weight:600;">EmpowerBank</td></tr>
                                    <tr><th style="padding:4px 0; color:#555;">Account Number</th>
                                        <td style="padding:4px 6px; font-weight:700; color:#1a5c1a;">953869211833</td></tr>
                                    <tr><th style="padding:4px 0; color:#555;">Account Type</th>
                                        <td style="padding:4px 6px;">Corporate Nostro FCA (Domestic) USD</td></tr>
                                    <tr><th style="padding:4px 0; color:#555;">Currency</th>
                                        <td style="padding:4px 6px; font-weight:600;">USD</td></tr>
                                </table>
                            </div>
                            <div style="flex:1; border-left:1px solid #eee; padding-left:16px; font-size:12px; color:#555;">
                                <p style="margin:0 0 6px 0;">
                                    <strong>Payment Reference:</strong> Use Invoice No.
                                    <strong>#{{ $invoice->id }}</strong> as your payment reference.
                                </p>
                                <p style="margin:0;">
                                    Submit proof of payment via the Exhibitor Portal or email
                                    <strong>{{ $settings['app_email'] ?? 'minofsportandarts@gmail.com' }}</strong>.
                                    Payments are confirmed only after Ministry Finance verification.
                                </p>
                            </div>
                        </div>
                    </div>

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
        </div>
    </div>

    <div class="modal fade" id="sendEmail" tabindex="-1" role="dialog" aria-labelledby="add client" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="/invoice/send" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">
                    <div class="modal-header">
                        <h5>Send Invoice</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="add-client-form">
                            <div class="form-group">
                                <label for="temp">Select Template</label>
                                <select id="temp" class="form-control" onchange="choose_template(event)">
                                    <option>Select Template</option>
                                    @foreach ($templates as $template)
                                        <option value="{{ $template->id }}">{{ $template->subject }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input disabled value="{{ $invoice->client?->email }}" type="email" class="form-control"
                                    id="email" name="email" placeholder="email address">
                            </div>
                            <div class="form-group">
                                <label for="subject">Subject</label>
                                <input @if ($invoice->email_subject)
                                value="{{ $invoice->email_subject }}"
                                @endif
                                type="text" class="form-control" id="subject" name="subject"
                                placeholder="email subject">
                            </div>
                            <div class="form-group">
                                <label for="bodyy">Body</label>
                                <textarea rows="5" class="form-control textarea" id="bodyy" name="body"
                                    placeholder="email body">
                                          @if ($invoice->email_body)
                                            {{ $invoice->email_body }}
                                        @endif
                                    </textarea>
                            </div>
                            <div class="form-check">
                                <input name="attach" @if ($invoice->attach)
                                checked
                            @elseif(!$invoice->attach)
                                checked="false"
                            @else
                                checked
                                @endif
                                type="checkbox" class="form-check-input"
                                id="exampleCheck1">
                                <label class="form-check-label" for="exampleCheck1">Add Attachment</label>
                            </div>
                            <div class="form-check">
                                <input @if ($invoice->is_scheduled)
                                checked
                                @endif
                                onchange="scheduleEmail(event)" name="schedule" type="checkbox" class="form-check-input"
                                id="schedule">
                                <label class="form-check-label" for="schedule">Schedule</label>
                            </div>
                            <div class="row @if (!$invoice->is_scheduled)
                               hidden
                                           @endif"
                                id="schedule_invoice">
                                <div class="form-group col-lg-6">
                                    <label for="schedule_date">Schedule Date</label>
                                    <div class="input-group date" data-target-input="nearest">
                                        <input id="schedule_date" value="
                                             @if ($invoice->is_scheduled)
                                        {{ $invoice->schedule_date }}
                                        @endif
                                        " name="schedule_date" type="text"
                                        class="form-control datetimepicker-input" data-target="#schedule_date"/>
                                        <div class="input-group-append" data-target="#schedule_date"
                                            data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="send-email-btn" disabled type="submit" class="btn btn-primary" style="display: flex; align-items: center">
                            <div class="spinner-border text-info mr-2 d-none" role="status">
                                <span class="sr-only">Loading...</span>
                              </div>
                            Send</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">

    </script>
    <script>
        $(function() {
            // Summernote
            $('.textarea').summernote();
            //Date range picker
            $('#schedule_date').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                autoUpdateInput: true,
                startDate: moment().subtract(6, 'days')
            });
        })

        function scheduleEmail(e) {
            if (e.target.checked) {
                $('#schedule_invoice').removeClass('hidden');
            }

            if (!e.target.checked) {
                $('#schedule_invoice').addClass('hidden');
            }
        }

        function recurringEmail(e) {
            if (e.target.checked) {
                $('#recurring_invoice').removeClass('hidden');
            }

            if (!e.target.checked) {
                $('#recurring_invoice').addClass('hidden');
            }
        }

        function choose_template(e) {
            let btn = $('#send-email-btn');
           let spinner = btn.find('.spinner-border');
           spinner.removeClass('d-none');
            btn.attr('disabled', 'true');
            var template_id = e.target.value;
            var token = "<?php echo e(csrf_token()); ?>";
            var invoice_id = "<?php echo $invoice->id; ?>";
            var url = "/etemplate/template"
            $.ajax({
                    url: url,
                    type: 'post',
                    data: 'template_id=' + template_id + '&invoice_id=' + invoice_id + '&_token=' + token,
                    dataType: 'json'
                })
                .done(function(response) {
                    var subject = $('#subject');
                    var body = $('#bodyy');
                    subject.val(response.subject);
                    body.summernote('code', '<p></p>');
                    body.summernote('code', response.body);
                    btn.removeAttr('disabled');
                    spinner.addClass('d-none');
                })

        }
    </script>
@endpush
