<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaxController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\MailsController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TermsController;
use App\Http\Controllers\UniteController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\KardexController;
use App\Http\Controllers\LabelsController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProcessController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\DocumentsController;
use App\Http\Controllers\ElementsController;
use App\Http\Controllers\EmpresasController;
use App\Http\Controllers\MovementsController;
use App\Http\Controllers\OperationController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\VendorOrderController;
use App\Http\Controllers\ProductReportController;
use App\Http\Controllers\ProductoPrecioController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::resource('empresa', EmpresasController::class);
Route::resource('documents', DocumentsController::class);

Route::resource('productoPrecio', ProductoPrecioController::class);
Route::post('/productoPrecio/delete/{id}', [ProductoPrecioController::class, 'delete'])->name('productoPrecio.delete');


Route::post('/operations/linea', [OperationController::class, 'filtrarLinea'])->name('operation.linea');
Route::post('/operations/item/description', [OperationController::class, 'getItemByDescription'])->name('operation.item.description');
Route::post('/operations/verificarCodigo', [OperationController::class, 'verificarCodigo'])->name('operation.verificarCodigo');
Route::get('/operations/item/code/{code}', [OperationController::class, 'getItemByCode'])->name('operation.item.code');

Route::resource('invoices', InvoiceController::class);
Route::get('/invoices/buscarCliente/{ruc}', [InvoiceController::class, 'verificarCliente'])->name('invoice.buscarCliente');
Route::get('/invoices/tipoDocumento/{tipo}', [InvoiceController::class, 'tipoDocumento'])->name('invoice.tipoDocumento');
Route::post('/invoices/xml', [InvoiceController::class, 'generarXML'])->name('invoice.xml');

Route::resource('cashier', CashierController::class)->except(['update', 'edit']);

Route::post('/imprimir-ticket', [CashierController::class, 'imprimirTicket']);

Route::get('/elements/priceProduct/row', [ElementsController::class, 'addRowPriceProduct'])->name('elements.priceProducts');
Route::get('/elements/order/row', [ElementsController::class, 'addRowOrder'])->name('element.order.row');




///////////////////////////////////////////////////////

Route::resource('bills', BillController::class)->middleware(['auth:sanctum', 'verified']);

Route::resource('cashier', CashierController::class)->except(['update', 'edit'])->middleware(['auth:sanctum', 'verified']);

Route::resource('customers', CustomerController::class)->except(['destroy'])->middleware(['auth:sanctum', 'verified']);

Route::resource('colors', ColorController::class)->except(['create', 'edit', 'show', 'destroy'])->middleware(['auth:sanctum', 'verified']);

Route::resource('deliveries', DeliveryController::class)->except(['create', 'store', 'update', 'edit', 'show', 'destroy'])->middleware(['auth:sanctum', 'verified']);

Route::get('/invoice/print/{id}', function () { return view('templates.invoice'); })->middleware(['auth:sanctum', 'verified']);

Route::resource('group', GroupController::class)->except(['create', 'store', 'update', 'edit', 'show', 'destroy'])->middleware(['auth:sanctum', 'verified']);

Route::resource('invoices', InvoiceController::class)->middleware(['auth:sanctum', 'verified']);
Route::get('/invoices/approved/{id}', [InvoiceController::class, 'approved'])->middleware(['auth:sanctum', 'verified']);
Route::get('/invoices/void/{id}', [InvoiceController::class, 'void'])->middleware(['auth:sanctum', 'verified']);

Route::resource('inventories', ProductController::class)->except(['show'])->middleware(['auth:sanctum', 'verified']);

Route::resource('mails', MailsController::class)->except(['create', 'store', 'edit', 'update', 'show', 'destroy'])->middleware(['auth:sanctum', 'verified']);

Route::resource('movements', MovementsController::class)->except(['update', 'edit'])->middleware(['auth:sanctum', 'verified']);

