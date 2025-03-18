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
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Airline Payment</h4>
                    <div class="col-md-6 heading">
                        <a href=" {{ route('airline.index') }}" class="backicon"><i class="mdi mdi-backburger"></i></a>
                    </div>
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('airline.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                        <div class="row">
                            <!-- First Section -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="code" class="">Code</label>
                                    <input type="text" class="form-control" id="code" name="code" value="{{$invoice_no}}" readonly>
                                </div>

                                <div class="form-group">
    <label for="type" class="required">Type</label>
    <select class="form-control" id="type" name="type" required onchange="filterCOA()">
        <option value="">Select Type</option>
        <option value="AIRLINES">AIRLINES</option>
        <option value="AIRLINES AGENT">AIRLINES AGENT</option>
    </select>
</div>

                                <div class="form-group">
                                    <label for="airline_name">Airline Name:</label>
                                    <select class="form-control" id="airline_name" name="airline_name">
                                        <option value="Qatar Airways" selected>Qatar Airways</option>
                                        <option value="Ethiopian Airlines">Ethiopian Airlines</option>
                                    </select>
                                </div>

                                <div class="form-group">
    <label for="coa_id" class="required">COA</label>
    <select class="form-control" name="coa_id" id="coa_id" required>
        <option value="">Select Account</option>
        @foreach ($airlinesCOA as $account)
            <option value="{{ $account->id }}" data-type="AIRLINES">{{ $account->name }}</option>
        @endforeach
        @foreach ($agentsCOA as $account)
            <option value="{{ $account->id }}" data-type="AIRLINES AGENT">{{ $account->name }}</option>
        @endforeach
    </select>
</div>

                                <div class="form-group">
                                    <label for="shipment_id" class="form-label">Shipment No:</label>
                                    <select name="shipment_id" id="shipment_id" class="form-control" required>
                                        <option value="">Select Shipment</option>
                                        @foreach ($shipments as $shipment)
                                            <option value="{{ $shipment->id }}">{{ $shipment->shipment_no }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="customer_id" class="form-label">Customer:</label>
                                    <select name="customer_id" id="customer_id" class="form-control" required>
                                        <option value="">Select Customer</option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}">{{ $customer->customer_name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="air_waybill_no" class="required">Air waybill No</label>
                                    <input type="text" class="form-control" id="air_waybill_no" name="air_waybill_no">
                                </div>
                                <div class="form-group">
                                    <label for="air_waybill_charge">Air waybill Charge</label>
                                    <input type="text" class="form-control" id="air_waybill_charge" name="air_waybill_charge">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date" class="required">Date</label>
                                    <input type="date" class="form-control" id="date" name="date" required>
                                </div>

                                <div class="form-group">
                                    <label for="airline_number">Flight Number:</label>
                                    <select class="form-control" id="airline_number" name="airline_number">
                                        <option value="QR 1476" selected>QR 1476</option>
                                        <option value="ET 814">ET 814</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="amount" class="required">Amount</label>
                                    <input type="text" class="form-control" id="amount" name="amount" placeholder="Enter Amount" required oninput="formatNumber(this)">
                                </div>

                                <div class="form-group">
                                    <label for="total_weight">Total Weight</label>
                                    <input type="text" class="form-control" id="total_weight" name="total_weight">
                                </div>

                                <div class="form-group">
                                    <label for="documents_charge">Documents Charge</label>
                                    <input type="text" class="form-control" id="documents_charge" name="documents_charge">
                                </div>

                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="4"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="submitbutton">
                            <button type="submit" class="btn btn-primary mt-4 d-block mx-auto">Submit <i class="fas fa-save"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function formatNumber(input) {
        let value = input.value.replace(/,/g, '');
        let number = parseFloat(value);
        if (!isNaN(number)) {
            input.value = new Intl.NumberFormat('en-US').format(number);
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const dateInput = document.getElementById('date');
        dateInput.value = new Date().toISOString().split('T')[0];
    });

   
</script>
<script>
    function filterCOA() {
        let type = document.getElementById('type').value;
        let coaDropdown = document.getElementById('coa_id');
        let options = coaDropdown.getElementsByTagName('option');

        for (let i = 0; i < options.length; i++) {
            let optionType = options[i].getAttribute('data-type');
            if (type === '' || optionType === type) {
                options[i].style.display = '';
            } else {
                options[i].style.display = 'none';
            }
        }
        coaDropdown.value = ''; // Reset selection when type changes
    }
</script>

@endsection
