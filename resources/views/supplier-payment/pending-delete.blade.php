@extends('layouts.layout')
@section('content')
<div class="main-panel">
     <div class="content-wrapper">
         <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                    <div class="card-body">
                       <h4 class="card-title">Pending Delete Requests</h4>
                       
                    <div class="table-responsive">
                       <table class="table table-bordered table-striped table-sm" style="font-size: 12px;">
              <thead style="background-color: #d6d6d6; color: #000;">
                                <tr>
                                   <th>No</th>
                                    <th>Date</th>
                                    <th>Shipment No</th>
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
                                @forelse($pendingPayments as $payment)
                                    <tr>
                                    <td>{{ $loop->iteration }}</td>
                                        <td>{{ $payment->payment_date }}</td>
                                        <td>{{ $payment->shipment->shipment_no ?? 'N/A' }}</td>
                                        <td>{{ $payment->supplier->name ?? 'N/A' }}</td>
                                        
                                        <td>{{ $payment->payment_type }}</td>
                                        <td>{{ $payment->bank_name ?? 'N/A' }}</td>
                                        <td>{{ number_format($payment->outstanding_amount, 2) }}</td>
                                        <td>{{ number_format($payment->allocated_amount, 2) }}</td>
                                        <td>{{ number_format($payment->balance, 2) }}</td>
                                       
                                    
                                       
                                    <td>
    <div class="d-flex align-items-center gap-1">
        <a href="{{ route('supplier-payment.view', $payment->id) }}" class="btn btn-info btn-sm">View</a>
        <a href="{{ route('supplier-payment.destroy', $payment->id) }}"
           class="btn btn-danger btn-sm"
           onclick="return confirm('Approve and delete this record?')">
           Approve Delete
        </a>
    </div>
</td>

  
                                        
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">No records found</td>
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
