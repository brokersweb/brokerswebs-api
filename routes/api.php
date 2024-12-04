<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;



/*
    |--------------------------------------------------------------------------
    | Admin Panel Routes
    |--------------------------------------------------------------------------
    */

Route::group(['middleware' => ['auth', 'jwt.role-admin']], function () {

    Route::group(['prefix' => 'panel'], function () {
        Route::group(['prefix' => 'roles'], function () {
            Route::get('/', [App\Http\Controllers\RoleController::class, 'index']);
            Route::post('/', [App\Http\Controllers\RoleController::class, 'store']);
            Route::get('/{id}', [App\Http\Controllers\RoleController::class, 'show']);
            Route::put('/{id}', [App\Http\Controllers\RoleController::class, 'update']);
            Route::delete('/{id}', [App\Http\Controllers\RoleController::class, 'destroy']);
            Route::post('/{id}/permissions', [App\Http\Controllers\RoleController::class, 'assignPermissions']);
            Route::put('/{id}/permissions-update', [App\Http\Controllers\RoleController::class, 'updatePermissions']);
        });
        Route::group(['prefix' => 'galleries'], function () {
            Route::get('/', [App\Http\Controllers\Panel\GalleryController::class, 'index']);
            Route::get('/{id}', [App\Http\Controllers\Panel\GalleryController::class, 'show']);
            Route::post('/', [App\Http\Controllers\Panel\GalleryController::class, 'store']);
            Route::delete('/{id}', [App\Http\Controllers\Panel\GalleryController::class, 'destroy']);
            Route::get('/{id}/status', [App\Http\Controllers\Panel\GalleryController::class, 'updateStatus']);
            Route::get('/rejected-all/{id}', [App\Http\Controllers\Panel\GalleryController::class, 'rejectedAll']);
            Route::get('/accepted-all/{id}', [App\Http\Controllers\Panel\GalleryController::class, 'acceptedAll']);
        });
        Route::group(['prefix' => 'permissions'], function () {
            Route::get('/', [App\Http\Controllers\PermissionController::class, 'index']);
            Route::post('/', [App\Http\Controllers\PermissionController::class, 'store']);
            Route::get('/{id}', [App\Http\Controllers\PermissionController::class, 'show']);
            Route::put('/{id}', [App\Http\Controllers\PermissionController::class, 'update']);
            Route::delete('/{id}', [App\Http\Controllers\PermissionController::class, 'destroy']);
        });
        Route::group(['prefix' => 'addresses'], function () {
            Route::get('/',  [App\Http\Controllers\AddressController::class, 'index']);
            Route::delete('/{id}', [App\Http\Controllers\AddressController::class, 'destroy']);
            Route::post('/',  [App\Http\Controllers\AddressController::class, 'store']);
            Route::get('/{id}', [App\Http\Controllers\AddressController::class, 'show']);
            Route::put('/{id}', [App\Http\Controllers\AddressController::class, 'update']);
        });
        Route::group(['prefix' => 'buildings'], function () {
            Route::get('/',  [App\Http\Controllers\BuildingCompanyController::class, 'index']);
            Route::delete('/{id}', [App\Http\Controllers\BuildingCompanyController::class, 'destroy']);
            Route::post('/',  [App\Http\Controllers\BuildingCompanyController::class, 'store']);
            Route::get('/{id}', [App\Http\Controllers\BuildingCompanyController::class, 'show']);
            Route::put('/{id}', [App\Http\Controllers\BuildingCompanyController::class, 'update']);
        });
        Route::group(['prefix' => 'common-zones'], function () {
            Route::get('/', [App\Http\Controllers\CommonZoneController::class, 'index']);
            Route::get('/{id}', [App\Http\Controllers\CommonZoneController::class, 'show']);
            Route::post('/', [App\Http\Controllers\CommonZoneController::class, 'store']);
            Route::put('/{id}', [App\Http\Controllers\CommonZoneController::class, 'update']);
            Route::delete('/{id}', [App\Http\Controllers\CommonZoneController::class, 'destroy']);
        });
        Route::group(['prefix' => 'internal-features'], function () {
            Route::get('/', [App\Http\Controllers\InternalFeatureController::class, 'index']);
            Route::get('/{id}', [App\Http\Controllers\InternalFeatureController::class, 'show']);
            Route::post('/', [App\Http\Controllers\InternalFeatureController::class, 'store']);
            Route::put('/{id}', [App\Http\Controllers\InternalFeatureController::class, 'update']);
            Route::delete('/{id}', [App\Http\Controllers\InternalFeatureController::class, 'destroy']);
        });
        Route::group(['prefix' => 'external-features'], function () {
            Route::get('/', [App\Http\Controllers\ExternalFeatureController::class, 'index']);
            Route::get('/{id}', [App\Http\Controllers\ExternalFeatureController::class, 'show']);
            Route::post('/', [App\Http\Controllers\ExternalFeatureController::class, 'store']);
            Route::put('/{id}', [App\Http\Controllers\ExternalFeatureController::class, 'update']);
            Route::delete('/{id}', [App\Http\Controllers\ExternalFeatureController::class, 'destroy']);
        });

        Route::group(['prefix' => 'immovable-types'], function () {
            Route::get('/{id}', [App\Http\Controllers\ImmovableTypeController::class, 'show']);
            Route::post('/', [App\Http\Controllers\ImmovableTypeController::class, 'store']);
            Route::put('/{id}', [App\Http\Controllers\ImmovableTypeController::class, 'update']);
            Route::delete('/{id}', [App\Http\Controllers\ImmovableTypeController::class, 'destroy']);
        });

        Route::group(['prefix' => 'company-configurations'], function () {
            Route::post('/', [App\Http\Controllers\CompanyConfigurationController::class, 'store']);
            Route::put('/{id}', [App\Http\Controllers\CompanyConfigurationController::class, 'update']);
        });

        Route::group(['prefix' => 'professions'], function () {
            Route::get('/{id}', [App\Http\Controllers\Base\ProfessionController::class, 'show']);
            Route::delete('/{id}', [App\Http\Controllers\Base\ProfessionController::class, 'destroy']);
            Route::post('/', [App\Http\Controllers\Base\ProfessionController::class, 'store']);
            Route::put('/{id}', [App\Http\Controllers\Base\ProfessionController::class, 'update']);
        });


        // TODO:: ------------------------------------------------USUARIOS------------------------------------------------

        Route::group(['prefix' => 'users'], function () {
            Route::get('/staff', [App\Http\Controllers\UserController::class, 'indexStaff']);
            Route::post('/role-add', [App\Http\Controllers\UserController::class, 'assignRole']);
            Route::put('/{id}/roles-update', [App\Http\Controllers\UserController::class, 'updateRoles']);


            Route::get('/', [App\Http\Controllers\UserController::class, 'index']);
            Route::get('/{id}', [App\Http\Controllers\UserController::class, 'show']);
            Route::get('/status-change/{id}', [App\Http\Controllers\UserController::class, 'changeStatus']);
            Route::put('/{id}', [App\Http\Controllers\UserController::class, 'update']);
            Route::post('/change-password/{id}', [App\Http\Controllers\UserController::class, 'updatePassword']);
            Route::delete('/{id}', [App\Http\Controllers\UserController::class, 'destroy']);
        });

        // TODO:: ------------------------------------------------INQUILINOS ------------------------------------------------

        Route::group(['prefix' => 'tenants'], function () {

            Route::get('/', [App\Http\Controllers\Panel\TenantController::class, 'index']);
            Route::get('/{id}', [App\Http\Controllers\Panel\TenantController::class, 'show']);
            Route::post('/', [App\Http\Controllers\Panel\TenantController::class, 'store']);
            Route::put('/{id}', [App\Http\Controllers\Panel\TenantController::class, 'update']);

            // Otra Información
            Route::get('/reference/{id}', [App\Http\Controllers\Panel\TenantController::class, 'getReferences']);
            Route::get('/cosigner/{id}', [App\Http\Controllers\Panel\TenantController::class, 'getCosigners']);
            // Immovables
            Route::get('/{id}/rentings', [App\Http\Controllers\Panel\TenantController::class, 'getRentings']);
            Route::get('/immovable-letter/{id}', [App\Http\Controllers\Panel\TenantController::class, 'getImmoLetter']);
        });

        // TODO:: ------------------------------------------------ PROPIETARIOS ------------------------------------------------

        Route::group(['prefix' => 'owners'], function () {
            Route::get('/', [App\Http\Controllers\Panel\OwnerController::class, 'index']);
            Route::get('/{id}', [App\Http\Controllers\Panel\OwnerController::class, 'show']);
            Route::post('/', [App\Http\Controllers\Panel\OwnerController::class, 'store']);
            Route::put('/{id}', [App\Http\Controllers\Panel\OwnerController::class, 'update']);

            Route::post('/import', [App\Http\Controllers\Panel\OwnerController::class, 'importCreate']);
        });


        // TODO:: UNIDADES O COPROPIEDADES
        Route::group(['prefix' => 'coownerships'], function () {
            Route::get('/', [App\Http\Controllers\Panel\CoownershipController::class, 'index']);
            // TODO:: Inmuebles - rentados
            Route::get('/rented', [App\Http\Controllers\Panel\CoownershipController::class, 'coownershipsRented']);
            Route::get('/immovables-rented/{id}', [App\Http\Controllers\Panel\CoownershipController::class, 'immovablesRentedByCoownership']);

            // TODO:: Inmuebles - vendidos
            Route::get('/sold', [App\Http\Controllers\Panel\CoownershipController::class, 'coownershipsSold']);
            Route::get('/immovables-sold/{id}', [App\Http\Controllers\Panel\CoownershipController::class, 'immovablesSoldByCoownership']);

            Route::post('/',  [App\Http\Controllers\Panel\CoownershipController::class, 'store']);
            Route::delete('/{id}', [App\Http\Controllers\Panel\CoownershipController::class, 'destroy']);
            Route::post('/store-modal',  [App\Http\Controllers\Panel\CoownershipController::class, 'storeModal']);

            Route::get('/{id}', [App\Http\Controllers\Panel\CoownershipController::class, 'show']);
            Route::put('/{id}', [App\Http\Controllers\Panel\CoownershipController::class, 'update']);
        });

        // TODO:: -------------------------------------- AREA CONTABLE -----------------------------------------

        Route::group(['prefix' => 'account-status'], function () {
            Route::get('/', [App\Http\Controllers\Panel\AccountStatusController::class, 'index']);
            Route::get('/{id}', [App\Http\Controllers\Panel\AccountStatusController::class, 'showMe']);
            Route::delete('/cancelled/{id}', [App\Http\Controllers\Panel\AccountStatusController::class, 'cancel']);
            Route::get('/invoice-sent-customer/{id}', [App\Http\Controllers\Panel\AccountStatusController::class, 'invoiceSentCustomer']);
            Route::post('/', [App\Http\Controllers\Panel\AccountStatusController::class, 'store']);
            Route::get('/download/{id}', [App\Http\Controllers\Panel\AccountStatusController::class, 'download']);
        });

        Route::group(['prefix' => 'account-status/inmovables'], function () {
            Route::get('/form', [App\Http\Controllers\Panel\AccountStatusController::class, 'indexImmovablesAc']);
        });

        Route::group(['prefix' => 'accounts-collection'], function () {
            Route::get('/', [App\Http\Controllers\Panel\AccountCollectionController::class, 'index']);
            Route::get('/select-account-collection', [App\Http\Controllers\Panel\AccountCollectionController::class, 'indexAccountCollection']);
            Route::post('/', [App\Http\Controllers\Panel\AccountCollectionController::class, 'store']);
        });

        Route::group(['prefix' => 'readjustments'], function () {
            Route::get('/', [App\Http\Controllers\Panel\ReadjustmentController::class, 'index']);
            Route::get('/{id}', [App\Http\Controllers\Panel\ReadjustmentController::class, 'show']);
            Route::post('/', [App\Http\Controllers\Panel\ReadjustmentController::class, 'store']);
            Route::put('/{id}', [App\Http\Controllers\Panel\ReadjustmentController::class, 'update']);
            Route::delete('/{id}', [App\Http\Controllers\Panel\ReadjustmentController::class, 'destroy']);
        });

        // TODO:: ------------------------------------------------ INMUEBLES ----------------------------------------------------------

        Route::group(['prefix' => 'immovables'], function () {
            Route::get('/', [App\Http\Controllers\Panel\ImmovableController::class, 'index']);

            // TODO:: Por Categoria
            Route::get('/byrent', [App\Http\Controllers\Panel\ImmovableController::class, 'indexByRent']);
            Route::get('/bysell', [App\Http\Controllers\Panel\ImmovableController::class, 'indexBySell']);
            Route::get('/byboth', [App\Http\Controllers\Panel\ImmovableController::class, 'indexByBoth']);

            // Por unidad
            Route::get('/coownership/rented', [App\Http\Controllers\Panel\ImmovableController::class, 'indexRentedWithCoOwnership']);
            Route::get('/coownership/sold', [App\Http\Controllers\Panel\ImmovableController::class, 'indexSoldWithCoOwnership']);


            Route::get('/rented', [App\Http\Controllers\Panel\ImmovableController::class, 'indexRented']);
            Route::get('/sold', [App\Http\Controllers\Panel\ImmovableController::class, 'indexSold']);

            // TODO:: Por Estado
            Route::get('/under-maintenance', [App\Http\Controllers\Panel\ImmovableController::class, 'indexUnderMaintenance']);
            Route::get('/inprocess-sale', [App\Http\Controllers\Panel\ImmovableController::class, 'indexProcessSale']);
            Route::get('/inprocess-renting', [App\Http\Controllers\Panel\ImmovableController::class, 'indexProcessRenting']);
            Route::get('/unpublished', [App\Http\Controllers\Panel\ImmovableController::class, 'indexUnpublished']);
            Route::get('/retired', [App\Http\Controllers\Panel\ImmovableController::class, 'indexRetired']);


            Route::get('/details/{id}', [App\Http\Controllers\Panel\ImmovableController::class, 'show']);
            Route::get('/owners/{id}', [App\Http\Controllers\Panel\ImmovableController::class, 'indexOwner']);
            Route::get('/status-change/{id}', [App\Http\Controllers\Panel\ImmovableController::class, 'statusChange']);
            Route::get('/select-adminvalue/{id}', [App\Http\Controllers\Panel\ImmovableController::class, 'getCoAdminValue']);
            Route::put('/update-adminvalue/{id}', [App\Http\Controllers\Panel\ImmovableController::class, 'updateCoAdminValue']);
            Route::post('/update-media/{id}', [App\Http\Controllers\Panel\ImmovableController::class, 'statusMedia']);
            Route::delete('/{id}', [App\Http\Controllers\Panel\ImmovableController::class, 'destroy']);
            // Update
            Route::get('/details-update/{id}', [App\Http\Controllers\Panel\ImmovableController::class, 'showUpdate']);
            Route::patch('/{id}', [App\Http\Controllers\Panel\ImmovableController::class, 'update']);
            Route::post('/status-general/{id}', [App\Http\Controllers\Panel\ImmovableController::class, 'statusUpdate']);

            Route::post('/update-basic/{id}', [App\Http\Controllers\Panel\ImmovableController::class, 'updateBasic']);
        });


        // TODO:: Contratos Arrendamiento - Docs
        Route::group(['prefix' => 'lease-rentdocs'], function () {
            Route::get('/', [App\Http\Controllers\Panel\LeaseDocContractController::class, 'index']);
            Route::post('/', [App\Http\Controllers\Panel\LeaseDocContractController::class, 'store']);
        });



        /*
    |--------------------------------------------------------------------------
    | TODO:: ARRENDAMIENTO RUTAS
    |--------------------------------------------------------------------------
    */

        Route::group(['prefix' => 'leases'], function () {
            Route::post('/', [App\Http\Controllers\Renting\RentalContractController::class, 'store']);
        });

        Route::group(['prefix' => 'applications'], function () {
            Route::get('/', [App\Http\Controllers\Renting\ApplicationController::class, 'index']);
            Route::get('/{id}', [App\Http\Controllers\Renting\ApplicationController::class, 'show']);
            Route::put('/{id}/status', [App\Http\Controllers\Renting\ApplicationController::class, 'changeStatus']);
        });

        Route::group(['prefix' => 'references'], function () {
            Route::get('/', [App\Http\Controllers\Renting\ReferenceController::class, 'index']);
            Route::get('/{id}', [App\Http\Controllers\Renting\ReferenceController::class, 'show']);
            Route::post('/', [App\Http\Controllers\Renting\ReferenceController::class, 'store']);
            Route::put('/{id}', [App\Http\Controllers\Renting\ReferenceController::class, 'update']);
            Route::delete('/{id}', [App\Http\Controllers\Renting\ReferenceController::class, 'destroy']);
        });

        Route::group(['prefix' => 'applicants'], function () {
            Route::get('/', [App\Http\Controllers\Renting\ApplicantController::class, 'index']);
            Route::get('/{id}', [App\Http\Controllers\Renting\ApplicantController::class, 'show']);
        });

        Route::group(['prefix' => 'cosigners'], function () {
            Route::get('/', [App\Http\Controllers\Renting\CosignerController::class, 'index']);
            Route::post('/', [App\Http\Controllers\Renting\CosignerController::class, 'store']);

            Route::get('/{id}', [App\Http\Controllers\Renting\CosignerController::class, 'show']);
            Route::delete('/{id}', [App\Http\Controllers\Renting\CosignerController::class, 'destroy']);
        });

        // Support
        Route::group(['prefix' => 'supports'], function () {
            Route::get('/', [App\Http\Controllers\SupportController::class, 'index']);
            Route::get('/{id}', [App\Http\Controllers\SupportController::class, 'show']);
            Route::post('/{id}/reply', [App\Http\Controllers\SupportController::class, 'createReply']);
        });

        // Facturas
        Route::group(['prefix' => 'invoices'], function () {
            Route::get('/', [App\Http\Controllers\InvoiceController::class, 'getPeso']);
        });


        /**
         *  TODO:: MODULO DE INVENTARIO DE MATERIALES Y HERRAMIENTAS
         */

        Route::prefix('inventory')->group(function () {

            // Proveedores
            Route::get('suppliers', [App\Http\Controllers\Inventory\SupplierController::class, 'index']);
            Route::get('suppliers/{id}', [App\Http\Controllers\Inventory\SupplierController::class, 'show']);
            Route::post('suppliers', [App\Http\Controllers\Inventory\SupplierController::class, 'store']);
            Route::put('suppliers/{id}', [App\Http\Controllers\Inventory\SupplierController::class, 'update']);
            Route::delete('suppliers/{id}', [App\Http\Controllers\Inventory\SupplierController::class, 'destroy']);


            // Materiales
            Route::prefix('materials')->group(function () {
                Route::get('/options', [App\Http\Controllers\Inventory\MaterialController::class, 'indexOptions']);
                Route::get('/sales', [App\Http\Controllers\Inventory\MaterialController::class, 'indexToSale']);


                Route::get('/', [App\Http\Controllers\Inventory\MaterialController::class, 'index']);
                Route::get('/{id}', [App\Http\Controllers\Inventory\MaterialController::class, 'show']);
                Route::post('/', [App\Http\Controllers\Inventory\MaterialController::class, 'store']);
                Route::put('/{id}', [App\Http\Controllers\Inventory\MaterialController::class, 'update']);
                Route::delete('/{id}', [App\Http\Controllers\Inventory\MaterialController::class, 'destroy']);

                Route::post('/import', [App\Http\Controllers\Inventory\MaterialController::class, 'import']);
                Route::patch('/update-price/{id}', [App\Http\Controllers\Inventory\MaterialController::class, 'updatePrice']);
                Route::post('/import-price', [App\Http\Controllers\Inventory\MaterialController::class, 'importPrice']);
            });

            Route::prefix('stocks')->group(function () {
                Route::post('/notification-low', [App\Http\Controllers\Inventory\MaterialController::class, 'generateLowStockReport']);
            });

            Route::prefix('material-stock')->group(function () {
                Route::get('/', [App\Http\Controllers\Inventory\InventoryStockMaterialController::class, 'index']);

                Route::get('/staff/{id}', [App\Http\Controllers\Inventory\InventoryStockMaterialController::class, 'stockStaff']);
            });

            // Ordenes de Trabajo
            Route::prefix('order-services')->group(function () {
                Route::get('/immovables', [App\Http\Controllers\Panel\ImmovableController::class, 'indexOrderServeice']);

                Route::get('/', [App\Http\Controllers\Inventory\ServiceOrderController::class, 'index']);
                Route::post('/', [App\Http\Controllers\Inventory\ServiceOrderController::class, 'store']);
                Route::get('/{id}', [App\Http\Controllers\Inventory\ServiceOrderController::class, 'show']);
                Route::get('/details/{id}', [App\Http\Controllers\Inventory\ServiceOrderController::class, 'showDetails']);

                // Servicios
                Route::get('/services/{id}', [App\Http\Controllers\Inventory\ServiceOrderController::class, 'getOrderServices']);
                Route::post('/services', [App\Http\Controllers\Inventory\ServiceOrderController::class, 'storeOrderService']);
                Route::delete('/services/{id}', [App\Http\Controllers\Inventory\ServiceOrderController::class, 'removeService']);

                Route::post('/add-consume', [App\Http\Controllers\Inventory\ServiceOrderController::class, 'StoreConsumeMaterials']);
                Route::get('/materials/{id}', [App\Http\Controllers\Inventory\ServiceOrderController::class, 'getOrderMaterials']);
                Route::delete('/cosume/{id}', [App\Http\Controllers\Inventory\ServiceOrderController::class, 'removeConsume']);


            });

            // Entradas
            Route::prefix('entries')->group(function () {
                Route::get('/', [App\Http\Controllers\Inventory\InventoryEntranceController::class, 'index']);
                Route::post('/', [App\Http\Controllers\Inventory\InventoryEntranceController::class, 'store']);
                Route::patch('/updated-statu/{id}', [App\Http\Controllers\Inventory\InventoryEntranceController::class, 'updateStatu']);
                Route::delete('/{id}', [App\Http\Controllers\Inventory\InventoryEntranceController::class, 'destroy']);
            });
            // Herramientas
            Route::prefix('tools')->group(function () {
                Route::get('/', [App\Http\Controllers\Inventory\ToolController::class, 'index']);
                Route::post('/', [App\Http\Controllers\Inventory\ToolController::class, 'store']);
                Route::get('/{id}', [App\Http\Controllers\Inventory\ToolController::class, 'show']);
                Route::put('/{id}', [App\Http\Controllers\Inventory\ToolController::class, 'update']);
                Route::delete('/{id}', [App\Http\Controllers\Inventory\ToolController::class, 'destroy']);
                Route::post('/import', [App\Http\Controllers\Inventory\ToolController::class, 'import']);
                // Dispobibilidad para prestamos
                Route::get('availability', [App\Http\Controllers\Inventory\ToolController::class, 'availableTools']);
            });

            // Prestamos de Herramientas
            Route::prefix('tool-loans')->group(function () {
                Route::get('/tools', [App\Http\Controllers\Inventory\ToolLoanController::class, 'tools']);

                Route::get('/', [App\Http\Controllers\Inventory\ToolLoanController::class, 'index']);
                Route::post('/', [App\Http\Controllers\Inventory\ToolLoanController::class, 'store']);
                Route::get('/{id}', [App\Http\Controllers\Inventory\ToolLoanController::class, 'show']);
                Route::put('/{id}', [App\Http\Controllers\Inventory\ToolLoanController::class, 'update']);
                Route::delete('/{id}', [App\Http\Controllers\Inventory\ToolLoanController::class, 'destroy']);

                Route::get('/pdf-details/{id}', [App\Http\Controllers\Inventory\ToolLoanController::class, 'generatePDFDetails']);
                Route::get('/edit-details/{id}', [App\Http\Controllers\Inventory\ToolLoanController::class, 'getToolsLoanTool']);
            });

            Route::get('/users/loans', [App\Http\Controllers\Panel\UserController::class, 'index']);

            // Detalles de Prestamos de Herramientas

            Route::post('tool-loan/items/{id}', [App\Http\Controllers\Inventory\ToolLoanController::class, 'addItems']);
            Route::delete('tool-loan/items/{id}', [App\Http\Controllers\Inventory\ToolLoanController::class, 'removeItem']);
            Route::patch('tool-loan/items/{id}', [App\Http\Controllers\Inventory\ToolLoanController::class, 'updateItemQty']);
            Route::get('tool-loan/items/{id}', [App\Http\Controllers\Inventory\ToolLoanController::class, 'getItem']);

            // Operaciones de materiales
            Route::prefix('operations-material')->group(function () {
                Route::get('/', [App\Http\Controllers\Inventory\InventoryOperationMaterialController::class, 'index']);
                Route::post('/', [App\Http\Controllers\Inventory\InventoryOperationMaterialController::class, 'store']);
                Route::get('/{code}', [App\Http\Controllers\Inventory\InventoryOperationMaterialController::class, 'show']);
            });


            // Categorias
            Route::get('categories', [App\Http\Controllers\Inventory\CategoryController::class, 'index']);
            Route::get('categories/tools', [App\Http\Controllers\Inventory\CategoryController::class, 'tools']);


            // Personal
            Route::prefix('staff')->group(function () {
                Route::get('/', [App\Http\Controllers\Inventory\StaffController::class, 'index']);
                Route::post('/', [App\Http\Controllers\Inventory\StaffController::class, 'store']);
                Route::get('/{id}', [App\Http\Controllers\Inventory\StaffController::class, 'show']);
                Route::patch('/{id}', [App\Http\Controllers\Inventory\StaffController::class, 'update']);
                Route::delete('/{id}', [App\Http\Controllers\Inventory\StaffController::class, 'destroy']);
            });

            // Personal
            Route::prefix('attendances')->group(function () {
                Route::get('/', [App\Http\Controllers\Inventory\AttendanceController::class, 'index']);
                Route::post('/', [App\Http\Controllers\Inventory\AttendanceController::class, 'store']);
                Route::put('/status-update/{id}', [App\Http\Controllers\Inventory\AttendanceController::class, 'updateStatus']);
            });

            // Clientes
            Route::prefix('customers')->group(function () {
                Route::get('/', [App\Http\Controllers\Inventory\InventoryClientController::class, 'index']);
                Route::post('/', [App\Http\Controllers\Inventory\InventoryClientController::class, 'store']);
            });
        });
    });
});


