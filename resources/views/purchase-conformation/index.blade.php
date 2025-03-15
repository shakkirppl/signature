
    @extends('layouts.layout')
@section('content')
<div class="main-panel">
  <div class="content-wrapper">
    <div class="col-12 grid-margin">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <h4 class="card-title">purchase Confirmation</h4>
            </div>
            <div class="col-md-6 text-right">
            <!-- <a href="{{ route('purchase-order.create') }}" class="newicon"><i class="mdi mdi-new-box"></i></a> -->
            </div>
          </div>
          <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
            <table class="table table-bordered table-striped table-sm" style="font-size: 12px;">
              <thead style="background-color: #d6d6d6; color: #000;">
                <tr>
                
                     <th>no</th>
                     <th>Weight Code </th>
                     <th>Date</th>
                     <th>Supplier</th>
                     <th>Actions</th>
                </tr>
              </thead>
              <tbody>
              @forelse ($conformations as $index => $conformation)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $conformation->weight_code }}</td>
            <td>{{ $conformation->date }}</td>
            <td>{{ $conformation->supplier->name ?? 'N/A' }}</td>
            <td>
            <a href="{{ route('purchase-conformation.Confirm', $conformation->id) }}" class="btn btn-warning btn-sm">Confirm</a>
            <a href="" 
                     class="btn btn-danger btn-sm" 
                     onclick="return confirm('Are you sure you want to delete this record?')">
                     Delete
                  </a>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="6" class="text-center">No weight calculations found with status 0.</td>
        </tr>
    @endforelse
                 
                                       
                 

                
              
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<style>
  .table-responsive {
    overflow-x: auto;
  }
  .table th, .table td {
    padding: 5px;
    text-align: center;
  }
  .btn-sm {
    padding: 3px 6px;
    font-size: 10px;
  }
  .newicon i {
    font-size: 30px;}
</style>
@endsection