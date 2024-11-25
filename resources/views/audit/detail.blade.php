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
            <!-- Main content -->
            <section class="content mb-4 mt-4">
                <div class="container-fluid" style="margin-bottom: 100px;">
                    <div class="card card-header-actions mb-4">
                        <div class="card-header text-dark">
                            <h3>Asset Detail</h3>
                        </div>

                        <!-- Include jQuery, Bootstrap JS, and Signature Pad -->
                        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
                        <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.min.js"></script>
                        <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>

                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                const canvas = document.getElementById('signatureCanvas');
                                const signaturePad = new SignaturePad(canvas);
                                const signatureInput = document.getElementById('signature');

                                // Clear button
                                document.getElementById('clearSignature').addEventListener('click', function () {
                                    signaturePad.clear();
                                });

                                // Save signature button
                                document.getElementById('saveSignature').addEventListener('click', function () {
                                    if (!signaturePad.isEmpty()) {
                                        signatureInput.value = signaturePad.toDataURL('image/png');
                                        document.querySelector('form').submit();
                                    } else {
                                        alert('Please provide a signature first.');
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
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-md-6 text-center">
                                                        @if($item['assetDetailData']->img)
                                                            @php
                                                                // Check if the `img` field contains a JSON array of images
                                                                $images = is_string($item['assetDetailData']->img) && is_array(json_decode($item['assetDetailData']->img, true))
                                                                    ? json_decode($item['assetDetailData']->img, true)
                                                                    : [$item['assetDetailData']->img]; // Treat as single image if not JSON
                                                            @endphp

                                                            @if(count($images) > 1)
                                                                <!-- Carousel for multiple images -->
                                                                <div id="carousel-{{ $item['assetDetailData']->id }}" class="carousel slide" data-bs-ride="carousel">
                                                                    <div class="carousel-inner">
                                                                        @foreach($images as $index => $image)
                                                                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                                                                <img src="{{ asset($image) }}" class="d-block w-100 img-fluid" alt="Asset Image" style="max-width: 400px; max-height: 300px; margin: auto;">
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                    <button class="carousel-control-prev" type="button" data-bs-target="#carousel-{{ $item['assetDetailData']->id }}" data-bs-slide="prev">
                                                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                                        <span class="visually-hidden">Previous</span>
                                                                    </button>
                                                                    <button class="carousel-control-next" type="button" data-bs-target="#carousel-{{ $item['assetDetailData']->id }}" data-bs-slide="next">
                                                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                                        <span class="visually-hidden">Next</span>
                                                                    </button>
                                                                </div>
                                                            @else
                                                                <!-- Single image -->
                                                                <img src="{{ asset($images[0]) }}" alt="Asset Image" class="img-fluid" style="width: 400px; height: 300px;">
                                                            @endif
                                                        @else
                                                            <p>No image available</p>
                                                        @endif
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <strong>Availability</strong><br>
                                                                <select readonly class="form-control" name="condition[{{$item['assetHeaderData']->asset_no}}][]" id="">
                                                                    <option value="">{{$item['assetDetailData']->availability}}</option>
                                                                </select>
                                                                <strong>Remarks</strong><br>
                                                                <textarea readonly name="Remarks[{{$item['assetHeaderData']->asset_no}}][]" id="" cols="30" rows="10">{{$item['assetDetailData']->remark}}</textarea>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <strong>Condition</strong><br>
                                                                <select readonly class="form-control" name="condition[{{$item['assetHeaderData']->asset_no}}][]" id="">
                                                                    <option value="">{{$item['assetDetailData']->condition}}</option>
                                                                </select>
                                                                <!-- Include the signature inside the tab content -->
                                                                <div class="mb-3">
                                                                    <label for="signature-{{ $index }}" class="form-label">Signature</label>
                                                                    <img src="{{ asset($item['assetDetailData']->signature) }}" alt="Signature" class="img-fluid" style="width: 150px; height: 100px;">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 text-center">
                                                <div id="carouselExampleControls{{ $index }}" class="carousel slide" data-bs-ride="carousel">
                                                    <div class="carousel-inner">
                                                        @php
                                                            $imagePaths = $item['assetHeaderData']->img ? json_decode($item['assetHeaderData']->img) : [];
                                                        @endphp

                                                        @foreach($imagePaths as $key => $imagePath)
                                                        <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                                            <img src="{{ asset($imagePath) }}" class="d-block w-100" alt="Image {{ $key + 1 }}" style="width: 300px; height: 200px;">
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
                                            <div class="col-md-3">
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
                                            <div class="col-md-3">
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
</main>

<!-- For Datatables -->
<script>
    $(document).ready(function () {
        var table = $("#tableUser").DataTable({
            "responsive": false,
            "lengthChange": false,
            "autoWidth": false,
            "order": [],
            "dom": 'Bfrtip',
            "buttons": []
        });
    });
</script>
@endsection
