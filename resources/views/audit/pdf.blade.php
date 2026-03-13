<!DOCTYPE html>
<html>
<head>
    <title>Audit PDF</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h1, h2 { text-align: center; margin: 5px 0; }
        .section { margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 6px 8px; text-align: left; vertical-align: middle; }
        th { background-color: #f2f2f2; }
        .img-cell img { max-width: 80px; max-height: 60px; display: block; margin: auto; }
        .sig-cell img { max-width: 120px; max-height: 60px; display: block; margin: auto; }
        .signature-section table { border: none; }
        .signature-section td, .signature-section th { border: none; text-align: center; }
        .no-img { color: #999; font-size: 10px; }
    </style>
</head>
<body>

    {{-- HEADER --}}
    <div class="section">
        <h1>Audit Report</h1>
    </div>

    {{-- AUDIT INFO --}}
    <div class="section">
        <h2>Audit Details</h2>
        <table>
            <thead>
                <tr>
                    <th>Audit Number</th>
                    <th>Audit Date</th>
                    <th>Created By</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $audit->audit_no }}</td>
                    <td>{{ date('d-M-Y', strtotime($audit->audit_date)) }}</td>
                    <td>{{ $audit->user->name }}</td>
                    <td>{{ $audit->status == 1 ? 'Done' : 'Closed' }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- AUDIT ITEM DETAILS --}}
    <div class="section">
        <h2>Audit Item Details</h2>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Asset ID</th>
                    <th>Condition</th>
                    <th>Availability</th>
                    <th>Remark</th>
                    <th>Image</th>
                    <th>Signature</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($auditDetails as $i => $detail)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $detail->asset_id }}</td>
                    <td>{{ $detail->condition }}</td>
                    <td>{{ $detail->availability }}</td>
                    <td>{{ $detail->remark }}</td>

                    {{-- Foto Kondisi Asset --}}
                    <td class="img-cell">
                        @if(!empty($detail->img_b64_list))
                            @foreach($detail->img_b64_list as $imgB64)
                                <img src="{{ $imgB64 }}" alt="Asset Image"
                                    style="max-width: 80px; max-height: 60px; display: block; margin: 2px auto;">
                            @endforeach
                        @else
                            <span class="no-img">No Image</span>
                        @endif
                    </td>

                    {{-- Tanda Tangan --}}
                    <td class="sig-cell">
                        @if($detail->signature_b64)
                            <img src="{{ $detail->signature_b64 }}" alt="Signature"
                                style="max-width: 120px; max-height: 60px; display: block; margin: auto;">
                        @else
                            <span class="no-img">No Signature</span>
                        @endif
                    </td>


                    <td>{{ date('d-M-Y', strtotime($detail->created_at)) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- SIGNATURE SECTION --}}
    <div class="section signature-section">
        <table>
            <tr>
                <td style="width: 50%; text-align: center; padding-top: 20px;">
                    <strong>Controlling Signature</strong><br><br>
                    @if($audit->signature_ctl_b64)
                        <img src="{{ $audit->signature_ctl_b64 }}"
                             style="max-width: 150px; max-height: 80px;">
                    @else
                        <span class="no-img">No Signature</span>
                    @endif
                </td>
                <td style="width: 50%; text-align: center; padding-top: 20px;">
                    <strong>Audit Signature</strong><br><br>
                    @if($audit->signature_aud_b64)
                        <img src="{{ $audit->signature_aud_b64 }}"
                             style="max-width: 150px; max-height: 80px;">
                    @else
                        <span class="no-img">No Signature</span>
                    @endif
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
