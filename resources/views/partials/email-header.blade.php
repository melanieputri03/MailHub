@php $logo = $logoSrc ?? asset('images/logo-batamindo.jpg'); @endphp
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td width="100" style="padding:20px 0 20px 24px; border-bottom:2px solid #803033; vertical-align:middle;">
            <img src="{{ $logo }}" alt="Batamindo" style="height:48px; width:auto; display:block;">
        </td>
        <td style="padding:20px 12px; border-bottom:2px solid #803033; vertical-align:middle; font-family:Arial,Helvetica,sans-serif;">
            <div style="color:#803033; font-weight:bold; font-size:16px;">BATAMINDO INDUSTRIAL PARK</div>
            <div style="color:#6B7280; font-size:11px; letter-spacing:0.1em; margin-top:2px;">PT BATAMINDO INVESTMENT CAKRAWALA</div>
        </td>
        <td align="right" style="padding:20px 24px 20px 0; border-bottom:2px solid #803033; vertical-align:top; font-family:Arial,Helvetica,sans-serif; font-size:10px; color:#6B7280;">
            <div style="font-weight:bold; color:#202124;">{{ now()->format('d M Y') }}</div>
            <div>Ref: BIC/{{ now()->format('Y') }}</div>
        </td>
    </tr>
</table>