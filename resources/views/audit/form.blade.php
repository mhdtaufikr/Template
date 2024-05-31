@extends('layouts.master')

@section('content')
<main>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-xl px-4">
            <div class="page-header-content pt-4">
            </div>
        </div>
    </header>

    <form action="{{ url('/audit/store') }}" method="POST">
        @csrf

        <!-- Main page content-->
        <div class="container-fluid px-4 mt-n10">
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Main content -->
                <section class="content mb-4 mt-4">
                    <div style="margin-bottom: 100px" class="container-fluid">
                        <div class="card card-header-actions mb-4">
                            <div class="card-header text-dark">
                                <h3>Asset Detail</h3>
                                <!-- Button to open the modal -->
                                <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#signatureModal">Submit</button>
                            </div>

                            <!-- Modal for signature input -->
                            <div class="modal fade" id="signatureModal" tabindex="-1" aria-labelledby="signatureModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="signatureModalLabel">Input Signatures</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="auditSignature" class="form-label">Audit Signature</label>
                                                    <div class="border">
                                                        <canvas id="auditSignatureCanvas" style="width: 100%; height: 200px;"></canvas>
                                                    </div>
                                                    <input type="hidden" id="auditSignature" name="audit_signature">
                                                    <button type="button" class="btn btn-danger mt-2" id="clearAuditSignature">Clear</button>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="controllingSignature" class="form-label">Controlling Signature</label>
                                                    <div class="border">
                                                        <canvas id="controllingSignatureCanvas" style="width: 100%; height: 200px;"></canvas>
                                                    </div>
                                                    <input type="hidden" id="controllingSignature" name="controlling_signature">
                                                    <button type="button" class="btn btn-danger mt-2" id="clearControllingSignature">Clear</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-primary" id="saveSignatures">Save Signatures</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Include jQuery, Bootstrap JS, and Signature Pad -->
                            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
                            <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.min.js"></script>
                            <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
                            <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

                            <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    // Signature pads initialization
                                    const auditCanvas = document.getElementById('auditSignatureCanvas');
                                    const auditSignaturePad = new SignaturePad(auditCanvas);
                                    const auditSignatureInput = document.getElementById('auditSignature');

                                    const controllingCanvas = document.getElementById('controllingSignatureCanvas');
                                    const controllingSignaturePad = new SignaturePad(controllingCanvas);
                                    const controllingSignatureInput = document.getElementById('controllingSignature');

                                    // Clear buttons
                                    document.getElementById('clearAuditSignature').addEventListener('click', function () {
                                        auditSignaturePad.clear();
                                    });

                                    document.getElementById('clearControllingSignature').addEventListener('click', function () {
                                        controllingSignaturePad.clear();
                                    });

                                    // Save signature buttons
                                    document.getElementById('saveSignatures').addEventListener('click', function () {
                                        if (!auditSignaturePad.isEmpty() && !controllingSignaturePad.isEmpty()) {
                                            auditSignatureInput.value = auditSignaturePad.toDataURL('image/png');
                                            controllingSignatureInput.value = controllingSignaturePad.toDataURL('image/png');
                                            document.querySelector('form').submit();
                                        } else {
                                            alert('Please provide both signatures.');
                                        }
                                    });
                                });
                            </script>

                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs" id="assetTab" role="tablist">
                                @forelse ($data as $index => $item)
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link {{ $index == 0 ? 'active' : '' }}" id="asset-tab-{{ $index }}" data-bs-toggle="tab" href="#asset-{{ $index }}" role="tab" aria-controls="asset-{{ $index }}" aria-selected="{{ $index == 0 ? 'true' : 'false' }}">
                                            {{$item['assetHeaderData']->asset_no}}
                                        </a>
                                    </li>
                                @empty
                                    <li>No data available</li>
                                @endforelse
                            </ul>

                            <!-- Tab content -->
                            <div class="tab-content" id="assetTabContent">
                                @forelse ($data as $index => $item)
                                    <div class="tab-pane fade {{ $index == 0 ? 'show active' : '' }}" id="asset-{{ $index }}" role="tabpanel" aria-labelledby="asset-tab-{{ $index }}">
                                        <div class="card-body">
                                            <div class="col-sm-12">
                                                <!--alert success -->
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

                                                @if (count($errors)>0)
                                                <div class="alert alert-info alert-dismissible fade show" role="alert">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <ul>
                                                        <li><strong>Data Process Failed !</strong></li>
                                                        @foreach ($errors->all() as $error)
                                                        <li><strong>{{ $error }}</strong></li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                                @endif
                                            </div>

                                            <div style="text-align:right;">
                                                @php
                                                $statusColor = ($item['assetHeaderData']->status == 1) ? 'btn-success' : (($item['assetHeaderData']->status == 0) ? 'btn-warning' : 'btn-danger');
                                                $statusText = ($item['assetHeaderData']->status == 1) ? 'Active' : (($item['assetHeaderData']->status == 0) ? 'Deactive' : 'Disposal');
                                            @endphp
                                            <button class="btn btn-sm {{ $statusColor }}">
                                                {{ $statusText }}
                                            </button>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-4 text-center">
                                                    <div id="carouselExampleControls{{ $index }}" class="carousel slide" data-bs-ride="carousel">
                                                        <div class="carousel-inner">
                                                            @php
                                                                $imagePaths = $item['assetHeaderData']->img ? json_decode($item['assetHeaderData']->img) : [];
                                                            @endphp

                                                            @foreach($imagePaths as $key => $imagePath)
                                                            <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                                                <img src="{{ asset($imagePath) }}" class="d-block w-100" alt="Image {{ $key + 1 }}">
                                                            </div>
                                                            @endforeach
                                                        </div>
                                                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls{{ $index }}" data-bs-slide="prev">
                                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                            <span class="visually-hidden">Previous</span>
                                                        </button>
                                                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls{{ $index }}" data-bs-slide="next">
                                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                            <span class="visually-hidden">Next</span>
                                                        </button>
                                                    </div>

                                                    <h3 class="text-center">{{$item['assetHeaderData']->desc}}</h3>
                                                </div>

                                                <div class="col-md-2">
                                                    <strong>Asset No.</strong><br>
                                                    <p>{{$item['assetHeaderData']->asset_no}}</p>
                                                    <strong>Quantity</strong><br>
                                                    <p>{{$item['assetHeaderData']->qty}} ({{$item['assetHeaderData']->uom}} )</p>
                                                    <strong>Asset Category</strong><br>
                                                    <p>{{$item['assetHeaderData']->asset_type}}</p>
                                                    <strong>Acquisition Date</strong><br>
                                                    <p>{{ date('d-M-Y', strtotime($item['assetHeaderData']->acq_date)) }}</p>
                                                    <div class="col-md-4">
                                                        <strong>PO No.</strong><br>
                                                        <p>{{$item['assetHeaderData']->po_no}} </p>
                                                    </div>
                                                    <strong>Serial No. </strong><br>
                                                    <p>{{$item['assetHeaderData']->serial_no}}</p>
                                                </div>
                                                <div class="col-md-2">
                                                    <strong>Department</strong><br>
                                                    <p>{{$item['assetHeaderData']->dept}} </p>
                                                    <strong>Location</strong><br>
                                                    <p>{{$item['assetHeaderData']->plant}} ({{$item['assetHeaderData']->loc}})</p>
                                                    <strong>Cost Center</strong><br>
                                                    <p>{{$item['assetHeaderData']->cost_center}} </p>
                                                    <strong>Acquisition Cost</strong><br>
                                                    <p>{{ 'Rp ' . number_format($item['assetHeaderData']->acq_cost, 0, ',', '.') }}</p>
                                                    <strong>BV End Of Year {{ now()->year }}</strong><br>
                                                    <p>{{ 'Rp ' . number_format($item['assetHeaderData']->bv_endofyear, 0, ',', '.') }}</p>
                                                    <strong>Remarks</strong><br>
                                                    <p>Latest Update ({{date('d-M-Y', strtotime($item['assetHeaderData']->updated_at))}}) : {{ $item['assetHeaderData']->remarks }}</p>
                                                </div>
                                                <div class="col-md-4">
                                                    <strong>Condition</strong><br>
                                                    <select class="form-control" name="condition[{{$item['assetHeaderData']->asset_no}}][]" id="">
                                                        <option value="">-- Select Condition --</option>
                                                        <option value="Good">Good</option>
                                                        <option value="Not Good">Not Good</option>
                                                    </select>
                                                    <strong>Remarks</strong><br>
                                                    <textarea name="Remarks[{{$item['assetHeaderData']->asset_no}}][]" id="" cols="50" rows="5"></textarea>
                                                    <!-- Signature pad section --> <br>
                                                    <strong>User Signature</strong><br>
                                                    <canvas id="userSignatureCanvas-{{ $index }}" class="border" width="400" height="200"></canvas>
                                                    <input type="hidden" id="userSignature-{{ $index }}" name="user_signature[{{ $item['assetHeaderData']->asset_no }}]"><br>
                                                    <button type="button" class="btn btn-danger mt-2 clearSignature" data-canvas="userSignatureCanvas-{{ $index }}">Clear</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <p>No data available</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <!-- /.content-wrapper -->
        </div>

        <!-- For Datatables -->
        <script>
            $(document).ready(function () {
                if ($.fn.DataTable) {
                    var table = $("#tableUser").DataTable({
                        "responsive": false,
                        "lengthChange": false,
                        "autoWidth": false,
                        "order": [],
                        "dom": 'Bfrtip',
                        "buttons": []
                    });
                }
            });
        </script>

        <!-- Signature Pads Initialization -->
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const signaturePads = [];

                @foreach ($data as $index => $item)
                    (function(index) {
                        const canvasId = `userSignatureCanvas-${index}`;
                        const canvas = document.getElementById(canvasId);
                        const signaturePad = new SignaturePad(canvas);
                        signaturePads.push(signaturePad);

                        // Clear button functionality
                        document.querySelector(`button[data-canvas="${canvasId}"]`).addEventListener('click', function () {
                            signaturePad.clear();
                        });
                    })({{ $index }});
                @endforeach

                // Form submission handling
                document.querySelector('form').addEventListener('submit', function (event) {
                    signaturePads.forEach((signaturePad, index) => {
                        if (!signaturePad.isEmpty()) {
                            document.getElementById(`userSignature-${index}`).value = signaturePad.toDataURL('image/png');
                        }
                    });
                });
            });
        </script>
    </form>
</main>
@endsection
