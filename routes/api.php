<?php

//use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CollabController;
use App\Http\Controllers\API\ServicesController;
use App\Http\Controllers\API\BlogsController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
//routes  admin to create lawyer and show




Route::post('admin-login',[\App\Http\Controllers\API\Admin\AdminController::class,'adminLogin']);


Route::middleware('auth:sanctum')->group( function () {
    Route::get('me',[App\Http\Controllers\API\AuthController::class,'me']);
    Route::get('userRoleRedirection',[App\Http\Controllers\API\AuthController::class,'userRoleRedirection']);
    Route::middleware(['lawyerToken'])->group(function () {
        Route::post('confirmCaseFile/{case_id}',[App\Http\Controllers\API\CaseController::class,'confirmCaseFile']);
        Route::post('rejectCaseFile/{case_id}',[App\Http\Controllers\API\CaseController::class,'rejectCaseFile']);
        Route::get('getAllServices',[App\Http\Controllers\API\ServicesController::class,'getAllServices']);
        Route::post('addServices',[App\Http\Controllers\API\ServicesController::class,'addServices']);
        Route::get('getSpecificService/{service_id}',[App\Http\Controllers\API\ServicesController::class,'getSpecificService']);
        Route::post('updateSpecificService/{service_id}',[App\Http\Controllers\API\ServicesController::class,'updateSpecificService']);
        Route::delete('deleteSpecificService/{service_id}',[App\Http\Controllers\API\ServicesController::class,'deleteSpecificService']);
        Route::post('createNewUser',[App\Http\Controllers\API\AuthController::class,'createNewUser']);
        Route::get('getAllusers',[App\Http\Controllers\API\UserController::class,'getAllusers']);
        Route::get('getUserById/{user_id}',[App\Http\Controllers\API\UserController::class,'getUserById']);
        Route::post('editUsers/{user_id}',[App\Http\Controllers\API\UserController::class,'editUsers']);
        Route::delete('deleteUser/{user_id}',[App\Http\Controllers\API\UserController::class,'deleteUser']);
//        Route::get('getAllEmployees',[App\Http\Controllers\API\UserController::class,'getAllEmployees']);
//        Route::get('employees/{user_id}',[App\Http\Controllers\API\UserController::class,'getEmployeesById']);
//        Route::post('edit_employees/{user_id}',[App\Http\Controllers\API\UserController::class,'editEmployees']);
//        Route::delete('delete_employees/{user_id}',[App\Http\Controllers\API\UserController::class,'deleteEmployees']);
    });
//    Route::get('getCaseFileById/{case_id}',[\App\Http\Controllers\API\CaseController::class,'getCaseFileById']);
//    Route::get('getAllPendingCaseFileSide',[\App\Http\Controllers\API\CaseController::class,'getAllPendingCaseFileSide']);
//    Route::post('addCaseFile',[App\Http\Controllers\API\CaseController::class,'addCaseFile']);
//    Route::get('getCaseFileForSpecificClient',[App\Http\Controllers\API\CaseController::class,'getCaseFileForSpecificClient']);
//    Route::post('editCaseFile/{case_id}',[App\Http\Controllers\API\CaseController::class,'editCaseFile']);
//    Route::delete('deleteCaseFile/{case_id}',[App\Http\Controllers\API\CaseController::class,'deleteCaseFile']);
//    Route::get('getAllCaseFile',[App\Http\Controllers\API\CaseController::class,'getAllCaseFile']);
        /*    BailiffPaper Routes
        */
    Route::post('addBailiffPaper',[App\Http\Controllers\API\BailiffController::class,'addBailiffPaper']);
    Route::post('editBailiffPaper/{bailiff_id}',[App\Http\Controllers\API\BailiffController::class,'editBailiffPaper']);
    Route::delete('deleteBailiffPaper/{bailiff_id}',[App\Http\Controllers\API\BailiffController::class,'deleteBailiffPaper']);
    Route::get('getAllBailiffPapers',[App\Http\Controllers\API\BailiffController::class,'getAllBailiffPapers']);
    Route::post('confirmBailiffPaper/{case_id}',[App\Http\Controllers\API\BailiffController::class,'confirmBailiffPaper']);
    Route::post('rejectBailiffPaper/{case_id}',[App\Http\Controllers\API\BailiffController::class,'rejectBailiffPaper']);
    Route::get('getBailifPaperByBailifId/{bailiff_id}',[App\Http\Controllers\API\BailiffController::class,'getBailifPaperByBailifId']);
    /*    Agencies Routes
       */
    Route::post('addAgenciesIndex',[App\Http\Controllers\API\AgenciesController::class,'addAgenciesIndex']);
    Route::post('editAgenciesIndex/{ageny_id}',[App\Http\Controllers\API\AgenciesController::class,'editAgenciesIndex']);
    Route::delete('deleteAgenciesIndex/{ageny_id}',[App\Http\Controllers\API\AgenciesController::class,'deleteAgenciesIndex']);
    Route::get('getAllAgenciesIndex',[App\Http\Controllers\API\AgenciesController::class,'getAllAgenciesIndex']);
    Route::post('confirmAgenciesIndex/{ageny_id}',[App\Http\Controllers\API\AgenciesController::class,'confirmAgenciesIndex']);
    Route::post('rejectAgenciesIndex/{ageny_id}',[App\Http\Controllers\API\AgenciesController::class,'rejectAgenciesIndex']);
    Route::get('getAllAgenciesIndexByAgencyId/{ageny_id}',[App\Http\Controllers\API\AgenciesController::class,'getAllAgenciesIndexByAgencyId']);
    Route::post('addSocialContacts',[App\Http\Controllers\API\ServicesController::class,'addSocialContacts']);
    Route::get('getAllPendingCaseFileLawyerSide',[App\Http\Controllers\API\CaseController::class,'getAllPendingCaseFileLawyerSide']);
    Route::get('getAllPendingCaseFileemployeeSide',[App\Http\Controllers\API\CaseController::class,'getAllPendingCaseFileemployeeSide']);
    Route::get('getAllPendingBailiffPapersLawyerSide',[App\Http\Controllers\API\BailiffController::class,'getAllPendingBailiffPapersLawyerSide']);
    Route::get('getAllPendingBailiffPapersemployeeSide',[App\Http\Controllers\API\BailiffController::class,'getAllPendingBailiffPapersEmployeeSide']);
    Route::get('getAllPendingAgenciesIndexLawyerSide',[App\Http\Controllers\API\AgenciesController::class,'getAllPendingAgenciesIndexLawyerSide']);
    Route::get('getAllPendingAgenciesIndexemployeeSide',[App\Http\Controllers\API\AgenciesController::class,'getAllPendingAgenciesIndexemployeeSide']);
    Route::get('getClientBailiffPapers',[App\Http\Controllers\API\BailiffController::class,'getClientBailiffPapers']);
});
    Route::post('storeContactsMessages',[App\Http\Controllers\API\CaseController::class,'storeContactsMessages']);
    Route::get('getAllContactsMessages',[App\Http\Controllers\API\CaseController::class,'getAllContactsMessages']);


    Route::get('getAllSocialContacts',[App\Http\Controllers\API\ServicesController::class,'getAllSocialContacts']);


