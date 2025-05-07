@extends('layouts.layout')
@section('content')

<div class="main-panel">
  <div class="content-wrapper">
    <div class="col-lg-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Edit Production Record</h4>

          @if($errors->any())
            <div class="alert alert-danger">
              <ul>
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form action="{{ route('production-record.update', $record->id) }}" method="POST">
            @csrf
           

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="required">Date</label>
                  <input type="date" class="form-control" name="date" value="{{ $record->date }}" required>
                </div>

                <div class="form-group">
                  <label>No of Animals Slaughtered</label>
                  <input type="number" class="form-control" name="number_of_animals" value="{{ $record->number_of_animals }}">
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label class="required">Species</label>
                  <select class="form-control" name="product_id" required>
                    <option value="">-- Select Product --</option>
                    @foreach($products as $product)
                      <option value="{{ $product->id }}" {{ $record->product_id == $product->id ? 'selected' : '' }}>
                        {{ $product->product_name }}
                      </option>
                    @endforeach
                  </select>
                </div>

                <div class="form-group">
                  <label>Processing Line Info</label>
                  <input type="text" class="form-control" name="processing_line" value="{{ $record->processing_line }}">
                </div>
              </div>
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('production-record.index') }}" class="btn btn-secondary">Cancel</a>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
