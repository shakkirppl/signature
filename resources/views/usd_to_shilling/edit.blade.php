@extends('layouts.layout')
@section('content')
<div class="container">
    <h2>Update Exchange Rate</h2>
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('usd_to_shilling.update', $currency->id) }}" method="POST">
        @csrf
        @method('POST')

        <div class="mb-3">
            <label for="usd" class="form-label">USD</label>
            <input type="text" class="form-control" name="usd" value="{{ $currency->usd }}" required>
        </div>

        <div class="mb-3">
            <label for="shilling" class="form-label">Shilling</label>
            <input type="text" class="form-control" name="shilling"value="{{ number_format($currency->shilling, 4) }}" required>
        </div>

        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('usd_to_shilling.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
