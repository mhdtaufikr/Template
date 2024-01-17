@extends('layouts.master')

@section('content')
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
                    <!-- Button to trigger the modal -->
                    @php
                    $statusColor = ($assetHeaderData->status == 1) ? 'btn-success' : (($assetHeaderData->status == 0) ? 'btn-warning' : 'btn-danger');
                    $statusText = ($assetHeaderData->status == 1) ? 'Active' : (($assetHeaderData->status == 0) ? 'Deactive' : 'Disposal');
                    @endphp

                <button class="btn btn-sm {{ $statusColor }}" onclick="openRemarksModal('{{ url("/asset/status/".encrypt($assetHeaderData->id)) }}', {{ $assetHeaderData->status }})">
                   {{ $statusText }}
                </button>
                @if(\Auth::user()->role === 'Super Admin')
                    <!-- Modal for Remarks -->
                    <div class="modal fade" id="remarksModal" tabindex="-1" aria-labelledby="remarksModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="remarksModalLabel">Update Status and Enter Remarks</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="remarksForm" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="status" class="form-label">Status</label>
                                            <select class="form-select" id="status" name="status" required>
                                                @foreach ($status as $item)
                                                <option value="{{$item->code_format}}">{{$item->name_value}}</option>
                                                @endforeach
                                            </select>
                                        </div>
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
                        @endif
                    <script>
                        function openRemarksModal(url, status) {
                            $('#remarksModal').modal('show');
                            // Set the form action to the specified URL
                            $('#remarksForm').attr('action', url);
                            // Set the initial value for the status dropdown
                            $('#status').val(status);
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

                        // Handle button click to show the modal
                        seeImageButton.addEventListener('click', function () {
                            imageModal.show();
                        });
                    });
                </script>

                </div>
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
                    
                        <!--alert success -->
                        <!--validasi form-->
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
                        <!--end validasi form-->
                    </div>

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
        </div>

        <div class="container-fluid">
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
                                @if(\Auth::user()->role === 'Super Admin')
                                <button  title="Add Asset" type="button" class="btn btn-dark btn-sm mb-2" data-bs-toggle="modal" data-bs-target="#modal-add">
                                    <i class="fas fa-plus-square"></i> 
                                </button>
                                <button  title="Import Asset" type="button" class="btn btn-info btn-sm mb-2" data-bs-toggle="modal" data-bs-target="#modal-import">
                                    Import Assets 
                                  </button>
                                  @endif
                                    <!-- Modal -->
                                <div class="modal fade" id="modal-import" tabindex="-1" aria-labelledby="modal-add-label" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modal-add-label">Import Asset</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ url('/asset/detail/import/'.$assetHeaderData->id) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <input type="file" class="form-control" id="csvFile" name="excel-file" accept=".csv">
                                                        <p class="text-danger">*file must be xlsx</p>
                                                    </div>

                                                    @error('excel-file')
                                                        <div class="alert alert-danger" role="alert">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                                <div class="modal-footer">
                                                    <a href="{{ url('/download/excel/format/detail') }}" class="btn btn-link">
                                                        Download Excel Format
                                                    </a>
                                                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Modal -->
                                <div class="modal fade" id="modal-add" tabindex="-1" aria-labelledby="modal-add-label" aria-hidden="true">
                                    <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                        <h5 class="modal-title" id="modal-add-label">Add Asset</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ url('/asset/detail/store') }}" method="POST" enctype="multipart/form-data">
                                        @csrf

                                        <input value="{{$assetHeaderData->id}}" type="text" name="id" id="" hidden>

                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <!-- Add a checkbox -->
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="editAssetNo">
                                                        <label class="form-check-label small" for="editAssetNo">Edit Asset Number</label>
                                                    </div>
                                                </div>
                                            
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <input readonly value="{{$assetHeaderData->asset_no}}" type="text" class="form-control" id="asset_no" name="asset_no" placeholder="Enter Asset Number" required>
                                                    </div>
                                                </div>
                                            
                                                <div class="col-md-3">
                                                    <div class="form-group mb-3">
                                                        <input type="number" class="form-control" id="sub_asset" name="sub_asset" placeholder="Enter Sub" min="0" required>
                                                    </div>
                                                </div>
                                            </div>  
                                            <script>
                                                document.addEventListener('DOMContentLoaded', function () {
                                                    // Get the checkbox and input field
                                                    var editCheckbox = document.getElementById('editAssetNo');
                                                    var assetNoInput = document.getElementById('asset_no');
                                            
                                                    // Add an onclick event to the checkbox
                                                    editCheckbox.addEventListener('click', function () {
                                                        // Toggle the "readonly" attribute based on the checkbox state
                                                        assetNoInput.readOnly = !editCheckbox.checked;
                                                    });
                                                });
                                            </script>
                                            
                                            
                                            <div class="form-group mb-3">
                                                <textarea class="form-control" id="desc" name="desc" placeholder="Enter Asset Description" required></textarea>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <input type="number" class="form-control" id="qty" name="qty" placeholder="Enter Qty" required min="0">
                                                </div>
                                                
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <select name="uom" id="uom" class="form-control" required>
                                                            <option value="">- Please Select UOM -</option>
                                                            @foreach ($dropdownUom as $uom)
                                                                <option value="{{ $uom->name_value }}">{{ $uom->name_value }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-3">

                                                <div class="col-md-6">
                                                    <input type="date" class="form-control" id="date" name="date" placeholder="Enter Date" required>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <select name="asset_type" id="asset_type" class="form-control" required>
                                                            <option value="">- Please Select Asset Category -</option>
                                                            @foreach ($assetCategory as $data)
                                                                <option value="{{ $data->class }}">{{ $data->class }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                            </div>

                                            <div class="form-group mb-3">
                                                <input type="text" class="form-control" id="cost" name="cost" placeholder="Enter Acquisition Cost" required>
                                            </div>

                                            <script>
                                                document.addEventListener('DOMContentLoaded', function () {
                                                    // Format input as Rupiah
                                                    var rupiahInput = new Cleave('#cost', {
                                                        numeral: true,
                                                        numeralThousandsGroupStyle: 'thousand'
                                                        // You can customize other options based on your needs
                                                    });
                                                });
                                            </script>

                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control" id="po_no" name="po_no" placeholder="Enter PO No." >
                                                </div>

                                                <div class="col-md-6">
                                                    <input type="text" class="form-control" id="serial_no" name="serial_no" placeholder="Enter Serial No." >
                                                </div>
                                            </div>

                                            {{-- <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <select name="plant" id="plant" class="form-control" required>
                                                            <option value="">- Please Select Plant -</option>
                                                            @foreach ($locHeader as $data)
                                                                <option value="{{ $data->name }}">{{ $data->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <select name="loc" id="loc" class="form-control" required>
                                                            <option value="">- Please Select Location -</option>
                                                            <!-- Location options will be dynamically populated using JavaScript -->
                                                        </select>
                                                    </div>
                                                </div>
                                            </div> --}}
                                            
                                            {{-- <script>
                                                document.addEventListener('DOMContentLoaded', function () {
                                                    var plantDropdown = document.getElementById('plant');
                                                    var locDropdown = document.getElementById('loc');
                                            
                                                    plantDropdown.addEventListener('change', function () {
                                                        locDropdown.innerHTML = '<option value="">- Please Select Location -</option>';
                                                        var selectedPlant = plantDropdown.value;
                                            
                                                        var locDetails = [
                                                            @foreach ($locDetail as $detail)
                                                                {
                                                                    plant: '{{ $detail->locHeader->name }}',
                                                                    location: '{{ $detail->name }}'
                                                                },
                                                            @endforeach
                                                        ];
                                            
                                                        locDetails.forEach(function (detail) {
                                                            if (detail.plant === selectedPlant) {
                                                                var option = document.createElement('option');
                                                                option.value = detail.location;
                                                                option.textContent = detail.location;
                                                                locDropdown.appendChild(option);
                                                            }
                                                        });
                                                    });
                                                });
                                            </script> --}}
                                            
                                            

                                            {{-- <div class="form-group mb-3">
                                                <select name="dept" id="dept" class="form-control" required>
                                                    <option value="">- Please Department-</option>
                                                    @foreach ($dept as $data)
                                                        <option value="{{ $data->dept }}">{{ $data->dept }}</option>
                                                    @endforeach
                                                </select>
                                            </div> --}}
                                            
                                                    <div class="form-group mb-3">
                                                        <input type="file" class="form-control" id="img" name="img" placeholder="Enter Image" required>
                                                    </div>
                                        
                                            <div class="form-group mb-3">
                                                <input type="text" class="form-control" id="bv_end" name="bv_end" placeholder="Enter BV End Of Year" required>
                                            </div>                                    

                                            <script>
                                                document.addEventListener('DOMContentLoaded', function () {
                                                    // Format input as Rupiah
                                                    var bvEndInput = new Cleave('#bv_end', {
                                                        numeral: true,
                                                        numeralThousandsGroupStyle: 'thousand'
                                                        // You can customize other options based on your needs
                                                    });
                                                });
                                            </script>
                                            
                                            
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                        </form>
                                    </div>
                                    </div>
                                </div>
                                

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
                            
                            <!--alert success -->
                            <!--validasi form-->
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
                            <!--end validasi form-->
                            </div>
                        </div>
                        <div class="table-responsive"> 
                        <table id="tableUser" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Asset No</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            @php
                            $no=1;
                            @endphp
                            @foreach ($assetDetailData as $data)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $data->asset_no}} - {{$data->sub_asset}} </td>
                                <td>{{ date('d-M-Y', strtotime($data->date )) }}</td>
                                <td>  
                                    <!-- Button for status -->
                                    <button class="btn btn-sm {{ ($data->status == 1) ? 'btn-success' : (($data->status == 0) ? 'btn-warning' : 'btn-danger') }}"
                                            onclick="openRemarksModalDetail('{{$data->id}}', '{{ url("/asset/status/detail/".encrypt($assetHeaderData->id).'/'.encrypt($data->id)) }}', {{ $data->status }})">
                                        {{ ($data->status == 1) ? 'Active' : (($data->status == 0) ? 'Deactive' : 'Disposal') }}
                                    </button>
                                </td>
                                <td>
                                    @if(\Auth::user()->role === 'Super Admin')
                                    <button title="Edit Asset" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-update{{ $data->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    @endif
                                    <button title="Detail Sub Asset" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modal-detail{{ $data->id }}">
                                        <i class="fas fa-info"></i>
                                    </button>
                                    @if(\Auth::user()->role === 'Super Admin')
                                    <button title="Delete Asset" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modal-delete{{ $data->id }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>  
                                    @endif 
                                </td>
                            </tr>
                            @endforeach

                            @foreach ($assetDetailData as $data)
                            @if(\Auth::user()->role === 'Super Admin')
                            <!-- Modal for Remarks (Detail) -->
                            <div class="modal fade" id="remarksModalDetail{{$data->id}}" tabindex="-1" aria-labelledby="remarksModalDetailLabel{{$data->id}}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="remarksModalLabel">Update Status and Enter Remarks</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form id="remarksFormDetail{{$data->id}}" method="POST">
                                                @csrf
                                                <div class="mb-3">
                                                    <label for="statusDetail{{$data->id}}" class="form-label">Status</label>
                                                    <select class="form-select" id="statusDetail{{$data->id}}" name="status" required>
                                                        <option value="1" {{ ($data->status == 1) ? 'selected' : '' }}>Active</option>
                                                        <option value="0" {{ ($data->status == 0) ? 'selected' : '' }}>Deactive</option>
                                                        <option value="2" {{ ($data->status == 2) ? 'selected' : '' }}>Disposal</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="remarkDetail{{$data->id}}" class="form-label">Remark</label>
                                                    <textarea class="form-control" id="remarkDetail{{$data->id}}" name="remark" rows="3" required></textarea>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-primary" onclick="submitFormDetail('{{$data->id}}')">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        @endforeach

                        <script>
                            function openRemarksModalDetail(id, url, status) {
                                $('#remarksModalDetail' + id).modal('show');
                                // Set the form action to the specified URL
                                $('#remarksFormDetail' + id).attr('action', url);
                                // Set the initial value for the status dropdown
                                $('#statusDetail' + id).val(status);
                            }

                            function submitFormDetail(id) {
                                // Validate and submit the form
                                if ($('#remarksFormDetail' + id)[0].checkValidity()) {
                                    $('#remarksFormDetail' + id).submit();
                                }
                            }
                        </script>

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
                                                    @if($data->status == 1)
                                                        <!-- Button for active status -->
                                                        <a class="btn btn-success btn-sm" href="#">
                                                            Active
                                                        </a>
                                                    @else
                                                        <!-- Button for disposal status -->
                                                        <a class="btn btn-danger btn-sm" href="#">
                                                            <i class="fa-solid fa-x"></i> Disposal
                                                        </a>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <strong>BV End Of Year</strong><br>
                                                    <p>{{ 'Rp ' . number_format($assetHeaderData->bv_endofyear, 0, ',', '.') }}</p>
                                                </div>
                                            </div>
                                            <!-- Add more fields as needed -->

                                            <!-- Display the image -->
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
                            {{-- Modal Update --}}
                            <div class="modal fade" id="modal-update{{ $data->id }}" tabindex="-1" aria-labelledby="modal-update{{ $data->id }}-label" aria-hidden="true">
                                <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h4 class="modal-title" id="modal-update{{ $data->id }}-label">Edit Cost Center</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ url('/asset/detail/update/'.$data->id) }}" method="POST"  enctype="multipart/form-data">
                                    @csrf
                                    @method('patch')
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="form-group mb-3">
                                                    <input readonly value="{{$assetHeaderData->asset_no}}" type="text" class="form-control" id="asset_no" name="asset_no" placeholder="Enter Asset Number" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3">
                                                    <input value="{{$data->sub_asset}}" type="number" class="form-control" id="sub_asset" name="sub_asset" placeholder="Enter Sub" min="0" required>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group mb-3">
                                            <textarea class="form-control" id="desc" name="desc" placeholder="Enter Asset Description" required>{{$data->desc}}</textarea>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <input value="{{$data->qty}}" type="number" class="form-control" id="qty" name="qty" placeholder="Enter Qty" required min="0">
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <select name="uom" id="uom" class="form-control" required>
                                                        <option value="{{$data->uom}}">{{$data->uom}}</option>
                                                        @foreach ($dropdownUom as $uom)
                                                            <option value="{{ $uom->name_value }}">{{ $uom->name_value }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3">

                                            <div class="col-md-6">
                                                <input value="{{$data->date}}" type="date" class="form-control" id="date" name="date" placeholder="Enter Date" required>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <select name="asset_type" id="asset_type" class="form-control" required>
                                                        <option value="{{$data->asset_type}}">{{$data->asset_type}}</option>
                                                        @foreach ($assetCategory as $item)
                                                            <option value="{{ $item->class }}">{{ $item->class }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            
                                        </div>

                                        <div class="form-group mb-3">
                                            <input value="{{ number_format($data->cost) }}" type="text" class="form-control" id="costEdit" name="costEdit" placeholder="Enter Acquisition Cost" required>
                                        </div>

                                        <script>
                                            document.addEventListener('DOMContentLoaded', function () {
                                                // Format input as Rupiah
                                                var rupiahInput = new Cleave('#costEdit', {
                                                    numeral: true,
                                                    numeralThousandsGroupStyle: 'thousand'
                                                    // You can customize other options based on your needs
                                                });
                                            });
                                        </script>

                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <input value="{{$data->po_no}}" type="text" class="form-control" id="po_no" name="po_no" placeholder="Enter PO No." >
                                            </div>

                                            <div class="col-md-6">
                                                <input value="{{$data->serial_no}}" type="text" class="form-control" id="serial_no" name="serial_no" placeholder="Enter Serial No." >
                                            </div>
                                        </div>
                                        
                                            <div class="form-group mb-3">
                                                <input type="file" class="form-control" id="img" name="img" placeholder="Enter Image">
                                            </div>
                                    
                                        <div class="form-group mb-3">
                                            <input value="{{number_format($data->bv_endofyear)}}" type="text" class="form-control" id="bv_endEdit" name="bv_endEdit" placeholder="Enter BV End Of Year" required>
                                        </div>                                    

                                        <script>
                                            document.addEventListener('DOMContentLoaded', function () {
                                                // Format input as Rupiah
                                                var bvEndInput = new Cleave('#bv_endEdit', {
                                                    numeral: true,
                                                    numeralThousandsGroupStyle: 'thousand'
                                                    // You can customize other options based on your needs
                                                });
                                            });
                                        </script>
                                        
                                        
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </div>
                                    </form>
                                </div>
                                </div>
                            </div>
                            {{-- Modal Update --}}

                            {{-- Modal Delete --}}
                            <div class="modal fade" id="modal-delete{{ $data->id }}" tabindex="-1" aria-labelledby="modal-delete{{ $data->id }}-label" aria-hidden="true">
                                <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h4 class="modal-title" id="modal-delete{{ $data->id }}-label">Delete Cost Center</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ url('/asset/detail/delete/'.$data->id) }}" method="POST">
                                    @csrf
                                    @method('delete')
                                    <div class="modal-body">
                                        <div class="form-group">
                                        Are you sure you want to delete <label >{{ $data->asset_no }} - {{$data->sub_asset}}</label>?
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                    </form>
                                </div>
                                </div>
                            </div>
                            {{-- Modal Delete --}}
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
                            "buttons": [{
                                title: 'Asset Management',
                                text: '<i class="fas fa-file-excel"></i> Export to Excel',
                                extend: 'excel',
                                className: 'btn btn-success btn-sm mb-2'
                            }]
                        });
                    });
    </script>
@endsection