Route::resource('labels', LabelsController::class)->except(['create', 'store', 'edit', 'update', 'destroy'])->middleware(['auth:sanctum', 'verified']);

Route::resource('kardex', KardexController::class)->except(['create', 'store', 'update', 'edit', 'show', 'destroy'])->middleware(['auth:sanctum', 'verified']);

Route::get('/options', function () { return view('options.index'); })->middleware(['auth:sanctum', 'verified']);

Route::resource('orders', OrderController::class)->except(['destroy'])->middleware(['auth:sanctum', 'verified']);

Route::resource('payments', PaymentController::class)->middleware(['auth:sanctum', 'verified']);
Route::get('/operations/customer/payment/delete/{id}', [OperationController::class, 'paymentDelete'])->middleware(['auth:sanctum', 'verified']);

Route::resource('productsReport', ProductReportController::class)->middleware(['auth:sanctum', 'verified']);

Route::resource('process', ProcessController::class)->except(['show'])->middleware(['auth:sanctum', 'verified']);

Route::resource('roles', RoleController::class)->middleware(['auth:sanctum', 'verified']);

Route::resource('productsReport', ProductReportController::class)->middleware(['auth:sanctum', 'verified']);

Route::resource('sizes', SizeController::class)->except(['create', 'edit', 'show', 'destroy'])->middleware(['auth:sanctum', 'verified']);

Route::resource('taxes', TaxController::class)->except(['create', 'store', 'update', 'edit', 'show', 'destroy'])->middleware(['auth:sanctum', 'verified']);

Route::get('/ticket', function () { return view('ticket.index'); })->middleware(['auth:sanctum', 'verified']);

Route::resource('terms', TermsController::class)->except(['create', 'store', 'update', 'edit', 'show', 'destroy'])->middleware(['auth:sanctum', 'verified']);

Route::resource('unite', UniteController::class)->except(['create', 'store', 'update', 'edit', 'show', 'destroy'])->middleware(['auth:sanctum', 'verified']);

Route::resource('users', UsersController::class)->except(['edit', 'show'])->middleware(['auth:sanctum', 'verified']);

// Route::resource('vendors', VendorController::class)->except(['show', 'destroy'])->middleware(['auth:sanctum', 'verified']);
// Route::post('/vendors/access/api/login', [VendorOrderController::class, 'login'])->name('vendor.access.api');
// Route::get('/vendors/access/api/page', function () { return view('providers.login'); });
// Route::get('/vendors/access/api/main', function () { return view('providers.main'); });
// Route::post('/vendors/access/api/order/{id}', [VendorOrderController::class, 'destroy'])->name('vendor.order.delete');
// Route::get('/vendors/access/api/order/{id}', [VendorOrderController::class, 'pdf']);

Route::resource('/vendors/access/api', VendorOrderController::class)->except(['destroy']);

// Route::get('register', ['register' => false]);
// Route::post('register', ['register' => false]);

// Route::get('/register', function () {return redirect('/');})->name('register');

Route::resource('warehouses', WarehouseController::class)->middleware(['auth:sanctum', 'verified']);

Route::resource('warehouses', WarehouseController::class)->middleware(['auth:sanctum', 'verified']);

Route::get('/operations/color/{name}', [OperationController::class, 'setNewColor'])->middleware(['auth:sanctum', 'verified'])->name('operation.color.name');
Route::post('/operations/color/update/{id}', [OperationController::class, 'updateColor'])->middleware(['auth:sanctum', 'verified'])->name('operation.color.update');
Route::get('/operations/color/delete/{id}', [OperationController::class, 'deleteColor'])->middleware(['auth:sanctum', 'verified'])->name('operation.color.delete');

Route::get('/operations/cashier/{fecha}', [OperationController::class, 'getCashier'])->middleware(['auth:sanctum', 'verified'])->name('operation.cashier');

