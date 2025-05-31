@extends('layouts.layout')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <h4>Pending Delete Requests</h4>
       <table class="table table-bordered table-striped table-sm" style="font-size: 12px;">
              <thead style="background-color: #d6d6d6; color: #000;">
                <tr>
                                    <th>Order No</th>
                                    <th>Date</th>
                                    <th>Customer</th>
                                    <th>Shipping Mode</th>
                                    <th>Shipping Agent</th>
                                    <th>Net Weight</th>
                                    <th>Gross Weight</th>
                                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pendingDeletes as $packing)
                    <tr>
                       <td>{{ $packing->packing_no }}</td>
                                    <td>{{ $packing->date }}</td>
                                    <td>{{ $packing->customer->customer_name ?? 'N/A' }}</td>
                                    <td>{{ $packing->shipping_mode }}</td>
                                    <td>{{ $packing->shipping_agent }}</td>
                                    <td>{{ $packing->net_weight }}</td>
                                    <td>{{ $packing->gross_weight }}</td>
                        <td>
                            <form action="{{ route('packinglist.destroy', $packing->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Approve and delete?')">Approve Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
