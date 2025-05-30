@extends('layouts.layout')
@section('content')
@php
    $user = Auth::user();
@endphp
<style>
     .newicon i {
        font-size: 30px;}
</style>
<div class="main-panel">
  <div class="content-wrapper">
    <div class="col-12 grid-margin">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <h4 class="card-title">Supplier Final Payment List</h4>
            </div>
             @if($user->designation_id == 1||$user->designation_id == 3)
            <div class="col-md-6 text-right">
              <a href="{{ route('return-supplier.create') }}" class="newicon"><i class="mdi mdi-new-box"></i></a>
            </div>
            @endif
          </div>

          <!-- Responsive Table -->
          <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
            <table class="table table-bordered table-striped table-sm" style="font-size: 12px;">
              <thead style="background-color: #d6d6d6; color: #000;">
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    
                                    <th>Supplier</th>
                                   
                                    <th> Amount</th>
                                    <th>created By</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($returns as $key => $payment)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $payment->date }}</td>
                                       
                                        <td>{{ $payment->supplier->name ?? 'N/A' }}</td>
                                        
                                        <td>{{ number_format($payment->retrun_amount, 2) }}</td>
                                         <td>{{ $payment->user->name ?? 'N/A' }}</td>
                                        <td>
                                        @if($user->designation_id == 1)
                                           <form action="{{ route('return-to-supplier.destroy', $payment->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this entry?');">
                                       @csrf
                                       @method('DELETE')
                                       <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                     @endif
                                           @if(auth()->user()->designation_id == 3 && !$payment->delete_status)
                <form action="{{ route('return-to-supplier.request-delete', $payment->id) }}" method="POST" onsubmit="return confirm('Request delete this entry?')">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-danger btn-sm">Request Delete</button>
                </form>
            @elseif($payment->delete_status)
                <span class="text-danger">Delete Requested</span>
            @endif

                                        </td>
                                    </tr>
                                @endforeach
                                @if($returns->isEmpty())
                                    <tr><td colspan="6" class="text-center">No  payments found.</td></tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection
