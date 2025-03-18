@extends('layouts.layout')

@section('content')

<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-6">
                            <h4 class="card-title">Account Heads</h4>
                        </div>
                        <div class="col-6 text-end">
                          
                        </div>
                    </div>

                    @if($message = Session::get('success'))
                        <div class="alert alert-success">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($accountHeads as $head)
                                    <tr>
                                        <td>
                                            <ul style="list-style-type: none;">
                                                @include('account_heads.partials.head', ['head' => $head])
                                            </ul>
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

@section('script')
<script>
    $(document).ready(function () {
        // Toggle child list and change arrow direction
        $('.arrow-toggle').on('click', function () {
            let $arrow = $(this);
            let $childList = $arrow.closest('li').find('.child-heads').first();

            // Slide toggle the child list
            $childList.slideToggle('fast', function () {
                // Change the arrow direction based on visibility
                if ($childList.is(':visible')) {
                    $arrow.html('&#9662;'); // Down Arrow (▼)
                } else {
                    $arrow.html('&#9656;'); // Right Arrow (▶)
                }
            });
        });

        // Optional: Clicking on the name can also toggle the child list
        $('.toggle-head').on('click', function () {
            $(this).siblings('.arrow-toggle').trigger('click');
        });
    });
    document.addEventListener('DOMContentLoaded', () => {
    let lastSelectedId = null;

    // Handle clicks on toggle-head
    document.querySelectorAll('.toggle-head').forEach(item => {
        item.addEventListener('click', () => {
            const currentId = item.dataset.id;

            // Remove buttons from the previously selected item
            if (lastSelectedId && lastSelectedId !== currentId) {
                const previousItem = document.querySelector(`.toggle-head[data-id="${lastSelectedId}"]`);
                const buttonContainer = previousItem.parentNode.querySelector('.action-buttons');
                if (buttonContainer) buttonContainer.remove();
            }

            // Add buttons to the current item if not already added
            const parentDiv = item.parentNode;
            if (!parentDiv.querySelector('.action-buttons')) {
                const buttonContainer = document.createElement('div');
                buttonContainer.classList.add('action-buttons');
                buttonContainer.innerHTML = `
                    <button class="btn btn-sm btn-primary new-btn">New</button>
                    <button class="btn btn-sm btn-warning edit-btn">Edit</button>
                `;
                parentDiv.appendChild(buttonContainer);

                // Add event listeners for the buttons
                buttonContainer.querySelector('.new-btn').addEventListener('click', () => handleNew(currentId));
                buttonContainer.querySelector('.edit-btn').addEventListener('click', () => handleEdit(currentId));
                buttonContainer.querySelector('.delete-btn').addEventListener('click', () => handleDelete(currentId));
            }

            // Update last selected item
            lastSelectedId = currentId;
        });
    });

    // Handle New
    function handleNew(parentId) {
    // Redirect to the New page, passing the parentId as a query parameter
    const baseUrl = '{{ url("account-heads/new") }}';
    
    // Append the `parent_id` dynamically
    const newUrl = `${baseUrl}?parent_id=${parentId}`;
    
    // Redirect to the constructed URL
    window.location.href = newUrl;
    }

    // Handle Edit
    function handleEdit(id) {
        // Redirect to the Edit page with the account head ID

    const baseUrl = '{{ url("account-heads/edit") }}';
            // Append the `parent_id` dynamically
     const newUrl = `${baseUrl}/${id}`;
    
    // Redirect to the constructed URL
     window.location.href = newUrl;
 
    }

    // Handle Delete
    // function handleDelete(id) {
    //     // Redirect to the Delete confirmation page
    //     if (confirm('Are you sure you want to delete this account head?')) {
    // const baseUrl = '{{ url("account-heads/delete") }}';
    //         // Append the `parent_id` dynamically
    //  const newUrl = `${baseUrl}?parent_id=${id}`;
    
    // // Redirect to the constructed URL
    //  window.location.href = newUrl;
    //     }
    // }
});


</script>

<script>
    function handleDelete(id) {
        if (confirm('Are you sure you want to delete this account head?')) {
            fetch(`{{ url('account-heads') }}/${id}`, {
                method: 'get',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload(); // Refresh page to reflect changes
                } else {
                    alert('Error deleting account head.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while deleting the account head.');
            });
        }
    }
</script>

<script>
   function handleEdit(id) {
    window.location.href = `{{ url('account-heads/edit') }}/${id}`;
}
</script>
@endsection

