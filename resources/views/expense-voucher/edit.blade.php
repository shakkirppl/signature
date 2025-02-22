@extends('layouts.layout')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Edit Vouchers Voucher</h4>
                    <div class="col-md-6 heading">
                        <a href="{{ route('expensevoucher.index') }}" class="backicon"><i class="mdi mdi-backburger"></i></a>
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
                    
                    <form action="{{ route('expensevoucher.update', $voucher->id) }}" method="POST">
                        @csrf
                        @method('POST')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="code">Code</label>
                                    <input type="text" class="form-control" id="code" name="code" value="{{ $voucher->code }}" readonly>
                                </div>

                                <div class="form-group">
                                    <label for="date" class="required">Date</label>
                                    <input type="date" class="form-control" id="date" name="date" value="{{ $voucher->date }}" required>
                                </div>

                                <div class="form-group">
    <label for="coa_id" class="required"> COA</label>
    <select class="form-control" name="coa_id" id="coa_id" required>
        <option value="">Select Account</option>
        @foreach ($coa as $account)
            <option value="{{ $account->id }}"  {{ $voucher->coa_id == $account->id ? 'selected' : '' }}>{{ $account->name }}</option>
        @endforeach
    </select>
</div>
                                
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="4">{{ $voucher->description }}</textarea>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="shipment_id">Shipment No</label>
                                    <select name="shipment_id" id="shipment_id" class="form-control" required>
                                        <option value="">Select Shipment</option>
                                        @foreach ($shipments as $shipment)
                                            <option value="{{ $shipment->id }}" {{ $voucher->shipment_id == $shipment->id ? 'selected' : '' }}>{{ $shipment->shipment_no }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="type" class="required">Type</label>
                                    <select class="form-control" id="type" name="type" required>
                                        <option value="cash" {{ $voucher->type == 'cash' ? 'selected' : '' }}>Cash</option>
                                        <option value="bank" {{ $voucher->type == 'bank' ? 'selected' : '' }}>Bank</option>
                                    </select>
                                </div>
                                
                                <div id="bankNameField" class="form-group" style="{{ $voucher->type == 'bank' ? '' : 'display: none;' }}">
                                    <label for="bank_id" class="required">Bank Name</label>
                                    <select class="form-control" name="bank_id">
                                        <option value="">Select Bank</option>
                                        @foreach ($banks as $bank)
                                            <option value="{{ $bank->id }}" {{ $voucher->bank_id == $bank->id ? 'selected' : '' }}>{{ $bank->bank_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="amount" class="required">Amount</label>
                                    <input type="number" class="form-control" id="amount" name="amount" value="{{ $voucher->amount }}" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="submitbutton">
                            <button type="submit" class="btn btn-primary mb-2"> Update <i class="fas fa-save"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('type').addEventListener('change', function() {
        var bankNameField = document.getElementById('bankNameField');
        if (this.value === 'bank') {
            bankNameField.style.display = 'block';
        } else {
            bankNameField.style.display = 'none';
        }
    });
</script>
@endsection