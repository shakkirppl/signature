<!DOCTYPE html>
<html>
<head>
  <title>Supplier Outstanding Report</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      font-size: 12px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 12px;
    }
    th, td {
      border: 1px solid #000;
      padding: 6px;
      text-align: center;
    }
    th {
      background-color: #f0f0f0;
    }
    .text-success { color: green; }
    .text-danger { color: red; }
  </style>
</head>
<body onload="window.print()">
<img src="{{ url('image/signature-logo.png') }}" class="logo">

<h2 style="text-align:center;">Supplier Outstanding Report</h2>

<table>
  <thead>
    <tr>
      <th>No</th>
      <th>Supplier Name</th>
      <th>Total Payment</th>
      <th>Total Receipt</th>
      <th>Outstanding Balance</th>
    </tr>
  </thead>
  <tbody>
    @php
      $totalPayment = 0;
      $totalReceipt = 0;
      $totalOutstanding = 0;
      $sumGreen = 0;
      $sumRed = 0;
    @endphp

    @foreach ($outstandings as $index => $outstanding)
      @php
        $totalPayment += $outstanding->total_payment;
        $totalReceipt += $outstanding->total_receipt;
        $totalOutstanding += $outstanding->outstanding_balance;

        if ($outstanding->total_payment > $outstanding->total_receipt) {
          $sumRed += $outstanding->outstanding_balance;
        } else {
          $sumGreen += $outstanding->outstanding_balance;
        }
      @endphp
      <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $suppliers[$outstanding->account_id] ?? 'Unknown Supplier' }}</td>
        <td>{{ number_format($outstanding->total_payment, 2) }}</td>
        <td>{{ number_format($outstanding->total_receipt, 2) }}</td>
        <td class="{{ $outstanding->total_payment > $outstanding->total_receipt ? 'text-danger' : 'text-success' }}">
          {{ number_format($outstanding->outstanding_balance, 2) }}
        </td>
      </tr>
    @endforeach
  </tbody>
  <tfoot>
    <tr>
      <th colspan="2">Total</th>
      <th>{{ number_format($totalPayment, 2) }}</th>
      <th>{{ number_format($totalReceipt, 2) }}</th>
      <th>{{ number_format($totalOutstanding, 2) }}</th>
    </tr>
    <tr>
      <th colspan="4" style="text-align:right;">Total Payable Amount</th>
      <th class="text-success">-{{ number_format($sumGreen, 2) }}</th>
    </tr>
    <tr>
      <th colspan="4" style="text-align:right;">Total Receivable Amount</th>
      <th class="text-danger">{{ number_format($sumRed, 2) }}</th>
    </tr>
  </tfoot>
</table>

</body>
</html>
