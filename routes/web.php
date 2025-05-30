<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BankMasterController;
use App\Http\Controllers\SupplierPaymentController;
use App\Http\Controllers\paymentvoucherController;
use App\Http\Controllers\SalesOrderController;
use App\Http\Controllers\ReceiptVoucherController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\InspectionController;
use App\Http\Controllers\PurchaseConformationController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\OffalSalesController;
use App\Http\Controllers\LocalCustomerController;
use App\Http\Controllers\ExpenseVoucherController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\SkinningController;
use App\Http\Controllers\WeightCalculatorController;
use App\Http\Controllers\AccountHeadController;
use App\Http\Controllers\SalesPaymentController;
use App\Http\Controllers\CustomerPaymentController;
use App\Http\Controllers\SlaughterScheduleController;
use App\Http\Controllers\OutstandingController;
use App\Http\Controllers\AnimalReceivingNoteController;
use App\Http\Controllers\PostMortemReportController;
use App\Http\Controllers\AnteMortemReportController;
use App\Http\Controllers\SupplierAdvanceController;
use App\Http\Controllers\DeathAnimalController;
use App\Http\Controllers\PackingListController;
use App\Http\Controllers\AirlineController;
use App\Http\Controllers\LedgerController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OffalReceiveController;
use App\Http\Controllers\UsdToShillingController;
use App\Http\Controllers\ShipmentProfitController;
use App\Http\Controllers\ScheSchedulenewSlaughter;
use App\Http\Controllers\ReturnAmountController;
use App\Http\Controllers\DispatchRecordController;
use App\Http\Controllers\ProductionRecordController;
use App\Http\Controllers\ChillingRoomController;
use App\Http\Controllers\BreakdownReportController;
use App\Http\Controllers\GMPController;
use App\Http\Controllers\TemperatureMonitoringRecordController;
use App\Http\Controllers\CleaningandSanitationController;
use App\Http\Controllers\CorrectiveActionController;
use App\Http\Controllers\CustomerComplaintController;
use App\Http\Controllers\InternalAuditChecklistController;
use App\Http\Controllers\CalibrationRecordController;
use App\Http\Controllers\CustomerFeedbackController;
use App\Http\Controllers\RequestingFormController;
use App\Http\Controllers\WaterQualityTestRecordController;
use App\Http\Controllers\ReturnToSupplierController;








