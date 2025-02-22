@extends('layouts.layout')
@section('content')

<div class="main-panel">
     <div class="content-wrapper">
         <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                    <div class="card-body">
                       <div class="row">
                           <div class="col-6 col-md-6 col-sm-6 col-xs-12" >
                                <h4 class="card-title">Bank Master List</h4>
                            </div>
                            <div class="col-6 col-md-6 col-sm-6 col-xs-12  heading" style="text-align:end;">
                              <a href="{{ route('bank-master.create')  }}" class="newicon"><i class="mdi mdi-new-box"></i></a>
                           </div>
                       </div>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            {{ $message }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover" id="value-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Code</th>
                                    <th>Bank Name</th>
                                   
                                    <th>GL</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($bankMasters as $index => $bank)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $bank->code }}</td>
                                        <td>{{ $bank->bank_name }}</td>
                                       
                                        <td>{{ $bank->gl ?? 'N/A' }}</td>
                                        <td>
                                       
                                            <a class="btn btn-warning btn-sm" href="{{ route('bank-master.edit', $bank->id) }}">
                                                Edit
                                            </a>
                                            <a href="{{ route('bank-master.destroy', $bank->id) }}" 
                                                    class="btn btn-danger btn-sm" 
                                                    onclick="return confirm('Are you sure you want to delete this record?')">
                                                     Delete
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No records found</td>
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

@endsection
