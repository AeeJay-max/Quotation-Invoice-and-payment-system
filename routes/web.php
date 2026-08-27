<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Route;

        $due = Carbon::createFromDate('2021', '09', '13');
        $left = Carbon::now()->diffInDays($due);
        if($due <= Carbon::now()){
            //dd('Your hosting has been suspended please contact your hosting provider');
        }

Route::group([], function () {

    // Public Root URL points directly to Event Exhibition Quotation Wizard
    Route::get('/', [\App\Http\Controllers\EventBookingWizardController::class, 'wizard'])->name('home');

    Route::group(['middleware' => 'guest'], function () {
        // Exhibitor / General Login
        Route::get('/login', [\App\Http\Controllers\AuthController::class, 'login'])->name('login');
        Route::post('/login', [\App\Http\Controllers\AuthController::class, 'signIn']);

        // Secret Admin Login URL (Manual Entry Required)
        Route::get('/admin/mosrac', [\App\Http\Controllers\AuthController::class, 'adminLogin'])->name('admin.login');
        Route::post('/admin/mosrac', [\App\Http\Controllers\AuthController::class, 'adminSignIn']);
    });
    Route::post('logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

    Route::group(['middleware' => ['auth', 'admin']], function () {
        Route::get('dashboard', [\App\Http\Controllers\DashboardController::class, 'index']);
        //invoice
        Route::group(['prefix' => 'invoice', 'as' => 'invoice.'], function () {
            Route::get('/', [\App\Http\Controllers\InvoiceController::class, 'index']);
            Route::get('search', [\App\Http\Controllers\InvoiceController::class, 'getBySearchTearm']);
            Route::get('view/{id}', [\App\Http\Controllers\InvoiceController::class, 'viewInvoice'])->name('view');
            Route::get('create', [\App\Http\Controllers\InvoiceController::class, 'create']);
            Route::get('print/{id}', [\App\Http\Controllers\InvoiceController::class, 'printInvoice']);
            Route::get('copy/{id}', [\App\Http\Controllers\InvoiceController::class, 'copy']);
            Route::post('save', [\App\Http\Controllers\InvoiceController::class, 'saveInvoice'])->name('save');
            Route::get('edit/{id}', [\App\Http\Controllers\InvoiceController::class, 'edit']);
            Route::post('update/{id}', [\App\Http\Controllers\InvoiceController::class, 'update'])->name('update');
            Route::post('/send', [\App\Http\Controllers\InvoiceController::class, 'send']);
            Route::delete('destroy/{id}', [\App\Http\Controllers\InvoiceController::class, 'destroy']);
            Route::get('/sent', [\App\Http\Controllers\InvoiceController::class, 'listSent']);
        });

        //quotation
        Route::group(['prefix' => 'quotation', 'as' => 'quotation.'], function () {
            Route::get('/', [\App\Http\Controllers\QuotationController::class, 'index']);
            Route::get('search', [\App\Http\Controllers\QuotationController::class, 'getBySearchTearm']);
            Route::get('view/{id}', [\App\Http\Controllers\QuotationController::class, 'viewQuotation'])->name('view');
            Route::get('create', [\App\Http\Controllers\QuotationController::class, 'create']);
            Route::get('print/{id}', [\App\Http\Controllers\QuotationController::class, 'printQuotation']);
            Route::get('copy/{id}', [\App\Http\Controllers\QuotationController::class, 'copy']);
            Route::post('save', [\App\Http\Controllers\QuotationController::class, 'saveQuotation'])->name('save');
            Route::get('edit/{id}', [\App\Http\Controllers\QuotationController::class, 'edit']);
            Route::post('update/{id}', [\App\Http\Controllers\QuotationController::class, 'update'])->name('update');
            Route::post('send', [\App\Http\Controllers\QuotationController::class, 'send']);
            Route::delete('destroy/{id}', [\App\Http\Controllers\QuotationController::class, 'destroy']);
            Route::post('{id}/status', [\App\Http\Controllers\QuotationController::class, 'updateStatus'])->name('status');
        });

        //user
        Route::group(['prefix' => 'user', 'as' => 'user.'], function () {
            Route::get('/', [\App\Http\Controllers\UserController::class, 'index']);
            Route::get('/create', [\App\Http\Controllers\UserController::class, 'create']);
            Route::get('/edit/{id}', [\App\Http\Controllers\UserController::class, 'edit']);
            Route::post('/update/{id}', [\App\Http\Controllers\UserController::class, 'update']);
            Route::post('/save', [\App\Http\Controllers\UserController::class, 'store']);
            Route::delete('/delete', [\App\Http\Controllers\UserController::class, 'destroy'])->name('delete');
            Route::post('/status', [\App\Http\Controllers\UserController::class, 'status'])->name('status');
        });

        //client
        Route::group(['prefix' => 'client', 'as' => 'client.'], function () {
            Route::get('/', [\App\Http\Controllers\ClientController::class, 'index']);
            Route::get('/create', [\App\Http\Controllers\ClientController::class, 'createClient']);
            Route::get('/edit/{id}', [\App\Http\Controllers\ClientController::class, 'editClient']);
            Route::get('/search', [\App\Http\Controllers\ClientController::class, 'getBySearch']);
            Route::get('/client/{id}/delete', [\App\Http\Controllers\ClientController::class, 'destroy']);
            Route::post('save', [\App\Http\Controllers\ClientController::class, 'saveClient']);
            Route::post('update/{id}', [\App\Http\Controllers\ClientController::class, 'updateClient']);
        });

        //permission
        Route::resource('role', \App\Http\Controllers\RoleController::class);
        Route::resource('permission', \App\Http\Controllers\PermissionController::class);

        //settings
        Route::group(['prefix' => 'settings', 'as' => 'settings.'], function () {
            Route::get('/smtp', [\App\Http\Controllers\SettingsController::class, 'SmtpSettings']);
            Route::post('smtp/test',[\App\Http\Controllers\SettingsController::class,'sendTestMail']);
            Route::post('/smtp/store', [\App\Http\Controllers\SettingsController::class, 'SmtpStore']);
            Route::get('/email', [\App\Http\Controllers\SettingsController::class, 'emailSettings']);
            Route::post('/email', [\App\Http\Controllers\SettingsController::class, 'storeEmailSettings']);
            Route::get('/system', [\App\Http\Controllers\SettingsController::class, 'systemSettings']);
            Route::post('/system/store', [\App\Http\Controllers\SettingsController::class, 'storeSystemSettings']);
            Route::get('/imap', [\App\Http\Controllers\SettingsController::class, 'imapSettings']);
            Route::post('/imap/store', [\App\Http\Controllers\SettingsController::class, 'imapStore']);
            Route::post('/imap/test', [\App\Http\Controllers\SettingsController::class, 'testImap']);
        });

        //expense
        Route::group(['prefix' => 'expense', 'as' => 'expense.'], function () {
            Route::get('/', [\App\Http\Controllers\ExpenseController::class, 'index']);
            Route::get('/create', [\App\Http\Controllers\ExpenseController::class, 'create']);
            Route::get('/view/{id}', [\App\Http\Controllers\ExpenseController::class, 'viewExpense']);
            Route::post('/save', [\App\Http\Controllers\ExpenseController::class, 'save']);
        });

        //email template
        Route::group(['prefix' => 'etemplate', 'as' => 'etemplate.'], function () {
            Route::get('/', [\App\Http\Controllers\EmailTemplateController::class, 'index']);
            Route::get('/create', [\App\Http\Controllers\EmailTemplateController::class, 'create']);
            Route::post('/save', [\App\Http\Controllers\EmailTemplateController::class, 'save']);
            Route::get('/edit/{id}', [\App\Http\Controllers\EmailTemplateController::class, 'edit']);
            Route::post('/template', [\App\Http\Controllers\EmailTemplateController::class, 'getTemplateInvoice']);
            Route::post('/template/quotation', [\App\Http\Controllers\EmailTemplateController::class, 'getTemplateQuotation']);
            Route::post('/update/{id}', [\App\Http\Controllers\EmailTemplateController::class, 'update']);
        });

        // --- Admin Event Exhibition Management ---
        Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
            Route::get('events', [\App\Http\Controllers\EventController::class, 'index'])->name('events.index');
            Route::get('events/create', [\App\Http\Controllers\EventController::class, 'create'])->name('events.create');
            Route::post('events/store', [\App\Http\Controllers\EventController::class, 'store'])->name('events.store');
            Route::get('events/{id}/manage', [\App\Http\Controllers\EventController::class, 'manage'])->name('events.manage');
            Route::post('events/{id}/update', [\App\Http\Controllers\EventController::class, 'update'])->name('events.update');
            Route::post('events/{id}/space', [\App\Http\Controllers\EventController::class, 'storeSpace'])->name('events.space.store');
            Route::post('events/{id}/stand', [\App\Http\Controllers\EventController::class, 'storeStandType'])->name('events.stand.store');
            Route::post('spaces/{id}/position', [\App\Http\Controllers\EventController::class, 'storePosition'])->name('spaces.position.store');
            Route::post('events/{id}/furniture', [\App\Http\Controllers\EventController::class, 'storeFurniture'])->name('events.furniture.store');
            Route::post('events/{id}/service', [\App\Http\Controllers\EventController::class, 'storeService'])->name('events.service.store');
            Route::post('events/{id}/attendee-type', [\App\Http\Controllers\EventController::class, 'storeAttendeeType'])->name('events.attendee-type.store');

            // Admin Confirmed Bookings
            Route::get('bookings', [\App\Http\Controllers\AdminBookingController::class, 'index'])->name('bookings.index');
            Route::get('bookings/{id}', [\App\Http\Controllers\AdminBookingController::class, 'show'])->name('bookings.show');
            Route::post('bookings/{id}/status', [\App\Http\Controllers\AdminBookingController::class, 'updateStatus'])->name('bookings.status');
            Route::post('bookings/{bookingId}/approve-attendees', [\App\Http\Controllers\AttendeeController::class, 'approveAllForBooking'])->name('bookings.approve-attendees');

            // Admin Attendees & Badges
            Route::get('attendees', [\App\Http\Controllers\AttendeeController::class, 'adminIndex'])->name('attendees.index');
            Route::post('attendees/{id}/approve', [\App\Http\Controllers\AttendeeController::class, 'approveAttendee'])->name('attendees.approve');
            Route::post('attendees/{id}/reject', [\App\Http\Controllers\AttendeeController::class, 'rejectAttendee'])->name('attendees.reject');

            Route::get('badges', [\App\Http\Controllers\BadgeController::class, 'adminIndex'])->name('badges.index');
            Route::get('badges/{id}/print', [\App\Http\Controllers\BadgeController::class, 'printBadge'])->name('badges.print');
            Route::post('badges/print-batch', [\App\Http\Controllers\BadgeController::class, 'printBatch'])->name('badges.print-batch');
            Route::post('badges/{id}/status', [\App\Http\Controllers\BadgeController::class, 'updateStatus'])->name('badges.status');

            // Admin Payments
            Route::get('payments', [\App\Http\Controllers\PaymentController::class, 'adminPayments'])->name('payments.index');
            Route::get('payments/{id}', [\App\Http\Controllers\PaymentController::class, 'adminPaymentShow'])->name('payments.show');
            Route::post('payments/{id}/verify', [\App\Http\Controllers\PaymentController::class, 'adminVerifyPayment'])->name('payments.verify');
            Route::post('payments/{id}/reject', [\App\Http\Controllers\PaymentController::class, 'adminRejectPayment'])->name('payments.reject');
            Route::get('payments/{id}/proof', [\App\Http\Controllers\PaymentController::class, 'adminServeProof'])->name('payments.proof');
        });
    });

    Route::group(['middleware' => 'auth'], function () {
        Route::get('profile', [\App\Http\Controllers\UserProfile::class, 'index']);
        Route::post('profile/update', [\App\Http\Controllers\UserProfile::class, 'update']);

        // --- Customer Portal Routes ---
        Route::group(['prefix' => 'customer', 'as' => 'customer.', 'middleware' => 'customer'], function () {
            Route::get('dashboard', [\App\Http\Controllers\CustomerPortalController::class, 'dashboard'])->name('dashboard');
            Route::get('bookings', [\App\Http\Controllers\CustomerPortalController::class, 'bookings'])->name('bookings.index');
            Route::get('bookings/{id}', [\App\Http\Controllers\CustomerPortalController::class, 'showBooking'])->name('bookings.show');
            Route::get('quotations', [\App\Http\Controllers\CustomerPortalController::class, 'quotations'])->name('quotations.index');
            Route::get('invoices', [\App\Http\Controllers\CustomerPortalController::class, 'invoices'])->name('invoices.index');
            Route::get('invoices/{id}', [\App\Http\Controllers\CustomerPortalController::class, 'showInvoice'])->name('invoices.show');
            Route::get('payments', [\App\Http\Controllers\PaymentController::class, 'customerPayments'])->name('payments.index');
            Route::post('payments/submit', [\App\Http\Controllers\PaymentController::class, 'submitPayment'])->name('payments.submit');
            Route::get('payments/{id}/proof', [\App\Http\Controllers\PaymentController::class, 'customerServeProof'])->name('payments.proof');
            Route::get('attendees', [\App\Http\Controllers\AttendeeController::class, 'index'])->name('attendees.index');
            Route::post('attendees/store', [\App\Http\Controllers\AttendeeController::class, 'store'])->name('attendees.store');
            Route::post('attendees/{id}/update', [\App\Http\Controllers\AttendeeController::class, 'update'])->name('attendees.update');
            Route::delete('attendees/{id}', [\App\Http\Controllers\AttendeeController::class, 'destroy'])->name('attendees.destroy');
            Route::post('bookings/{id}/submit-attendees', [\App\Http\Controllers\AttendeeController::class, 'submitList'])->name('attendees.submit');
            Route::get('badges', [\App\Http\Controllers\BadgeController::class, 'customerIndex'])->name('badges.index');
        });
    });

    // --- Public ZITF-Style Event Exhibition Booking Wizard ---
    Route::get('booking/wizard', [\App\Http\Controllers\EventBookingWizardController::class, 'wizard'])->name('public.booking.wizard');
    Route::post('booking/calculate', [\App\Http\Controllers\EventBookingWizardController::class, 'calculateAjax'])->name('public.booking.calculate');
    Route::post('booking/submit', [\App\Http\Controllers\EventBookingWizardController::class, 'submitQuotation'])->name('public.booking.submit');
    Route::get('quotation/public/{id}', [\App\Http\Controllers\EventBookingWizardController::class, 'showPublicQuotation'])->name('public.quotation.view');
    Route::post('quotation/public/{id}/confirm', [\App\Http\Controllers\EventBookingWizardController::class, 'confirmQuotation'])->name('public.quotation.confirm');

});