/*
|--------------------------------------------------------------------------
| Auth  Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['auth']], function () {

    Route::group(['prefix' => 'favorites'], function () {
        Route::post('/',  [App\Http\Controllers\FavoriteController::class, 'immovableStore']);
        Route::get('/byuser/{userId}',  [App\Http\Controllers\FavoriteController::class, 'indexByUser']);
    });

    Route::get('cousine-types',  [App\Http\Controllers\Panel\CuisineTypeController::class, 'index']);
    Route::get('floor-types',  [App\Http\Controllers\Panel\FloorTypeController::class, 'index']);
});


/*
    |--------------------------------------------------------------------------
    | No Admin Role && No Auth login - Routes
    |--------------------------------------------------------------------------
*/

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [App\Http\Controllers\AuthController::class, 'login']);
    Route::post('register', [App\Http\Controllers\AuthController::class, 'register']);
    Route::get('check-token', [App\Http\Controllers\AuthController::class, 'verifyToken']);
    Route::post('password-reset', [App\Http\Controllers\AuthController::class, 'resetPassword']);
});

Route::prefix('supports')->group(function () {
    Route::post('/', [App\Http\Controllers\SupportController::class, 'store']);
});

// Configuration Company
Route::get('panel/company-configurations', [App\Http\Controllers\CompanyConfigurationController::class, 'index']);
Route::get('immovable-types', [App\Http\Controllers\ImmovableTypeController::class, 'index']);

