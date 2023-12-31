<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Codes PDF</title>
</head>
<body>

@foreach ($assetIds as $assetId)
    <?php
        // Decrypt the assetId here (replace this with your decryption logic)
        $decryptedId = encrypt($assetId);
        // Generate the QR code
        $qrCode = QrCode::size(300)->generate("http://127.0.0.1:8000/asset/detail/{$decryptedId}");
    ?>

    <img src="data:image/png;base64, {!! base64_encode($qrCode) !!}" alt="QR Code for Asset ID: {{ $decryptedId }}">
    <br><br>

@endforeach

</body>
</html>
