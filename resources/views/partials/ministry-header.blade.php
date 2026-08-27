{{--
    Ministry Document Header Partial
    Usage: @include('partials.ministry-header')
    Requires: $settings (plucked from Settings::where('type','system'))
              $bankSettings (plucked from Settings::where('type','email')) — optional
--}}
<table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:0;">
    <tr>
        <td width="20%" style="vertical-align:middle; padding-right:12px;">
            <img src="{{ public_path($settings['logo'] ?? 'assets/files/ministry-logo.png') }}"
                 alt="Ministry Logo"
                 style="width:110px; height:auto; display:block;"
                 onerror="this.style.display='none'">
        </td>
        <td width="80%" style="vertical-align:middle;">
            <div style="font-size:15px; font-weight:700; color:#1a5c1a; text-transform:uppercase; letter-spacing:0.5px; line-height:1.3;">
                {{ $settings['app_name'] ?? 'Ministry of Sports, Recreation, Arts and Culture' }}
            </div>
            <div style="font-size:11px; color:#333; margin-top:4px; line-height:1.6;">
                {{ $settings['app_address'] ?? 'Chinengundu Mashayamombe Building 95, Cnr N. Mandela & S. V. Muzenda Street, Harare' }}<br>
                {{ $settings['app_postal_address'] ?? 'P.O. Box HR 480 Harare' }}<br>
                <strong>Email:</strong> {{ $settings['app_email'] ?? 'minofsportandarts@gmail.com' }}
                &nbsp;|&nbsp;
                <strong>Tel:</strong> {{ $settings['app_phone'] ?? '+263242708345' }}
            </div>
        </td>
    </tr>
</table>
<div style="border-top:3px solid #1a5c1a; border-bottom:1px solid #ccc; margin:10px 0 14px 0;"></div>
