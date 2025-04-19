@extends('layouts.layout')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-12 grid-margin createtable">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="card-title">Death Animal Details</h4>
                        </div>
                        <div class="col-md-6 heading" style="text-align:right;">
                        </div>
                    </div>

                    <table class="table">
                        <tbody>
                            <tr>
                                <th>Date:</th>
                                <td>{{ $deathAnimal->date }}</td>
                            </tr>
                            <tr>
                                <th>Shipment No:</th>
                                <td>{{ $deathAnimal->shipment->shipment_no ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Supplier:</th>
                                <td>{{ $deathAnimal->supplier->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Inspection No:</th>
                                <td>{{ $deathAnimal->inspection ? $deathAnimal->inspection->inspection_no : 'N/A' }}</td>
                            </tr>

                            <tr>
                                <th>Note</th>
                                <td>{{ $deathAnimal->note ?? 'N/A' }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <h4>Product Details</h4>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                               
                                <th>Death Male Qty</th>
                                <th>Death Female Qty</th>
                                <th>Total Death Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($deathAnimal->details as $detail)
                                <tr>
                                    <td>{{ $detail->product->product_name }}</td>
                                   
                                    <td>{{ $detail->death_male_qty }}</td>
                                    <td>{{ $detail->death_female_qty }}</td>
                                    <td>{{ $detail->death_male_qty + $detail->death_female_qty }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
