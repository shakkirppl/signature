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
                    <h4 class="card-title">Edit Payment Voucher</h4>
                    <div class="col-md-6 heading">
                        <a href="{{ route('paymentvoucher.index') }}" class="backicon"><i class="mdi mdi-backburger"></i></a>
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

                    <form action="{{ route('paymentvoucher.update', $voucher->id) }}" method="POST">
                        @csrf
                        @method('POST')
                        <div class="row">
                            <!-- First Section -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="code" class="">Code</label>
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
                                    <label for="date" class="form-group">Payment To</label>
                                    <select name="employee_id" class="form-control"  >
                                         <option value="">Select Employee</option>
                                             @foreach ($employees as $employee)
                                              <option value="{{ $employee->id }}" {{ $voucher->employee_id == $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>
                                             @endforeach
                                    </select>
                                </div>
                                    

                                
                            </div>
                            <!-- Second Section -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type" class="required">Type</label>
                                    <select class="form-control" id="type" name="type" required>
                                        <option value="cash" {{ $voucher->type === 'cash' ? 'selected' : '' }}>Cash</option>
                                        <option value="bank" {{ $voucher->type === 'bank' ? 'selected' : '' }}>Bank</option>
                                        <option value="bank" {{ $voucher->type === 'mobilemoney' ? 'selected' : '' }}>Mobile Money</option>
                                    </select>
                                </div>

                                <div id="bankNameField" class="form-group" style="{{ $voucher->type === 'bank' ? 'display: block;' : 'display: none;' }}">
                                  <label for="bank_id">Bank Name</label>
                                     <select class="form-control" name="bank_id">
                                           <option value="">Select Bank</option>
                                              @foreach ($banks as $bank)
                                                <option value="{{ $bank->id }}" {{ $voucher->bank_id === $bank->id ? 'selected' : '' }}>
                                                 {{ $bank->bank_name }}
                                               </option>
                                              @endforeach
                                      </select>
                                       </div>
                                       <div class="form-group ">
                                    <label class=" required">Currency</label>
                                   
                                        <select class="form-control" name="currency">
                                        <option value="Shilling" {{ $voucher->currency == 'Shilling' ? 'selected' : '' }}>Shilling</option>
                                            <option value="USD" {{ $voucher->currency == 'USD' ? 'selected' : '' }}>USD</option>
                                        </select>
                                    
                                </div>
                                <div class="form-group">
                                    <label for="amount" class="required">Amount</label>
                                    <input type="number" class="form-control" id="amount" name="amount" value="{{ $voucher->amount }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="4">{{ $voucher->description }}</textarea>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Update</button>
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
