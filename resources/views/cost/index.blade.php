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
                <h3 class="card-title">List of Cost Center</h3>
              </div>
              
              <!-- /.card-header -->
              <div class="card-body">
                <div class="row">
                    <div class="mb-3 col-sm-12">
                        <button type="button" class="btn btn-dark btn-sm mb-2" data-bs-toggle="modal" data-bs-target="#modal-add">
                            <i class="fas fa-plus-square"></i> 
                          </button>
                          
                          <!-- Modal -->
                          <div class="modal fade" id="modal-add" tabindex="-1" aria-labelledby="modal-add-label" aria-hidden="true">
                            <div class="modal-dialog">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="modal-add-label">Add Cost Center</h5>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ url('/cost_center/store') }}" method="POST">
                                  @csrf
                                  <div class="modal-body">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="number" class="form-control" id="costctr" name="costctr" placeholder="Enter Cost Ctr" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="number" class="form-control" id="coar" name="coar" placeholder="Enter COAr" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="number" class="form-control" id="cocd" name="cocd" placeholder="Enter CoCD" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="cctc" name="cctc" placeholder="Enter CCtC" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mb-3">
                                      <input type="text" class="form-control" id="pic" name="pic" placeholder="Enter Person Responsible" required>
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                      <input type="text" class="form-control" id="userpic" name="userpic" placeholder="Enter User Resp.">
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <input type="text" class="form-control" id="remarks" name="remarks" placeholder="Enter Short Text" required>
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
                    <th>Cost Ctr</th>
                    <th>COAr</th>
                    <th>CoCD</th>
                    <th>CCtC</th>
                    <th>Person Responsible</th>
                    <th>User Resp.</th>
                    <th>Short Text</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                    @php
                      $no=1;
                    @endphp
                    @foreach ($costCenterData as $data)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $data->cost_ctr }}</td>
                        <td>{{ $data->coar }}</td>
                        <td>{{ $data->cocd }}</td>
                        <td>{{ $data->cctc }}</td>
                        <td>{{ $data->pic }}</td>
                        <td>{{ $data->user_pic }}</td>
                        <td>{{ $data->remarks }}</td>
                        <td>
                            <button title="Edit Cost Center" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-update{{ $data->id }}">
                                <i class="fas fa-edit"></i>
                              </button>
                            <button title="Delete Cost Center" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modal-delete{{ $data->id }}">
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
                            <form action="{{ url('/cost_center/update/'.$data->id) }}" method="POST">
                              @csrf
                              @method('patch')
                              <div class="modal-body">
                                <input name="id" type="text" value="{{$data->id}}" hidden>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input value="{{$data->cost_ctr}}" type="number" class="form-control" id="costctr" name="costctr" placeholder="Enter Cost Ctr" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input value="{{$data->coar}}" type="number" class="form-control" id="coar" name="coar" placeholder="Enter COAr" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input value="{{$data->cocd}}" type="number" class="form-control" id="cocd" name="cocd" placeholder="Enter CoCD" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input value="{{$data->cctc}}" type="text" class="form-control" id="cctc" name="cctc" placeholder="Enter CCtC" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                  <input value="{{$data->pic}}" type="text" class="form-control" id="pic" name="pic" placeholder="Enter Person Responsible" required>
                                </div>
                                
                                <div class="form-group mb-3">
                                  <input value="{{$data->user_pic}}" type="text" class="form-control" id="userpic" name="userpic" placeholder="Enter User Resp.">
                                </div>
                                
                                <div class="form-group mb-3">
                                    <input value="{{$data->remarks}}" type="text" class="form-control" id="remarks" name="remarks" placeholder="Enter Short Text" required>
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