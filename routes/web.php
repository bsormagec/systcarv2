<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\StripeWebHookController;
use Illuminate\Support\Str;
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
Route::post('stripe/webhook',[StripeWebHookController::class,'handleWebhook']);

Route::get('/', function () {
    // $tenant = auth()->user()->tenant;
    // $tenant->run(function () {
    //     $user = \App\Models\tenant\User::create([
    //         "name" => "Augusto",
    //         "email" => "auguss24@gmail.com",
    //         "email_verified_at" => now(),
    //         "password" => bcrypt("password"),
    //         "remember_token" => Str::random(10),
    //     ]);
    //     $user->sucursals()->attach(1);
    // });
    //dd(auth()->user()->tenant->domains[0]->domain);
    $planes = \App\Models\Plan::all();
    return view('admin.welcome',compact('planes'));
});
Route::get('/payment-order', function () {
    return view('admin.paymentorder');
})->name('payment.order');

Route::group(['middleware' => ['auth']], function () {
    Route::get("credit-card",[BillingController::class,'creditCardForm'])
        ->name("billing.credit_card_form");
    Route::post("credit-card",[BillingController::class,'processCreditCardForm'])
        ->name("billing.process_credit_card");

    Route::get("plans",[PlanController::class,'index'])->name("plans.index");
    Route::get("createplan", [PlanController::class,'create'])->name("plans.create");
    Route::post("planstore", [PlanController::class,'store'])->name("plans.store");

    Route::post("plans/purchase",[PlanController::class,'purchase'])->name("plans.purchase");
    Route::post("plans/cancel",[PlanController::class,'cancelSubscription'])->name("plans.cancel");
    Route::post("plans/resume",[PlanController::class,'resumeSubscription'])->name("plans.resume");
});


Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia\Inertia::render('Dashboard');
})->name('dashboard');
