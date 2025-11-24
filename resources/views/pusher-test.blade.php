<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pusher Test</title>
    @vite('resources/js/app.js')
</head>
<body>
    <h1>Pusher Test</h1>
    <p>
        Buka konsol browser Anda untuk melihat log Pusher. Jika semuanya terkonfigurasi dengan benar, Anda akan melihat pesan koneksi yang berhasil.
    </p>

    <script type="module">
        // Echo is initialized in resources/js/bootstrap.js
        // We can listen for an event here
        window.Echo.channel('my-channel')
            .listen('.my-event', (e) => {
                console.log('Event received:', e);
                alert('Event received! Check the console.');
            });

        console.log("Listening for events on 'my-channel'...");
    </script>
</body>
</html>