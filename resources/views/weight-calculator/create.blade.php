@extends('layouts.layout')
@section('content')
<style>
    .table {
    border-collapse: collapse;
    width: 70%;
}

button.remove-row {
    padding: 5px 10px;
}
</style>
<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-12 grid-margin createtable">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Weight Calculator </h4>
                    
                        <div class="col-md-12 text-right">
                            <strong>Shipment No: </strong>{{ $shipment_no }}
                        </div>
                        @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

                    <form method="POST" action="{{ route('weight_calculator.store') }}">
                        @csrf
                        <input type="hidden" name="shipment_id" value="{{ $shipment_id }}">
                        <div class="row mb-3 align-items-center">
                   <div class="col-md-4">
                        <label for="weight_code">Weight Code</label>
                        <input type="text" class="form-control" name="weight_code" value="{{$invoice_no}}" readonly>
                        <input type="hidden" class="form-control" id="inspection_id" name="inspection_id" value="{{ $inspection->id }}" >
                        <input type="hidden" class="form-control" id="purchaseOrder_id" name="purchaseOrder_id">



                   </div>

                    <div class="col-md-4">
                       <label for="date">Date</label>
                       <input type="date" class="form-control" name="date" required>
                   </div>

                   <div class="col-md-4">
                       <label for="supplier">Supplier</label>
                       <select id="supplierDropdown" class="form-control" style="width: 250px;" name="supplier_id">
                           <option value="">Select Supplier</option>
                                @foreach($suppliers as $supplier)
                                 <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                @endforeach
                          </select>
                  </div>
               </div>
               <div class="table-responsive">
                  <table class="table">
                            <thead>
                                <tr>
                                    <th>Products</th>
                                    <th>Male Accepted Quandity</th>
                                    <th>Female Accepted Quandity</th>
                                    <th>Total Accepted Quandity</th>
                                    <th>Male Death Quandity</th>
                                    <th>Female Death Quandity</th>
                                    <th>Weight</th>
                                    
                                   
                                </tr>
                            </thead>
                            <tbody>
                            <tbody id="productDetails">
        <!-- Products will be loaded here via AJAX -->
                           </tbody>
                            <tfoot>
                            <tr>
                             <td colspan="2" class="text-right"><strong>Total Weight:</strong></td>
                             <td>
                                <input type="text" id="total_weight_display" class="form-control" readonly>
                                <input type="hidden" id="total_weight" name="total_weight">
                             </td>
                            
                             </tr>
                            </tfoot>
                        </table>
                  </div>
                        <div class="modal fade" id="editWeightModal" tabindex="-1" aria-labelledby="editWeightModalLabel" aria-hidden="true">
                           <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                       <h5 class="modal-title" id="editWeightModalLabel">Edit Weight Calculation</h5>
                                   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                              </div>
                                <div class="modal-body" id="editModalBody">
                <!-- The edit form will be loaded here via AJAX -->
                                 </div>
                             </div>
                         </div>
                      </div>

                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    function calculateTotalWeight() {
        let totalWeight = 0;
        document.querySelectorAll('.weight-input').forEach(input => {
            totalWeight += parseFloat(input.value) || 0;
        });

        // Update the visible input with "kg"
        document.getElementById('total_weight_display').value = totalWeight.toFixed(2) + " kg";

        // Store only the numerical value in the hidden input for form submission
        document.getElementById('total_weight').value = totalWeight.toFixed(2);
    }

    document.querySelectorAll('.weight-input').forEach(input => {
        input.addEventListener('input', calculateTotalWeight);
    });
});

</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>



<script>

