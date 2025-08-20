<!DOCTYPE html>
<html>

<head>
    <style>
        @page {
            margin: 0;
        }

        body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            font-family: 'Times New Roman', serif;
        }

        .certificate-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            object-fit: cover;
        }

        .certificate-content {
            position: relative;
            z-index: 1;
            padding: 100px 50px;
            text-align: center;
        }

        .participant-name {
            font-size: 36px;
            font-weight: bold;
            margin: 200px 0 20px 0;
            color: #000;
            text-transform: uppercase;
        }

        .date {
            font-size: 16px;
            margin-top: 50px;
        }
    </style>
</head>

<body>
    <div class="certificate-container">
        <img src="{{ $backgroundImage }}" class="certificate-background" style="width: 100%; height: 100%;">
        <div class="certificate-content">
            @php
                // Default X and Y positions (in px)    
                $nameX = isset($name_x) ? $name_x : 0;
                $nameY = isset($name_y) ? $name_y : 200;
                $fontSize = isset($font_size) ? $font_size : 16;
            @endphp
            <div class="participant-name"
                style="position: absolute; left: {{ $nameX }}px; top: {{ $nameY }}px; right: 0; width: 100%; text-align: center; font-size: {{ $fontSize }}pt;">
                {{ $nama }}
            </div>           
        </div>
    </div>
</body>

</html>