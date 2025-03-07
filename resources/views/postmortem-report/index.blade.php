@extends('layouts.layout')
@section('content')
<div class="main-panel">
  <div class="content-wrapper">
    <div class="col-12 grid-margin">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <h4 class="card-title">PostMortem Reports</h4>
            </div>
            <div class="col-md-6 text-right">
            <a href="{{ route('postmortem.create') }}" class="newicon"><i class="mdi mdi-new-box"></i></a>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th>No</th>
                 
                   <th>Postmortem No</th>
                    <th>Inspection Date</th>
                    <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($postmortems as $index => $postmortem)
                <tr>
                  <td>{{ $index + 1 }}</td>
                  <td>{{ $postmortem->postmortem_no }}</td>
                  <td>{{ $postmortem->inspection_date }}</td>
                       
                  <td>
                  <a href="{{ route('postmortem.edit', $postmortem->id) }}" class="btn btn-warning">Edit</a>
                  <a href="{{ route('postmortem.print', $postmortem->id) }}" target="_blank" class="btn btn-primary">
                   Print 
                    </a>

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