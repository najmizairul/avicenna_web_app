<?php

use App\Http\Controllers\API\CollectionController;
use App\Http\Controllers\API\TokenController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\Patients\AuthController;
use App\Http\Controllers\Patients\PrescriptionsController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::middleware(['auth:sanctum', 'verified'])->group(function() {

    Route::prefix('api')->name('api.')->group(function() {

        Route::post('/tokens/create', [TokenController::class, 'create'])->name('createToken');

        Route::get('/user', [UserController::class, 'index'])->name('user');

        Route::get('/collections/{code}', [CollectionController::class, 'show'])
            ->name('collection.show');

        Route::post('/collections/notify', [CollectionController::class, 'notify'])
            ->name('collection.notify');



    });

    Route::get('/dashboard', [DashboardController::class,  'index'])->name('dashboard');

    Route::post('/toggle-availability', [AvailabilityController::class, 'toggleAvailability'])->name('toggleAvailability');

    Route::prefix('pharmacy')->name('pharmacy.')->group(function() {
        Route::get('/dashboard', [DashboardController::class,  'pharmacyDashboard'])->name('dashboard');

        Route::get('/stock', [StockController::class, 'index'])->name('stock');
        Route::delete('/stock/{id}', [StockController::class, 'destroy'])->name('stock.destroy');
        Route::post('/stock', [StockController::class, 'update'])->name('stock.create');
        Route::post('/stock/{id}', [StockController::class, 'update'])->name('stock.update');
//        Route::get('/stock/{code}', [StockController::class, 'pharmacyStockShow'])->name('stock.show');

        Route::prefix('orders')->group(function() {
            Route::get('/', [OrderController::class, 'pharmacyOrders'])->name('orders');
            //Route::get('/code/{code}', [ OrderController::class, 'pharmacyVerifyPrescription'])->name('orders.code');
            Route::get('/{id}', [OrderController::class, 'pharmacyOrderShow'])->name('orders.show');
            Route::post('/{id}/accept', [OrderController::class, 'pharmacyOrderAccept'])->name('orders.accept');
            Route::post('/{id}', [OrderController::class, 'pharmacyUpdate'])->name('orders.update');


        });
    });

    Route::prefix('doctors')->name('doctors.')->group(function() {
        Route::get('/dashboard', [DashboardController::class,  'doctorsDashboard'])->name('dashboard');
        Route::get('/patients', [PatientController::class, 'doctorsIndex'])->name('patients');
        Route::get('/patients/{code}', [PatientController::class, 'doctorsShow'])->name('patients.show');
        Route::post('/patients/{code}', [PatientController::class, 'doctorsFinishConsultation'])->name('patients.finishConsultation');
        Route::post('/patients/visits', [PatientController::class, 'seePatient'])->name('patients.see');
    });

    Route::prefix('reception')->name('reception.')->group(function() {
        Route::get('/dashboard', [DashboardController::class, 'receptionDashboard'])->name('dashboard');
        Route::get('/patients', [PatientController::class, 'receptionIndex'])->name('patients');
//        Route::post('/patients/search', [PatientController::class, 'receptionSearch'])->name('patients.search');
        Route::post('/patients/assign-doctor', [PatientController::class, 'receptionAssignDoctor'])->name('patients.assignDoctor');
        Route::get('/patients/register', [PatientController::class, 'register'])->name('patients.register');
        Route::post('/patients/register', [PatientController::class, 'store'])->name('patients.register');
        Route::get('/patients/{code}', [PatientController::class, 'receptionShow'])->name('patients.show');

    });
});



Route::get('pharmacy/orders/code/{code}', [ OrderController::class, 'pharmacyVerifyPrescription'])->name('pharmacy.orders.code');

Route::get('customers/dashboard', [CustomersController::class, 'index'])
    ->name('customers.dashboard');
//Route::get('pharmacy/orders/show', [OrderController::class, 'pharmacyOrders'])->name('pharmacy.orders.show');


Route::get('/patients/login', [ AuthController::class, 'showLogin'])->name('patients.login');
Route::post('/patients/login', [ AuthController::class, 'login'])->name('patients.login');


Route::prefix('patients')->middleware('auth:patients')->name('patients.')->group(function() {
    Route::post('/logout', [ AuthController::class, 'logout'])->name('logout');

    Route::get('/prescriptions', [PrescriptionsController::class, 'index'])->name('prescriptions');
    Route::get('/prescriptions/{code}', [ PrescriptionsController::class, 'show'])->name('prescriptions.show');
    Route::put('/prescriptions/{code}', [OrderController::class, 'pharmacyVerifyPrescription'])->name('prescriptions.update');
}); //->middleware('auth:patients');

Route::prefix('pharmacy')->middleware('auth:patients')->name('pharmacy.')->group(function() {
    Route::post('/orders/{id}/onway', [OrderController::class, 'pharmacyUpdateOnWay'])->name('orders.update.onway');
    Route::post('/orders/{id}/infence', [OrderController::class, 'pharmacyUpdateInFence'])->name('orders.update.infence');
});
