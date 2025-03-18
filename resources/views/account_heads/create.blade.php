<!-- resources/views/account_heads/create.blade.php -->
@extends('layouts.layout')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-lg-6 mx-auto">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Create Account Head</h4>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ url('account-heads/store') }}">
                        @csrf

                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter account head name" required>
                        </div>
                        <div class="form-group">
                            <label for="opening_balance">Opening Balance</label>
                            <input type="number" class="form-control" id="opening_balance" name="opening_balance" placeholder="Enter opening balance" >
                        </div>

                        <div class="form-group">
                            <label>Type</label>
                            <div>
                                <label class="mr-3">
                                    <input type="radio" name="dr_cr" value="Dr" > Dr
                                </label>
                                <label>
                                    <input type="radio" name="dr_cr" value="Cr" > Cr
                                </label>
                            </div>
                        </div>

                        <input type="hidden" id="parent_id" name="parent_id" value="{{ $parentId ?? '' }}">

                        <button type="submit" class="btn btn-primary">Create</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
function handleDelete(id) {
    if (confirm('Are you sure you want to delete this account head?')) {
        fetch(`{{ url('account-heads') }}/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        }).then(response => response.json())
          .then(data => {
              if (data.success) {
                  alert(data.message);
                  location.reload(); // Refresh page to reflect changes
              } else {
                  alert('Error deleting account head.');
              }
          }).catch(error => console.error('Error:', error));
    }
}


</script>
@endsection
