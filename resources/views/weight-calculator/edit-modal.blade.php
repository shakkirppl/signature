<form id="editWeightForm">
    @csrf
    <input type="hidden" name="weight_master_id" value="{{ $weightMaster->id }}">

    <div class="form-group">
        <label>Weight Code</label>
        <input type="text" class="form-control" name="weight_code" value="{{ $weightMaster->weight_code }}" readonly>
    </div>

    <div class="form-group">
        <label>Date</label>
        <input type="date" class="form-control" name="date" value="{{ $weightMaster->date }}" required>
    </div>
    <div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Weight</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($weightDetails as $detail)
                <tr>
                    <td>{{ $detail->product->product_name }}</td>
                    <td>
                        <input type="text" name="quandity[{{ $detail->id }}]" value="{{ $detail->quandity }}" class="form-control">
                    </td>
                    <td>
                        <input type="text" name="weight[{{ $detail->id }}]" value="{{ $detail->weight }}" class="form-control weight-input" style="width: 100px;">
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
    <!-- Single Total Weight Field -->
    <div class="form-group">
        <label for="edit_total_weight">Total Weight</label>
        <input type="text" id="edit_total_weight" class="form-control" readonly>
        <input type="hidden" name="total_weight" id="hidden_edit_total_weight">
    </div>

    <button type="submit" class="btn btn-primary">Update</button>
</form>

<script>
$(document).ready(function() {
    function calculateEditTotalWeight() {
        let totalWeight = 0;
        $('.weight-input').each(function() {
            totalWeight += parseFloat($(this).val()) || 0;
        });

        $('#edit_total_weight').val(totalWeight.toFixed(2) + " kg");
        $('#hidden_edit_total_weight').val(totalWeight.toFixed(2));
    }

    // Trigger calculation on input change
    $('.weight-input').on('input', calculateEditTotalWeight);

    // Calculate total weight when the modal opens
    setTimeout(() => {
        calculateEditTotalWeight();
    }, 500);

    $('#editWeightForm').submit(function(event) {
        event.preventDefault();

        $.ajax({
            url: "{{ route('update.weight.calculation') }}",
            type: "POST",
            data: $(this).serialize(),
            success: function(response) {
                Swal.fire('Updated!', 'Weight calculation updated successfully.', 'success').then(() => {
                    $('#editWeightModal').modal('hide');
                    location.reload();
                });
            }
        });
    });
});
</script>



