<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $emailSubject }}</title>
</head>
<body style="margin:0; padding:0; background:#F5ECEA; font-family: Arial, Helvetica, sans-serif; color:#202124;">

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#F5ECEA; padding:24px 0;">
        <tr>
            <td align="center">

                {{-- KONTEN EMAIL --}}
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" border="0" style="max-width:600px; background:#ffffff; border-radius:8px; overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,0.05);">

                    {{-- HEADER --}}
                    <tr>
                        <td style="padding:20px 24px; border-bottom:2px solid #803033;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td style="vertical-align:middle;" width="90">
                                        <img src="{{ $message->embed(public_path('images/logo-batamindo.jpg')) }}"
                                            alt="Batamindo"
                                            style="height:48px; width:auto; display:block;">
                                    </td>
                                    <td style="vertical-align:middle; padding-left:12px;">
                                        <div style="color:#803033; font-weight:bold; font-size:16px; letter-spacing:-0.3px;">
                                            BATAMINDO INDUSTRIAL PARK
                                        </div>
                                        <div style="color:#6B7280; font-size:11px; letter-spacing:0.1em; margin-top:2px;">
                                            PT BATAMINDO INVESTMENT CAKRAWALA
                                        </div>
                                    </td>
                                    <td align="right" style="vertical-align:top; color:#6B7280; font-size:10px;">
                                        <div style="font-weight:bold; color:#202124;">{{ now()->format('d M Y') }}</div>
                                        <div>Ref: BIC/{{ now()->format('Y') }}</div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- BODY --}}
                    <tr>
                        <td style="padding:32px 24px; min-height:280px;">
                            <h2 style="margin:0 0 20px; color:#803033; font-size:18px; text-transform:uppercase; letter-spacing:1px; text-align:center;">
                                {{ $emailNama }}
                            </h2>
                            <div style="font-size:13px; color:#202124; line-height:1.75;">
                                {!! nl2br(e($emailBody)) !!}
                            </div>
                        </td>
                    </tr>

                    {{-- FOOTER --}}
                    <tr>
                        <td style="background:#1a1a1a; padding:20px 24px;">
                            <div style="color:#ffffff; text-align:center; font-size:12px; font-weight:bold; letter-spacing:0.5px; margin-bottom:8px;">
                                PT BATAMINDO INVESTMENT CAKRAWALA
                            </div>
                            <div style="color:#9CA3AF; text-align:center; font-size:10px; line-height:1.6; margin-bottom:8px;">
                                Wisma Batamindo Lt. 3, Jl. Rasamala No. 1<br>
                                Mukakuning, Batam 29433, Indonesia
                            </div>
                            <div style="color:#9CA3AF; text-align:center; font-size:10px; line-height:1.6; margin-bottom:12px;">
                                <span style="color:#ffffff; font-weight:bold;">Telp:</span> (62-770) 611-222 
                                &nbsp;&bull;&nbsp;
                                <span style="color:#ffffff; font-weight:bold;">Fax:</span> (62-770) 611-432<br>
                                <span style="text-decoration:underline; color:#d1d5db;">customerservice@batamindo.co.id</span>
                                &nbsp;&bull;&nbsp;
                                <span style="text-decoration:underline; color:#d1d5db;">www.batamindoindustrial.co.id</span>
                            </div>
                            <div style="color:#6B7280; text-align:center; font-size:9px; line-height:1.6;">
                                &copy; {{ now()->format('Y') }} BIC MailHub. All rights reserved.
                            </div>
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>

</body>
</html>