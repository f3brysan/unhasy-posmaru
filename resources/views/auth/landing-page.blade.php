<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>POSMARU</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }

        .video-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: 1;
            overflow: hidden;
        }

        

        .video-bg iframe {
            position: absolute;
            top: 50%;
            left: 50%;
            min-width: 100vw;
            min-height: 100vh;
            width: 177.77vh;
            /* 16/9 ratio */
            height: 100vh;
            transform: translate(-50%, -50%);
            pointer-events: none;
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.5);
            z-index: 2;
        }

        .content {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: 3;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: #fff;
            text-align: center;
        }

        .btn-login {
            margin-top: 2rem;
            font-size: 1.25rem;
            padding: 0.75rem 2.5rem;
        }

        @media (max-width: 768px) {
            .content h1 {
                font-size: 2.2rem;
            }

            .btn-login {
                font-size: 1rem;
                padding: 0.5rem 1.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="video-bg">
        <!-- Replace the YouTube video ID below with your desired background video -->
        <iframe
            src="https://www.youtube.com/embed/IQKeRPkUdGQ?autoplay=1&mute=1&loop=1&controls=0&showinfo=0&modestbranding=1"
            frameborder="0" allow="autoplay; encrypted-media" allowfullscreen title="YouTube video background">
        </iframe>

    </div>
    <div class="overlay"></div>
    <div class="content">
        <h1 class="display-2 fw-bold mb-4" style="text-shadow: 2px 2px 8px #000;">POSMARU</h1>
        <h2 class="display-4 fw-bold mb-4" style="text-shadow: 2px 2px 8px #000;">Universitas Hasyim Asy'ari</h2>
        <a href="{{ route('login') }}" class="btn btn-outline-light btn-login shadow-lg">Join Now</a>
    </div>
    <!-- Bootstrap 5 JS (optional, for components) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script></script>
</body>

</html>
