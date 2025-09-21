<!DOCTYPE html>
<html>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
<head>
    <title>Audit PDF</title>
    <style>
        /* Add your PDF styles here */
        body {
            font-family: Arial, sans-serif;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .details {
            margin-bottom: 20px;
        }
        .details p {
            margin: 5px 0;
        }
        .signature img, .image img {
            max-width: 200px;
            height: auto;
            margin: 10px 0;
        }
        .audit-details-table {
            width: 100%;
            border-collapse: collapse;
        }
        .audit-details-table th, .audit-details-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        .audit-details-table th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Audit Report</h1>
    </div>
    <h2>Audit Details</h2>
    <div class="audit-details">
        <table class="audit-details-table">
            <tr>
                <th>Audit Number</th>
                <th>Audit Date</th>
                <th>Created By</th>
                <th>Status</th>
            </tr>
            <tr>
                <td> {{ $audit->audit_no }}</td>
                <td>{{ date('d-M-Y', strtotime($audit->audit_date)) }}</td>
                <td>{{ $audit->user->name }}</td>
                <td>{{ $audit->status == 1 ? 'Done' : 'Pending' }}</td>
            </tr>
        </table>
    </div>

    <div class="audit-details">
        <h2>Audit Item Details</h2>
        <table class="audit-details-table">
            <thead>
                <tr>
                    <th>Asset ID</th>
                    <th>Condition</th>
                    <th>Remark</th>
                    <th>Signature</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($auditDetails as $detail)
                    <tr>
                        <td>{{ $detail->asset_id }}</td>
                        <td>{{ $detail->condition }}</td>
                        <td>{{ $detail->remark }}</td>
                        <td class="signature">
                            <img src="{{ public_path($detail->signature) }}" alt="Signature">
                        </td>
                        <td>{{ date('d-M-Y', strtotime($detail->created_at)) }}</td>
                        <td>{{ date('d-M-Y', strtotime($detail->updated_at)) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="signature">
            <table>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                <tr>
                    <td style="text-align: right;">
                        <p><strong>Controlling Signature:</strong></p>
                        <img src="{{ public_path($audit->signature_ctl) }}" alt="Controlling Signature" style="display: block; margin-left: auto;">
                    </td>
                    <td colspan="3" style="text-align: right;">
                        <p><strong>Audit Signature:</strong></p>
                        <img src="{{ public_path($audit->signature_aud) }}" alt="Audit Signature" style="display: block; margin-left: auto;">
                    </td>


                </tr>
            </table>




    </div>
</body>
</html>
