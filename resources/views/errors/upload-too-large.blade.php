<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Upload Too Large</title>
    <style>
        body { margin: 0; min-height: 100vh; background: #f8fafc; color: #0f172a; font-family: Arial, sans-serif; }
        main { min-height: 100vh; max-width: 42rem; margin: 0 auto; display: flex; align-items: center; padding: 3rem 1.5rem; box-sizing: border-box; }
        section { width: 100%; background: #fff; border: 1px solid #fecaca; border-radius: 1.5rem; padding: 2rem; box-shadow: 0 1px 2px rgb(15 23 42 / 0.06); box-sizing: border-box; }
        p.label { color: #dc2626; font-size: 0.75rem; font-weight: 700; letter-spacing: 0.2em; text-transform: uppercase; }
        h1 { margin: 0.75rem 0 0; font-size: 1.875rem; line-height: 2.25rem; font-weight: 900; }
        p.message { margin: 1rem 0 0; color: #475569; line-height: 1.6; }
        a { display: inline-flex; margin-top: 1.5rem; background: #0f172a; color: #fff; text-decoration: none; border-radius: 1rem; padding: 0.75rem 1.25rem; font-size: 0.875rem; font-weight: 700; }
        a:hover { background: #1e293b; }
    </style>
</head>
<body>
    <main>
        <section>
            <p class="label">Upload failed</p>
            <h1>The upload is too large</h1>
            <p class="message">{{ $message }}</p>
            <a href="{{ $backUrl }}">
                Back to product
            </a>
        </section>
    </main>
</body>
</html>
