<?php

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\KpiController;
use App\Http\Controllers\Api\IzinController;
use App\Http\Controllers\Api\TodoController;
use App\Http\Controllers\Api\KasbonController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\AbsensiController;
use App\Http\Controllers\Api\CitizenController;
use App\Http\Controllers\Api\HolidayController;
use App\Http\Controllers\Api\PayrollController;
use App\Http\Controllers\Api\SettingsController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\EmployeeTypeController;
use App\Http\Controllers\Api\FunctionsController;
use App\Http\Controllers\Api\GenderController;
use App\Http\Controllers\Api\JenisAktifitasController;
use App\Http\Controllers\Api\StructureController;
use App\Http\Controllers\Api\MasterRoleController;
use App\Http\Controllers\Api\MasterUserController;
use App\Http\Controllers\Api\MasterPlaceController;
use App\Http\Controllers\Api\MasterShiftController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\OrganizationController;
use App\Http\Controllers\MasterPermissionController;
use App\Http\Controllers\Api\LevelStructureController;
use App\Http\Controllers\Api\MarriedStatusController;
use App\Http\Controllers\Api\MasterScheduleController;
use App\Http\Controllers\Api\MenuMappingController;
use App\Http\Controllers\Api\PackageTypeController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\ReligionController;

Route::group(['prefix' => 'auth'], function ($router) {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('logout', [AuthController::class, 'logout']);
});
Route::post('/register', [MasterUserController::class, 'makeUser'])->name('user.make');

Route::post('/register', [RegisterController::class, 'register']);
Route::get('/get-packages', [PackageTypeController::class, 'index']);


