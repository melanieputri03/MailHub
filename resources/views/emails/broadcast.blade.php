<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $emailSubject }}</title>
    <style>
        p { margin: 0 0 8px 0 !important; }
        p:empty { display: none !important; }
    </style>
</head>
<body style="margin:0; padding:0; background:#F5ECEA; font-family: Arial, Helvetica, sans-serif; color:#202124;">

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#F5ECEA; padding:24px 0;">
        <tr>
            <td align="center">

                <table role="presentation" width="600" cellpadding="0" cellspacing="0" border="0" style="max-width:600px; background:#ffffff; border-radius:8px; overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,0.05);">

                    {{-- HEADER: CUSTOM atau BAWAAN --}}
                    <tr>
                        <td>
                            @if($headerHtml)
                                {!! $embedHeader($message) !!}
                            @else
                                @include('partials.email-header', [
                                    'logoSrc' => $message->embed(public_path('images/logo-batamindo.jpg')),
                                ])
                            @endif
                        </td>
                    </tr>

                    {{-- BODY --}}
                    <tr>
                        <td style="padding:32px 24px; min-height:280px;">
                            <h2 style="margin:0 0 20px; color:#803033; font-size:18px; letter-spacing:1px; text-align:center;">
                                {{ $emailNama }}
                            </h2>
                            <div style="font-size:13px; color:#202124; line-height:1.5;">
                                {!! $embedBody($message) !!}
                            </div>
                        </td>
                    </tr>

                    {{-- FOOTER: CUSTOM atau BAWAAN --}}
                    <tr>
                        <td>
                            @if($footerHtml)
                                {!! $embedFooter($message) !!}
                            @else
                                @include('partials.email-footer')
                            @endif
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>