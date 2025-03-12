@extends('layouts.layout')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <h4 class="card-title">Sales Packing List</h4>
                        </div>
                        <div class="col-6 text-end">
                            <a href="{{ route('packinglist.create') }}" class="newicon"><i class="mdi mdi-new-box"></i></a>

                        </div>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    <div class="table-responsive">
            <table class="table">
              <thead>
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
                                @foreach($packingLists as $packing)
                                <tr>
                                    <td>{{ $packing->packing_no }}</td>
                                    <td>{{ $packing->date }}</td>
                                    <td>{{ $packing->customer->customer_name ?? 'N/A' }}</td>
                                    <td>{{ $packing->shipping_mode }}</td>
                                    <td>{{ $packing->shipping_agent }}</td>
                                    <td>{{ $packing->net_weight }}</td>
                                    <td>{{ $packing->gross_weight }}</td>
                                    <td>
                                        <a href="{{ route('packinglist.show', $packing->id) }}" class="btn btn-info btn-sm">View</a>
                                        <a href="{{ route('packinglist.edit', $packing->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                        <a href="{{ route('packinglist.print', $packing->id) }}" class="btn btn-primary btn-sm">
                                         <i class="mdi mdi-printer"></i> Print
                                        </a>
                                        <form action="{{ route('packinglist.destroy', $packing->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
              </tbody>
            </table>
                   
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
