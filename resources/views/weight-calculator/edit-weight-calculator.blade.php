<div class="modal fade" id="editWeightCalculatorModal" tabindex="-1" aria-labelledby="editWeightCalculatorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Weight Calculator</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editWeightCalculatorForm">
                    @csrf
                    <input type="hidden" name="edit_supplier_id" id="editSupplierId">
                    <input type="hidden" name="edit_shipment_id" id="editShipmentId">

                    <div class="row">
                        <div class="col-md-6">
                            <label for="editWeightCode">Weight Code</label>
                            <input type="text" class="form-control" name="edit_weight_code" id="editWeightCode" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="editTotalWeight">Total Weight</label>
                            <input type="text" class="form-control" name="edit_total_weight" id="editTotalWeight" readonly>
                        </div>
                    </div>

                    <table class="table mt-3" id="editWeightTable">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Weight</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Existing weight calculation details will be loaded here -->
                        </tbody>
                    </table>

                    <div class="text-right">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Weight Calculation</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
