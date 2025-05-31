@extends('layouts.layout')
@section('content')

<div class="main-panel">
  <div class="content-wrapper">
    <h4>Pending Delete Requests</h4>

    <table class="table table-bordered table-striped table-sm" style="font-size: 12px;">
              <thead style="background-color: #d6d6d6; color: #000;">
        <tr>
          <th>No</th>
          <th>Skinning Code</th>
          <th>Date</th>
          <th>Time</th>
          <th>Shipment</th>
          <th>Requested By</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($pendingSkinnings as $index => $record)
        <tr>
          <td>{{ $index + 1 }}</td>
          <td>{{ $record->skinning_code }}</td>
          <td>{{ $record->time }}</td>
         <td>{{ $record->shipment->shipment_no ?? 'N/A' }}</td>
          <td>{{ $record->date }}</td>
          <td>{{ $record->user->name ?? 'Unknown' }}</td>
          <td>
            
             <a href="{{ route('skinning.destroy', $record->id) }}" 
                       class="btn btn-danger btn-sm" 
                       onclick="return confirm('Approve and delete this record?')">Approve Delete
                 </a>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

@endsection
