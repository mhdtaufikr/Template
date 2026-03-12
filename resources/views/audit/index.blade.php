@extends('layouts.master')

@section('content')

<main>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
            </div>
        </div>
    </header>
    <!-- Main page content-->
    <div class="container-fluid px-4 mt-n10">
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Scan Asset No.</h3>
                                </div>
                                <div class="col-sm-12">
                                    <!-- Alert success -->
                                    @if (session('status'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <strong>{{ session('status') }}</strong>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                    @endif

                                    @if (session('failed'))
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <strong>{{ session('failed') }}</strong>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                    @endif

                                    <!-- Validasi form -->
                                    @if (count($errors) > 0)
                                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        <ul>
                                            <li><strong>Data Process Failed !</strong></li>
                                            @foreach ($errors->all() as $error)
                                            <li><strong>{{ $error }}</strong></li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    @endif
                                    <!-- End validasi form -->
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <div class="row">
                                        <div class="mb-3 col-sm-12">
                                            <form action="{{ url('/audit/scan') }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                <div class="form-group mb-4">
                                                    <label for="assetSelect">Choose asset:</label>
                                                    <select name="asset[]" id="assetSelect" class="form-control" multiple="multiple" style="width: 100%">
                                                        <!-- Initial options will be loaded via AJAX -->
                                                    </select>
                                                </div>



                                                </div>
                                                <div class="modal-footer d-flex justify-content-center">
                                                    <button id="submitBtn" type="submit" class="btn btn-primary">Submit</button>
                                                </div>

                                            </form>

                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                            </div>

                            <!-- /.card -->

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
                                                        <tr class="audit-row" style="cursor:pointer;" data-audit="{{ $data->audit_no }}">
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
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
    </div>
</main>
<style>
    .audit-row:hover { background-color: #f0f4ff !important; cursor: pointer; }
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
        var resultContainer = document.getElementById('qr-reader-results');
        var inputField = document.getElementById('qr-value');
        var lastResult, countResults = 0;

        function onScanSuccess(decodedText, decodedResult) {
            if (decodedText !== lastResult) {
                console.log(`Decoded text: ${decodedText}`);
                lastResult = decodedText;
                inputField.value = decodedText;
            }
        }

        var html5QrcodeScanner = new Html5QrcodeScanner(
            "qr-reader", { fps: 10, qrbox: 250 });
        html5QrcodeScanner.render(onScanSuccess);
    });
</script>

<script>
    $(document).ready(function () {

        var table = $("#tableUser").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        });

        var assetCache = null;

        function buildAssetTable(assets) {
            const statusColor = { active: 'success', inactive: 'danger', disposed: 'secondary' };
            const rows = assets.map((a, i) => `
                <tr>
                    <td>${i + 1}</td>
                    <td><span class="badge bg-secondary">${a.asset_no ?? '-'}</span></td>
                    <td>${a.desc ?? '-'}</td>
                    <td>${a.qty ?? '-'}</td>
                    <td>${a.uom ?? '-'}</td>
                    <td>${a.asset_type ?? '-'}</td>
                    <td>${a.dept ?? '-'}</td>
                    <td>${a.loc ?? '-'}</td>
                    <td><span class="badge bg-${statusColor[String(a.status).toLowerCase()] ?? 'warning'}">${a.status ?? '-'}</span></td>
                </tr>
            `).join('');

            return `
                <div class="p-3 bg-light">
                    <h6 class="text-primary mb-2"><i class="fas fa-boxes me-1"></i> Asset List</h6>
                    <table class="table table-sm table-bordered mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th><th>Asset No</th><th>Description</th>
                                <th>Qty</th><th>UOM</th><th>Type</th>
                                <th>Dept</th><th>Location</th><th>Status</th>
                            </tr>
                        </thead>
                        <tbody>${rows}</tbody>
                    </table>
                </div>
            `;
        }

        function showChildRow(tr, row) {
            if (assetCache) {
                row.child(buildAssetTable(assetCache)).show();
                tr.addClass('shown');
            } else {
                row.child('<div class="p-3 text-muted"><i class="fas fa-spinner fa-spin me-1"></i> Loading assets...</div>').show();
                tr.addClass('shown');

                $.get('{{ url("audit/asset-list") }}', function (data) {
                    assetCache = data;
                    if (row.child.isShown()) {
                        row.child(buildAssetTable(data)).show();
                    }
                });
            }
        }

        $('#tableUser tbody').on('dblclick', 'tr.audit-row', function (e) {
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


<script>
    $(document).ready(function() {
        $('#assetSelect').select2({
            ajax: {
                url: '{{ route("assets.query") }}', // ✅ using route name
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        search: params.term,
                        page: params.page || 1
                    };
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data.items,
                        pagination: {
                            more: (params.page * 30) < data.total_count
                        }
                    };
                },
                cache: true
            },
            placeholder: 'Choose asset...',
            minimumInputLength: 1,
            multiple: true
        });
    });
    </script>





@endsection