$(document).ready(function() {
    function calculateTotalWeight() {
        let totalWeight = 0;
        $('.weight-input').each(function() {
            totalWeight += parseFloat($(this).val()) || 0;
        });

        $('#total_weight_display').val(totalWeight.toFixed(2) + " kg");
        $('#total_weight').val(totalWeight.toFixed(2));
    }

    $('.weight-input').on('input', calculateTotalWeight);

   

   
supplier_id
    function fetchEditForm(, shipment_id) {
        $.ajax({
            url: "{{ route('get.existing.weight.calculation') }}",
            type: "GET",
            data: { supplier_id: supplier_id, shipment_id: shipment_id },
            success: function(response) {
                $('#editModalBody').html(response);
                $('#editWeightModal').modal('show');
                setTimeout(() => {
                    calculateTotalWeight();
                }, 500);
            }
        });
    }
});
</script>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    $('#supplierDropdown').change(function () {
        let supplier_id = $(this).val();
        let shipment_id = "{{ $shipment_id }}";

        if (supplier_id) {
            // Fetch Supplier Products
            $.ajax({
                url: "{{ route('get.supplier.products') }}",
                type: "GET",
                data: { supplier_id: supplier_id, shipment_id: shipment_id },
                success: function (response) {
                    let rows = '';
                    response.forEach(detail => {
                        let totalAcceptedQty = detail.male_accepted_qty + detail.female_accepted_qty;

                        rows += `
                            <tr>
                                <td>
                                    <input type="hidden" name="product_id[]" value="${detail.product.id}">
                                    ${detail.product.product_name}
                                </td>
                                <td>
                                    <input type="number" name="male_accepted_qty[]" value="${detail.male_accepted_qty}" class="form-control" readonly>
                                </td>
                                <td>
                                    <input type="number" name="female_accepted_qty[]" value="${detail.female_accepted_qty}" class="form-control" readonly>
                                </td>
                                <td>
                                    <input type="number" name="total_accepted_qty[]" value="${totalAcceptedQty}" class="form-control total-accepted" readonly>
                                </td>
                                <td>
                                    <input type="number" name="male_death_qty[]" value="${detail.death_male_qty}" class="form-control" readonly>
                                </td>
                                <td>
                                    <input type="number" name="female_death_qty[]" value="${detail.death_female_qty}" class="form-control" readonly>
                                </td>
                                <td>
                                    <input type="number" name="weight[]" class="form-control weight-input" style="width: 200px;" step="0.01" required>
                                </td>
                            </tr>
                        `;
                    });
                    
                    $('#productDetails').html(rows);
                    $('.weight-input').on('input', calculateTotalWeight);
                }
            });

            // Fetch Purchase Order ID
            $.ajax({
                url: "{{ route('get.purchase.order.id') }}",
                type: "GET",
                data: { supplier_id: supplier_id, shipment_id: shipment_id },
                success: function (response) {
                    if (response.purchaseOrder_id) {
                        $('#purchaseOrder_id').val(response.purchaseOrder_id);
                    } else {
                        $('#purchaseOrder_id').val('');
                    }
                }
            });
        } else {
            $('#productDetails').html('');
            $('#purchaseOrder_id').val('');
        }
    });



    function calculateTotalWeight() {
        let totalWeight = 0;
        $('.weight-input').each(function () {
            totalWeight += parseFloat($(this).val()) || 0;
        });

        $('#total_weight_display').val(totalWeight.toFixed(2) + " kg");
        $('#total_weight').val(totalWeight.toFixed(2));
    }
});

</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#supplierDropdown').change(function() {
            var supplierId = $(this).val();
            var shipmentId = $('#shipment_id').val(); // Make sure you have the shipment ID available

            if (supplierId && shipmentId) {
                $.ajax({
                    url: "{{ route('check.weight.calculation') }}",
                    type: "GET",
                    data: {
                        supplier_id: supplierId,
                        shipment_id: shipmentId
                    },
                    success: function(response) {
                        if (response.exists) {
                            var confirmEdit = confirm("Weight calculation already exists for this supplier in this shipment. Do you want to edit?");
                            if (!confirmEdit) {
                                $('#supplier_id').val('').trigger('change'); // Reset selection if user cancels
                            }
                        }
                    }
                });
            }
        });
    });
</script>










@endsection