// TODO:: INMUEBLES RUTAS PUBLICAS
Route::prefix('immovables')->group(function () {
    Route::get('/', [App\Http\Controllers\ImmovableController::class, 'index']);
    Route::get('/to-renting/{code}', [App\Http\Controllers\ImmovableController::class, 'getImmovable']);
    Route::get('/show-code/{code}', [App\Http\Controllers\ImmovableController::class, 'showCode']);
    Route::post('/', [App\Http\Controllers\ImmovableController::class, 'store']);
    Route::post('/requests', [App\Http\Controllers\ImmovableRequestController::class, 'store']);

    // Renting
    Route::get('/rentings', [App\Http\Controllers\ImmovableController::class, 'indexToRenting']);
});

Route::prefix('coownerships')->group(function () {
    Route::get('/',  [App\Http\Controllers\CoownershipController::class, 'index']);
    Route::get('/details/{id}',  [App\Http\Controllers\CoownershipController::class, 'show']);
    Route::post('/store-modal',  [App\Http\Controllers\CoownershipController::class, 'storeModal']);
});


/**
 * Facturas
 */
Route::get('invoice/{id}', [App\Http\Controllers\InvoiceController::class, 'scannerDownload'])->name('invoice.download');

/*
    |--------------------------------------------------------------------------
    | Renting Routes
    |--------------------------------------------------------------------------
    */
