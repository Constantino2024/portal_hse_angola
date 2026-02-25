<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Nova notícia – Portal HSE</title>
</head>
<body style="font-family: Arial, sans-serif; background:#f3f4f6; padding:24px;">
<div style="max-width:600px;margin:0 auto;background:#ffffff;border-radius:12px;overflow:hidden;">
    <div style="background:#111827;color:#ffffff;padding:16px 24px;">
        <h2 style="margin:0;font-size:20px;">Portal HSE Angola</h2>
    </div>

    <div style="padding:24px;">
        <p style="margin-top:0;">Olá,</p>

        <p>Foi publicada uma nova notícia:</p>

        <h1 style="font-size:22px;margin-bottom:8px;">{{ $post->title }}</h1>

        @if($post->excerpt)
            <p style="color:#4b5563;">{{ $post->excerpt }}</p>
        @endif

        <p style="margin:24px 0;">
            <a href="{{ url('/noticias/' . $post->slug) }}"
               style="background:#ff6b35;color:#ffffff;padding:10px 18px;border-radius:999px;
               text-decoration:none;font-weight:bold;">
                Ler no Portal
            </a>
        </p>

        <small>Recebeu este email porque está inscrito na newsletter.</small>

    </div>
</div>
</body>
</html>
