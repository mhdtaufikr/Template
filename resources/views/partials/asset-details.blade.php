<div class="col-md-4">
    <strong>Asset No.</strong><br>
    <p>{{ $asset->asset_no }}</p>
    <strong>Quantity</strong><br>
    <p>{{ $asset->qty }} ({{ $asset->uom }})</p>
    <strong>Asset Category</strong><br>
    <p>{{ $asset->asset_type }}</p>
    <strong>Acquisition Date</strong><br>
    <p>{{ date('d-M-Y', strtotime($asset->acq_date)) }}</p>
    <strong>PO No.</strong><br>
    <p>{{ $asset->po_no }}</p>
    <strong>Serial No.</strong><br>
    <p>{{ $asset->serial_no }}</p>
    <strong>Department</strong><br>
    <p>{{ $asset->dept }}</p>
    <strong>Location</strong><br>
    <p>{{ $asset->plant }} ({{ $asset->loc }})</p>
    <strong>Cost Center</strong><br>
    <p>{{ $asset->cost_center }}</p>
    <strong>Acquisition Cost</strong><br>
    <p>{{ 'Rp ' . number_format($asset->acq_cost, 0, ',', '.') }}</p>
</div>
