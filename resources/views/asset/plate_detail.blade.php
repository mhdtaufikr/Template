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
            font-family: arial, helvetica, sans-serif;
            width: 9cm;
            min-height: 4cm;
            margin: 0.10000in 0.00000in 0.00000in 0.00000in;
            font-size: 9pt;
        }

        .plate {
        width: 9cm;
        min-height: 4cm;
        overflow: hidden;
        border: 2px solid #000;
        box-sizing: border-box;
        text-align: center; /* Align center the content inside the label */
    }


        #header {
            font-size: 10px;
            font-weight: bold;
            margin-bottom: 10px;
            text-align: center; /* Center header text */
        }

        #qrCode img {
            width: auto;
            height: auto;
            margin-left: auto; /* Align the image to the right by pushing it to the left as much as possible */
        }

        .plate td {
            padding: 0;
            padding-left: 3px; /* Add padding to table cells */
            height: 4cm; /* Set the height of table cells to 4cm */
        }

        .qr_img {
            width: 0.40866in;
            height: 0.40866in;
            float: left;
            padding-right: .10in;
        }

        .qr_img img {
            width: 100%;
            height: 100%;
            margin-top: -6.9%;
            margin-left: -6.9%;
            padding-bottom: 0; /* Reduced bottom padding for QR code image */
        }

        .qr_text {
            width: calc(9cm - 0.40866in);
            height: 4cm;
            padding-top: 0.00000in;
            font-family: arial, helvetica, sans-serif;
            font-size: 6;
            padding-right: .01in;
            overflow: hidden !important;
            word-wrap: break-word;
            word-break: break-all;
            background-color: aqua;
            box-sizing: border-box;
        }

        .pull-left {
            padding-left: 5mm;
        }

        .next-padding {
            margin: 0.10000in 0.00000in 0.00000in 0.00000in;
        }
        #additionalInfo {
            font-size: 10px;
            text-align: left; /* Center additional info text */
        }
        @page { margin-left: 20;
        margin-top: 5px; }
    </style>
</head>

<body>
    @foreach ($assets->chunk(12) as $assetChunk) <!-- Ubah 12 menjadi 4 baris x 3 kolom -->
    <page> <!-- Tambahkan pembukaan tag halaman di sini -->
        <table >
            <tr>
                <td style="width: 50%;">
                    <table>
                        @foreach ($assetChunk as $key => $asset)
                            <?php
                                // Decrypt the assetId here (replace this with your decryption logic)
                                $decryptedId = $asset->id;
                                // Generate the QR code
                                $qrCode = QrCode::size(120)
                                    ->margin(5) // Adjust the margin to increase the size of the pixels
                                    ->generate("$rule{$decryptedId}");
                            ?>
                            @if ($key % 3 == 0) <!-- Start a new row for every 3 assets -->
                                <tr>
                            @endif
                            <td style="width:33.33%;">
                                <table class="table table-bordered plate custom-table" style="margin-right: 120px;">
                                    <tbody>
                                        <tr>
                                            <td style="width:30%" id="qrCode">
                                                <img style="margin-top: 10px;" src="data:image/png;base64, {!! base64_encode($qrCode) !!}" alt="QR Code for Asset ID: {{ $decryptedId }}">
                                            </td>
                                            <td style="width:70%;" id="additionalInfo">
                                                <h3 style="margin-bottom: 0; margin-top:20px; font-size: 16px;">Asset By PT.MKM</h3>
                                                <h1 style="margin-bottom: 0; font-size: 22px;">{{ $asset->asset_no }} - {{$asset->sub_asset}}</h1>
                                                <p style="margin-bottom: 0; font-size: 13px;">{{ Illuminate\Support\Str::limit($asset->desc, 50) }}</p>
                                                <p style="margin: 0; font-size: 15px;">{{$segment}} <br>{{ date('d/m/Y', strtotime($asset->acq_date)) }}</p>
                                            </td>

                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                            @if (($key + 1) % 3 == 0 || $loop->last || ($loop->remaining < 3 && $loop->remaining % 3 == 0)) <!-- Close the row for every 3 assets or if it's the last asset or if there are less than 3 assets remaining and the remaining count is divisible by 3 -->
                                </tr>
                            @endif
                        @endforeach
                    </table>
                </td>
            </tr>
        </table>
    </page> <!-- Tambahkan penutup tag halaman di sini -->
    @endforeach
</body>

</html>