Route::post('/operations/customer/new/{id}', [OperationController::class, 'setCustomer'])->middleware(['auth:sanctum', 'verified'])->name('operation.customer.new');
Route::get('/operations/customer/{id}', [OperationController::class, 'getCustomer'])->middleware(['auth:sanctum', 'verified'])->name('operation.customer.id');
Route::get('/operations/customer/name/{name}', [OperationController::class, 'getCustomerbyName'])->middleware(['auth:sanctum', 'verified'])->name('operation.customer.name');
Route::get('/operations/customer/delete/{id}', [OperationController::class, 'delCustomer'])->middleware(['auth:sanctum', 'verified'])->name('operation.customer.delete');
Route::get('/operations/customer/file/delete/{id}', [OperationController::class, 'delFile'])->middleware(['auth:sanctum', 'verified'])->name('operation.file.delete');
Route::post('/operations/customer/notes/{id}', [OperationController::class, 'updateNotes'])->middleware(['auth:sanctum', 'verified'])->name('operation.customer.notes');
Route::post('/operations/customer/contacts/{id}', [OperationController::class, 'setContact'])->middleware(['auth:sanctum', 'verified'])->name('operation.customer.contact');
Route::get('/operations/customer/contacts/delete/{id}', [OperationController::class, 'deleteContact'])->middleware(['auth:sanctum', 'verified'])->name('operation.customer.contact.delete');
Route::get('/operations/customer/{id}/get/transaction', [OperationController::class, 'getTransacciones'])->middleware(['auth:sanctum', 'verified'])->name('operation.customer.get.transaction');

Route::get('/operations/delivery/{name}', [OperationController::class, 'setNewDelivery'])->middleware(['auth:sanctum', 'verified'])->name('operation.delivery.name');
Route::post('/operations/delivery/update', [OperationController::class, 'updateDelivery'])->middleware(['auth:sanctum', 'verified'])->name('operation.delivery.update');
Route::get('/operations/delivery/delete/{id}', [OperationController::class, 'deleteDelivery'])->middleware(['auth:sanctum', 'verified'])->name('operation.delivery.delete');

Route::get('/operations/documents/update', [OperationController::class, 'updateDocumentNumber'])->middleware(['auth:sanctum', 'verified'])->name('operation.document.update');

Route::get('/operations/file/customer/{id}', [OperationController::class, 'deleteCustomerFile'])->middleware(['auth:sanctum', 'verified'])->name('operation.file.customer.delete');
Route::get('/operations/file/stage/{id}', [OperationController::class, 'deleteStageFile'])->middleware(['auth:sanctum', 'verified'])->name('operation.file.stage.delete');

Route::get('/operations/group/{name}', [OperationController::class, 'setNewGroup'])->middleware(['auth:sanctum', 'verified'])->name('operation.group.name');
Route::post('/operations/group/update/{id}', [OperationController::class, 'updateGroup'])->middleware(['auth:sanctum', 'verified'])->name('operation.group.update');
Route::get('/operations/group/delete/{id}', [OperationController::class, 'deleteGroup'])->middleware(['auth:sanctum', 'verified'])->name('operation.group.delete');

