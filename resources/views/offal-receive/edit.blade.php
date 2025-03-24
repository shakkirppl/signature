@extends('layouts.layout')
@section('content')

<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Edit Offal Receive</h4>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('offal-receive.update', $offalReceive->id) }}" method="POST">
                        @csrf
                       

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="order_no" class="form-label">Order No:</label>
                                <input type="text" class="form-control" id="order_no" name="order_no" value="{{ $offalReceive->order_no }}" readonly>
                            </div>

                            <div class="col-md-4">
                                <label for="date" class="form-label">Date:</label>
                                <input type="date" class="form-control" id="date" name="date" value="{{ $offalReceive->date }}" required>
                            </div>

                            <div class="col-md-4">
                                <label for="shipment_id" class="form-label">Shipment No:</label>
                                <select name="shipment_id" id="shipment_id" class="form-control" required>
                                    <option value="">Select Shipment No</option>
                                    @foreach ($shipments as $shipment)
                                        <option value="{{ $shipment->id }}" {{ $shipment->id == $offalReceive->shipment_id ? 'selected' : '' }}>
                                            {{ $shipment->shipment_no }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Good Offals</th>
                                        <th>Damaged Offals</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <select name="product_id[]" class="form-control" required>
                                                <option value="">Select Product</option>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}" {{ $product->id == $offalReceive->product_id ? 'selected' : '' }}>
                                                        {{ $product->product_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td><input type="text" name="qty[]" class="form-control" value="{{ $offalReceive->qty }}" required></td>
                                        <td><input type="text" name="good_offal[]" class="form-control" value="{{ $offalReceive->good_offal }}" required></td>
                                        <td><input type="text" name="damaged_offal[]" class="form-control" value="{{ $offalReceive->damaged_offal }}" required></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-primary mt-2">Update</button>
                            <a href="{{ route('offal-receive.index') }}" class="btn btn-secondary mt-2">Cancel</a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
