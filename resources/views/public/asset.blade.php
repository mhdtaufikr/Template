<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>MKM Asset Management</title>
        <link href="{{asset('assets/css/styles.css')}}" rel="stylesheet" />
        <link rel="icon" href="{{ asset('assets/img/logo_kop2.gif') }}">
        <script data-search-pseudo-elements defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.28.0/feather.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>
        <script src={{ url("https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js") }}></script>
        <script src="{{ url('https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ url('https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap.min.js') }}"></script>
        <!-- DataTables -->
        <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
        <link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
        <link rel="stylesheet" href="{{asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
         <!-- DataTables  & Plugins -->
        <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
        <script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
        <script src="{{ asset('plugins/pdfmake/pdfmake.min.js') }}"></script>
        <script src="{{ asset('plugins/pdfmake/vfs_fonts.js') }}"></script>
        <script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
        <script src="{{ asset('plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
        <script src="{{ asset('plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
        <script src="{{ url('https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js') }}"></script>
        <script src="{{ url('https://cdn.datatables.net/datetime/1.1.1/js/dataTables.dateTime.min.js') }}"></script>
    
         <!-- Include cleave.js -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/cleave.js/1.6.0/cleave.min.js"></script>
    
    </head>
<body>
        
            <main>
                <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
                    <div class="container-xl px-4">
                        <div class="page-header-content pt-4">
                            {{-- <div class="row align-items-center justify-content-between">
                                <div class="col-auto mt-4">
                                    <h1 class="page-header-title">
                                        <div class="page-header-icon"><i data-feather="tool"></i></div>
                                        Dropdown App Menu
                                    </h1>
                                    <div class="page-header-subtitle">Use this blank page as a starting point for creating new pages inside your project!</div>
                                </div>
                                <div class="col-12 col-xl-auto mt-4">Optional page header content</div>
                            </div> --}}
                        </div>
                    </div>
                </header>
            <!-- Main page content-->
            <div class="container-xl px-4 mt-n10">       
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                {{-- <div class="container-fluid">
                    <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>    </h1>
                    </div>
                    </div>
                </div><!-- /.container-fluid --> --}}
                </section>
            
                <!-- Main content -->
                <section class="content">
            
                <div class="container-fluid">
            
                    <div class="card card-header-actions mb-4">
                        <div class="card-header text-dark">
                        <h3>Asset Detail</h3>
                        @php
                        $statusColor = ($assetHeaderData->status == 1) ? 'btn-success' : (($assetHeaderData->status == 0) ? 'btn-warning' : 'btn-danger');
                        $statusText = ($assetHeaderData->status == 1) ? 'Active' : (($assetHeaderData->status == 0) ? 'Deactive' : 'Disposal');
                        @endphp

                    <button class="btn btn-sm {{ $statusColor }}" >
                        {{ $statusText }}
                    </button>
            
                        <!-- Modal for Remarks -->
                        <div class="modal fade" id="remarksModal" tabindex="-1" aria-labelledby="remarksModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="remarksModalLabel">Enter Remarks</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="remarksForm" method="POST">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="remark" class="form-label">Remark</label>
                                                <textarea class="form-control" id="remark" name="remark" rows="3" required></textarea>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary" onclick="submitRemarksForm()">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <script>
                            function openRemarksModal(url) {
                                $('#remarksModal').modal('show');
                                // Set the form action to the specified URL
                                $('#remarksForm').attr('action', url);
                            }
                        
                            function submitRemarksForm() {
                                // Validate and submit the form
                                if ($('#remarksForm')[0].checkValidity()) {
                                    $('#remarksForm').submit();
                                }
                            }
                        </script>
                        
                        <!-- Modal -->
                        <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Image Preview</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Image container -->
                                        <img id="modalImage" class="img-fluid" alt="Image Preview">
                                    </div>
                                </div>
                            </div>
                        </div>
            
                        <!-- JavaScript to set image source when the modal is shown -->
                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                var imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
                                var modalImage = document.getElementById('modalImage');
                                var seeImageButton = document.querySelector('.btn-see-image');
                                var imgPath = '{{ asset($assetHeaderData->img) }}';
            
                                // Set the image source dynamically when the modal is shown
                                imageModal._element.addEventListener('shown.bs.modal', function (event) {
                                    // Set the image source dynamically using the asset helper
                                    modalImage.src = imgPath;
                                });
            
                                
                            });
                        </script>
                    </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>Asset No.</strong><br>
                                    <p>{{$assetHeaderData->asset_no}} <br> <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#imageModal">
                                        See Image
                                    </button></p>
                                </div>
                                <div class="col-md-4">
                                    <strong>Description</strong><br>
                                    <p>{{$assetHeaderData->desc}}</p>
                                </div>
                                <div class="col-md-4">
                                    <strong>Quantity</strong><br>
                                    <p>{{$assetHeaderData->qty}} ({{$assetHeaderData->uom}} )</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>Asset Category</strong><br>
                                    <p>{{$assetHeaderData->asset_type}}</p>
                                </div>
                                <div class="col-md-4">
                                    <strong>Acquisition Date</strong><br>
                                    <p>{{ date('d-M-Y', strtotime($assetHeaderData->acq_date)) }}</p>
                                </div>                    
                                <div class="col-md-4">
                                    <strong>Acquisition Cost</strong><br>
                                    <p>{{ 'Rp ' . number_format($assetHeaderData->acq_cost, 0, ',', '.') }}</p>
                                </div>                    
                            </div>
            
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>PO No.</strong><br>
                                    <p>{{$assetHeaderData->po_no}} </p>
                                </div>
                                <div class="col-md-4">
                                    <strong>Serial No. </strong><br>
                                    <p>{{$assetHeaderData->serial_no}}</p>
                                </div>
                                <div class="col-md-4">
                                    <strong>Department</strong><br>
                                    <p>{{$assetHeaderData->dept}} </p>
                                </div>
                            </div>
            
            
                            <div class="row">
                                <div class="col-md-4">
                                    <strong>Location</strong><br>
                                    <p>{{$assetHeaderData->plant}} ({{$assetHeaderData->loc}})</p>
                                </div>
                                <div class="col-md-4">
                                    <strong>Cost Center</strong><br>
                                    <p>{{$assetHeaderData->cost_center}} </p>
                                </div>
                                <div class="col-md-4">
                                    <strong>BV End Of Year</strong><br>
                                    <p>{{ 'Rp ' . number_format($assetHeaderData->bv_endofyear, 0, ',', '.') }}</p>
                                </div>                    
                            </div>
            
                        </div>
                    </div>
            
            
                    <div class="row">
                    <div class="col-12">
                        <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Bill of Materials</h3>
                        </div>
                        
                        
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="mb-3 col-sm-12">
                            </div>
                            <div class="table-responsive"> 
                            <table id="tableUser" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Asset No</th>
                                <th>Description</th>
                                <th>Qty</th>
                                <th>Acquisition date</th>
                                <th>Status</th>
                                <th>Action</th>
                            
                            </tr>
                            </thead>
                            <tbody>
                                @foreach ($assetDetailData as $data)
                                <tr>
                                    <td>{{ $data->asset_no}} - {{$data->sub_asset}} </td>
                                    <td>{{ $data->desc}} </td>
                                    <td>{{ $data->qty }} ({{ $data->uom }})</td>
                                    <td>{{ date('d-M-Y', strtotime($data->date )) }}</td>
                                    <td> <!-- Button for Active/Disposal status -->
                                        @php
                                        $statusColor = ($assetHeaderData->status == 1) ? 'btn-success' : (($assetHeaderData->status == 0) ? 'btn-warning' : 'btn-danger');
                                        $statusText = ($assetHeaderData->status == 1) ? 'Active' : (($assetHeaderData->status == 0) ? 'Deactive' : 'Disposal');
                                        @endphp
                    
                                    <button class="btn btn-sm {{ $statusColor }}" >
                                    {{ $statusText }}
                                    </button>
                                    </td>
                                    <td>
                                        <button title="Detail Sub Asset" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modal-detail{{ $data->id }}">
                                            <i class="fas fa-info"></i>
                                        </button> 
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            </table>
                        </div>
                        </div>
                        <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
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

            @foreach ($assetDetailData as $data)
            <!-- Modal -->
            <div class="modal fade" id="modal-detail{{ $data->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Asset Detail</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Asset Number</strong><br>
                                    <p>{{ $data->asset_no }} - {{ $data->sub_asset }}</p>
                                </div>
                                <div class="col-md-6">
                                    <strong>Description</strong><br>
                                    <p>{{ $data->desc }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Quantity</strong><br>
                                    <p>{{ $data->qty }} ({{$data->uom}})</p>
                                </div>
                                <div class="col-md-6">
                                    <strong>Asset Category</strong><br>
                                    <p>{{ $data->asset_type }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Acquisition Date</strong><br>
                                    <p>{{ date('d-M-Y', strtotime($data->date)) }}</p>
                                </div>
                                <div class="col-md-6">
                                    <strong>Acquisition Cost</strong><br>
                                    <p>{{ 'Rp ' . number_format($data->cost, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>PO No.</strong><br>
                                    <p>{{ $data->po_no }}</p>
                                </div>
                                <div class="col-md-6">
                                    <strong>Serial No.</strong><br>
                                    <p>{{ $data->serial_no }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Status</strong><br>
                                    @php
                                    $statusColor = ($data->status == 1) ? 'btn-success' : (($data->status == 0) ? 'btn-warning' : 'btn-danger');
                                    $statusText = ($data->status == 1) ? 'Active' : (($data->status == 0) ? 'Deactive' : 'Disposal');
                                    @endphp
            
                                <button class="btn btn-sm {{ $statusColor }}" >
                                    {{ $statusText }}
                                </button>
                                </div>
                                <div class="col-md-6">
                                    <strong>BV End Of Year</strong><br>
                                    <p>{{ 'Rp ' . number_format($assetHeaderData->bv_endofyear, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            <!-- Add more fields as needed -->
                            <div class="row">
                                <div class="col-md-12">
                                    <strong>Image</strong><br>
                                    <img src="{{ asset($data->img) }}" alt="Asset Image" class="img-fluid">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
            <script src={{asset('assets/js/scripts.js')}} ></script>
        </main>

<script>
    $(document).ready(function() {
      var table = $("#tableUser").DataTable({
        "responsive": true, 
        "lengthChange": false, 
        "autoWidth": false,
        // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
      });
    });
  </script>
</body>
</html>

