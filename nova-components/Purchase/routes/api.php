<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Augusto\Purchase\Http\Controllers\SettingController;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

Route::namespace('Augusto\Purchase\Http\Controllers')->group(function(){
    //modulo compras
    Route::get('/selectproviders', 'PurchaseController@selectprovider');
    Route::get('/selectproducts', 'PurchaseController@selectproduct');
    Route::resource('purchases','PurchaseController');
    Route::get('search/purchases/{field}/{search}','PurchaseController@search');

    //modulo de ventas
    Route::get('/selectcustomers', 'SalesController@selectcustomer');
    Route::get('/selectproduct', 'SalesController@selectproduct');
    Route::get('/selectypepayments','SalesController@selectypepayments');
    Route::resource('sales','SalesController')->except(['index']);
    Route::get('ventas/{type?}','SalesController@filtersales');
    Route::get('search/sales/{field}/{type}/{search}','SalesController@search');

    //imprimir factura
    Route::get('invoices/{invoice}/print', 'SalesController@printInvoice');
    //imprimir recibo de pago
    Route::get('recibo/{account}/print', 'ReportesController@printboletapago');
    //rutas para los ajustes del sistema
    Route::group(['as'=>'settings.','prefix'=>'settings'], function(){
      Route::get('getinvoicelectronic',[SettingController::class,'getinvoicelectronic'])
             ->middleware(
            [
              InitializeTenancyByDomain::class,
               PreventAccessFromCentralDomains::class
            ]);
      Route::get('general', [SettingController::class, 'general']);
      Route::post('updategeneral', [SettingController::class, 'updategeneral']);

      Route::get('invoices', [SettingController::class, 'invoices'])
            ->middleware(
              [
                InitializeTenancyByDomain::class,
                PreventAccessFromCentralDomains::class
              ]);
      Route::post('updateinvoices', [SettingController::class, 'updateinvoices']);

      Route::get('default', [SettingController::class, 'default']);
      Route::post('updatedefault', [SettingController::class, 'updatedefault']);
    });
  });

// Route::get('/endpoint', function (Request $request) {
//     //
// });
