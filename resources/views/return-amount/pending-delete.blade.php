@extends('layouts.layout')
@section('content')
@php $user = Auth::user(); @endphp

<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Pending Delete Requests</h4>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-sm" style="font-size: 12px;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Shipment No</th>
                                    <th>Supplier</th>
                                    <th>Type</th>
                                    <th>Return Amount</th>
                                    <th>Requested By</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pendingDeletes as $key => $payment)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $payment->date }}</td>
                                        <td>{{ $payment->shipment->shipment_no ?? 'N/A' }}</td>
                                        <td>{{ $payment->supplier->name ?? 'N/A' }}</td>
                                        <td>{{ $payment->type }}</td>
                                        <td>{{ number_format($payment->retrun_amount, 2) }}</td>
                                        <td>{{ $payment->user->name ?? 'N/A' }}</td>
                                        <td>
                                            @if($user->designation_id == 1)
                                            <form action="{{ route('return-payment.approveDelete', $payment->id) }}" method="POST" onsubmit="return confirm('Approve and delete this return payment?');">
                                                @csrf
                                                @method('DELETE')
                      <button type="submit" class="btn btn-danger btn-sm"><i class="mdi mdi-delete"></i> Approve Delete</button>
                                            </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="8" class="text-center">No pending deletions.</td></tr>
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
