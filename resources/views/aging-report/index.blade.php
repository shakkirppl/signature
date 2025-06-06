@extends('layouts.layout')
@section('content')

<div class="container-fluid">
    <h1 class="mb-4">Aging Summary Report</h1>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Summary</h6>
            <div>
                <a href="{{ route('aging.detailed') }}" class="btn btn-sm btn-info">View Detailed</a>
                <a href="{{ route('aging.customers') }}" class="btn btn-sm btn-primary">Customer Aging</a>
                <a href="{{ route('aging.suppliers') }}" class="btn btn-sm btn-warning">Supplier Aging</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h4>Customer Receivables</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>Aging Period</th>
                                    <th class="text-right">Count</th>
                                    <th class="text-right">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(['0-30', '31-60', '61-90', '90+'] as $period)
                                <tr>
                                    <td>{{ $period }} days</td>
                                    <td class="text-right">{{ number_format($customerSummary[$period]['count']) }}</td>
                                    <td class="text-right">{{ number_format($customerSummary[$period]['amount'], 2) }}</td>
                                </tr>
                                @endforeach
                                <tr class="table-active">
                                    <td><strong>Total</strong></td>
                                    <td class="text-right"><strong>{{ number_format($customerSummary['total']['count']) }}</strong></td>
                                    <td class="text-right"><strong>{{ number_format($customerSummary['total']['amount'], 2) }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <h4>Supplier Payables</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>Aging Period</th>
                                    <th class="text-right">Count</th>
                                    <th class="text-right">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                               @foreach(['0-30', '31-60', '61-90', '90+'] as $period)
<tr>
    <td>{{ $period }} days</td>
    <td class="text-right">{{ number_format($supplierSummary[$period]['count']) }}</td>
    <td class="text-right">{{ number_format($supplierSummary[$period]['amount'], 2) }}</td>
</tr>
@endforeach
                                <tr class="table-active">
                                    <td><strong>Total</strong></td>
                                    <td class="text-right"><strong>{{ number_format($supplierSummary['total']['count']) }}</strong></td>
                                    <td class="text-right"><strong>{{ number_format($supplierSummary['total']['amount'], 2) }}</strong></td>
                                </tr>
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
