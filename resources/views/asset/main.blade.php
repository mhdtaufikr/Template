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
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">List of Asset</h3>
              </div>
              
              <!-- /.card-header -->
              <div class="card-body">
                <div class="row">
                    <div class="mb-3 col-sm-12">
                        <button  title="Add Asset" type="button" class="btn btn-dark btn-sm mb-2" data-bs-toggle="modal" data-bs-target="#modal-add">
                            <i class="fas fa-plus-square"></i> 
                          </button>
                          
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
                                      <input type="text" class="form-control" id="asset_no" name="asset_no" placeholder="Enter Person Asset Number" required>
                                    </div>
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
                                                <input type="number" class="form-control" id="cost_center" name="cost_center" placeholder="Enter Cost Center" required>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="file" class="form-control" id="img" name="img" placeholder="Enter Image" required>
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
                    <th>Desc.</th>
                    <th>Qty</th>
                    <th>Acquisition date</th>
                    <th>Location</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                    @php
                      $no=1;
                    @endphp
                    @foreach ($assetData as $data)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $data->asset_no }}</td>
                        <td>{{ $data->desc }}</td>
                        <td>{{ $data->qty}} ( <small>{{$data->uom}}</small> ) </td>
                        <td>{{ $data->acq_date }}</td>
                        <td>{{ $data->plant}} <br> ( <small>{{$data->loc}}</small> )</td>
                        <td>
                            <button title="Edit Asset" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-update{{ $data->id }}">
                                <i class="fas fa-edit"></i>
                              </button>
                              <a title="Detail Asset" class="btn btn-success btn-sm" href="{{url('asset/detail/'.encrypt($data->id))}}">
                                <i class="fas fa-info"></i>
                              </a>
                            <button title="Delete Asset" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modal-delete{{ $data->id }}">
                                <i class="fas fa-trash-alt"></i>
                              </button>   
                        </td>
                    </tr>

                    {{-- Modal Update --}}
                    <div class="modal fade" id="modal-update{{ $data->id }}" tabindex="-1" aria-labelledby="modal-update{{ $data->id }}-label" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h4 class="modal-title" id="modal-update{{ $data->id }}-label">Edit Cost Center</h4>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ url('/asset/update/'.$data->id) }}" method="POST">
                              @csrf
                              @method('patch')
                              <div class="modal-body">
                                <div class="form-group mb-3">
                                  <input value="{{$data->asset_no}}" type="text" class="form-control" id="asset_no" name="asset_no" placeholder="Enter Person Asset Number" >
                                </div>
                                <div class="form-group mb-3">
                                    <textarea class="form-control" id="desc" name="desc" placeholder="Enter Asset Description" >{{$data->desc}}</textarea>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <input value="{{$data->qty}}" type="number" class="form-control" id="qty" name="qty" placeholder="Enter Qty" required min="0">
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <select name="uom" id="uom" class="form-control" >
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
                                        <input value="{{$data->acq_date}}" type="date" class="form-control" id="date" name="date" placeholder="Enter Date" >
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
                                    <input value="{{$data->acq_cost}}" type="number" class="form-control" id="cost_edit" name="cost_edit" placeholder="Enter Acquisition Cost" >
                                </div>

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
                                            locDropdown.innerHTML = '<option value="">- Please Select Location -</option>';
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
                                            <input value="{{$data->cost_center}}" type="number" class="form-control" id="cost_center" name="cost_center" placeholder="Enter Cost Center" >
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="file" class="form-control" id="img" name="img" placeholder="Enter Image">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <input value="{{$data->bv_endofyear}}"  type="number" class="form-control" id="bv_endEdit" name="bv_end" placeholder="Enter BV End Of Year" >
                                </div>  
                                
                                
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
                            <form action="{{ url('/cost_center/delete/'.$data->id) }}" method="POST">
                            @csrf
                            @method('delete')
                            <div class="modal-body">
                                <div class="form-group">
                                Are you sure you want to delete <label for="Dropdown">{{ $data->cost_ctr }}</label>?
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
    $(document).ready(function() {
      var table = $("#tableUser").DataTable({
        "responsive": true, 
        "lengthChange": false, 
        "autoWidth": false,
        // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
      });
    });
  </script>
@endsection