/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/clear', function() {
    //   $mytime = Carbon\Carbon::now();
    //  return $mytime->toDateTimeString();
    $exitCode = Artisan::call('cache:clear');
     $exitCode = Artisan::call('config:clear');
     $exitCode = Artisan::call('route:clear');
    $exitCode = Artisan::call('view:clear');
    $exitCode = Artisan::call('config:cache');

    return '<h1>cleared</h1>';
});
Route::get('/', function () {
    return view('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('dashboard', [DashboardController::class,'dashboard'])->name('dashboard');
});

Route::resource('ledger', 'App\Http\Controllers\LedgerController');

Route::get('supplier-create', [SupplierController::class, 'create'])->name('supplier.create');
Route::post('supplier-store', [SupplierController::class, 'store'])->name('supplier.store');
Route::get('supplier-index', [SupplierController::class, 'index'])->name('supplier.index');
Route::get('supplier/{id}/edit', [SupplierController::class, 'edit'])->name('supplier.edit');
Route::post('supplier/{id}', [SupplierController::class, 'update'])->name('supplier.update');
Route::delete('supplier/delete/{id}', [SupplierController::class, 'destroy'])->name('supplier.destroy');



Route::get('customer-create', [CustomerController::class, 'create'])->name('customer.create');
Route::post('customer-store', [CustomerController::class, 'store'])->name('customer.store');
Route::get('customer-index', [CustomerController::class, 'index'])->name('customer.index');
Route::get('customer/{id}/edit', [CustomerController::class, 'edit'])->name('customer.edit');
Route::post('customer/{id}', [CustomerController::class, 'update'])->name('customer.update');
Route::delete('customer/{id}', [CustomerController::class, 'destroy'])->name('customer.destroy');



Route::get('localcustomer-create', [LocalCustomerController::class, 'create'])->name('localcustomer.create');
Route::post('localcustomer-store', [LocalCustomerController::class, 'store'])->name('localcustomer.store');
Route::get('localcustomer-index', [LocalCustomerController::class, 'index'])->name('localcustomer.index');
Route::get('localcustomer/{id}/edit', [LocalCustomerController::class, 'edit'])->name('localcustomer.edit');
Route::post('localcustomer/{id}', [LocalCustomerController::class, 'update'])->name('localcustomer.update');
Route::get('localcustomer/delete/{id}', [LocalCustomerController::class, 'destroy'])->name('localcustomer.destroy');



Route::get('category-create', [CategoryController::class, 'create'])->name('category.create');
Route::post('category-store', [CategoryController::class, 'store'])->name('category.store');
Route::get('category-index', [CategoryController::class, 'index'])->name('category.index');
Route::get('category/{id}/edit', [CategoryController::class, 'edit'])->name('category.edit');
Route::post('category/{id}', [CategoryController::class, 'update'])->name('category.update');
Route::get('category/delete/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');



Route::get('product-create', [ProductController::class, 'create'])->name('product.create');  
Route::post('product-store', [ProductController::class, 'store'])->name('product.store');
Route::get('product-index', [ProductController::class, 'index'])->name('product.index');
Route::get('product/{id}/edit', [ProductController::class, 'edit'])->name('product.edit');
Route::put('product/{id}', [ProductController::class, 'update'])->name('product.update');
Route::get('customer/delete/{id}', [ProductController::class, 'destroy'])->name('product.destroy');



Route::get('bank-master-index', [BankMasterController::class, 'index'])->name('bank-master.index');
Route::get('bank-master-create', [BankMasterController::class,'create'])->name('bank-master.create');
Route::post('bank-master-store', [BankMasterController::class, 'store'])->name('bank-master.store');
Route::get('bank-master-edit/{id}', [BankMasterController::class, 'edit'])->name('bank-master.edit');
Route::post('bank-master-update/{id}', [BankMasterController::class, 'update'])->name('bank-master.update');
Route::get('bank-master-delete/{id}', [BankMasterController::class, 'destroy'])->name('bank-master.destroy');

Route::get('supplier-payment-create', [SupplierPaymentController::class,'create'])->name('supplier-payment.create');
Route::post('supplier-payment-store', [SupplierPaymentController::class,'store'])->name('supplier-payment.store');
Route::get('supplier-payment-index', [SupplierPaymentController::class, 'index'])->name('supplier-payment.index');
Route::get('supplier-payment-view/{id}', [SupplierPaymentController::class,'view'])->name('supplier-payment.view');
Route::get('supplier-payment-edit/{id}', [SupplierPaymentController::class, 'edit'])->name('supplier-payment.edit');
Route::post('supplier-payment-update/{id}', [SupplierPaymentController::class, 'update'])->name('supplier-payment.update');
Route::get('supplier-payment-delete/{id}', [SupplierPaymentController::class, 'destroy'])->name('supplier-payment.destroy');
Route::get('supplier-payment-report', [SupplierPaymentController::class, 'report'])->name('supplier-payment.report');
Route::get('get-suppliers-by-shipment-payment', [SupplierPaymentController::class, 'getSuppliersByShipment']);
Route::get('/get-supplier-conformations', [SupplierPaymentController::class, 'getSupplierConformations']);



Route::get('paymentvoucher-create', [paymentvoucherController::class, 'create'])->name('paymentvoucher.create');
Route::post('paymentvoucher-store', [paymentvoucherController::class,'store'])->name('paymentvoucher.store');
Route::get('paymentvoucher-index', [paymentvoucherController::class, 'index'])->name('paymentvoucher.index');
Route::get('paymentvoucher-edit/{id}', [paymentvoucherController::class, 'edit'])->name('paymentvoucher.edit');
Route::post('paymentvoucher-update/{id}', [paymentvoucherController::class, 'update'])->name('paymentvoucher.update');
Route::get('paymentvoucher-delete/{id}', [paymentvoucherController::class, 'destroy'])->name('paymentvoucher.destroy');
Route::get('paymentvoucher-report', [paymentvoucherController::class, 'report'])->name('paymentvoucher.report');




Route::get('receiptvoucher-create', [ReceiptVoucherController::class, 'create'])->name('receiptvoucher.create');
Route::post('receiptvoucher-store', [ReceiptVoucherController::class,'store'])->name('receiptvoucher.store');
Route::get('receiptvoucher-index', [ReceiptVoucherController::class, 'index'])->name('receiptvoucher.index');
Route::get('receiptvoucher-edit/{id}', [ReceiptVoucherController::class, 'edit'])->name('receiptvoucher.edit');
Route::post('receiptvoucher-update/{id}', [ReceiptVoucherController::class, 'update'])->name('receiptvoucher.update');
Route::get('receiptvoucher-delete/{id}', [ReceiptVoucherController::class, 'destroy'])->name('receiptvoucher.destroy');
Route::get('receiptvoucher-report', [ReceiptVoucherController::class, 'report'])->name('receiptvoucher.report');



Route::get('goodsout-order-create', [SalesOrderController::class, 'create'])->name('goodsout-order.create');
Route::post('goodsout-order-store', [SalesOrderController::class,'store'])->name('goodsout-order.store');
Route::get('goodsout-order-index', [SalesOrderController::class, 'index'])->name('goodsout-order.index');
Route::get('goodsout-order-view/{id}', [SalesOrderController::class,'view'])->name('goodsout-order.view');
Route::get('goodsout-order-edit/{id}', [SalesOrderController::class, 'edit'])->name('goodsout-order.edit');
Route::post('goodsout-order-update/{id}', [SalesOrderController::class, 'update'])->name('goodsout-order.update');
Route::get('goodsout-order-delete/{id}', [SalesOrderController::class, 'destroy'])->name('goodsout-order.destroy');
Route::get('goodsout-order-report', [SalesOrderController::class, 'report'])->name('goodsout-order.report');




Route::get('purchase-order-create', [PurchaseOrderController::class, 'create'])->name('purchase-order.create');
Route::post('purchase-order-store', [PurchaseOrderController::class,'store'])->name('purchase-order.store');
Route::get('purchase-order-index', [PurchaseOrderController::class, 'index'])->name('purchase-order.index');
Route::get('purchase-order-view/{id}', [PurchaseOrderController::class,'view'])->name('purchase-order.view');
Route::get('purchase-order-edit/{id}', [PurchaseOrderController::class, 'edit'])->name('purchase-order.edit');
Route::post('purchase-order-update/{id}', [PurchaseOrderController::class, 'update'])->name('purchase-order.update');
Route::get('purchase-order-delete/{id}', [PurchaseOrderController::class, 'destroy'])->name('purchase-order.destroy');
Route::get('purchase-order-report', [PurchaseOrderController::class, 'report'])->name('purchase-order.report');
Route::get('/purchase-order/{id}/view', [PurchaseOrderController::class, 'reportview'])->name('purchase-order.reportview');
Route::get('/get-outstanding/{supplierId}', [PurchaseOrderController::class, 'getOutstandingBalance']);



Route::get('inspection-index', [InspectionController::class, 'index'])->name('inspection.index');
Route::get('inspection-view/{id}', [InspectionController::class,'view'])->name('inspection.view');
Route::post('inspection-store', [InspectionController::class, 'store'])->name('inspection.store');
Route::get('inspection-report', [InspectionController::class, 'report'])->name('inspection.report');
// Route::get('/inspection/{id}/reportview', [InspectionController::class, 'viewReport'])->name('inspection.reportview');
Route::get('/rejected-animal-report', [InspectionController::class, 'rejectedAnimalReport'])->name('rejected.animal.report');
Route::get('/shipment/rejected/details/{shipment_no}', [InspectionController::class, 'shipmentRejectedDetails'])->name('shipment.rejected.details');
Route::get('/shipment/{shipment_no}/supplier/{supplier_name}/details', [InspectionController::class, 'viewSupplierRejectedProducts'])->name('supplier.rejected.details');
Route::get('/inspection/{id}/edit', [InspectionController::class, 'edit'])->name('inspection.edit');
Route::put('/inspection/{id}', [InspectionController::class, 'update'])->name('inspection.update');
Route::get('/inspectionview/{id}/view', [InspectionController::class, 'inspectionview'])->name('inspection.inspectionview');
Route::delete('/inspection/{id}', [InspectionController::class, 'destroy'])->name('inspection.destroy');










Route::get('purchade-conformation-index', [PurchaseConformationController::class, 'index'])->name('purchase-conformation.index');
Route::get('purchase-conformation/{id}', [PurchaseConformationController::class, 'Confirm'])->name('purchase-conformation.Confirm');
Route::post('purchade-conformation-store', [PurchaseConformationController::class,'store'])->name('purchase-conformation.store');
Route::get('purchase-conformation-report', [PurchaseConformationController::class, 'report'])->name('purchase-confirmation.report');
Route::get('purchase-conformation/{id}/view', [PurchaseConformationController::class, 'view']);
Route::get('/purchase-conformation/{id}/edit', [PurchaseConformationController::class, 'edit'])->name('purchase-conformation.edit');
Route::put('/purchase-conformation/{id}/update', [PurchaseConformationController::class, 'update'])->name('purchase-conformation.update');




Route::get('shipment-index', [ShipmentController::class, 'index'])->name('shipment.index');
Route::get('shipment-create', [ShipmentController::class, 'create'])->name('shipment.create');
Route::post('shipment-store', [ShipmentController::class, 'store'])->name('shipment.store');
Route::delete('/shipment/{id}', [ShipmentController::class, 'destroy'])->name('shipment.destroy');

Route::get('shipment-report', [ShipmentController::class, 'report']);
Route::get('shipment-report.view/{id}', [ShipmentController::class,'view'])->name('shipment-report.view');
Route::get('shipment-suppllier-final-payment-report/{id}', [ShipmentController::class,'shipment_suppllier_final_payment_report'])->name('shipment-suppllier-final-payment-report');
Route::get('shipment-suppllier-final-payment-report-detail', [ShipmentController::class,'shipment_suppllier_final_payment_report_detail'])->name('shipment-suppllier-final-payment-report-detail');


Route::get('/supplier-final-payment-print', [ShipmentController::class, 'print'])->name('supplier-final-payment-print');






Route::get('offal-sales-create', [OffalSalesController::class, 'create'])->name('offal-sales.create');
Route::post('offal-sales-store', [OffalSalesController::class,'store'])->name('offal-sales.store');
Route::get('offal-sales-index', [OffalSalesController::class, 'index'])->name('offal-sales.index');
Route::get('offal-sales-view/{id}', [OffalSalesController::class,'view'])->name('offal-sales.view');
Route::get('offal-sales-edit/{id}', [OffalSalesController::class, 'edit'])->name('offal-sales.edit');
Route::post('offal-sales-update/{id}', [OffalSalesController::class, 'update'])->name('offal-sales.update');
Route::get('offal-sales-delete/{id}', [OffalSalesController::class, 'destroy'])->name('offal-sales.destroy');
Route::get('offal-sales-report', [OffalSalesController::class, 'report'])->name('offal-sales.report');






Route::get('expensevoucher-create', [ExpenseVoucherController::class, 'create'])->name('expensevoucher.create');
Route::post('expensevoucher-store', [ExpenseVoucherController::class,'store'])->name('expensevoucher.store');
Route::get('expensevoucher-index', [ExpenseVoucherController::class, 'index'])->name('expensevoucher.index');
Route::get('expensevoucher-edit/{id}', [ExpenseVoucherController::class, 'edit'])->name('expensevoucher.edit');
Route::post('expensevoucher-update/{id}', [ExpenseVoucherController::class, 'update'])->name('expensevoucher.update');
Route::get('expensevoucher-delete/{id}', [ExpenseVoucherController::class, 'destroy'])->name('expensevoucher.destroy');


Route::get('employee-create', [EmployeeController::class, 'create'])->name('employee.create');
Route::post('employee-store', [EmployeeController::class, 'store'])->name('employee.store');
Route::get('employee-index', [EmployeeController::class, 'index'])->name('employee.index');
Route::get('employee/{id}/edit', [EmployeeController::class, 'edit'])->name('employee.edit');
Route::post('employee/{id}', [EmployeeController::class, 'update'])->name('employee.update');
Route::get('employee/delete/{id}', [EmployeeController::class, 'destroy'])->name('employee.destroy');


Route::get('skinning-create', [SkinningController::class, 'create'])->name('skinning.create');
Route::post('skinning-store', [SkinningController::class, 'store'])->name('skinning.store');
Route::get('skinning-index', [SkinningController::class, 'index'])->name('skinning.index');
Route::get('skinning-view/{id}', [SkinningController::class,'view'])->name('skinning.view');
Route::get('skinning/{id}/edit', [SkinningController::class, 'edit'])->name('skinning.edit');
Route::post('skinning/{id}', [SkinningController::class, 'update'])->name('skinning.update');
Route::get('/skinning/delete/{id}', [SkinningController::class, 'destroy'])->name('skinning.destroy');
Route::get('skinning-report', [SkinningController::class, 'report'])->name('skinning.report');







Route::get('/weight-calculator', [WeightCalculatorController::class, 'index'])->name('weight_calculator.index');
Route::get('/weight_calculator/create/{shipment_id}', [WeightCalculatorController::class, 'create'])->name('weight_calculator.create');
Route::post('/weight-calculator/store', [WeightCalculatorController::class, 'store'])->name('weight_calculator.store');
Route::get('weight-calculator-report', [WeightCalculatorController::class, 'report'])->name('weight_calculator.report');
Route::get('weight-calculator-report/{id}/view', [WeightCalculatorController::class, 'reportview'])->name('weight_calculator.view');
Route::get('/get-products-by-supplier', [WeightCalculatorController::class, 'getProductsBySupplier'])->name('get.products.by.supplier');
Route::get('/check-weight-calculation', [WeightCalculatorController::class, 'checkExistingWeightCalculation'])->name('check.weight.calculation');

Route::get('/get-existing-weight-calculation', [WeightCalculatorController::class, 'getExistingWeightCalculation'])->name('get.existing.weight.calculation');
Route::post('/update-weight-calculation', [WeightCalculatorController::class, 'updateWeightCalculation'])->name('update.weight.calculation');
Route::get('/get-supplier-products', [WeightCalculatorController::class, 'getSupplierProducts'])->name('get.supplier.products');
Route::get('/get-purchase-order-id', [WeightCalculatorController::class, 'getPurchaseOrderId'])->name('get.purchase.order.id');

Route::get('weight-approval/facility', [WeightCalculatorController::class, 'pendingFacilityApproval'])->name('weight_approval.facility');
Route::post('weight-approval/facility/{id}/approve', [WeightCalculatorController::class, 'facilityApprove'])->name('weight_approval.facility.approve');

Route::get('weight-approval/accountant', [WeightCalculatorController::class, 'pendingAccountantApproval'])->name('weight_approval.accountant');
Route::post('weight-approval/accountant/{id}/approve', [WeightCalculatorController::class, 'accountantApprove'])->name('weight_approval.accountant.approve');




    



Route::get('/account-heads', [AccountHeadController::class, 'index'])->name('account-heads.index');
Route::get('/account-heads/new', [AccountHeadController::class, 'create'])->name('account-heads.new');
Route::get('/account-heads/edit/{id}', [AccountHeadController::class, 'edit'])->name('account-heads.edit');
Route::post('/account-heads/update/{id}', [AccountHeadController::class, 'update'])->name('account-heads.update');
Route::get('/account-heads/{id}', [AccountHeadController::class, 'destroy'])->name('account-heads.destroy');
Route::post('/account-heads/store', [AccountHeadController::class, 'store'])->name('account-heads.store');
Route::get('account-heads/check-delete/{id}', [AccountHeadController::class, 'checkDelete']);





Route::get('sales_payment-create', [SalesPaymentController::class, 'create'])->name('sales_payment.create');
Route::post('sales_payment-store', [SalesPaymentController::class, 'store'])->name('sales_payment.store');
Route::get('sales_payment-index', [SalesPaymentController::class, 'index'])->name('sales_payment.index');
Route::get('sales_payment-view/{id}', [SalesPaymentController::class,'view'])->name('sales_payment.view');
Route::get('sales_payment/{id}/edit', [SalesPaymentController::class, 'edit'])->name('sales_payment.edit');
Route::post('sales_payment/{id}', [SalesPaymentController::class, 'update'])->name('sales_payment.update');
Route::get('/sales_payment/delete/{id}', [SalesPaymentController::class, 'destroy'])->name('sales_payment.destroy');
Route::get('sales_payment-report', [SalesPaymentController::class, 'report'])->name('sales_payment.report');
Route::get('/invoice-print/{order_no}', [SalesPaymentController::class, 'printInvoice'])->name('invoice.print');
Route::get('/get-outstanding/{customerId}', [SalesPaymentController::class, 'getOutstandingBalance']);
Route::get('/get-shipment-by-sales-no', [SalesPaymentController::class, 'getShipmentBySalesNo'])->name('get.shipment');
Route::get('/get-shipment-by-sales-no', [SalesPaymentController::class, 'getShipmentBySalesNo'])->name('getShipmentBySalesNo');








Route::get('customer-payment-create', [CustomerPaymentController::class,'create'])->name('customer-payment.create');
Route::post('/customer-payment/store', [CustomerPaymentController::class, 'store'])->name('customer-payment.store');
Route::get('customer-payment-index', [CustomerPaymentController::class, 'index'])->name('customer-payment.index');
Route::get('customer-payment-view/{id}', [CustomerPaymentController::class,'view'])->name('customer-payment.view');
Route::get('customer-payment-delete/{id}', [CustomerPaymentController::class, 'destroy'])->name('customer-payment.destroy');
Route::get('customer-payment-report', [CustomerPaymentController::class, 'report'])->name('customer-payment.report');
Route::get('/get-customer-sales', [CustomerPaymentController::class, 'getCustomersales']);
Route::get('customer-payment-report', [CustomerPaymentController::class, 'report'])->name('customer-payment.report');
Route::get('customer-payment-view/{id}', [CustomerPaymentController::class,'view'])->name('customer-payment.view');




Route::get('slaughter-schedule-create', [SlaughterScheduleController::class, 'create']);
Route::post('slaughter-schedule', [SlaughterScheduleController::class, 'store'])->name('slaughter.store');
Route::get('slaughter-schedules-index', [SlaughterScheduleController::class, 'index'])->name('slaughter.index');
Route::get('/slaughter/{id}/products', [SlaughterScheduleController::class, 'viewProducts'])->name('slaughter.view-products');
Route::get('slaughter-schedule/{id}/edit', [SlaughterScheduleController::class, 'edit'])->name('slaughter-schedule.edit');
Route::post('slaughter-schedule/{id}/update', [SlaughterScheduleController::class, 'update'])->name('slaughter-schedule.update');
Route::get('slaughter-schedule/{id}', [SlaughterScheduleController::class, 'destroy'])->name('slaughter-schedule.destroy');
Route::get('/slaughter-schedule/print/{id}', [SlaughterScheduleController::class, 'print'])->name('slaughter-schedule.print');
Route::post('/upload-screenshot', [SlaughterScheduleController::class, 'uploadScreenshot'])->name('upload.screenshot');









Route::get('supplier-ledger', [OutstandingController::class, 'supplierLedger'])->name('supplier.ledger');
Route::get('customer-ledger', [OutstandingController::class, 'customerLedger'])->name('customer.ledger');
Route::get('supplier-outstanding', [OutstandingController::class, 'supplierOutstanding'])->name('supplier.outstanding');
Route::get('customer-outstanding', [OutstandingController::class, 'customerOutstanding'])->name('customer.outstanding');
Route::get('/supplier-outstanding/print', [OutstandingController::class, 'supplierOutstandingPrint'])->name('supplier.outstanding.print');
Route::get('/customer-outstanding/print', [OutstandingController::class, 'customerOutstandingPrint'])->name('customer.outstanding.print');





Route::get('postmortem-report-create', [PostMortemReportController::class, 'create'])->name('postmortem.create');;
Route::post('postmortem-report-store', [PostMortemReportController::class, 'store'])->name('postmortem.store');
Route::get('postmortem-report-index', [PostMortemReportController::class, 'index'])->name('postmortem.index');
Route::get('postmortem/edit/{id}', [PostMortemReportController::class, 'edit'])->name('postmortem.edit');
Route::post('postmortem/update/{id}', [PostMortemReportController::class, 'update'])->name('postmortem.update');
Route::get('/postmortem/print/{id}', [PostMortemReportController::class, 'print'])->name('postmortem.print');


Route::get('antemortem-report-create', [AnteMortemReportController::class, 'create'])->name('antemortem.create');
Route::post('antemortem-store', [AnteMortemReportController::class, 'store'])->name('antemortem.store');
Route::get('antemortem-report-index', [AnteMortemReportController::class, 'index'])->name('antemortem.index');
Route::get('/antemortem/edit/{id}', [AnteMortemReportController::class, 'edit'])->name('antemortem.edit');
Route::post('/antemortem/update/{id}', [AnteMortemReportController::class, 'update'])->name('antemortem.update');
Route::delete('/antemortem/{id}/delete', [AnteMortemReportController::class, 'deleteAllData'])->name('antemortem.delete');
Route::get('antemortem-report-view/{id}', [AnteMortemReportController::class, 'view'])->name('antemortem.view');
Route::get('antemortem-report-print/{id}', [AnteMortemReportController::class, 'print'])->name('antemortem.print');







Route::get('supplier-advance-create', [SupplierAdvanceController::class, 'create'])->name('supplieradvance.create');
Route::get('supplier-advance-index', [SupplierAdvanceController::class, 'index'])->name('supplieradvance.index');
Route::get('/get-suppliers', [SupplierAdvanceController::class, 'getSuppliersByShipment'])->name('getSuppliersByShipment');
Route::get('/get-orders', [SupplierAdvanceController::class, 'getOrdersBySupplier'])->name('getOrdersBySupplier');
Route::post('supplier-advance-store', [SupplierAdvanceController::class, 'store'])->name('supplieradvance.store');
Route::delete('/supplieradvance/{id}', [SupplierAdvanceController::class, 'destroy'])->name('supplieradvance.destroy');





Route::get('/fetch-products', [DeathAnimalController::class, 'fetchProducts'])->name('fetch.products');
Route::post('/death-animal/store', [DeathAnimalController::class, 'store'])->name('deathanimal.store');
Route::get('/death-animal/create', [DeathAnimalController::class, 'create'])->name('deathanimal.create');
Route::get('/get-suppliers-by-shipment', [DeathAnimalController::class, 'getSuppliersByShipment'])->name('inspection.getSuppliersByShipment');
Route::delete('/deathanimal/{id}', [DeathAnimalController::class, 'destroy'])->name('deathanimal.destroy');
Route::get('/deathanimal', [DeathAnimalController::class, 'index'])->name('deathanimal.index');
Route::get('deathanimal/{id}', [DeathAnimalController::class, 'show'])->name('deathanimal.show');




Route::get('packinglist-create', [PackingListController::class, 'create'])->name('packinglist.create');
Route::get('packinglist-index', [PackingListController::class, 'index'])->name('packinglist.index');
Route::post('packinglist-store', [PackingListController::class, 'store'])->name('packinglist.store');
Route::delete('packinglist-destroy/{id}', [PackingListController::class, 'destroy'])->name('packinglist.destroy');
Route::get('packinglist-show/{id}', [PackingListController::class, 'show'])->name('packinglist.show');
Route::get('packinglist/{id}/edit', [PackingListController::class, 'edit'])->name('packinglist.edit');
Route::post('packinglist/{id}', [PackingListController::class, 'update'])->name('packinglist.update');
Route::get('packinglist-print/{id}', [PackingListController::class, 'packlistPrint'])->name('packinglist.print');



Route::get('airline-create', [AirlineController::class, 'create'])->name('airline.create');
Route::post('airline-store', [AirlineController::class,'store'])->name('airline.store');
Route::get('airline-index', [AirlineController::class, 'index'])->name('airline.index');
Route::get('airline-edit/{id}', [AirlineController::class, 'edit'])->name('airline.edit');
Route::post('airline-update/{id}', [AirlineController::class, 'update'])->name('airline.update');
Route::get('airline-delete/{id}', [AirlineController::class, 'destroy'])->name('airline.destroy');




Route::get('users-create', [UserController::class, 'create'])->name('users.create');
Route::post('/users', [UserController::class, 'store'])->name('users.store');
Route::get('users-index', [UserController::class, 'index'])->name('users.index');
Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
Route::post('/users/{id}', [UserController::class, 'update'])->name('users.update');



Route::get('/offal-receive-index', [OffalReceiveController::class, 'index'])->name('offal-receive.index');
Route::get('/offal-receive-create', [OffalReceiveController::class, 'create'])->name('offal-receive.create');
Route::post('/offal-receive-store', [OffalReceiveController::class, 'store'])->name('offal-receive.store');
Route::get('/offal-receive-edit/{id}', [OffalReceiveController::class, 'edit'])->name('offal-receive.edit');
Route::post('/offal-receive-update/{id}', [OffalReceiveController::class, 'update'])->name('offal-receive.update');
Route::delete('/offal-receive-delete/{id}', [OffalReceiveController::class, 'destroy'])->name('offal-receive.destroy');


Route::get('/usd-to-shilling', [UsdToShillingController::class, 'index'])->name('usd_to_shilling.index');
Route::get('/usd-to-shilling/{id}/edit', [UsdToShillingController::class, 'edit'])->name('usd_to_shilling.edit');
Route::post('/usd-to-shilling/{id}', [UsdToShillingController::class, 'update'])->name('usd_to_shilling.update');


Route::get('shipment-profit/{id}', [ShipmentProfitController::class, 'shipmentprofit'])->name('shipmentprofit.report');
// Route::get('shipment-/{id}', [ShipmentProfitController::class, 'shipmentprofit'])->name('shipmentprofit.report');


Route::get('create-new-scheduletime', [ScheSchedulenewSlaughter::class, 'create'])->name('new.schedule');
Route::post('store-new-scheduletime', [ScheSchedulenewSlaughter::class, 'store'])->name('store.schedule');
Route::get('index-new-scheduletime', [ScheSchedulenewSlaughter::class, 'index'])->name('index.schedule');
Route::get('edit-new-scheduletime/{id}', [ScheSchedulenewSlaughter::class, 'edit'])->name('edit.schedule');;
Route::put('update-new-scheduletime/{id}', [ScheSchedulenewSlaughter::class, 'update'])->name('update.schedule');;
Route::delete('delete-new-scheduletime/{id}', [ScheSchedulenewSlaughter::class, 'destroy']);
Route::get('/stop-slaughter', [ScheSchedulenewSlaughter::class, 'stop'])->name('slaughter.stop');





Route::get('return-payment-create', [ReturnAmountController::class, 'create'])->name('return-payment.create');
Route::post('return-payment-store', [ReturnAmountController::class, 'store'])->name('return-payment.store');
Route::get('/get-supplier-outstanding', [ReturnAmountController::class, 'getSupplierOutstanding'])->name('get.supplier.outstanding');
Route::get('/get-suppliers-by-shipment', [ReturnAmountController::class, 'getSuppliersByShipment'])->name('get.suppliers.by.shipment');
Route::get('/get-supplier-outstanding', [ReturnAmountController::class, 'getSupplierOutstanding'])->name('get.supplier.outstanding');
Route::get('return-payment-index', [ReturnAmountController::class, 'index'])->name('return-payment.index');
Route::delete('/return-payment/{id}', [ReturnAmountController::class, 'destroy'])->name('return-payment.destroy');
Route::get('/get-all-suppliers', [ReturnAmountController::class, 'getAllSuppliers'])->name('get.all.suppliers');


// 

Route::get('production-record-create', [ProductionRecordController::class, 'create'])->name('production-record.create');
Route::post('production-record-store', [ProductionRecordController::class,'store'])->name('production-record.store');
Route::get('production-record-index', [ProductionRecordController::class, 'index'])->name('production-record.index');
Route::get('production-record-edit/{id}', [ProductionRecordController::class, 'edit'])->name('production-record.edit');
Route::post('production-record-update/{id}', [ProductionRecordController::class, 'update'])->name('production-record.update');
Route::delete('production-record-delete/{id}', [ProductionRecordController::class, 'destroy'])->name('production-record.destroy');
// Route::get('paymentvoucher-report', [paymentvoucherController::class, 'report'])->name('paymentvoucher.report');

Route::get('dispatch-record-create', [DispatchRecordController::class, 'create'])->name('dispatch-record.create');
Route::post('dispatch-record-store', [DispatchRecordController::class,'store'])->name('dispatch-record.store');
Route::get('dispatch-record-index', [DispatchRecordController::class, 'index'])->name('dispatch-record.index');
Route::get('dispatch-record-edit/{id}', [DispatchRecordController::class, 'edit'])->name('dispatch-record.edit');
Route::post('dispatch-record-update/{id}', [DispatchRecordController::class, 'update'])->name('dispatch-record.update');
Route::delete('dispatch-record-delete/{id}', [DispatchRecordController::class, 'destroy'])->name('dispatch-record.destroy');



Route::get('chilling-room-create', [ChillingRoomController::class, 'create'])->name('chilling-room.create');
Route::post('chilling-room-store', [ChillingRoomController::class,'store'])->name('chilling-room.store');
Route::get('chilling-room-index', [ChillingRoomController::class, 'index'])->name('chilling-room.index');
Route::get('chilling-room-edit/{id}', [ChillingRoomController::class, 'edit'])->name('chilling-room.edit');
Route::post('chilling-room-update/{id}', [ChillingRoomController::class, 'update'])->name('chilling-room.update');
Route::delete('chilling-room-delete/{id}', [ChillingRoomController::class, 'destroy'])->name('chilling-room.destroy');


Route::get('breakdown-report-create', [BreakdownReportController::class, 'create'])->name('breakdown-report.create');
Route::post('breakdown-report-store', [BreakdownReportController::class,'store'])->name('breakdown-report.store');
Route::get('breakdown-report-index', [BreakdownReportController::class, 'index'])->name('breakdown-report.index');
Route::get('breakdown-report-edit/{id}', [BreakdownReportController::class, 'edit'])->name('breakdown-report.edit');
Route::post('breakdown-report-update/{id}', [BreakdownReportController::class, 'update'])->name('breakdown-report.update');
Route::delete('breakdown-report-delete/{id}', [BreakdownReportController::class, 'destroy'])->name('breakdown-report.destroy');

Route::get('gmp-create', [GMPController::class, 'create'])->name('gmp.create');
Route::post('gmp-store', [GMPController::class,'store'])->name('gmp.store');
Route::get('gmp-index', [GMPController::class, 'index'])->name('gmp.index');
Route::delete('gmp/{id}', [GMPController::class, 'destroy'])->name('gmp.destroy');


Route::get('temperature-monitoring-create', [TemperatureMonitoringRecordController::class, 'create'])->name('temperature-monitoring.create');
Route::post('temperature-monitoring-store', [TemperatureMonitoringRecordController::class,'store'])->name('temperature-monitoring.store');
Route::get('temperature-monitoring-index', [TemperatureMonitoringRecordController::class, 'index'])->name('temperature-monitoring.index');
Route::delete('temperature-monitoring/{id}', [TemperatureMonitoringRecordController::class, 'destroy'])->name('temperature-monitoring.destroy');


Route::get('cleaning-sanitation-create', [CleaningandSanitationController::class, 'create'])->name('cleaning-sanitation.create');
Route::post('cleaning-sanitation-store', [CleaningandSanitationController::class,'store'])->name('cleaning-sanitation.store');
Route::get('cleaning-sanitation-index', [CleaningandSanitationController::class, 'index'])->name('cleaning-sanitation.index');
Route::delete('cleaning-sanitation/{id}', [CleaningandSanitationController::class, 'destroy'])->name('cleaning-sanitation.destroy');


Route::get('corrective-action-create', [CorrectiveActionController::class, 'create'])->name('corrective-action.create');
Route::post('corrective-action-store', [CorrectiveActionController::class,'store'])->name('corrective-action.store');
Route::get('corrective-action-index', [CorrectiveActionController::class, 'index'])->name('corrective-action.index');
Route::delete('corrective-action/{id}', [CorrectiveActionController::class, 'destroy'])->name('corrective-action.destroy');


Route::get('customer-complaint-create', [CustomerComplaintController::class, 'create'])->name('customer-complaint.create');
Route::post('customer-complaint-store', [CustomerComplaintController::class,'store'])->name('customer-complaint.store');
Route::get('customer-complaint-index', [CustomerComplaintController::class, 'index'])->name('customer-complaint.index');
Route::delete('customer-complaint/{id}', [InternalAuditChecklistController::class, 'destroy'])->name('customer-complaint.destroy');


Route::get('internal-auditchecklist-create', [InternalAuditChecklistController::class, 'create'])->name('internal-auditchecklist.create');
Route::post('internal-auditchecklist-store', [InternalAuditChecklistController::class,'store'])->name('internal-auditchecklist.store');
Route::get('internal-auditchecklist-index', [InternalAuditChecklistController::class, 'index'])->name('internal-auditchecklist.index');
Route::delete('internal-auditchecklist/{id}', [InternalAuditChecklistController::class, 'destroy'])->name('internal-auditchecklist.destroy');


Route::get('calibration-record-create', [CalibrationRecordController::class, 'create'])->name('calibration-record.create');
Route::post('calibration-record-store', [CalibrationRecordController::class,'store'])->name('calibration-record.store');
Route::get('calibration-record-index', [CalibrationRecordController::class, 'index'])->name('calibration-record.index');
Route::delete('calibration-record/{id}', [CalibrationRecordController::class, 'destroy'])->name('calibration-record.destroy');



Route::get('customer-feedback-create', [CustomerFeedbackController::class, 'create'])->name('customer-feedback.create');
Route::post('customer-feedback/store', [CustomerFeedbackController::class, 'store'])->name('customer-feedback.store');
Route::get('customer-feedback', [CustomerFeedbackController::class, 'index'])->name('customer-feedback.index');
Route::delete('customer-feedback/{id}/delete', [CustomerFeedbackController::class, 'destroy'])->name('customer-feedback.destroy');


Route::get('requesting-form-create', [RequestingFormController::class, 'create'])->name('requesting-form.create');
Route::get('requesting-form-index', [RequestingFormController::class, 'index'])->name('requesting-form.index');
Route::post('requesting-form/store', [RequestingFormController::class, 'store'])->name('requesting-form.store');
Route::get('/get-supplier/{id}', [RequestingFormController::class, 'getSupplier']);
Route::delete('requesting-form/{id}/delete', [RequestingFormController::class, 'destroy'])->name('requesting-form.destroy');
Route::post('/requesting-form/{id}/accept', [RequestingFormController::class, 'accept'])->name('requesting-form.accept');
Route::post('/requesting-form/{id}/reject', [RequestingFormController::class, 'reject'])->name('requesting-form.reject');
Route::get('/requesting-form/{id}', [RequestingFormController::class, 'show'])->name('requesting-form.show');
Route::get('requesting-form/{id}/edit', [RequestingFormController::class, 'edit'])->name('requesting-form.edit');
Route::put('requesting-form/{id}', [RequestingFormController::class, 'update'])->name('requesting-form.update');
Route::patch('/requesting-form/{id}/update-advance', [RequestingFormController::class, 'updateAdvance'])->name('requesting-form.update-advance');



Route::get('/animal-receive-notes', [AnimalReceivingNoteController::class, 'index'])->name('animalReceive.index');
Route::get('/animal-receive-notes/{id}', [AnimalReceivingNoteController::class, 'show'])->name('animalReceive.view');
Route::get('/animal-receive-notes/print/{id}', [AnimalReceivingNoteController::class, 'print'])->name('animalReceive.print');

Route::get('water-quality-create', [WaterQualityTestRecordController::class, 'create'])->name('water-quality.create');
Route::get('water-quality-index', [WaterQualityTestRecordController::class, 'index'])->name('water-quality.index');
Route::post('water-quality-store', [WaterQualityTestRecordController::class, 'store'])->name('water-quality.store');
Route::delete('water-quality/{id}', [WaterQualityTestRecordController::class, 'destroy'])->name('water-quality.destroy');

// 
Route::post('/paymentvoucher/soft-delete/{id}', [PaymentVoucherController::class, 'softDelete'])->name('paymentvoucher.softdelete');
Route::get('/admin/paymentvoucher/deleted', [PaymentVoucherController::class, 'viewMarkedForDeletion'])->name('admin.paymentvoucher.deleted');
Route::delete('/admin/paymentvoucher/destroy/{id}', [PaymentVoucherController::class, 'admindelete'])->name('admin.paymentvoucher.destroy');

Route::post('/airline/softdelete/{id}', [AirlineController::class, 'softDelete'])->name('airline.softdelete');
Route::get('/admin/airline/deletion-requests', [AirlineController::class, 'deletionRequests'])->name('admin.airline.deletion_requests');
Route::delete('/admin/airline/{id}', [AirlineController::class, 'adminDestroy'])->name('admin.airline.destroy');

Route::post('/return-payment/request-delete/{id}', [ReturnAmountController::class, 'requestDelete'])->name('return-payment.requestDelete');
Route::get('/return-payment/pending-delete', [ReturnAmountController::class, 'pendingDeleteList'])->name('return-payment.pendingDeleteList');
Route::delete('/return-payment/approve-delete/{id}', [ReturnAmountController::class, 'approveDelete'])->name('return-payment.approveDelete');

Route::post('/supplieradvance/request-delete/{id}', [SupplierAdvanceController::class, 'requestDelete'])->name('supplieradvance.requestDelete');
Route::get('/supplieradvance/delete-requests', [SupplierAdvanceController::class, 'deleteRequests'])->name('supplieradvance.deleteRequests');
Route::delete('/supplieradvance/approve-delete/{id}', [SupplierAdvanceController::class, 'approveDelete'])->name('supplieradvance.approveDelete');


Route::get('/return-supplier-create', [ReturnToSupplierController::class, 'create'])->name('return-supplier.create');
Route::post('return-supplier-store', [ReturnToSupplierController::class, 'store'])->name('return-to-supplier.store');
Route::get('return-supplier-index', [ReturnToSupplierController::class, 'index'])->name('return-to-supplier.index');
Route::get('/getsupplier-outstanding', [ReturnToSupplierController::class, 'getSupplierOutstanding'])->name('getsupplier.outstanding');
Route::get('/getsuppliers-by-shipment', [ReturnToSupplierController::class, 'getSuppliersByShipment'])->name('getsuppliers.by.shipment');
Route::get('getall-suppliers', [ReturnToSupplierController::class, 'getAllSuppliers'])->name('getall.suppliers');
Route::delete('/return-to-supplier/{id}', [ReturnToSupplierController::class, 'approvedestroy'])->name('return-to-supplier.destroy');
Route::get('/return-to-supplier/delete-requests', [ReturnToSupplierController::class, 'pendingDeletes'])->name('return-to-supplier.pending-deletes');
Route::patch('/return-to-supplier/request-delete/{id}', [ReturnToSupplierController::class, 'requestDelete'])->name('return-to-supplier.request-delete');


Route::post('/expensevoucher/request-delete/{id}', [ExpenseVoucherController::class, 'requestDelete'])->name('expensevoucher.requestDelete');
Route::get('/expensevoucher/pending-deletes', [ExpenseVoucherController::class, 'pendingDeleteRequests'])->name('expensevoucher.pendingDeletes');
Route::delete('/expensevoucher/approve-delete/{id}', [ExpenseVoucherController::class, 'approveDelete'])->name('expensevoucher.approveDelete');

Route::post('/receiptvoucher/request-delete/{id}', [ReceiptVoucherController::class, 'requestDelete'])->name('receiptvoucher.requestDelete');
Route::get('/receiptvoucher/delete-requests', [ReceiptVoucherController::class, 'deleteRequests'])->name('receiptvoucher.deleteRequests');
Route::delete('/receiptvoucher/confirm-delete/{id}', [ReceiptVoucherController::class, 'confirmDelete'])->name('receiptvoucher.confirmDelete');


Route::patch('/supplier-payment/request-delete/{id}', [SupplierPaymentController::class, 'requestDelete'])->name('supplier-payment.request-delete');
Route::get('/supplier-payment/pending-deletes', [SupplierPaymentController::class, 'pendingDeletes'])->name('supplier-payment.pending-deletes');

Route::post('/customer-payment/{id}/request-delete', [CustomerPaymentController::class, 'requestDelete'])->name('customer-payment.request-delete');
Route::get('/customer-payment/pending-deletes', [CustomerPaymentController::class, 'pendingDeletes'])->name('customer-payment.pending-deletes');

Route::post('/supplier/request-delete/{id}', [SupplierController::class, 'requestDelete'])->name('supplier.requestDelete');
Route::get('/supplier/delete-requests', [SupplierController::class, 'deleteRequests'])->name('supplier.deleteRequests');














































require __DIR__.'/auth.php';
