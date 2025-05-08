@extends('layouts.layout')
@section('content')

<div class="main-panel">
  <div class="content-wrapper">
    <div class="col-lg-8 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Customer Feedback</h4>
          <div class="col-md-12 heading">
                        <a href=" {{ route('customer-feedback.index') }}" class="backicon"><i class="mdi mdi-backburger"></i></a>
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

          <form action="{{ route('customer-feedback.store') }}" method="POST">
            @csrf

            <div class="form-group">
              <label for="date" class="required">Date</label>
              <input type="date" class="form-control" id="date" name="date" value="{{ date('Y-m-d') }}" required>
            </div>

            <div class="form-group">
              <label for="customer_id" class="required">Customer</label>
              <select name="customer_id" id="customer_id" class="form-control select2" required>
  <option value="">-- Select Customer --</option>
  @foreach($customers as $customer)
    <option value="{{ $customer->id }}">{{ $customer->customer_name }}</option>
  @endforeach
</select>

            </div>

            <div class="form-group">
              <label for="feedback" class="required">Feedback</label>
              <textarea name="feedback" class="form-control" rows="5" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Submit Feedback</button>
          </form>

        </div>
      </div>
    </div>
  </div>
</div>


@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>


<script>
  $(document).ready(function () {
    $('#customer_id').select2({
      placeholder: "-- Select Customer --",
      allowClear: true
    });
  });
</script>
@endsection



@endsection



