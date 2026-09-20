<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Hello</title>
        <style>
            :root {
                color-scheme: light;
                font-family: Georgia, 'Times New Roman', serif;
                color: #55706a;
                background: #f4f1e8;
            }

            * {
                box-sizing: border-box;
            }

            body {
                min-height: 100vh;
                margin: 0;
                display: grid;
                place-items: center;
                padding: 24px;
                background: radial-gradient(circle at top right, #d7e8dc, transparent 42%), #f4f1e8;
            }

            main {
                width: min(100%,700px);
                padding: clamp(48px, 10vw, 88px) 32px;
                text-align: center;
                border: 1px solid #c6d8cb;
                border-radius: 8px;
                background: rgba(255, 255, 255, 0.72);
                box-shadow: 0 18px 50px rgba(24, 59, 59, 0.1);
            }

            h1 {
                margin: 0;
                font-size: clamp(3.5rem, 12vw, 7rem);
                line-height: 0.95;
                font-weight: 400;
                letter-spacing: -0.04em;
            }

            p {
                margin: 24px 0 0;
                color: #55706a;
                font-family: 'Trebuchet MS', sans-serif;
                font-size: 0.95rem;
                letter-spacing: 0.08em;
                text-transform: uppercase;
            }
        </style>
    </head>
    <body>
        <main>
            <h1>Hello zacky</h1>
            <p>Selamat datang di Laravel</p><br>
        </main>
    </body>
</html>
