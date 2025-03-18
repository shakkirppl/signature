@extends('layouts.layout')
@section('content')
<div class="container-fluid py-4">
    <div class="page-header d-flex justify-content-between align-items-center">
        <h1 class="text-primary">Ledger</h1>
        <button type="button" class="btn btn-success btn-sm" id="exportExcel">
            <i class="fa fa-file-excel-o"></i> Export to Excel
        </button>
    </div>

    <div class="card shadow-lg p-4 mt-3">
        @if(session('message'))
            <div class="alert alert-info">{{ session('message') }}</div>
        @endif

        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <label for="from_date" class="form-label">From Date</label>
                <input type="date" id="from_date" name="from_date" value="{{ request('from_date', date('Y-m-d')) }}" class="form-control equal-input">
            </div>
            <div class="col-md-4">
                <label for="to_date" class="form-label">To Date</label>
                <input type="date" id="to_date" name="to_date" value="{{ request('to_date', date('Y-m-d')) }}" class="form-control equal-input">
            </div>
            <div class="col-md-4">
                <label for="account" class="form-label">Account</label>
                <select name="account" class="form-select equal-input select2">
                    <option value="">Select Account</option>
                    @foreach($accounts as $id => $account)
                        <option value="{{ $id }}" {{ request('account') == $id ? 'selected' : '' }}>{{ $account }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-12 d-flex justify-content-end">
                <button type="submit" class="btn btn-primary btn-sm">Go</button>
            </div>
        </form>
    </div>

    <div class="table-responsive mt-4">
        <table id="simple-table" class="table table-bordered table-striped text-center">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Date</th>
                    <th>Accounts</th>
                    <th>Dr</th>
                    <th>Cr</th>
                    <th>Description</th>
                    <th>Narration</th>
                </tr>
            </thead>
            <tbody>
                <tr class="fw-bold text-primary">
                    <td colspan="3">Opening Balance</td>
                    <td></td>
                    <td>{{ $opening_balance > 0 ? number_format($opening_balance, 2) : '' }}</td>
                    <td>{{ $opening_balance < 0 ? number_format(abs($opening_balance), 2) : '' }}</td>
                    <td colspan="2"></td>
                </tr>
                @forelse($ledgers as $key => $ledger)
                    @if($ledger->dr != 0.00 || $ledger->cr != 0.00)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $ledger->date }}</td>
                            <td>{{ $ledger->name }}</td>
                            <td>{{ $ledger->dr ? number_format($ledger->dr, 2) : '' }}</td>
                            <td>{{ $ledger->cr ? number_format($ledger->cr, 2) : '' }}</td>
                            <td>{{ $ledger->description }}</td>
                            <td>{{ $ledger->narration }}</td>
                        </tr>
                    @endif
                @empty
                    <tr>
                        <td colspan="7" class="text-danger fw-bold">No records found!</td>
                    </tr>
                @endforelse
                <tr class="fw-bold text-success">
                    <td colspan="2">Total</td>
                    <td></td>
                    <td>{{ number_format($total_dr, 2) }}</td>
                    <td>{{ number_format($total_cr, 2) }}</td>
                    <td colspan="2"></td>
                </tr>
                <tr class="fw-bold text-warning">
                    <td colspan="2">Closing Balance</td>
                    <td></td>
                    <td>{{ $closing_balance > 0 ? number_format($closing_balance, 2) : '' }}</td>
                    <td>{{ $closing_balance < 0 ? number_format(abs($closing_balance), 2) : '' }}</td>
                    <td colspan="2"></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelector("#exportExcel").addEventListener("click", function () {
        let table = document.querySelector("#simple-table");
            let wb = XLSX.utils.book_new();
            let ws = XLSX.utils.table_to_sheet(table);
            XLSX.utils.book_append_sheet(wb, ws, "Ledger Data");
            XLSX.writeFile(wb, "Ledger_Report.xlsx");
        });
    
    $('.select2').select2({
        placeholder: "Select an Account",
        allowClear: true
    });
});
</script>
<style>
    .equal-input {
        width: 100%;
        height: 40px;
    }
</style>
@endsection