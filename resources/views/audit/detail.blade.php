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

        <!-- Check if there is any data -->
        @forelse ($data as $index => $item)
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

                @if (count($errors) > 0)
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

            <div class="row mb-4 border-bottom pb-3">
                <!-- Carousel Section -->
                <div class="col-md-4 text-center">
                    <div id="carouselExampleControls{{ $index }}" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @php
                                $imagePaths = $item['assetHeaderData']->img ? json_decode($item['assetHeaderData']->img) : [];
                            @endphp
                            @foreach($imagePaths as $key => $imagePath)
                            <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                <img src="{{ asset($imagePath) }}" class="d-block w-100 rounded" alt="Image {{ $key + 1 }}">
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
                    <h3 class="text-center mt-2">{{$item['assetHeaderData']->desc}}</h3>
                </div>

                <!-- Asset Details Section -->
                @include('partials.asset-details', ['asset' => $item['assetHeaderData']])

                <!-- Input and Signature Section -->
                @include('partials.asset-inputs', ['asset' => $item['assetHeaderData'], 'index' => $index])
            </div>
        @empty
            <p>No data available</p>
        @endforelse

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