Route::get('/operations/item/{id}', [OperationController::class, 'getItem'])->middleware(['auth:sanctum', 'verified'])->name('operation.item.id');
Route::get('/operations/item/delete/{id}', [OperationController::class, 'delItem'])->middleware(['auth:sanctum', 'verified'])->name('operation.item.delete');
Route::get('/operations/item/image/{id}', [OperationController::class, 'getImage'])->middleware(['auth:sanctum', 'verified'])->name('operation.item.getimage');
Route::get('/operations/item/comparer/delete/{id}', [OperationController::class, 'deleteItemComparer'])->middleware(['auth:sanctum', 'verified'])->name('operation.item.comparer.delete');
Route::get('/operations/item/codebar/{code}', [OperationController::class, 'getItemCodebar'])->middleware(['auth:sanctum', 'verified'])->name('operation.item.codebar');
Route::get('/operations/item/customer/inventory/delete/{id}', [OperationController::class, 'deleteCustomerInventory'])->middleware(['auth:sanctum', 'verified'])->name('operation.item.customer.inventory.delete');
Route::get('/operations/item/image/delete/{id}', [OperationController::class, 'delImage'])->middleware(['auth:sanctum', 'verified'])->name('operation.image.delete');
Route::get('/operations/item/colors/{id}', [OperationController::class, 'getColor'])->middleware(['auth:sanctum', 'verified'])->name('operation.order.colors');
Route::get('/operations/item/sizes/{id}', [OperationController::class, 'getSize'])->middleware(['auth:sanctum', 'verified'])->name('operation.order.sizes');
Route::get('/operations/colors', [OperationController::class, 'getAllColors'])->name('operation.colors');
Route::get('/operations/sizes', [OperationController::class, 'getAllSizes'])->name('operation.sizes');
Route::post('/operations/item/customer/inventory/{id}', [OperationController::class, 'setCustomerInventory'])->middleware(['auth:sanctum', 'verified'])->name('operation.item.customer.inventory');
Route::post('/operations/item/comparer', [OperationController::class, 'setItemComparer'])->middleware(['auth:sanctum', 'verified'])->name('operation.item.comparer');

Route::post('/operations/mail/update/{id}', [OperationController::class, 'updateMail'])->middleware(['auth:sanctum', 'verified'])->name('operation.mail.update');

Route::get('/operations/payment/list/{customer}', [OperationController::class, 'paymentList'])->name('operation.payments.list');

Route::get('/elements/estimate/pdf/{id}', [ElementsController::class, 'estimate'])->middleware(['auth:sanctum', 'verified'])->name('element.estimate.pdf');
Route::get('/elements/invoice/pdf/{id}', [ElementsController::class, 'invoice'])->middleware(['auth:sanctum', 'verified'])->name('element.invoice.pdf');
Route::get('/elements/invoice/print/{id}', [ElementsController::class, 'invoiceTicket'])->middleware(['auth:sanctum', 'verified'])->name('element.invoice.ticket');
Route::get('/elements/movement/pdf/{id}', [ElementsController::class, 'movement'])->middleware(['auth:sanctum', 'verified'])->name('element.movement.pdf');
Route::get('/elements/order/row/invoice', [ElementsController::class, 'addRowOrder3'])->middleware(['auth:sanctum', 'verified'])->name('element.order.row.invoice');
Route::get('/elements/vendor/order/row', [ElementsController::class, 'addRowOrder2'])->name('element.vendor.order.row');
Route::get('/elements/vendor/international/order/row', [ElementsController::class, 'addRowVendorOrder'])->name('element.vendor.international.order.row');
Route::get('/elements/bill/row', [ElementsController::class, 'addRowBill'])->middleware(['auth:sanctum', 'verified'])->name('element.bill.row');
Route::post('/elements/kardex/pdf', [ElementsController::class, 'kardex'])->middleware(['auth:sanctum', 'verified'])->name('element.kardex.pdf');
Route::post('/elements/product/report/pdf', [ElementsController::class, 'productsReport'])->middleware(['auth:sanctum', 'verified'])->name('element.product.report.pdf');
Route::get('/elements/production/items', [ElementsController::class, 'addRowItemProd'])->middleware(['auth:sanctum', 'verified'])->name('element.production.item');
Route::get('/elements/production/item/delete/{id}', [ElementsController::class, 'delRowItemProd'])->middleware(['auth:sanctum', 'verified'])->name('element.production.delete.item');
Route::post('/elements/product/report/pdf', [ElementsController::class, 'productsReport'])->middleware(['auth:sanctum', 'verified'])->name('element.product.report.pdf');

