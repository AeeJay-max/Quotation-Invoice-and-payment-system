<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        $driver = DB::getDriverName();
        if ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys=OFF;');
            DB::statement('CREATE TABLE invoices_temp AS SELECT * FROM invoices;');
            DB::statement('DROP TABLE invoices;');
            DB::statement('CREATE TABLE invoices (
                id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
                client_id INTEGER NOT NULL,
                user_id INTEGER NOT NULL,
                payment_type INTEGER NOT NULL,
                payment_status INTEGER NULL,
                payment_currency INTEGER NOT NULL,
                discount INTEGER NULL,
                vat INTEGER NULL,
                create_date date NULL,
                due_date date NULL,
                note TEXT NULL,
                terms_condition TEXT NULL,
                email_subject TEXT NULL,
                email_body TEXT NULL,
                attach tinyint(1) NULL,
                is_schedule_sent tinyint(1) NOT NULL DEFAULT 0,
                is_scheduled tinyint(1) NULL,
                schedule_date datetime NULL,
                created_at datetime NULL,
                updated_at datetime NULL,
                invoice_number varchar NULL,
                event_id INTEGER NULL,
                booking_id INTEGER NULL,
                quotation_id INTEGER NULL,
                amount_paid numeric NOT NULL DEFAULT 0,
                amount_outstanding numeric NOT NULL DEFAULT 0,
                total numeric NOT NULL DEFAULT 0
            );');
            DB::statement('INSERT INTO invoices SELECT * FROM invoices_temp;');
            DB::statement('DROP TABLE invoices_temp;');
            DB::statement('PRAGMA foreign_keys=ON;');
        } else {
            Schema::table('invoices', function (Blueprint $table) {
                $table->unsignedBigInteger('payment_status')->nullable()->change();
            });
        }
    }

    public function down()
    {
    }
};