//  Route::post('addCollab',[CollabController::class,'addCollab']);

//  Route::delete('deleteCollab/{collab_id}',[CollabController::class,'deleteCollab']);

//  Route::get('getAllCollab',[CollabController::class,'getAllCollab']);


//  Route::post('addService',[ServicesController::class,'addService']);

//  Route::post('updateService/{service_id}',[ServicesController::class,'updateService']);

//  Route::delete('deleteService/{service_id}',[ServicesController::class,'deleteService']);

//  Route::get('getAllServices',[ServicesController::class,'getAllServices']);


//  Route::post('addSubService/{service_id}',[ServicesController::class,'addSubService']);

//  Route::post('updateSubService/{sub_service_id}',[ServicesController::class,'updateSubService']);

//  Route::delete('deleteSubService/{sub_service_id}',[ServicesController::class,'deleteSubService']);

//  Route::get('getAllSubServices',[ServicesController::class,'getAllSubServices']);

//  Route::get('getAllSubServicesRelatedToService/{service_id}',[ServicesController::class,'getAllSubServicesRelatedToService']);



//  Route::post('addBlogs',[BlogsController::class,'addBlogs']);

//  Route::post('updateBlogs/{blog_id}',[BlogsController::class,'updateBlogs']);

//  Route::delete('deleteBlogs/{blog_id}',[BlogsController::class,'deleteBlogs']);

//  Route::get('getAllBlogs',[BlogsController::class,'getAllBlogs']);


//  Route::post('addBlogKeyword/{blog_id}',[BlogsController::class,'addBlogKeyword']);

//  Route::post('updateBlogKeyword/{blog_id}',[BlogsController::class,'updateBlogs']);

//  Route::delete('deleteBlogKeyword/{blog_id}',[BlogsController::class,'deleteBlogKeyword']);

//  Route::get('getAllBlogKeywordsRelatedToBlog/{blog_id}',[BlogsController::class,'getAllBlogKeywordsRelatedToBlog']);



// Route::post('addBlogSection/{blog_id}',[BlogsController::class,'addBlogSection']);

//  Route::post('updateBlogSection/{blogsect_id}',[BlogsController::class,'updateBlogSection']);

//  Route::delete('deleteBlogSection/{blogsect_id}',[BlogsController::class,'deleteBlogSection']);

//  Route::get('getAllBlogSectionsRelatedToBlog/{blog_id}',[BlogsController::class,'getAllBlogSectionsRelatedToBlog']);



// Route::post('addBlogSubSection/{section_id}',[BlogsController::class,'addBlogSubSection']);

//  Route::post('updateBlogSubSection/{blogsubsection_id}',[BlogsController::class,'updateBlogSubSection']);

//   Route::delete('deleteBlogSubSection/{blogsubsection_id}',[BlogsController::class,'deleteBlogSubSection']);

//  Route::get('getAllBlogSubSectionsRelatedToSection/{section_id}',[BlogsController::class,'getAllBlogSubSectionsRelatedToSection']);



// Route::get('getBlogData/{blog_id}',[BlogsController::class,'getBlogData']);












