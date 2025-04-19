@extends('layouts.layout')
@section('content')

<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-6">
                            <h4 class="card-title">Slaughter Timing List</h4>
                        </div>
                        <div class="col-md-6 text-right">
            <a href="{{ route('new.schedule') }}" class="newicon"><i class="mdi mdi-new-box"></i></a>
            </div>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="table-responsive">
                        <table class="table ">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Slaughter Date</th>
                                    <th>Time</th>
                                    
                                    <th>Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($timings as $index => $timing)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $timing->date }}</td>
                                        <td>{{ $timing->time }}</td>
                                        <td>{{ $timing->created_at->format('d-m-Y H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No data found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection
