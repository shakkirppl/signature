@extends('layouts.layout')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Edit Inspection</h4>
                    
                    <form method="POST" action="{{ route('inspection.update', $inspection->id) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <label>Order No</label>
                                <input type="text" name="order_no" class="form-control" value="{{ $inspection->order_no }}" required>
                            </div>
                            <div class="col-md-6">
                                <label>Inspection No</label>
                                <input type="text" name="inspection_no" class="form-control" value="{{ $inspection->inspection_no }}" required readonly>
                            </div>
                        </div>
                        
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label>Date</label>
                                <input type="date" name="date" class="form-control" value="{{ $inspection->date }}" required  readonly>
                            </div>
                            <div class="col-md-6">
                                <label>Supplier</label>
                                <select name="supplier_id" class="form-control" required>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ $supplier->id == $inspection->supplier_id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-12">
                                <label>Products</label>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Recived Quantity</th>
                                            <th>Male</th>
                                            <th> Female</th>
                                            <th>Rejected Male</th>
                                            <th>Rejected Female</th>
                                            <th>Rejected Reason</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($inspection->details as $detail)
                                            <tr>
                                                <td>{{ $detail->product->product_name }}</td>
                                                <td><input type="number" name="products[{{ $detail->id }}][received_qty]" class="form-control" value="{{ $detail->received_qty }}"></td>
                                                <td><input type="number" name="products[{{ $detail->id }}][male_accepted_qty]" class="form-control" value="{{ $detail->male_accepted_qty }}"></td>
                                                <td><input type="number" name="products[{{ $detail->id }}][female_accepted_qty]" class="form-control" value="{{ $detail->female_accepted_qty }}"></td>
                                                <td><input type="number" name="products[{{ $detail->id }}][male_rejected_qty]" class="form-control" value="{{ $detail->male_rejected_qty }}"></td>
                                                <td><input type="number" name="products[{{ $detail->id }}][female_rejected_qty]" class="form-control" value="{{ $detail->female_rejected_qty }}"></td>
                                                <td>
                                                    <select name="products[{{ $detail->id }}][rejected_reason]" class="form-control">
                                                        <option value="">Select Reason</option>
                                                        @foreach ($rejectReasons as $reason)
                                                         <option value="{{ $reason->id }}" {{ isset($detail->rejected_reasons) && $detail->rejected_reasons == $reason->id ? 'selected' : '' }}>
                                                      {{ $reason->rejected_reasons }}
                                                       </option>
                                                       @endforeach
                                                    </select>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <button type="submit" class="btn btn-success">Update</button>
                            <a href="{{ route('inspection.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
