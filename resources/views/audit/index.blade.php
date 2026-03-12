@extends('layouts.master')

@section('content')

<main>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
            </div>
        </div>
    </header>

    <div class="container-fluid px-4 mt-n10">
        <div class="content-wrapper">
            <section class="content-header"></section>

            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">

                            {{-- Card Scan --}}
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Scan Asset No.</h3>
                                </div>
                                <div class="col-sm-12">
                                    @if (session('status'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <strong>{{ session('status') }}</strong>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                    @endif

                                    @if (session('failed'))
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <strong>{{ session('failed') }}</strong>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                    @endif

                                    @if (count($errors) > 0)
                                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                        <ul>
                                            <li><strong>Data Process Failed !</strong></li>
                                            @foreach ($errors->all() as $error)
                                            <li><strong>{{ $error }}</strong></li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    @endif
                                </div>

                                <div class="card-body">
                                    <div class="row">
                                        <div class="mb-3 col-sm-12">
                                            <form action="{{ url('/audit/scan') }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="form-group mb-4">
                                                        <label for="assetSelect">Choose asset:</label>
                                                        <select name="asset[]" id="assetSelect" class="form-control" multiple="multiple" style="width: 100%"></select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer d-flex justify-content-center">
                                                    <button id="submitBtn" type="submit" class="btn btn-primary">Submit</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Card List Audit --}}
                            <div class="card mt-4">
                                <div class="card-header">
                                    <h3 class="card-title">List Audit</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="mb-3 col-sm-12">
                                            <div class="table-responsive">
                                                <table id="tableUser" class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Audit No.</th>
                                                            <th>Audit Date</th>
                                                            <th>Created By</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php $no = 1; @endphp
                                                        @foreach ($item as $data)
                                                        <tr class="audit-row"
                                                            style="cursor:pointer;"
                                                            data-id="{{ $data->id }}"
                                                            data-audit="{{ $data->audit_no }}">
                                                            <td>{{ $no++ }}</td>
                                                            <td>{{ $data->audit_no }}</td>
                                                            <td>{{ $data->audit_date }}</td>
                                                            <td>{{ $data->user->name ?? 'Unknown' }}</td>
                                                            <td>
                                                                <div class="btn-group">
                                                                    <a href="audit/detail/{{ encrypt($data->id) }}" class="btn btn-primary btn-sm" title="Detail">
                                                                        <i class="fas fa-info"></i>
                                                                    </a>
                                                                    <a href="audit/edit/{{ encrypt($data->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                                                        <i class="fas fa-edit"></i>
                                                                    </a>
                                                                    <button title="Delete" class="btn btn-danger btn-sm"
                                                                        data-bs-toggle="modal" data-bs-target="#modal-delete{{ $data->id }}">
                                                                        <i class="fas fa-trash-alt"></i>
                                                                    </button>
                                                                    <a href="audit/pdf/{{ encrypt($data->id) }}" class="btn btn-success btn-sm" title="Download PDF">
                                                                        <i class="far fa-file-pdf"></i>
                                                                    </a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</main>

<style>
.audit-row:hover { background-color: #f0f4ff !important; }
tr.shown { background-color: #e8f0fe !important; }
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>

<script>
    function docReady(fn) {
        if (document.readyState === "complete" || document.readyState === "interactive") {
            setTimeout(fn, 1);
        } else {
            document.addEventListener("DOMContentLoaded", fn);
        }
    }

    docReady(function () {
        var inputField = document.getElementById('qr-value');
        var lastResult;

        function onScanSuccess(decodedText, decodedResult) {
            if (decodedText !== lastResult) {
                lastResult = decodedText;
                if (inputField) inputField.value = decodedText;
            }
        }

        var html5QrcodeScanner = new Html5QrcodeScanner("qr-reader", { fps: 10, qrbox: 250 });
        html5QrcodeScanner.render(onScanSuccess);
    });
</script>

<script>
$(document).ready(function () {

    // ── DataTable ──────────────────────────────────────────
    var table = $("#tableUser").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
    });

    // ── Select2 ────────────────────────────────────────────
    $('#assetSelect').select2({
        ajax: {
            url: '{{ route("assets.query") }}',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return { search: params.term, page: params.page || 1 };
            },
            processResults: function (data, params) {
                params.page = params.page || 1;
                return {
                    results: data.items,
                    pagination: { more: (params.page * 30) < data.total_count }
                };
            },
            cache: true
        },
        placeholder: 'Choose asset...',
        minimumInputLength: 1,
        multiple: true
    });

    // ── Double Click Child Row ─────────────────────────────
    function buildAssetTable(details) {
        const statusColor = { active: 'success', inactive: 'danger', disposed: 'secondary' };

        if (!details.length) {
            return `<div class="p-3 text-muted"><i class="fas fa-info-circle me-1"></i> No assets found for this audit.</div>`;
        }

        const rows = details.map((d, i) => {
            const a = d.asset ?? {};
            const status = String(a.status ?? '').toLowerCase();
            return `
                <tr>
                    <td>${i + 1}</td>
                    <td><span class="badge bg-secondary">${a.asset_no ?? '-'}</span></td>
                   <td>${a.desc ?? '-'}</td>
                    <td>${a.dept ?? '-'}</td>
                    <td>${a.loc ?? '-'}</td>
                    <td>${d.condition ?? '-'}</td>
                    <td>${d.availability ?? '-'}</td>
                    <td>${d.remark ?? '-'}</td>
                    <td><span class="badge bg-${statusColor[status] ?? 'warning'}">${a.status ?? '-'}</span></td>
                </tr>
            `;
        }).join('');

        return `
            <div class="p-3 bg-light">
                <h6 class="text-primary mb-2"><i class="fas fa-boxes me-1"></i> Asset List</h6>
                <table class="table table-sm table-bordered mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th><th>Asset No</th><th>Description</th>
                            <th>Dept</th><th>Location</th><th>Condition</th>
                            <th>Availability</th><th>Remark</th><th>Status</th>
                        </tr>
                    </thead>
                    <tbody>${rows}</tbody>
                </table>
            </div>
        `;
    }

    function showChildRow(tr, row) {
        const auditId = tr.data('id');

        row.child(`<div class="p-3 text-muted"><i class="fas fa-spinner fa-spin me-1"></i> Loading assets...</div>`).show();
        tr.addClass('shown');

        $.get(`{{ url('audit/asset-list') }}/${auditId}`, function (data) {
            if (row.child.isShown()) {
                row.child(buildAssetTable(data)).show();
            }
        }).fail(function () {
            if (row.child.isShown()) {
                row.child(`<div class="p-3 text-danger"><i class="fas fa-exclamation-triangle me-1"></i> Failed to load assets.</div>`).show();
            }
        });
    }

    $('#tableUser tbody').on('click', 'tr.audit-row', function (e) {
        if ($(e.target).closest('.btn-group').length) return;

        var tr  = $(this);
        var row = table.row(tr);

        if (row.child.isShown()) {
            row.child.hide();
            tr.removeClass('shown');
        } else {
            showChildRow(tr, row);
        }
    });

});
</script>

@endsection
