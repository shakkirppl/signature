
@extends('layouts.layout')
@section('content')

<div class="main-panel">
     <div class="content-wrapper">
         <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                    <div class="card-body">
                       <div class="row">
                           <div class="col-6 col-md-6 col-sm-6 col-xs-12" >
                                <h4 class="card-title">Customer Payment List</h4>
                            </div>
                            <div class="col-6 col-md-6 col-sm-6 col-xs-12  heading" style="text-align:end;">
                              <a href="{{ route('customer-payment.create')  }}" class="newicon"><i class="mdi mdi-new-box"></i></a>
                           </div>
                       </div>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            {{ $message }}
                        </div>
                    @endif

                    <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
            <table class="table table-bordered table-striped table-sm" style="font-size: 12px;">
              <thead style="background-color: #d6d6d6; color: #000;">
                                <tr>
                                   <th>No</th>
                                    <th>Date</th>
                                    <!-- <th>PI NO</th> -->
                                    <th>Payment To</th>
                                    <th>Payment Type</th>
                                    <th>Bank</th>
                                    <th>Outsanding Amount</th>
                                    <th>Allocated Amount</th>
                                    <th>Balance Amount</th>
                                   
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($customerPayments as $payment)
                                    <tr>
                                    <td>{{ $loop->iteration }}</td>
                                        <td>{{ $payment->payment_date }}</td>
                                        <td>{{ $payment->customer->customer_name ?? 'N/A' }}</td>
                                        <!-- <td> @if ($payment->details->isNotEmpty())
                                            @foreach ($payment->details as $detail)
                                                {{ $detail->pi_no }}
                                            @endforeach
                                        @endif</td> -->
                                        <td>{{ $payment->payment_type }}</td>
                                        <td>{{ $payment->bank_name }}</td>
                                        <td>{{ number_format($payment->outstanding_amount, 2) }}</td>
                                        <td>{{ number_format($payment->allocated_amount, 2) }}</td>
                                        <td>{{ number_format($payment->balance, 2) }}</td>
                                       
                                        <td>
                                       
                                        <a href="{{ route('customer-payment.view', $payment->id) }}" class="btn btn-info btn-sm">View</a>
                                            <a href="{{ route('customer-payment.destroy',  $payment->id) }}" 
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
