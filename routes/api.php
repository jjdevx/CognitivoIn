<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'auth:api'], function ()
{
    Route::prefix('{profile}')->group(function ()
    {
        Route::prefix('sync')->group(function ()
        {
            Route::post('transaction', 'Api\TransactionController@upload');
            Route::post('payment', 'Api\PaymentController@upload');
            Route::post('item', 'Api\ItemController@sync');
            Route::post('Promotion', 'Api\PromotionController@upload');
            Route::post('contract', 'Api\ContractController@sync');
            Route::post('customer', 'Api\CustomerController@sync');
            Route::post('location', 'Api\LocationController@upload');
            Route::post('saletax', 'Api\SaleTaxController@sync');
            Route::post('supplier', 'Api\SupplierController@upload');
        });

        Route::prefix('download')->group(function ()
        {
            Route::get('transaction', 'Api\TransactionController@download');
            Route::get('payment', 'Api\PaymentController@download');
            Route::get('item', 'Api\ItemController@download');
            Route::get('Promotion', 'Api\PromotionController@download');
            Route::get('contract', 'Api\ContractController@download');
            Route::get('customer', 'Api\CustomerController@download');
            Route::get('location', 'Api\LocationController@download');
            Route::get('saletax', 'Api\SaleTaxController@download');
            Route::get('supplier', 'Api\SupplierController@download');
        });
    });
});

Route::resource('profile', 'ProfileController');
Route::get('companys/{id}', 'ProfileController@get_companies');

Route::prefix('{profile}')->group(function ()
{
    Route::prefix('back-office')->group(function ()
    {
        Route::post('customers/store', 'CustomerController@store');
        Route::post('opportunities/{opportunity}/tasks/checked', 'OpportunityTaskController@taskChecked');

        Route::prefix('list')->group(function ()
        {
            Route::get('locations/{filterBy}', 'LocationController@index');
            Route::get('vats/{filterBy}', 'VatController@index');
            Route::get('carts/{filterBy}', 'CartController@index');
            Route::get('contracts/{filterBy}', 'ContractController@index');
            Route::get('customers/{filterBy}', 'CustomerController@index');
            Route::get('suppliers/{filterBy}', 'SupplierController@index');
            Route::get('items/{filterBy}', 'ItemController@index');
            Route::get('itemmovement/{filterBy}', 'ItemMovementController@index');
            Route::get('itempromotions/{filterBy}', 'ItemPromotionController@index');
            Route::get('followers/{filterBy}', 'FollowerController@index');
            Route::get('pipelines/{filterBy}', 'PipelineController@index');
            Route::get('opportunities/{filterBy}', 'OpportunityController@index');
            Route::get('orders/{filterBy}', 'OrderController@index');
            Route::get('accounts/{filterBy}', 'Api\AccountController@index');
            Route::get('account-payables/{filterBy}', 'AccountPayableController@index');
            Route::get('account-receivables/{filterBy}', 'AccountReceivableController@index');
            Route::get('account-movement/{filterBy}', 'AccountMovementController@index');
        });

        //Searches using ElasticSearch Server for index based search results.
        Route::prefix('search')->group(function ()
        {
            Route::get('profiles/{query}', 'ProfileController@search');
            Route::get('customers/{query}', 'CustomerController@search');
            Route::get('suppliers/{query}', 'SupplierController@search');
            Route::get('items/{query}', 'ItemController@search');
            Route::get('accounts/{query}', 'Api\AccountController@search');
            //TODO
            Route::get('opportunities/{query}', 'OpportunityController@search');
            Route::get('orders/{query}', 'OrderController@search');
            Route::get('account-receivables/{query}', 'AccountReceivableController@search');
            Route::get('account-payables/{query}', 'AccountPayableController@search');

            //Search Account Receivables by Customer
            Route::post('account-receivables', 'AccountReceivableController@search');
        });

        //Annull movements on specific modules
        Route::prefix('transact')->group(function ()
        {
            Route::get('salesApprove/{orderID}', 'OrderController@approve');
            Route::post('opportunities', 'OpportunityController@approve');

            Route::post('payment-made', 'AccountPayableController@store');
            Route::post('payment-received', 'AccountReceivableController@store');
        });

        //Annull movements on specific modules
        Route::prefix('revert')->group(function ()
        {
            Route::post('orders/{id}', 'OrderController@annul');
            Route::post('opportunities', 'OpportunityController@annul');

            //TODO
            Route::post('payment-made/{id}', 'AccountPayableController@annul');
            Route::post('payment-recieved/{id}', 'AccountReceivableController@annull');
        });

        Route::get('list-currency', 'Api\ApiController@list_currency');

        Route::resources([
            'locations' => 'LocationController',
            'vats' => 'VatController',
            'contracts' => 'ContractController',
            'followers' => 'FollowerController',
            'customers' => 'CustomerController',
            'suppliers' => 'SupplierController',
            'items' => 'ItemController',
            'itempromotions' => 'ItemPromotionController',
            'stockmovment' => 'ItemMovementController',
            'pipelines' => 'PipelineController',
            'pipeline-stages' => 'PipelineStageController',
            'opportunities' => 'OpportunityController',
            'opportunities/{id}/tasks' => 'OpportunityTaskController',
            'opportunities/{id}/members' => 'OpportunityMemberController',
            'opportunities/{id}/items' => 'CartController',
            'orders' => 'OrderController',
            'accounts' => 'Api\AccountController',
            'account-payables' => 'AccountPayableController',
            'account-receivables' => 'AccountReceivableController',
            'account-movement' => 'AccountMovementController'
        ]);
        Route::post('AccountMovmentTransfer', 'AccountMovementController@Movement');
    });

    Route::get('PromotionTypeByItem', 'ItemPromotionController@getPromotionTypeByItem');
    Route::get('PromotionTypeByLocation', 'ItemPromotionController@getPromotionTypeByLocation');
    Route::post('AmountFromContract', 'Api\AccountController@AmountFromContract');
    Route::post('SentReceipt', 'EmailController@sentemail');
});

Route::get('login/{email}/{password}', 'Auth\SocialAuthController@Login');
