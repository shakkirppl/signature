@extends('layouts.layout')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Sales Report</h4>
                    
                    <form method="GET" action="">
                        @csrf
                        <div class="row">
                            <div class="col-md-3">
                                <select class="form-control" name="customer_id">
                                    <option value="">Select Customer</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->customer_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="date" name="from_date" class="form-control" placeholder="From Date">
                            </div>
                            <div class="col-md-3">
                                <input type="date" name="to_date" class="form-control" placeholder="To Date">
                            </div>
                            <div class="col-md-1">
                                <button type="submit" class="btn btn-primary">Get</button>
                            </div>
                            <div class="col-md-2">
                            <!-- <button class="btn btn-success mt-3" onclick="exportTableToExcel('sales-report-table', 'Sales_Report')">
                             Export to Excel
                            </button>  
                            </div> -->
                   
                        </div>
                    </form>

                    <div class="table-responsive mt-4">
                        <table class="table table-hover" id="sales-report-table">
                            <thead>
                                <tr>
                                    <th>Invoice No</th>
                                    
                                    <th>Date</th>
                                    <th>Customer</th>
                                    <th>Grand Total</th>
                                    <th>Advance Amount</th>
                                    <th>Balance Amount</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($SalesPayments as $sale)
                                <tr>
                                    
                                    <td>{{ $sale->order_no }}</td>
                                    <td>{{$sale->date }}</td>
                                    <td>{{ $sale->customer->customer_name }}</td>
                                    <td>{{ number_format($sale->grand_total, 2) }}</td>
                                    <td>{{ number_format($sale->advance_amount, 2) }}</td>
                                    <td>{{ number_format($sale->balance_amount, 2) }}</td>
                                    <td>
                                        @if($sale->status == 1)
                                            <span class="badge badge-success">Confirmed</span>
                                        @else
                                            <span class="badge badge-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                    <a href="{{ route('sales_payment.view', $sale->id) }}" class="btn btn-info btn-sm">View</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                  

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<!-- <script>
function exportTableToExcel(tableID, filename = '') {
    let table = document.getElementById(tableID);
    let wb = XLSX.utils.book_new();
    let ws = XLSX.utils.table_to_sheet(table);

    // Ensure date is formatted correctly
    let range = XLSX.utils.decode_range(ws['!ref']);
    for (let row = range.s.r + 1; row <= range.e.r; row++) {
        let cellRef = XLSX.utils.encode_cell({ r: row, c: 1 }); 
        if (ws[cellRef] && ws[cellRef].v) {
            let rawDate = ws[cellRef].v;  
            let formattedDate = new Date(rawDate).toISOString().split('T')[0]; 
            ws[cellRef].v = formattedDate;
            ws[cellRef].t = 's'; // Set as string
        }
    }

    XLSX.utils.book_append_sheet(wb, ws, "Sales Report");
    XLSX.writeFile(wb, filename ? filename + ".xlsx" : "sales_report.xlsx");
}
</script> -->




@endsection
