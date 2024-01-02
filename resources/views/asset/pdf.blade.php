<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plate Label</title>
    <!-- Add Bootstrap CSS link -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Add styles for formatting -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .plate {
            border: 2px solid #000;
            width: 9cm;
            height: 4cm;
            overflow: hidden;
        }

        #header {
            font-size: 10px;
            font-weight: bold;
            margin-bottom: 10px;
            text-align: center; /* Center header text */
        }

        #qrCode img {
                width: 100%;
                height: auto;
                margin-left: auto; /* Align the image to the right by pushing it to the left as much as possible */
                display: block; /* Ensure that margin-left: auto works correctly */
            }


        #additionalInfo {
            font-size: 10px;
            text-align: left; /* Center additional info text */
        }

        .plate td {
            padding: 5px;
            padding-left: 10px; /* Add padding to table cells */
        }
    </style>
</head>
<body>

    @foreach ($assets as $asset)
    <?php
        // Decrypt the assetId here (replace this with your decryption logic)
        $decryptedId = encrypt($asset->id);
        // Generate the QR code
        $qrCode = QrCode::size(80)->generate("http://127.0.0.1:8000/asset/detail/{$decryptedId}");
    ?>

<table class="table table-bordered plate custom-table">
    <thead>
        <tr>
            <th colspan="2" id="header">PT. MITSUBISHI KRAMA YUDHA MOTORS AND MANUFACTURING</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="width:30%" id="qrCode">
                <img src="data:image/png;base64, {!! base64_encode($qrCode) !!}" alt="QR Code for Asset ID: {{ $decryptedId }}">
            </td>
            <td style="width:70%" id="additionalInfo">
                <h1>{{ $asset->asset_no }}</h1>
                <p style="margin: 0">{{ $asset->desc }}</p>
                <p > TDRA 39/40</p>
                <p>{{ date('d/m/Y', strtotime($asset->acq_date)) }}</p>
            </td>
        </tr>
    </tbody>
</table>



    <br><br>
@endforeach


</body>
</html>
