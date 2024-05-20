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
<div class="container-fluid px-4 mt-n10">
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
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">List of Asset</h3>
              </div>

              <!-- /.card-header -->
              <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-4">
                    <!-- Button to trigger the search modal -->


                <!-- Search Modal -->
                <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div style="margin-bottom: -30px" class="modal-header">
                                <h5 class="modal-title" id="searchModalLabel">Search Assets</h5>
                                <button type="button" class="btn" data-bs-dismiss="modal">X</button>
                            </div>
                            <div class="modal-body">
                                <form id="searchForm" action="{{ url('/asset/search/multiple') }}" method="GET">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <div  id="assetNoInput" class="form-group mt-4">
                                                    <label style="margin-top:-80px" for="assetNo" class="form-label">Asset No</label>
                                                    <select name="assetNo[]" id="assetNo" class="form-control chosen-select" multiple data-placeholder="Select Asset Numbers...">
                                                        @foreach ($assetNo as $number)
                                                            <option value="{{ $number }}">{{ $number }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="location" class="form-label">Plant</label>
                                                <select name="plant" id="plant" class="form-control" >
                                                    <option value="">- Please Select Plant -</option>
                                                    @foreach ($locHeader as $data)
                                                        <option value="{{ $data->name }}">{{ $data->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="location" class="form-label">Location</label>
                                                <select name="loc" id="loc" class="form-control" >
                                                    <option value="">- Please Select Location -</option>
                                                    <!-- Location options will be dynamically populated using JavaScript -->
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="assetCategory" class="form-label">Asset Category</label>
                                                <select class="form-select" id="assetCategory" name="assetCategory">
                                                    <option value="">Select Asset Category</option>
                                                    @foreach ($assetCategory as $category)
                                                        <option value="{{ $category->desc }}">{{ $category->desc }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="department" class="form-label">Department</label>
                                                <select class="form-select" id="department" name="department">
                                                    <option value="">Select Department</option>
                                                    @foreach ($dept as $item)
                                                        <option value="{{$item->dept}}">{{$item->dept}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="startDate" class="form-label">Start Date</label>
                                                <input type="date" class="form-control" id="startDate" name="startDate">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="endDate" class="form-label">End Date</label>
                                                <input type="date" class="form-control" id="endDate" name="endDate">
                                            </div>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Search</button>
                                    <!-- Close Button -->
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                        {{-- <form action="{{url('/asset/search')}}" method="GET">
                            <div class="input-group input-group-sm">
                                <select class="form-control" name="searchBy" id="searchByModal" onchange="toggleSearchInputsModal()">
                                    <option value="">Search by</option>
                                    <option value="assetNo">Asset No</option>
                                    <option value="destination">Location</option>
                                    <option value="department">Department</option>
                                    <option value="dateRange">Date Range</option>
                                    <option value="assetCategory">Asset Category</option> <!-- New option -->
                                </select>

                                <input name="assetNo" type="text" class="form-control" id="searchAssetNoModal" placeholder="Enter Asset No" style="display: none;">

                                <select name="destination" class="form-control" id="searchDestinationModal" style="display: none;">
                                    <option value="">Select Plant</option>
                                    @foreach ($locHeader as $item)
                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                    @endforeach
                                </select>

                                <select name="department" class="form-control" id="searchdepartmentModal" style="display: none;">
                                    <option value="">Select Department</option>
                                    @foreach ($dept as $item)
                                        <option value="{{$item->dept}}">{{$item->dept}}</option>
                                    @endforeach
                                </select>

                                <select name="location" class="form-control" id="searchlocationModal" style="display: none;">
                                    <option value="">Select Location</option>
                                    <!-- Options will be dynamically populated using jQuery -->
                                </select>

                                <input name="startDate" type="date" class="form-control" id="startDateModal" style="display: none;">
                                <input name="endDate" type="date" class="form-control" id="endDateModal" style="display: none;">

                                <select name="assetCategory" class="form-control" id="searchAssetCategoryModal" style="display: none;">
                                    <option value="">Select Asset Category</option>
                                    @foreach ($assetCategory as $category)
                                        <option value="{{ $category->desc }}">{{ $category->desc }}</option>
                                    @endforeach
                                </select>

                                <button class="btn btn-dark btn-sm" type="submit">Search</button>
                            </div>

                            <div id="assetNoInput" class="form-group mt-4" style="display: none;">
                                <select name="assetNo[]" id="assetNo" class="form-control chosen-select" multiple data-placeholder="Select Asset Numbers...">
                                    @foreach ($assetNo as $number)
                                        <option value="{{ $number }}">{{ $number }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Add more form fields as needed -->
                        </form> --}}
                    </div>

                    <script>
                        function toggleSearchInputs() {
                                $('#assetNoInput').show();

                        }

                        $(document).ready(function() {
                            $('#searchBy').change(function() {
                                toggleSearchInputs();
                            });

                            $('.chosen-select').chosen({
                                width: '100%'
                            });
                        });
                    </script>

{{--
                    <div class="col-sm-4 mb-4">
                        <form action="{{url('/asset/search')}}" method="GET">
                            <div class="input-group input-group-sm">
                                <select class="form-control" name="searchBy" id="searchBy" onchange="toggleSearchInputs()">
                                    <option value="">Search By</option>
                                    <option value="destination">Location</option>
                                    <option value="department">Department</option>
                                    <option value="dateRange">Date Range</option>
                                    <option value="assetCategory">Asset Category</option> <!-- New option -->
                                </select>


                                <select name="destination" class="form-control" id="searchDestination" style="display: none;">
                                    <option value="">Select Plant</option>
                                    @foreach ($locHeader as $item)
                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                    @endforeach
                                </select>

                                <select name="department" class="form-control" id="searchdepartment" style="display: none;">
                                    <option value="">Select Department</option>
                                    @foreach ($dept as $item)
                                        <option value="{{$item->dept}}">{{$item->dept}}</option>
                                    @endforeach
                                </select>

                                <select name="location" class="form-control" id="searchlocation" style="display: none;">
                                    <option value="">Select Location</option>
                                    <!-- Options will be dynamically populated using jQuery -->
                                </select>

                                <input name="startDate" type="date" class="form-control" id="startDate" style="display: none;">
                                <input name="endDate" type="date" class="form-control" id="endDate" style="display: none;">

                                <select name="assetCategory" class="form-control" id="searchAssetCategory" style="display: none;">
                                    <option value="">Select Asset Category</option>
                                    @foreach ($assetCategory as $category)
                                        <option value="{{ $category->desc }}">{{ $category->desc }}</option>
                                    @endforeach
                                </select>

                                <button class="btn btn-dark btn-sm" type="submit">Search</button>
                            </div>
                        </form>
                    </div>

                    <div class="col-md-4">
                        <form action="/asset/search" method="GET" class="d-flex align-items-end">
                            @csrf

                            <div class="form-group flex-grow-1">
                                <input hidden type="text" name="searchBy" value="assetNo" id="">
                                <select name="assetNo[]" id="assetNo" class="form-control chosen-select" multiple data-placeholder="Select Asset Numbers...">
                                    @foreach ($assetNo as $number)
                                        <option value="{{ $number }}">{{ $number }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <button class="btn btn-dark btn-sm" type="submit">Search</button>
                        </form>
                    </div>
                    <script>
                        $(document).ready(function() {
                            $('.chosen-select').chosen({
                                width: '100%'
                            });
                        });
                    </script>

                    <script>
                        function toggleSearchInputs(modalId) {
                            var searchBy = $('#' + (modalId ? modalId + ' #' : '') + 'searchBy').val();
                            var searchAssetNo = $('#' + (modalId ? modalId + ' #' : '') + 'searchAssetNo');
                            var searchDestination = $('#' + (modalId ? modalId + ' #' : '') + 'searchDestination');
                            var searchStartDate = $('#' + (modalId ? modalId + ' #' : '') + 'startDate');
                            var searchEndDate = $('#' + (modalId ? modalId + ' #' : '') + 'endDate');
                            var searchDepartment = $('#' + (modalId ? modalId + ' #' : '') + 'searchdepartment');
                            var searchLocation = $('#' + (modalId ? modalId + ' #' : '') + 'searchlocation');
                            var searchAssetCategory = $('#' + (modalId ? modalId + ' #' : '') + 'searchAssetCategory');

                            // Hide all input fields
                            searchAssetNo.hide();
                            searchDestination.hide();
                            searchStartDate.hide();
                            searchEndDate.hide();
                            searchDepartment.hide();
                            searchLocation.hide();
                            searchAssetCategory.hide();

                            if (searchBy === 'assetNo') {
                                searchAssetNo.show();
                            } else if (searchBy === 'destination') {
                                searchDestination.show();
                            } else if (searchBy === 'dateRange') {
                                searchStartDate.show();
                                searchEndDate.show();
                            } else if (searchBy === 'department') {
                                searchDepartment.show();
                            } else if (searchBy === 'location') {
                                searchLocation.show();
                            } else if (searchBy === 'assetCategory') { // New condition
                                searchAssetCategory.show();
                            }
                        }

                        // Add an event listener to dynamically populate locDetailDropdown
                        $('#searchDestination').change(function() {
                            var locHeaderId = $(this).val();
                            var locDetailDropdown = $('#searchlocation');

                            // Fetch loc_details based on loc_header selection using Laravel AJAX or other methods
                            // Update locDetailDropdown options dynamically
                            // For simplicity, let's assume you have the locDetails data available in a JavaScript variable

                            var locDetailsData = {!! json_encode($locDetail->toArray()) !!}; // Convert PHP array to JavaScript variable

                            // Clear existing options
                            locDetailDropdown.empty().append('<option value="">Select Location</option>');

                            // Populate locDetailDropdown with options based on locHeaderId
                            $.each(locDetailsData, function(index, item) {
                                if (item.loc_header_id == locHeaderId) {
                                    locDetailDropdown.append('<option value="' + item.id + '">' + item.name + '</option>');
                                }
                            });

                            // Show locDetailDropdown
                            locDetailDropdown.show();
                        });
                    </script> --}}



                    <div class="mb-3 col-sm-12">
                        @if(\Auth::user()->role === 'Super Admin')
                        <!-- Search buttons -->
                        <div class="btn-group mb-2" role="group" aria-label="Search">
                            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#searchModal">
                                <i class="fas fa-search"></i> Serch Multiple Variable
                            </button>
                            <button title="Search Asset" type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-search">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>

                        <!-- Add and import buttons -->
                        <div class="btn-group mb-2" role="group" aria-label="Add and Import">
                            <button title="Import Asset" type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modal-import">
                                <i class="fas fa-file-import"></i> Import Assets
                            </button>
                            <button title="Add Asset" type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-add">
                                <i class="fas fa-plus-square"></i>
                            </button>

                        </div>
                        @endif

                        <!-- Additional buttons -->
                        <div class="btn-group mb-2" role="group" aria-label="Generate and Export">

                            <button title="Export to Excel" type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modal-export-excel">
                                <i class="fas fa-file-excel"></i> Export to Excel
                            </button>
                            <a title="Generate Checklist" class="btn btn-primary btn-sm" href="#" onclick="generateChecklist(); return false;" id="generateChecklistBtn">
                                <i class="fas fa-qrcode"></i>
                            </a>
                        </div>


                        <!-- Export to Excel Modal -->
                        <div class="modal fade" id="modal-export-excel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Export to Excel</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Add any content related to exporting to Excel here -->
                                        <p>Choose export options and click export.</p>
                                        <!-- You can add form elements, checkboxes, or any other export-related options here -->

                                        <div class="col-sm-12 mb-2">
                                            <form action="{{ url('/asset/export') }}" method="GET">
                                                @csrf
                                                <div class="input-group input-group-sm">
                                                    <select class="form-control" name="searchBy" id="searchByModal" onchange="toggleSearchInputsModal()">
                                                        <option value="">Export by</option>
                                                        <option value="assetNo">Asset No</option>
                                                        <option value="destination">Location</option>
                                                        <option value="department">Department</option>
                                                        <option value="dateRange">Date Range</option>
                                                        <option value="assetCategory">Asset Category</option> <!-- New option -->
                                                    </select>

                                                    <input name="assetNo" type="text" class="form-control" id="searchAssetNoModal" placeholder="Enter Asset No" style="display: none;">

                                                    <select name="destination" class="form-control" id="searchDestinationModal" style="display: none;">
                                                        <option value="">Select Plant</option>
                                                        @foreach ($locHeader as $item)
                                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                                        @endforeach
                                                    </select>

                                                    <select name="department" class="form-control" id="searchdepartmentModal" style="display: none;">
                                                        <option value="">Select Department</option>
                                                        @foreach ($dept as $item)
                                                            <option value="{{$item->dept}}">{{$item->dept}}</option>
                                                        @endforeach
                                                    </select>

                                                    <select name="location" class="form-control" id="searchlocationModal" style="display: none;">
                                                        <option value="">Select Location</option>
                                                        <!-- Options will be dynamically populated using jQuery -->
                                                    </select>

                                                    <input name="startDate" type="date" class="form-control" id="startDateModal" style="display: none;">
                                                    <input name="endDate" type="date" class="form-control" id="endDateModal" style="display: none;">

                                                    <select name="assetCategory" class="form-control" id="searchAssetCategoryModal" style="display: none;">
                                                        <option value="">Select Asset Category</option>
                                                        @foreach ($assetCategory as $category)
                                                            <option value="{{ $category->desc }}">{{ $category->desc }}</option>
                                                        @endforeach
                                                    </select>

                                                    <button class="btn btn-success btn-sm" type="submit">Export</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    <script>
                                        function toggleSearchInputsModal() {
                                            var searchBy = $('#searchByModal').val();
                                            var searchAssetNo = $('#searchAssetNoModal');
                                            var searchDestination = $('#searchDestinationModal');
                                            var searchStartDate = $('#startDateModal');
                                            var searchEndDate = $('#endDateModal');
                                            var searchDepartment = $('#searchdepartmentModal');
                                            var searchLocation = $('#searchlocationModal');
                                            var searchAssetCategory = $('#searchAssetCategoryModal');

                                            // Hide all input fields
                                            searchAssetNo.hide();
                                            searchDestination.hide();
                                            searchStartDate.hide();
                                            searchEndDate.hide();
                                            searchDepartment.hide();
                                            searchLocation.hide();
                                            searchAssetCategory.hide();

                                            if (searchBy === 'assetNo') {
                                                $('#assetNoInput').show();
                                            } else if (searchBy === 'destination') {
                                                $('#assetNoInput').hide();
                                                searchDestination.show();
                                            } else if (searchBy === 'dateRange') {
                                                $('#assetNoInput').hide();
                                                searchStartDate.show();
                                                searchEndDate.show();
                                            } else if (searchBy === 'department') {
                                                $('#assetNoInput').hide();
                                                searchDepartment.show();
                                            } else if (searchBy === 'location') {
                                                $('#assetNoInput').hide();
                                                searchLocation.show();
                                            } else if (searchBy === 'assetCategory') {
                                                $('#assetNoInput').hide();// New condition
                                                searchAssetCategory.show();
                                            }
                                        }

                                        // Add an event listener to dynamically populate locDetailDropdownModal
                                        $('#searchDestinationModal').change(function() {
                                            var locHeaderId = $(this).val();
                                            var locDetailDropdown = $('#searchlocationModal');

                                            // Fetch loc_details based on loc_header selection using Laravel AJAX or other methods
                                            // Update locDetailDropdown options dynamically
                                            // For simplicity, let's assume you have the locDetails data available in a JavaScript variable

                                            var locDetailsData = {!! json_encode($locDetail->toArray()) !!}; // Convert PHP array to JavaScript variable

                                            // Clear existing options
                                            locDetailDropdown.empty().append('<option value="">Select Location</option>');

                                            // Populate locDetailDropdown with options based on locHeaderId
                                            $.each(locDetailsData, function(index, item) {
                                                if (item.loc_header_id == locHeaderId) {
                                                    locDetailDropdown.append('<option value="' + item.id + '">' + item.name + '</option>');
                                                }
                                            });

                                            // Show locDetailDropdown
                                            locDetailDropdown.show();
                                        });
                                    </script>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
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
                                <form action="{{ url('/asset/store') }}" method="POST" enctype="multipart/form-data">
                                  @csrf

                                  <div class="modal-body">
                                    <div class="form-group mb-3">
                                      <input type="text" class="form-control" id="asset_no" name="asset_no" placeholder="Enter Asset Number" required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <textarea class="form-control" id="desc" name="desc" placeholder="Enter Asset Description" required></textarea>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <input type="number" class="form-control" id="qty" name="qty" placeholder="Enter Qty" required min="0">
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <select name="uom" id="uom" class="form-control" required>
                                                    <option value="">- Please Select UOM -</option>
                                                    @foreach ($dropdownUom as $uom)
                                                        <option value="{{ $uom->name_value }}">{{ $uom->name_value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="date" class="form-control" id="date" name="date" placeholder="Enter Date" required>
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

                                    <div class="row mb-3">
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
                                    </div>

                                    <script>
                                        document.addEventListener('DOMContentLoaded', function () {
                                            var plantDropdowns = document.querySelectorAll('select[name="plant"]');
                                            var locDropdowns = document.querySelectorAll('select[name="loc"]');

                                            plantDropdowns.forEach(function (plantDropdown, index) {
                                                plantDropdown.addEventListener('change', function () {
                                                    var locDropdown = locDropdowns[index];
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
                                        });
                                    </script>


                                    <div class="form-group mb-3">
                                        <select name="dept" id="dept" class="form-control" required>
                                            <option value="">- Please Department-</option>
                                            @foreach ($dept as $data)
                                                <option value="{{ $data->dept }}">{{ $data->dept }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <select  id="cost_center" name="cost_center" class="form-control" required>
                                                    <option value="">- Please Enter Cost Center-</option>
                                                    @foreach ($costCenter as $data)
                                                        <option value="{{ $data->cost_ctr }}">{{ $data->cost_ctr }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="file" class="form-control" id="img" name="img[]" placeholder="Enter Image" required multiple >
                                            </div>
                                        </div>
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

                          <!-- Modal -->
                        <div class="modal fade" id="modal-import" tabindex="-1" aria-labelledby="modal-add-label" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modal-add-label">Import Asset</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ url('/asset/import') }}" method="POST" enctype="multipart/form-data">
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
                                            <a href="{{ url('/download/excel/format') }}" class="btn btn-link">
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
                        <div class="modal fade" id="modal-search" tabindex="-1" aria-labelledby="modal-add-label" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modal-add-label">Search Asset</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ url('/asset/search/no') }}" method="POST" enctype="multipart/form-data">
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
                                            <a href="{{ url('/download/excel/format/search') }}" class="btn btn-link">
                                                Download Excel Format
                                            </a>
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
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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
                    <th> <input type="checkbox"  id="checkAllBtn">  </th>
                    <th>Asset No</th>
                    <th>Desc.</th>
                    <th>Qty</th>
                    <th>Acquisition date</th>
                    <th>Acquisition Cost</th>
                    <th>Book Value</th>
                    <th>Department</th>
                    <th>Location</th>
                    <th>Sub</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                    @foreach ($assetData as $data)
                    <tr>

                        <td>
                            <input class="form-check-input" type="checkbox" name="assetCheckbox[]" value="{{ $data->id }}">
                        </td>
                        <td><strong>{{ $data->asset_no }}</strong><br>

                            @php
                                $statusColor = ($data->status == 1) ? 'btn-success' : (($data->status == 0) ? 'btn-warning' : 'btn-danger');
                                $statusText = ($data->status == 1) ? 'Active' : (($data->status == 0) ? 'Deactive' : 'Disposal');
                            @endphp

<button class="btn btn-xs {{ $statusColor }}" onclick="openRemarksModal({{ $data->id }}, '{{ $data->status }}', '{{ $data->remarks }}')">
    {{ $statusText }}
</button>


@if(\Auth::user()->role === 'Super Admin')
<div class="modal fade" id="remarksModal{{ $data->id }}" tabindex="-1" aria-labelledby="remarksModalLabel{{ $data->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="remarksModalLabel{{ $data->id }}">Update Status and Enter Remarks</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="remarksForm{{ $data->id }}" method="POST" action="{{ url('/asset/status/'.$data->id) }}">
                    @csrf
                    <div class="mb-3">
                        <label for="status{{ $data->id }}" class="form-label">Status</label>
                        <select class="form-select" id="status{{ $data->id }}" name="status" required>
                            @foreach ($status as $item)
                                <option value="{{ $item->code_format }}">{{ $item->name_value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="remark{{ $data->id }}" class="form-label">Remark</label>
                        <textarea class="form-control" id="remark{{ $data->id }}" name="remark" rows="3" required>{{ $data->remarks }}</textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="submitRemarksForm({{ $data->id }})">Submit</button>
            </div>
        </div>
    </div>
</div>
@endif

<script>
    function openRemarksModal(id, status, remarks) {
        // Set the initial value for the status dropdown
        $('#status' + id).val(status);

        // Set the initial value for the remarks textarea
        $('#remark' + id).val(remarks);

        // Show the modal
        $('#remarksModal' + id).modal('show');
    }

    function submitRemarksForm(id) {
        // Validate and submit the form
        if ($('#remarksForm' + id)[0].checkValidity()) {
            $('#remarksForm' + id).submit();
        }
    }
</script>
                        </td>
                        <td>{{ $data->desc}} </td>
                        <td>{{ str_replace(' ', '', $data->qty) }} (<small>{{ str_replace(' ', '', $data->uom) }}</small>)</td>
                        <td>{{ date('d-M-Y', strtotime($data->acq_date)) }}</td>
                        <td>{{ 'Rp ' . number_format($data->acq_cost, 0, ',', '.') }}</td>
                        <td>{{ 'Rp ' . number_format($data->bv_endofyear, 0, ',', '.') }}</td>
                        <td>{{ $data->dept }} </td>
                        <td>{{ str_replace(' ', '',$data->plant) }} <br> (<small>{{str_replace(' ', '',$data->loc)}}</small>)</td>
                        <td>
                            @php
                            $hasSubAssets = count($data->details) > 0; // Check if there are sub-assets
                            $buttonClass = $hasSubAssets ? 'btn-info' : 'btn-light'; // Set button color class based on presence of sub-assets
                            @endphp

                        <button class="btn btn-sm details-btn {{ $buttonClass }}" data-bs-toggle="modal" data-bs-target="#detailsModal{{ $data->id }}">
                            Sub
                        </button>
                        </td>

                        <td><div class="dropdown">
                            <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                Actions
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                @if(\Auth::user()->role === 'Super Admin')
                                    <li>
                                        <button title="Edit Asset" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modal-update{{ $data->id }}">
                                            <i class="fas fa-edit me-2"></i>Edit Asset
                                        </button>
                                    </li>
                                    <li>
                                        <button title="Delete Asset" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modal-delete{{ $data->id }}">
                                            <i class="fas fa-trash-alt me-2"></i>Delete Asset
                                        </button>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                @endif
                                <li>
                                    <a title="Detail Asset" class="dropdown-item" href="{{ url('asset/detail/' . encrypt($data->id)) }}">
                                        <i class="fas fa-info me-2"></i>Detail Asset
                                    </a>
                                </li>
                            </ul>
                        </div>


                        </td>

                    </tr>





                    {{-- Modal Update --}}
                    <div class="modal fade" id="modal-update{{ $data->id }}" tabindex="-1" aria-labelledby="modal-update{{ $data->id }}-label" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h4 class="modal-title" id="modal-update{{ $data->id }}-label">Edit Asset</h4>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ url('/asset/update/'.$data->id) }}" method="POST"  enctype="multipart/form-data">
                              @csrf
                              @method('patch')
                              <div class="modal-body">
                                <div class="form-group mb-3">
                                  <input value="{{$data->asset_no}}" type="text" class="form-control" id="asset_no" name="asset_no" placeholder="Enter Asset Number" >
                                </div>
                                <div class="form-group mb-3">
                                    <textarea class="form-control" id="desc" name="desc" placeholder="Enter Asset Description" >{{$data->desc}}</textarea>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <input value="{{$data->qty}}" type="number" class="form-control" id="qty" name="qty" placeholder="Enter Qty" required min="0">
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <select name="uom" id="uom" class="form-control" >
                                                <option value="{{$data->uom}}">{{$data->uom}}</option>
                                                @foreach ($dropdownUom as $uom)
                                                    <option value="{{ $uom->name_value }}">{{ $uom->name_value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <input value="{{$data->acq_date}}" type="date" class="form-control" id="date" name="date" placeholder="Enter Date" >
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <input value="{{number_format($data->acq_cost)}}" type="text" class="form-control" id="costEdit" name="cost_edit" placeholder="Enter Acquisition Cost" >
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

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <select name="plant" id="plantEdit" class="form-control">
                                                <option value="{{$data->plant}}">{{$data->plant}}</option>
                                                @foreach ($locHeader as $header)
                                                    <option value="{{ $header->name }}">{{ $header->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <select name="loc" id="locEdit" class="form-control">
                                                <option value="{{$data->loc}}">{{$data->loc}}</option>
                                                <!-- Location options will be dynamically populated using JavaScript -->
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <script>
                                    document.addEventListener('DOMContentLoaded', function () {
                                        var plantDropdown = document.getElementById('plantEdit');
                                        var locDropdown = document.getElementById('locEdit');
                                        var selectedPlant = '{{$data->plant}}';
                                        var selectedLoc = '{{$data->loc}}';

                                        // Function to populate locations based on the selected plant
                                        function populateLocations(selectedPlant) {
                                            locDropdown.innerHTML = '<option value="{{$data->loc}}">{{$data->loc}}</option>';
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
                                                    if (detail.location === selectedLoc) {
                                                        option.selected = true; // Select the previously saved location
                                                    }
                                                    locDropdown.appendChild(option);
                                                }
                                            });
                                        }

                                        // Initially populate locations based on the selected plant
                                        populateLocations(selectedPlant);

                                        // Change event for plant dropdown
                                        plantDropdown.addEventListener('change', function () {
                                            var selectedPlant = plantDropdown.value;
                                            populateLocations(selectedPlant);
                                        });
                                    });
                                </script>


                                <div class="form-group mb-3">
                                    <select name="dept" id="dept" class="form-control" >
                                        <option value="{{$data->dept}}">{{$data->dept}}</option>
                                        @foreach ($dept as $item)
                                            <option value="{{ $item->dept }}">{{ $item->dept }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <select  id="cost_center" name="cost_center" class="form-control" required>
                                                <option value="{{$data->cost_center}}">{{$data->cost_center}}</option>
                                                @foreach ($costCenter as $item)
                                                    <option value="{{ $item->cost_ctr }}">{{ $item->cost_ctr }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="file" class="form-control" id="img" name="img[]" multiple placeholder="Enter Image">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <input value="{{$data->bv_endofyear}}"  type="text" class="form-control" id="bv_endEdit" name="bv_end" placeholder="Enter BV End Of Year" >
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
                            <h4 class="modal-title" id="modal-delete{{ $data->id }}-label">Delete Asset</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ url('/asset/delete/'.$data->id) }}" method="POST">
                                @csrf
                                @method('delete')
                            <div class="modal-body">
                                <div class="form-group">
                                Are you sure you want to delete <label for="Dropdown">{{ $data->asset_no }}</label>?
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
                @foreach ($assetData as $data)
                     <!-- Modal for details -->
                    <div class="modal fade" id="detailsModal{{ $data->id }}" tabindex="-1" aria-labelledby="detailsModalLabel{{ $data->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="detailsModalLabel{{ $data->id }}">Bill of Materials</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <a title="Generate Checklist" class="btn btn-primary btn-sm mb-2" href="#" onclick="generateChecklistDetail('{{ $data->id }}'); return false;" id="generateChecklistBtn{{ $data->id }}">
                                        Generate QR Code
                                    </a>
                                    <table id='detail{{ $data->id }}' class="table">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <input type="checkbox" id="checkAllBtn{{ $data->id }}" class="check-all">
                                                </th>
                                                <th>Asset No</th>
                                                <th>Description</th>
                                                <th>Qty</th>
                                                <th>Acquisition date</th>
                                                <!-- Add more columns based on your AssetDetail model -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data->details as $detail)
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" class="form-check-input checkbox-detail" name="assetCheckboxDetail[{{ $data->id }}][]" value="{{ $detail->id }}">
                                                    </td>
                                                    <td>{{ $detail->asset_no }} - {{ $detail->sub_asset }}</td>
                                                    <td>{{ $detail->desc }}</td>
                                                    <td>{{ $detail->qty }} ({{ $detail->uom }})</td>
                                                    <td>{{ date('d-M-Y', strtotime($detail->date)) }}</td>
                                                    <!-- Add more cells based on your AssetDetail model -->
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="modal-footer">
                                    <!-- You can add footer buttons or additional content here -->
                                    <!-- Example: <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            // Check All checkbox
                            document.getElementById('checkAllBtn{{ $data->id }}').addEventListener('change', function () {
                                var checkboxes = document.querySelectorAll('input[name="assetCheckboxDetail[{{ $data->id }}][]"]');
                                checkboxes.forEach(function (checkbox) {
                                    checkbox.checked = document.getElementById('checkAllBtn{{ $data->id }}').checked;
                                });
                            });
                        });
                    </script>
                    <script>
                        function generateChecklistDetail(id) {
                            var checkboxes = document.querySelectorAll('input[name="assetCheckboxDetail[' + id + '][]"]:checked');

                            if (checkboxes.length > 0) {
                                var selectedAssetIds = [];

                                checkboxes.forEach(function (checkbox) {
                                    var assetId = checkbox.value;
                                    selectedAssetIds.push(assetId);
                                });

                                var url = "{{ url('/asset/qr/detail') }}/" + id + "?assetIds=" + selectedAssetIds.join(',');

                                window.open(url, '_blank');
                            } else {
                                alert("Please select at least one asset to generate a checklist.");
                            }
                        }
                    </script>
                @endforeach
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
            "buttons": [] // Remove the button configuration
        });
    });
</script>

<script>
    $(document).ready(function () {
        $(".table").DataTable({
            "responsive": false,
            "lengthChange": false,
            "autoWidth": false,
            "order": [],
            "dom": 'Bfrtip',
            "bDestroy": true,
            "buttons": [] // Remove the button configuration
        });
    });
</script>

    <!-- Add this script at the end of your HTML file or in a separate script section -->
 <script>
    function generateChecklist() {
        // Get all selected checkboxes
        var checkboxes = document.querySelectorAll('input[name="assetCheckbox[]"]:checked');

        // Check if at least one checkbox is selected
        if (checkboxes.length > 0) {
            // Create an array to store selected asset IDs
            var selectedAssetIds = [];

            // Iterate through selected checkboxes and add asset IDs to the array
            checkboxes.forEach(function (checkbox) {
                var assetId = checkbox.value;
                selectedAssetIds.push(assetId);
            });

            // Construct the URL with selected asset IDs as query parameters
            var url = "{{ url('/asset/qr') }}?assetIds=" + selectedAssetIds.join(',');

            // Open a new tab with the generated URL
            window.open(url, '_blank');
        } else {
            alert("Please select at least one asset to generate a checklist.");
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
    // Check All checkbox
    document.getElementById('checkAllBtn').addEventListener('change', function () {
        var checkboxes = document.querySelectorAll('input[name="assetCheckbox[]"]');
        checkboxes.forEach(function (checkbox) {
            checkbox.checked = document.getElementById('checkAllBtn').checked;
        });
    });
});
    </script>

@endsection
