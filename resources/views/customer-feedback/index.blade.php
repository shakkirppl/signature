@extends('layouts.layout')
@section('content')

<div class="main-panel">
  <div class="content-wrapper">
    <div class="col-lg-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Customer Feedback List</h4>
          <div class="col-md-12 text-right">
              <a href="{{ route('customer-feedback.create') }}" class="newicon"><i class="mdi mdi-new-box"></i></a>
            </div>
          <!-- Success Message -->
          @if(session('success'))
            <div class="alert alert-success">
              {{ session('success') }}
            </div>
          @endif

          <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
            <table class="table table-bordered table-striped table-sm" style="font-size: 12px;">
              <thead style="background-color: #d6d6d6; color: #000;">
              <tr>
                <th>No</th>
                <th>Date</th>
                <th>Customer</th>
                <th>Feedback</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($feedbacks as $index => $feedback)
                <tr>
                  <td>{{ $index + 1 }}</td>
                  <td>{{ $feedback->date }}</td>
                  <td>{{ $feedback->customer->customer_name }}</td>
                  <td>{{ $feedback->feedback }}</td>
                  <td>
                   
                   
                      <form action="{{ route('customer-feedback.destroy', $feedback->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">Delete</button>
                      </form>
                 
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
    font-size: 30px;
  }
</style>

@endsection
