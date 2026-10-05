<!DOCTYPE html>
<html lang="es">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"></head>
<body style="margin:0;padding:0;background:#f3f4f6;font-family:-apple-system,'Segoe UI',Roboto,Arial,sans-serif;color:#1f2937;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f3f4f6;padding:24px 12px;">
  <tr><td align="center">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:560px;">
      <tr><td align="center" style="padding:0 0 16px;">
        <a href="{{ url('/') }}" style="text-decoration:none;">
          <img src="{{ url('/images/logo.png') }}" width="56" height="56" alt="KosherMap" style="border:0;vertical-align:middle;border-radius:12px;">
          <span style="font-size:26px;font-weight:800;color:#1e40af;vertical-align:middle;margin-left:8px;">Kosher<span style="color:#111827;">Map</span></span>
        </a>
      </td></tr>
      <tr><td style="background:#ffffff;border-radius:14px;padding:32px 28px;border:1px solid #e5e7eb;">
        <h1 style="margin:0 0 16px;font-size:21px;line-height:1.3;color:#111827;">{{ $heading }}</h1>

        @foreach($paragraphs as $p)
          <p style="margin:0 0 14px;font-size:15px;line-height:1.6;color:#374151;">{{ $p }}</p>
        @endforeach

        @if(!empty($details))
          <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:6px 0 18px;border:1px solid #e5e7eb;border-radius:8px;">
            @foreach($details as $label => $value)
              <tr>
                <td style="padding:9px 12px;font-size:13px;color:#6b7280;width:36%;vertical-align:top;border-bottom:1px solid #f3f4f6;">{{ $label }}</td>
                <td style="padding:9px 12px;font-size:14px;color:#111827;vertical-align:top;border-bottom:1px solid #f3f4f6;">{!! nl2br(e($value)) !!}</td>
              </tr>
            @endforeach
          </table>
        @endif

        @if($button)
          <table role="presentation" cellpadding="0" cellspacing="0" style="margin:22px 0 4px;">
            <tr><td style="background:#2563eb;border-radius:8px;">
              <a href="{{ $button[1] }}" style="display:inline-block;padding:13px 26px;font-size:15px;font-weight:600;color:#ffffff;text-decoration:none;">{{ $button[0] }}</a>
            </td></tr>
          </table>
        @endif
      </td></tr>
      <tr><td align="center" style="padding:16px 8px 0;font-size:12px;color:#9ca3af;line-height:1.5;">
        KosherMap · Productos kosher, certificadoras y guías de kashrut<br>
        <a href="{{ url('/') }}" style="color:#9ca3af;">koshermap.org</a>
      </td></tr>
    </table>
  </td></tr>
</table>
</body>
</html>
