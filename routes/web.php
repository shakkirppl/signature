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



Route::get('supplier-create', [SupplierController::class, 'create'])->name('supplier.create');
Route::post('supplier-store', [SupplierController::class, 'store'])->name('supplier.store');
Route::get('supplier-index', [SupplierController::class, 'index'])->name('supplier.index');
Route::get('supplier/{id}/edit', [SupplierController::class, 'edit'])->name('supplier.edit');
Route::post('supplier/{id}', [SupplierController::class, 'update'])->name('supplier.update');
Route::get('supplier/delete/{id}', [SupplierController::class, 'destroy'])->name('supplier.destroy');


Route::get('customer-create', [CustomerController::class, 'create'])->name('customer.create');
Route::post('customer-store', [CustomerController::class, 'store'])->name('customer.store');
Route::get('customer-index', [CustomerController::class, 'index'])->name('customer.index');
Route::get('customer/{id}/edit', [CustomerController::class, 'edit'])->name('customer.edit');
Route::post('customer/{id}', [CustomerController::class, 'update'])->name('customer.update');
Route::get('customer/delete/{id}', [CustomerController::class, 'destroy'])->name('customer.destroy');



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




Route::get('inspection-index', [InspectionController::class, 'index'])->name('inspection.index');
Route::get('inspection-view/{id}', [InspectionController::class,'view'])->name('inspection.view');
Route::post('inspection-store', [InspectionController::class, 'store'])->name('inspection.store');
Route::get('inspection-report', [InspectionController::class, 'report'])->name('inspection.report');
Route::get('/inspection/{id}/reportview', [InspectionController::class, 'viewReport'])->name('inspection.reportview');
Route::get('/rejected-animal-report', [InspectionController::class, 'rejectedAnimalReport'])->name('rejected.animal.report');
Route::get('/shipment/rejected/details/{shipment_no}', [InspectionController::class, 'shipmentRejectedDetails'])->name('shipment.rejected.details');





Route::get('purchade-conformation-index', [PurchaseConformationController::class, 'index'])->name('purchase-conformation.index');
Route::get('purchase-conformation/{id}', [PurchaseConformationController::class, 'Confirm'])->name('purchase-conformation.Confirm');
Route::post('purchade-conformation-store', [PurchaseConformationController::class,'store'])->name('purchase-conformation.store');
Route::get('purchase-conformation-report', [PurchaseConformationController::class, 'report'])->name('purchase-confirmation.report');
Route::get('purchase-conformation/{id}/view', [PurchaseConformationController::class, 'view']);


Route::get('shipment-index', [ShipmentController::class, 'index'])->name('shipment.index');
Route::get('shipment-create', [ShipmentController::class, 'create'])->name('shipment.create');
Route::post('shipment-store', [ShipmentController::class, 'store'])->name('shipment.store');
Route::delete('/shipment/{id}', [ShipmentController::class, 'destroy'])->name('shipment.destroy');



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

    



Route::get('/account-heads', [AccountHeadController::class, 'index'])->name('account-heads.index');
Route::get('/account-heads/new', [AccountHeadController::class, 'create'])->name('account-heads.new');
Route::get('/account-heads/edit/{id}', [AccountHeadController::class, 'edit'])->name('account-heads.edit');
Route::post('/account-heads/update/{id}', [AccountHeadController::class, 'update'])->name('account-heads.update');
Route::delete('/account-heads/{id}', [AccountHeadController::class, 'destroy'])->name('account-heads.destroy');
Route::post('/account-heads/store', [AccountHeadController::class, 'store'])->name('account-heads.store');




Route::get('sales_payment-create', [SalesPaymentController::class, 'create'])->name('sales_payment.create');
Route::post('sales_payment-store', [SalesPaymentController::class, 'store'])->name('sales_payment.store');
Route::get('sales_payment-index', [SalesPaymentController::class, 'index'])->name('sales_payment.index');
Route::get('sales_payment-view/{id}', [SalesPaymentController::class,'view'])->name('sales_payment.view');
Route::get('sales_payment/{id}/edit', [SalesPaymentController::class, 'edit'])->name('sales_payment.edit');
Route::post('sales_payment/{id}', [SalesPaymentController::class, 'update'])->name('sales_payment.update');
Route::get('/sales_payment/delete/{id}', [SalesPaymentController::class, 'destroy'])->name('sales_payment.destroy');
Route::get('sales_payment-report', [SalesPaymentController::class, 'report'])->name('sales_payment.report');
Route::get('/invoice-print/{order_no}', [SalesPaymentController::class, 'printInvoice'])->name('invoice.print');





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
Route::delete('slaughter-schedule/{id}', [SlaughterScheduleController::class, 'destroy'])->name('slaughter-schedule.destroy');





Route::get('supplier-ledger', [OutstandingController::class, 'supplierLedger'])->name('supplier.ledger');
Route::get('customer-ledger', [OutstandingController::class, 'customerLedger'])->name('customer.ledger');
Route::get('supplier-outstanding', [OutstandingController::class, 'supplierOutstanding'])->name('supplier.outstanding');
Route::get('customer-outstanding', [OutstandingController::class, 'customerOutstanding'])->name('customer.outstanding');


Route::get('animal-receiving-note', [AnimalReceivingNoteController::class, 'create']);


Route::get('postmortem-report-create', [PostMortemReportController::class, 'create']);
Route::post('postmortem-report-store', [PostMortemReportController::class, 'store'])->name('postmortem.store');



Route::get('antemortem-report-create', [AnteMortemReportController::class, 'create'])->name('antemortem.create');










require __DIR__.'/auth.php';