Route::get('/operations/order/delete/{id}', [OperationController::class, 'deleteOrder'])->middleware(['auth:sanctum', 'verified'])->name('operation.order.delete');

Route::get('/operations/payment/invoices/{customer}', [OperationController::class, 'getInvoices'])->middleware(['auth:sanctum', 'verified'])->name('operation.payment.invoice');

Route::get('/operations/process/{id}', [OperationController::class, 'getProcess'])->middleware(['auth:sanctum', 'verified'])->name('operation.process.list');
Route::get('/operations/process/condition/{id}', [OperationController::class, 'getCondition'])->middleware(['auth:sanctum', 'verified'])->name('operation.process.condition');
Route::get('/operations/process/description/{name}', [OperationController::class, 'getProcessName'])->middleware(['auth:sanctum', 'verified'])->name('operation.process.name');
Route::post('/operations/process/stage/update', [OperationController::class, 'updateStageData'])->middleware(['auth:sanctum', 'verified'])->name('operation.stage.update');
Route::post('/operations/process/stage/approve/{id}', [OperationController::class, 'setStageApprove'])->middleware(['auth:sanctum', 'verified'])->name('operation.stage.approve');
Route::post('/operations/process/stage/codebar', [OperationController::class, 'setCodebar'])->middleware(['auth:sanctum', 'verified'])->name('operation.stage.codebar');
Route::get('/operations/process/stage/codebar/delete/{id}', [OperationController::class, 'deleteCodebar'])->middleware(['auth:sanctum', 'verified'])->name('operation.stage.codebar.delete');

Route::get('/operations/size/{name}', [OperationController::class, 'setNewSize'])->middleware(['auth:sanctum', 'verified'])->name('operation.size.name');
Route::post('/operations/size/update/{id}', [OperationController::class, 'updateSize'])->middleware(['auth:sanctum', 'verified'])->name('operation.size.update');
Route::get('/operations/size/delete/{id}', [OperationController::class, 'deleteSize'])->middleware(['auth:sanctum', 'verified'])->name('operation.size.delete');

Route::post('/operations/shipto', [OperationController::class, 'setShipTo'])->middleware(['auth:sanctum', 'verified'])->name('operation.shipto');
Route::post('/operations/shipto/update/{id}', [OperationController::class, 'updateShipTo'])->middleware(['auth:sanctum', 'verified'])->name('operation.shipto.update');
Route::get('/operations/shipto/{id}', [OperationController::class, 'getShipList'])->middleware(['auth:sanctum', 'verified'])->name('operation.shipto.list');
Route::get('/operations/shipto/address/{id}', [OperationController::class, 'getShipTo'])->middleware(['auth:sanctum', 'verified'])->name('operation.shipto.address');
Route::get('/operations/shipto/customer/{id}', [OperationController::class, 'delCustomerShipTo'])->middleware(['auth:sanctum', 'verified'])->name('operation.shipto.customer.delete');
Route::get('/operations/shipto/delete', [OperationController::class, 'delShipTo'])->middleware(['auth:sanctum', 'verified'])->name('operation.shipto.delete');
Route::get('/operations/shipto/delete/{id}', [OperationController::class, 'destroyShipTo'])->middleware(['auth:sanctum', 'verified'])->name('operation.shipto.destroy');

Route::post('/operations/tax', [OperationController::class, 'setNewTax'])->middleware(['auth:sanctum', 'verified'])->name('operation.tax');
Route::post('/operations/tax/update', [OperationController::class, 'updateTax'])->middleware(['auth:sanctum', 'verified'])->name('operation.tax.update');
Route::get('/operations/tax/delete/{id}', [OperationController::class, 'deleteTax'])->middleware(['auth:sanctum', 'verified'])->name('operation.tax.delete');