Route::group(['middleware' => 'jwt.verify', 'otp.verified'], function ($router) {
    Route::post('send-otp', [AuthController::class, 'sendOtp']);
    Route::post('verify-otp', [AuthController::class, 'verifyOtp']);
    Route::group(['prefix' => 'dashboard'], function ($router) {
        Route::get('/user', [DashboardController::class, 'getDashboardUserData']);
        Route::get('/total-staff', [DashboardController::class, 'getTotalStaff']);
        Route::get('/total-places', [DashboardController::class, 'getTotalPlaces']);
        Route::get('/total-payouts', [DashboardController::class, 'getTotalPayouts']);
        Route::get('/total-advances', [DashboardController::class, 'getTotalAdvances']);
        Route::get('/getChartData', [DashboardController::class, 'getChartData']);
        Route::get('/upcoming-birthdays', [DashboardController::class, 'getUpcomingBirthdays']);
    });
    Route::group(['prefix' => 'organization'], function ($router) {
        Route::get('/get', [OrganizationController::class, 'getOrganization'])->name('organization.get');
        Route::get('/export', [OrganizationController::class, 'export']);
        Route::post('/make', [OrganizationController::class, 'makeOrganization'])->name('organization.make');
        Route::post('/update', [OrganizationController::class, 'updateOrganization'])->name('organization.update');
        Route::post('/delete', [OrganizationController::class, 'deleteOrganization'])->name('organization.delete');
    });
    Route::group(['prefix' => 'function'], function ($router) {
        Route::get('/get', [FunctionsController::class, 'getFunctions'])->name('function.get');
        Route::get('/export', [FunctionsController::class, 'export']);
        Route::post('/make', [FunctionsController::class, 'makeFunctions'])->name('function.make');
        Route::post('/update', [FunctionsController::class, 'updateFunctions'])->name('function.update');
        Route::post('/delete', [FunctionsController::class, 'deleteFunctions'])->name('function.delete');
    });
    Route::group(['prefix' => 'levelStructure'], function ($router) {
        Route::get('/get', [LevelStructureController::class, 'getLevelStructure'])->name('level.get');
        Route::get('/export', [LevelStructureController::class, 'export']);
        Route::post('/make', [LevelStructureController::class, 'makeLevelStructure'])->name('level.make');
        Route::post('/update', [LevelStructureController::class, 'updateLevelStructure'])->name('level.update');
        Route::post('/delete', [LevelStructureController::class, 'deleteLevelStructure'])->name('level.delete');
    });

    Route::group(['prefix' => 'structure'], function ($router) {
        Route::get('/get', [StructureController::class, 'getStructure'])->name('structure.get');
        Route::get('/export', [StructureController::class, 'export']);
        Route::post('/make', [StructureController::class, 'makeStructure'])->name('structure.make');
        Route::post('/update', [StructureController::class, 'updateStructure'])->name('structure.update');
        Route::post('/delete', [StructureController::class, 'deleteStructure'])->name('structure.delete');
        Route::post('/reorder', [StructureController::class, 'reorderStructure'])->name('structure.reorder');
        Route::post('/assign-user', [StructureController::class, 'assignUserToStructure'])->name('structure.assignUser');
        Route::post('/remove-user', [StructureController::class, 'removeUserFromStructure'])->name('structure.removeUser');
    });
    Route::group(['prefix' => 'holidays'], function ($router) {
        Route::get('/get', [HolidayController::class, 'getHolidays'])->name('holidays.get');
        Route::get('/export', [HolidayController::class, 'export']);
        Route::post('/make', [HolidayController::class, 'createHoliday'])->name('holidays.create');
        Route::post('/update', [HolidayController::class, 'updateHoliday'])->name('holidays.update');
        Route::post('/delete', [HolidayController::class, 'deleteHoliday'])->name('holidays.delete');
        Route::post('/bulk-upload', [HolidayController::class, 'bulkUpload'])->name('holidays.bulk_upload');
    });

    Route::prefix('employee-types')->group(function () {
        Route::get('/get', [EmployeeTypeController::class, 'index']);
        Route::post('/make', [EmployeeTypeController::class, 'store']);
        Route::post('/update', [EmployeeTypeController::class, 'update']);
        Route::post('/delete', [EmployeeTypeController::class, 'destroy']);
        Route::post('/bulk-upload', [EmployeeTypeController::class, 'bulkUpload']);
        Route::get('/export', [EmployeeTypeController::class, 'export']);
    });
    Route::group(['prefix' => 'package-types'], function () {
        Route::get('/get', [PackageTypeController::class, 'index']);
        Route::post('/make', [PackageTypeController::class, 'store']);
        Route::post('/update', [PackageTypeController::class, 'update']);
        Route::post('/delete', [PackageTypeController::class, 'destroy']);
        Route::post('/bulk-upload', [PackageTypeController::class, 'bulkUpload']);
        Route::get('/export', [PackageTypeController::class, 'export']);
    });
    Route::group(['prefix' => 'menuMapping'], function ($router) {
        Route::get('/get', [MenuMappingController::class, 'getMenuMapping'])->name('menu_mapping.get');
        Route::get('/export', [MenuMappingController::class, 'export']);
        Route::post('/make', [MenuMappingController::class, 'makeMenuMapping'])->name('menu_mapping.make');
        Route::post('/update', [MenuMappingController::class, 'updateMenuMapping'])->name('menu_mapping.update');
        Route::post('/delete', [MenuMappingController::class, 'deleteMenuMapping'])->name('menu_mapping.delete');
    });
    Route::group(['prefix' => 'gender'], function ($router) {
        Route::get('/get', [GenderController::class, 'getGender'])->name('gender.get');
        Route::post('/make', [GenderController::class, 'makeGender'])->name('gender.make');
        Route::post('/update', [GenderController::class, 'updateGender'])->name('gender.update');
        Route::post('/delete', [GenderController::class, 'deleteGender'])->name('gender.delete');
        Route::get('/export', [GenderController::class, 'export']);
    });
    Route::group(['prefix' => 'marriedStatus'], function ($router) {
        Route::get('/get', [MarriedStatusController::class, 'getMarriedStatus'])->name('marriedStatus.get');
        Route::post('/make', [MarriedStatusController::class, 'makeMarriedStatus'])->name('marriedStatus.make');
        Route::post('/update', [MarriedStatusController::class, 'updateMarriedStatus'])->name('marriedStatus.update');
        Route::post('/delete', [MarriedStatusController::class, 'deleteMarriedStatus'])->name('marriedStatus.delete');
        Route::get('/export', [MarriedStatusController::class, 'export']);
    });
    Route::group(['prefix' => 'religion'], function ($router) {
        Route::get('/get', [ReligionController::class, 'getReligion'])->name('religion.get');
        Route::post('/make', [ReligionController::class, 'makeReligion'])->name('religion.make');
        Route::post('/update', [ReligionController::class, 'updateReligion'])->name('religion.update');
        Route::post('/delete', [ReligionController::class, 'deleteReligion'])->name('religion.delete');
        Route::get('/export', [ReligionController::class, 'export']);
    });
    Route::group(['prefix' => 'citizen'], function ($router) {
        Route::get('/get', [CitizenController::class, 'getCitizen'])->name('citizen.get');
        Route::post('/make', [CitizenController::class, 'makeCitizen'])->name('citizen.make');
        Route::post('/update', [CitizenController::class, 'updateCitizen'])->name('citizen.update');
        Route::post('/delete', [CitizenController::class, 'deleteCitizen'])->name('citizen.delete');
        Route::get('/export', [CitizenController::class, 'export']);
    });

    Route::group(['prefix' => 'settings'], function () {
        Route::get('/get', [SettingsController::class, 'getSettings']);
        Route::post('/login-timeout', [SettingsController::class, 'updateLoginTimeout']);
        Route::post('/workday/{id}', [SettingsController::class, 'updateWorkday']);
    });
    Route::group(['prefix' => 'jenis-aktifitas'], function ($router) {
        Route::get('/get', [JenisAktifitasController::class, 'index'])->name('jenis_aktifitas.get');
        Route::post('/make', [JenisAktifitasController::class, 'store'])->name('jenis_aktifitas.make');
        Route::post('/update', [JenisAktifitasController::class, 'update'])->name('jenis_aktifitas.update');
        Route::post('/delete', [JenisAktifitasController::class, 'destroy'])->name('jenis_aktifitas.delete');
        // Route::get('/export', [JenisAktifitasController::class, 'export']);
        // Route::post('/bulk-upload', [JenisAktifitasController::class, 'bulkUpload']);
        // Route::get('/bulk-upload/{id}/details', [JenisAktifitasController::class, 'bulkUploadDetails']);

        Route::post('/bulk-upload', [JenisAktifitasController::class, 'bulkUpload'])->name('jenis_aktifitas.bulk_upload');
        Route::get('/export', [JenisAktifitasController::class, 'export'])->name('jenis_aktifitas.export');
    });

    Route::group(['prefix' => 'auth'], function ($router) {
        Route::post('me', [AuthController::class, 'me']);
    });

    Route::group(['prefix' => 'user'], function ($router) {
        Route::get('/list', [MasterUserController::class, 'getUser'])->name('user.get');
        Route::get('/get/role', [MasterUserController::class, 'getRole'])->name('user.get.role');
        Route::post('/make', [MasterUserController::class, 'makeUser'])->name('user.make.admin');
        Route::post('/make/area', [MasterUserController::class, 'makeUserArea'])->name('user.make.area');
        Route::post('/update/area', [MasterUserController::class, 'updateUserArea'])->name('user.update.area');
        Route::post('/remove/area', [MasterUserController::class, 'removeUserArea'])->name('user.remove.area');
        Route::post('/update', [MasterUserController::class, 'updateUser'])->name('user.update');
        Route::post('/update/admin', [MasterUserController::class, 'adminUpdateUser'])->name('user.update.admin');
        Route::post('/delete', [MasterUserController::class, 'deleteUser'])->name('user.delete');
    });

    Route::group(['prefix' => 'place'], function ($router) {
        Route::get('/list', [MasterPlaceController::class, 'getPlace'])->name('place.get');
        Route::post('/make', [MasterPlaceController::class, 'makePlace'])->name('place.make');
        Route::post('/update', [MasterPlaceController::class, 'updatePlace'])->name('place.update');
        Route::post('/delete', [MasterPlaceController::class, 'deletePlace'])->name('place.delete');
    });
    Route::group(['prefix' => 'shift'], function ($router) {
        Route::get('/list', [MasterShiftController::class, 'getShift'])->name('shift.get');
        Route::post('/make', [MasterShiftController::class, 'makeShift'])->name('shift.make');
        Route::post('/update', [MasterShiftController::class, 'updateShift'])->name('shift.update');
        Route::post('/delete', [MasterShiftController::class, 'deleteShift'])->name('shift.delete');
    });
    Route::group(['prefix' => 'schedule'], function ($router) {
        Route::get('/get', [MasterScheduleController::class, 'getScheduleUser'])->name('schedule.get.user');
        Route::get('/get/user', [MasterScheduleController::class, 'getScheduleFilterUser'])->name('schedule.get.user.filter');
        Route::get('/list', [MasterScheduleController::class, 'getSchedule'])->name('schedule.get');
        Route::post('/make', [MasterScheduleController::class, 'makeSchedule'])->name('schedule.make');
        Route::post('/delete', [MasterScheduleController::class, 'deleteSchedule'])->name('schedule.delete');
        Route::post('/deleteBulk', [MasterScheduleController::class, 'deleteScheduleBulk'])->name('schedule.delete.bulk');
    });

    Route::group(['prefix' => 'izin'], function ($router) {
        Route::get('/list', [IzinController::class, 'getIzin'])->name('izin.get');
        Route::get('/get', [IzinController::class, 'getIzinUser'])->name('izin.get.user');
        Route::post('/make', [IzinController::class, 'makeIzin'])->name('izin.make');
        Route::post('/update', [IzinController::class, 'updateIzin'])->name('izin.update');
        Route::post('/approve', [IzinController::class, 'updateIzinApprove'])->name('izin.update.approve');
        Route::post('/reject', [IzinController::class, 'updateIzinReject'])->name('izin.update.reject');
        Route::post('/delete', [IzinController::class, 'deleteIzin'])->name('izin.delete');
    });
    Route::group(['prefix' => 'todo'], function ($router) {
        Route::get('/list', [TodoController::class, 'getTodo'])->name('todo.get');
        Route::get('/get', [TodoController::class, 'getTodoUser'])->name('todo.get.user');
        Route::get('/get/id', [TodoController::class, 'getTodoUserId'])->name('todo.get.user.id');
        Route::post('/make', [TodoController::class, 'makeTodo'])->name('todo.make');
        Route::post('/update', [TodoController::class, 'updateTodo'])->name('todo.update');
        Route::post('/approve', [TodoController::class, 'updateTodoApprove'])->name('todo.update.approve');
        Route::post('/reject', [TodoController::class, 'updateTodoReject'])->name('todo.update.reject');
        Route::post('/make/attachment', [TodoController::class, 'makeTodoAttachment'])->name('todo.make.attachment');
        Route::post('/remove/attachment', [TodoController::class, 'removeTodoAttachment'])->name('todo.remove.attachment');
        Route::post('/delete', [TodoController::class, 'deleteTodo'])->name('todo.delete');
    });
    Route::group(['prefix' => 'kasbon'], function ($router) {
        Route::get('/list', [KasbonController::class, 'getKasbon'])->name('kasbon.get');
        Route::get('/get', [KasbonController::class, 'getKasbonUser'])->name('kasbon.get.user');

        Route::post('/make', [KasbonController::class, 'makeKasbon'])->name('kasbon.make');
        Route::post('/update', [KasbonController::class, 'updateKasbon'])->name('kasbon.update');
        Route::post('/approve', [KasbonController::class, 'updateKasbonApprove'])->name('kasbon.update.approve');
        Route::post('/reject', [KasbonController::class, 'updateKasbonReject'])->name('kasbon.update.reject');
        Route::post('/delete', [KasbonController::class, 'deleteKasbon'])->name('kasbon.delete');
    });
    Route::group(['prefix' => 'payroll'], function ($router) {
        Route::post('/list', [PayrollController::class, 'getPayroll'])->name('payroll.get');
        Route::get('/get', [PayrollController::class, 'getPayrollUser'])->name('payroll.get.user');

        Route::post('/make', [PayrollController::class, 'makePayroll'])->name('payroll.make');
        Route::post('/update', [PayrollController::class, 'updatePayroll'])->name('payroll.update');
        Route::post('/update/bulk', [PayrollController::class, 'updatePayrollBulk'])->name('payroll.update.bulk');
        Route::post('/update/one', [PayrollController::class, 'updatePayrollOne'])->name('payroll.update.one');
        Route::post('/approve', [PayrollController::class, 'updatePayrollApprove'])->name('payroll.update.approve');
        Route::post('/reject', [PayrollController::class, 'updatePayrollReject'])->name('payroll.update.reject');
        Route::post('/delete', [PayrollController::class, 'deletePayroll'])->name('payroll.delete');
    });
    Route::group(['prefix' => 'kpi'], function ($router) {
        Route::post('/', [KpiController::class, 'getKpiData'])->name('kpi.get.data');
        Route::get('/aspects', [KpiController::class, 'getKpiAspects'])->name('kpi.get.aspects');
        Route::get('/list', [KpiController::class, 'getKpi'])->name('kpi.get');
        Route::get('/get', [KpiController::class, 'getKpiUser'])->name('kpi.get.user');
        Route::post('/make', [KpiController::class, 'makeKpi'])->name('kpi.make');
        Route::post('/update', [KpiController::class, 'updateKpi'])->name('kpi.update');
        Route::post('/approve', [KpiController::class, 'updateKpiApprove'])->name('kpi.update.approve');
        Route::post('/reject', [KpiController::class, 'updateKpiReject'])->name('kpi.update.reject');
        Route::post('/delete', [KpiController::class, 'deleteKpi'])->name('kpi.delete');
    });
    Route::group(['prefix' => 'absen'], function ($router) {
        Route::get('/list', [AbsensiController::class, 'getAbsenAll'])->name('absen.get.all');
        Route::get('/get', [AbsensiController::class, 'getAbsen'])->name('absen.get');
        Route::get('/get/user', [AbsensiController::class, 'getAbsenUser'])->name('absen.get.user');
        Route::post('/make', [AbsensiController::class, 'makeAbsen'])->name('absen.make');
        Route::post('/make/attachment', [AbsensiController::class, 'makeAbsenAttachment'])->name('absen.make.attachment');
        Route::post('/remove/attachment', [AbsensiController::class, 'removeAbsenAttachment'])->name('absen.remove.attachment');
    });
    Route::group(['prefix' => 'role'], function ($router) {
        Route::get('/list', [MasterRoleController::class, 'getRole'])->name('role.get');
        Route::post('/make', [MasterRoleController::class, 'makeRole'])->name('role.make');
        Route::post('/update', [MasterRoleController::class, 'updateRole'])->name('role.update');
        Route::post('/delete', [MasterRoleController::class, 'deleteRole'])->name('role.delete');
    });
    Route::group(['prefix' => 'permission'], function ($router) {
        Route::get('/list', [MasterPermissionController::class, 'getMasterPermission'])->name('permission.get');
        Route::post('/make', [MasterPermissionController::class, 'makeMasterPermission'])->name('permission.make');
        Route::post('/update', [MasterPermissionController::class, 'updateMasterPermission'])->name('permission.update');
        Route::post('/delete', [MasterPermissionController::class, 'deleteMasterPermission'])->name('permission.delete');
    });
    Route::group(['prefix' => 'notification'], function ($router) {
        Route::get('/list', [NotificationController::class, 'getMasterNotification'])->name('notification.get');
        Route::get('/list/user', [NotificationController::class, 'getMasterNotificationUser'])->name('notification.get.user');
        Route::post('/make', [NotificationController::class, 'makeMasterNotification'])->name('notification.make');
        Route::post('/update', [NotificationController::class, 'updateMasterNotification'])->name('notification.update');
        Route::post('/delete', [NotificationController::class, 'deleteMasterNotification'])->name('notification.delete');
        Route::post('/delete/temporary', [NotificationController::class, 'temporaryDeleteMasterNotification'])->name('notification.delete.temporary');
    });
    Route::group(['prefix' => 'report'], function ($router) {
        Route::post('/get', [ReportController::class, 'getFilteredReport'])->name('report.get');
        // Route::get('/export/excel', [ReportController::class, 'exportToExcel'])->name('report.export.excel');
        // Route::get('/export/pdf', [ReportController::class, 'exportToPdf'])->name('report.export.pdf');
    });
});
