@extends('layouts.layout')
@section('content')
<style>
  .required:after {
    content: " *";
    color: red;
  }
</style>
<div class="main-panel">
  <div class="content-wrapper">
    <div class="col-12 grid-margin createtable">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <h4 class="card-title">New Customer</h4>
            </div>
            <div class="col-md-6 heading">
              <a href="{{ route('customer.index') }}" class="backicon"><i class="mdi mdi-backburger"></i></a>
            </div>
          </div>
          <div class="row">
            <br>
          </div>
          <div class="col-xl-12 col-md-12 col-sm-12 col-12">
            @if ($errors->any())
            <div class="alert alert-danger">
              <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div><br />
            @endif
          </div>
          <div class="container" style="border: 1px solid black; padding: 20px; max-width: 800px;">
    <h2 class="text-center">ANIMAL RECEIVING NOTE</h2>
    <p><strong>Signature Trading Limited</strong></p>
    <p>Kaloleni Street | Condo Building | Office No: 218 | PO Box: 1506 | Arusha | Tanzania</p>
    <p>Landline No: +255 272 97 97 97 | Mobile No: +255 696 666 606 | Email: info@signaturetz.com</p>
    <p><strong>ARN No:</strong> ________________ <strong>Date:</strong> ________________</p>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ url('/animal-receiving-note') }}" method="POST">
        @csrf
        <table class="table table-bordered">
            <tr>
                <td><strong>Supplier Name:</strong></td>
                <td><input type="text" name="supplier_name" class="form-control" required></td>
            </tr>
            <tr>
                <td><strong>Supplier Address:</strong></td>
                <td><input type="text" name="supplier_address" class="form-control" required></td>
            </tr>
            <tr>
                <td><strong>Supplier Registration ID:</strong></td>
                <td><input type="text" name="supplier_registration_id" class="form-control" required></td>
            </tr>
            <tr>
                <td><strong>Animal Movement Permit Number:</strong></td>
                <td><input type="text" name="permit_number" class="form-control" required></td>
            </tr>
            <tr>
                <td><strong>Copy Attached?</strong></td>
                <td>
                    <select name="copy_attached" class="form-control" required>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><strong>Truck Number:</strong></td>
                <td><input type="text" name="truck_number" class="form-control" required></td>
            </tr>
        </table>

        <h4>Signatures</h4>
        <table class="table table-bordered">
            <tr>
                <td><strong>Supplier Signature:</strong></td>
                <td><input type="text" name="supplier_signature" class="form-control" required></td>
            </tr>
            <tr>
                <td><strong>Inspector Signature:</strong></td>
                <td><input type="text" name="inspector_signature" class="form-control" required></td>
            </tr>
        </table>

        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('script')

@endsection
