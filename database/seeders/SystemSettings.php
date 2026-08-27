<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Settings;

class SystemSettings extends Seeder
{
    /**
     * Seed the official Ministry of Sports, Recreation, Arts and Culture settings.
     * Uses updateOrCreate so this is safe to re-run without overwriting user changes
     * that differ from the defaults — only inserts if the row does not exist.
     */
    public function run()
    {
        // ── System / Organisation Settings ────────────────────────────────────
        $systemSettings = [
            'app_name'            => 'Ministry of Sports, Recreation, Arts and Culture',
            'app_address'         => 'Chinengundu Mashayamombe Building 95, Cnr N. Mandela & S. V. Muzenda Street, Harare',
            'app_postal_address'  => 'P.O. Box HR 480 Harare',
            'app_email'           => 'minofsportandarts@gmail.com',
            'app_phone'           => '+263242708345',
            'app_moto'            => 'Unity • Freedom • Work',
            'logo'                => 'assets/files/ministry-logo.png',
        ];

        foreach ($systemSettings as $label => $description) {
            Settings::firstOrCreate(
                ['type' => 'system', 'label' => $label],
                ['type' => 'system', 'label' => $label, 'description' => $description]
            );
        }

        // ── Banking / Payment Settings (used on invoices & customer payments) ─
        $bankingSettings = [
            'acc_name'     => 'Sports and Recreation',
            'bank'         => 'EmpowerBank',
            'acc_number'   => '953869211833',
            'acc_type'     => 'Corporate Nostro FCA (Domestic) USD',
            'acc_currency' => 'USD',
            'branch'       => 'Main Branch',
            'show_bank'    => '1',
        ];

        foreach ($bankingSettings as $label => $description) {
            Settings::firstOrCreate(
                ['type' => 'email', 'label' => $label],
                ['type' => 'email', 'label' => $label, 'description' => $description]
            );
        }
    }
}