Route::prefix('renting-request')->group(function () {
    Route::post('/', [App\Http\Controllers\Renting\ApplicantController::class, 'store']);
});

Route::prefix('professions')->group(function () {
    Route::get('/', [App\Http\Controllers\Base\ProfessionController::class, 'index']);
});
Route::prefix('buildings')->group(function () {
    Route::get('/', [App\Http\Controllers\BuildingCompanyController::class, 'index']);
    Route::post('/', [App\Http\Controllers\BuildingCompanyController::class, 'store']);
});
Route::prefix('markets')->group(function () {
    Route::get('/', [App\Http\Controllers\Base\MarketController::class, 'index']);
});

/*
|--------------------------------------------------------------------------
| Only Auth && Panel - Routes
|--------------------------------------------------------------------------
*/


Route::group(['prefix' => 'panel', 'middleware' => ['auth']], function () {


    Route::get('account-status/owner/{owner_id}', [App\Http\Controllers\Panel\AccountStatusController::class, 'indexByOwner']);
    Route::get('accounts-collection/details/{id}', [App\Http\Controllers\Panel\AccountCollectionController::class, 'show']);
    Route::get('accounts-collection/tenant/{tenant_id}', [App\Http\Controllers\Panel\AccountCollectionController::class, 'indexByTenant']);
    Route::get('owners/byuser/{id}', [App\Http\Controllers\Panel\OwnerController::class, 'getOwnerByUser']);


    // Counters
    Route::get('counters', [App\Http\Controllers\Panel\CounterController::class, 'index']);

    Route::post('admincontract/files', [App\Http\Controllers\Panel\ImmovableContractController::class, 'storeRequest']);
    Route::put('owners/update-profile/{id}', [App\Http\Controllers\Panel\OwnerController::class, 'updateProfile']);

    Route::prefix('immovable-requests')->group(function () {
        Route::get('/', [App\Http\Controllers\ImmovableRequestController::class, 'index']);
        Route::get('/{id}', [App\Http\Controllers\ImmovableRequestController::class, 'show']);
    });

    Route::prefix('letters')->group(function () {
        Route::get('/admissions', [App\Http\Controllers\Panel\LetterController::class, 'index']);
        Route::get('/exits', [App\Http\Controllers\Panel\LetterController::class, 'index2']);
        Route::get('/peaces', [App\Http\Controllers\Panel\LetterController::class, 'index3']);
        Route::post('/', [App\Http\Controllers\Panel\LetterController::class, 'store']);
        Route::delete('/{id}', [App\Http\Controllers\Panel\LetterController::class, 'delete']);
        Route::get('/status/{id}', [App\Http\Controllers\Panel\LetterController::class, 'status']);
        Route::get('/{id}', [App\Http\Controllers\Panel\LetterController::class, 'show']);
    });
});




/*
|--------------------------------------------------------------------------
| Only Auth , Inquilino y Admin && Panel - Routes
|--------------------------------------------------------------------------
*/



/*
|--------------------------------------------------------------------------
| Only Auth , Propietario y Admin && Panel - Routes
|--------------------------------------------------------------------------
*/