Route::get('/operations/term/{name}', [OperationController::class, 'setNewTerm'])->middleware(['auth:sanctum', 'verified'])->name('operation.terms.name');
Route::post('/operations/term/update', [OperationController::class, 'updateTerm'])->middleware(['auth:sanctum', 'verified'])->name('operation.terms.update');
Route::get('/operations/term/delete/{id}', [OperationController::class, 'deleteTerm'])->middleware(['auth:sanctum', 'verified'])->name('operation.terms.delete');

Route::post('/operations/unit/{id}', [OperationController::class, 'setUnitMeasure'])->middleware(['auth:sanctum', 'verified'])->name('operation.unit');
Route::post('/operations/unit/update/{id}', [OperationController::class, 'updateUnitMeasure'])->middleware(['auth:sanctum', 'verified'])->name('operation.unit.update');
Route::get('/operations/unit/delete/{id}', [OperationController::class, 'deleteUnitMeasure'])->middleware(['auth:sanctum', 'verified'])->name('operation.unit.delete');

Route::post('/operations/upload/customer', [OperationController::class, 'uploadCustomerFile'])->middleware(['auth:sanctum', 'verified'])->name('operation.upload.customer');
Route::post('/operations/upload/stage', [OperationController::class, 'uploadStageFile'])->middleware(['auth:sanctum', 'verified'])->name('operation.upload.stage');

Route::get('/operations/users', [OperationController::class, 'getUsers'])->middleware(['auth:sanctum', 'verified'])->name('operation.users.get');
Route::get('/operations/user/image/{id}', [OperationController::class, 'getUsersImage'])->middleware(['auth:sanctum', 'verified'])->name('operation.users.image');

Route::post('/operations/vendor/new/{id}', [OperationController::class, 'setVendor'])->middleware(['auth:sanctum', 'verified'])->name('operation.vendor.new');
Route::get('/operations/vendor/get/{id}', [OperationController::class, 'getVendor'])->middleware(['auth:sanctum', 'verified'])->name('operation.vendor.get');
Route::get('/operations/vendor/get/name/{name}', [OperationController::class, 'getVendor2'])->middleware(['auth:sanctum', 'verified'])->name('operation.vendor.get.name');
Route::get('/operations/vendor/delete/{id}', [OperationController::class, 'deleteVendor'])->middleware(['auth:sanctum', 'verified'])->name('operation.vendor.delete');
Route::post('/operations/vendor/generate/user', [OperationController::class, 'generatePassVendor'])->middleware(['auth:sanctum', 'verified'])->name('operation.vendor.generate.user');

Route::get('reports/sales', [ReportController::class, 'salesreport'])->middleware(['auth:sanctum', 'verified']);
Route::get('reports/product', [ReportController::class, 'productreport'])->middleware(['auth:sanctum', 'verified']);
Route::get('reports/customer', [ReportController::class, 'customereport'])->middleware(['auth:sanctum', 'verified']);
Route::post('reports/sales/find', [ReportController::class, 'findSales'])->middleware(['auth:sanctum', 'verified']);
Route::post('reports/product/find', [ReportController::class, 'findProduct'])->middleware(['auth:sanctum', 'verified']);
Route::post('reports/customer/find', [ReportController::class, 'findCustomer'])->middleware(['auth:sanctum', 'verified']);

Route::get('reports/sales', [ReportController::class, 'salesreport'])->middleware(['auth:sanctum', 'verified']);
Route::get('reports/product', [ReportController::class, 'productreport'])->middleware(['auth:sanctum', 'verified']);
Route::get('reports/customer', [ReportController::class, 'customereport'])->middleware(['auth:sanctum', 'verified']);
Route::post('reports/sales/find', [ReportController::class, 'findSales'])->middleware(['auth:sanctum', 'verified']);
Route::post('reports/product/find', [ReportController::class, 'findProduct'])->middleware(['auth:sanctum', 'verified']);
Route::post('reports/customer/find', [ReportController::class, 'findCustomer'])->middleware(['auth:sanctum', 'verified']);
