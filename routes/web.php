<?php


use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DropdownController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RulesController;
use App\Http\Controllers\AssetCategoryController;
use App\Http\Controllers\CostCenterController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\AssetController;

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

//Login Controller
Route::get('/', [AuthController::class, 'login'])->name('login');
Route::post('/auth/login', [AuthController::class, 'postLogin']);
Route::get('/logout', [AuthController::class, 'logout']);

Route::middleware(['auth'])->group(function () {
    //Home Controller
    Route::get('/home', [HomeController::class, 'index']);

    //Home Controller
    Route::get('/asset', [AssetController::class, 'index']);
    Route::post('/asset/store', [AssetController::class,'store']);
    Route::patch('/asset/update/{id}', [AssetController::class,'update']);
    Route::get('/asset/detail/{id}', [AssetController::class, 'detail']);
    Route::get('/asset/disposal/{id}', [AssetController::class,'disposal']);
    Route::get('/asset/active/{id}', [AssetController::class,'active']);
    Route::post('/asset/detail/store', [AssetController::class,'detailStore']);
    Route::patch('/asset/detail/update/{id}', [AssetController::class,'detailUpdate']);
    Route::delete('/asset/detail/delete/{id}', [AssetController::class, 'detailDelete']);
    Route::get('/asset/detail/disposal/{id_header}/{id}', [AssetController::class,'detailDisposal']);
    Route::get('/asset/detail/active/{id_header}/{id}', [AssetController::class,'detailActive']);
    Route::get('/download/excel/format', [AssetController::class, 'excelFormat']);
    Route::post('/asset/import', [AssetController::class, 'excelData']);
    Route::get('/asset/qr', [AssetController::class,'generateQRCodesAndReturnPDF']);

    

    //Dropdown Controller
    Route::get('/dropdown', [DropdownController::class, 'index'])->middleware(['checkRole:Super Admin']);
    Route::post('/dropdown/store', [DropdownController::class, 'store'])->middleware(['checkRole:Super Admin']);
    Route::patch('/dropdown/update/{id}', [DropdownController::class, 'update'])->middleware(['checkRole:Super Admin']);
    Route::delete('/dropdown/delete/{id}', [DropdownController::class, 'delete'])->middleware(['checkRole:Super Admin']);

    //Rules Controller
    Route::get('/rule', [RulesController::class, 'index'])->middleware(['checkRole:Super Admin']);
    Route::post('/rule/store', [RulesController::class, 'store'])->middleware(['checkRole:Super Admin']);
    Route::patch('/rule/update/{id}', [RulesController::class, 'update'])->middleware(['checkRole:Super Admin']);
    Route::delete('/rule/delete/{id}', [RulesController::class, 'delete'])->middleware(['checkRole:Super Admin']);

    //User Controller
    Route::get('/user', [UserController::class, 'index'])->middleware(['checkRole:Super Admin']);
    Route::post('/user/store', [UserController::class, 'store'])->middleware(['checkRole:Super Admin']);
    Route::post('/user/store-partner', [UserController::class, 'storePartner'])->middleware(['checkRole:Super Admin']);
    Route::patch('/user/update/{user}', [UserController::class, 'update'])->middleware(['checkRole:Super Admin']);
    Route::get('/user/revoke/{user}', [UserController::class, 'revoke'])->middleware(['checkRole:Super Admin']);
    Route::get('/user/access/{user}', [UserController::class, 'access'])->middleware(['checkRole:Super Admin']);

    //Asset Controller
    Route::get('/asset_category', [AssetCategoryController::class, 'index'])->middleware(['checkRole:Super Admin']);
    Route::post('/asset_category/store', [AssetCategoryController::class, 'store'])->middleware(['checkRole:Super Admin']);
    Route::patch('/asset_category/update/{id}', [AssetCategoryController::class, 'update'])->middleware(['checkRole:Super Admin']);
    Route::delete('/asset_category/delete/{id}', [AssetCategoryController::class, 'delete'])->middleware(['checkRole:Super Admin']);
    
    //CostCenter Controller
    Route::get('/cost_center', [CostCenterController::class, 'index'])->middleware(['checkRole:Super Admin']);
    Route::post('/cost_center/store', [CostCenterController::class, 'store'])->middleware(['checkRole:Super Admin']);
    Route::patch('/cost_center/update/{id}', [CostCenterController::class, 'update'])->middleware(['checkRole:Super Admin']);
    Route::delete('/cost_center/delete/{id}', [CostCenterController::class, 'delete'])->middleware(['checkRole:Super Admin']);

    //Department Controller
    Route::get('/department', [DepartmentController::class, 'index'])->middleware(['checkRole:Super Admin']);
    Route::post('/department/store', [DepartmentController::class, 'store'])->middleware(['checkRole:Super Admin']);
    Route::patch('/department/update/{id}', [DepartmentController::class, 'update'])->middleware(['checkRole:Super Admin']);
    Route::delete('/department/delete/{id}', [DepartmentController::class, 'delete'])->middleware(['checkRole:Super Admin']);

    //Location Controller
    Route::get('/location', [LocationController::class, 'index'])->middleware(['checkRole:Super Admin']);
    Route::post('/location/store', [LocationController::class, 'store'])->middleware(['checkRole:Super Admin']);
    Route::patch('/location/update/{id}', [LocationController::class, 'update'])->middleware(['checkRole:Super Admin']);
    Route::delete('/location/delete/{id}', [LocationController::class, 'delete'])->middleware(['checkRole:Super Admin']); 
    
    Route::get('/location/detail/{id}', [LocationController::class, 'detail'])->middleware(['checkRole:Super Admin']);
    Route::post('/location/detail/store/{id}', [LocationController::class, 'storeDetail'])->middleware(['checkRole:Super Admin']);
    Route::patch('/location/detail/update/{id}', [LocationController::class, 'updateDetail'])->middleware(['checkRole:Super Admin']);
    Route::delete('/location/detail/delete/{id}', [LocationController::class, 'deleteDetail'])->middleware(['checkRole:Super Admin']);

});
