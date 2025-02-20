<?php
use App\Http\Controllers\Sectors\DC1Controller;
use App\Http\Controllers\Sectors\ASUTPController;
use App\Http\Controllers\Sectors\ESController;
use App\Http\Controllers\Sectors\FSOController;
use App\Http\Controllers\Sectors\MolibdenController;
use App\Http\Controllers\Sectors\PNS1AController;
use App\Http\Controllers\Sectors\PNS2AController;
use App\Http\Controllers\Sectors\PresFilterController;
use App\Http\Controllers\Sectors\ReagentController;
use App\Http\Controllers\Sectors\RMUController;
use App\Http\Controllers\Sectors\Sector1Controller;
use App\Http\Controllers\Sectors\Sector2Controller;
use App\Http\Controllers\Sectors\UdiiController;
use App\Http\Controllers\Sectors\UdipController;
use App\Http\Controllers\Sectors\UiifController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Session;

use App\Http\Controllers\Reports\ASUTPReportController;
use App\Http\Controllers\Reports\DC1ReportController;
use App\Http\Controllers\Reports\ESReportController;
use App\Http\Controllers\Reports\FSOReportController;
use App\Http\Controllers\Reports\MolibdenReportController;
use App\Http\Controllers\Reports\PNS1AReportController;
use App\Http\Controllers\Reports\PNS2AReportController;
use App\Http\Controllers\Reports\PresFilterReportController;
use App\Http\Controllers\Reports\ReagentReportController;
use App\Http\Controllers\Reports\RMUReportController;
use App\Http\Controllers\Reports\Sector1ReportController;
use App\Http\Controllers\Reports\Sector2ReportController;
use App\Http\Controllers\Reports\UdiiReportController;
use App\Http\Controllers\Reports\UdipReportController;
use App\Http\Controllers\Reports\UiifReportController;
use Illuminate\Support\Facades\DB;



Route::get('/', function () {
    return view('auth/login');
});

require __DIR__.'/auth.php';
Route::post('/get-report-by-period-asutp', [ASUTPReportController::class, 'getReportByPeriod']);
Route::post('/get-data-asutp', [ASUTPReportController::class, 'getData']);
Route::auto('/asutp-report', ASUTPReportController::class);


Route::post('/get-report-by-period-dc1', [DC1ReportController::class, 'getReportByPeriod']);
Route::post('/get-data-dc1', [DC1ReportController::class, 'getData']);
Route::auto('/dc1-report', DC1ReportController::class);

Route::post('/get-report-by-period-ec', [ESReportController::class, 'getReportByPeriod']);
Route::post('/get-data-ec', [ESReportController::class, 'getData']);
Route::auto('/ec-report', ESReportController::class);

Route::post('/get-report-by-period-fso', [FSOReportController::class, 'getReportByPeriod']);
Route::post('/get-data-fso', [FSOReportController::class, 'getData']);
Route::auto('/fso-report', FSOReportController::class);

Route::post('/get-report-by-period-molibden', [MolibdenReportController::class, 'getReportByPeriod']);
Route::post('/get-data-molibden', [MolibdenReportController::class, 'getData']);
Route::auto('/molibden-report', MolibdenReportController::class);

Route::post('/get-report-by-period-pns1a', [PNS1AReportController::class, 'getReportByPeriod']);
Route::post('/get-data-pns1a', [PNS1AReportController::class, 'getData']);
Route::auto('/pns1a-report', PNS1AReportController::class);

Route::post('/get-report-by-period-pns2a', [PNS2AReportController::class, 'getReportByPeriod']);
Route::post('/get-data-pns2a', [PNS2AReportController::class, 'getData']);
Route::auto('/pns2a-report', PNS2AReportController::class);

Route::post('/get-report-by-period-presfilter', [PresFilterReportController::class, 'getReportByPeriod']);
Route::post('/get-data-presfilter', [PresFilterReportController::class, 'getData']);
Route::auto('/presfilter-report', PresFilterReportController::class);

Route::post('/get-report-by-period-reagent', [ReagentReportController::class, 'getReportByPeriod']);
Route::post('/get-data-reagent', [ReagentReportController::class, 'getData']);
Route::auto('/reagent-report', ReagentReportController::class);

Route::post('/get-report-by-period-rmu', [RMUReportController::class, 'getReportByPeriod']);
Route::post('/get-data-rmu', [RMUReportController::class, 'getData']);
Route::auto('/rmu-report', RMUReportController::class);

Route::post('/get-report-by-period-sector1', [Sector1ReportController::class, 'getReportByPeriod']);
Route::post('/get-data-sector1', [Sector1ReportController::class, 'getData']);
Route::auto('/sector1-report', Sector1ReportController::class);

Route::post('/get-report-by-period-sector2', [Sector2ReportController::class, 'getReportByPeriod']);
Route::post('/get-data-sector2', [Sector2ReportController::class, 'getData']);
Route::auto('/sector2-report', Sector2ReportController::class);

Route::post('/get-report-by-period-udii', [UdiiReportController::class, 'getReportByPeriod']);
Route::post('/get-data-udii', [UdiiReportController::class, 'getData']);
Route::auto('/udii-report', UdiiReportController::class);

Route::post('/get-report-by-period-udip', [UdipReportController::class, 'getReportByPeriod']);
Route::post('/get-data-udip', [UdipReportController::class, 'getData']);
Route::auto('/udip-report', UdipReportController::class);

Route::post('/get-report-by-period-uiif', [UiifReportController::class, 'getReportByPeriod']);
Route::post('/get-data-uiif', [UiifReportController::class, 'getData']);
Route::auto('/uiif-report', UiifReportController::class);


#DC1

Route::post('/dc1/success-record', [DC1Controller::class, 'updateSuccess'])->name('success-record');
Route::post('/dc1/success-records', [DC1Controller::class, 'updateRecord'])->name('success-records');
Route::post('/dc1/create-record', [DC1Controller::class, 'createRecord'])->name('create-record');
Route::post('/dc1/edit-record', [DC1Controller::class, 'editRecord'])->name('edit-record');
Route::post('/dc1/solved-record', [DC1Controller::class, 'solvedRecord'])->name('solved-record');
Route::auto('dc1', DC1Controller::class);


#Asutp
Route::post('/asutp/success-record', [ASUTPController::class, 'updateSuccess'])->name('success-record');
Route::post('/asutp/success-records', [ASUTPController::class, 'updateRecord'])->name('success-records');
Route::post('/asutp/create-record', [ASUTPController::class, 'createRecord'])->name('create-record');
Route::post('/asutp/edit-record', [ASUTPController::class, 'editRecord'])->name('edit-record');
Route::post('/asutp/solved-record', [ASUTPController::class, 'solvedRecord'])->name('solved-record');
Route::auto('asutp', ASUTPController::class);

#ES
Route::post('/ec/success-record', [ESController::class, 'updateSuccess'])->name('success-record');
Route::post('/ec/success-records', [ESController::class, 'updateRecord'])->name('success-records');
Route::post('/ec/create-record', [ESController::class, 'createRecord'])->name('create-record');
Route::post('/ec/edit-record', [ESController::class, 'editRecord'])->name('edit-record');
Route::post('/ec/solved-record', [ESController::class, 'solvedRecord'])->name('solved-record');
Route::auto('ec', ESController::class);


#FSO
Route::post('/fso/success-record', [FSOController::class, 'updateSuccess'])->name('success-record');
Route::post('/fso/success-records', [FSOController::class, 'updateRecord'])->name('success-records');
Route::post('/fso/create-record', [FSOController::class, 'createRecord'])->name('create-record');
Route::post('/fso/edit-record', [FSOController::class, 'editRecord'])->name('edit-record');
Route::post('/fso/solved-record', [FSOController::class, 'solvedRecord'])->name('solved-record');
Route::auto('fso', FSOController::class);


#Molibden
Route::post('/molibden/success-record', [MolibdenController::class, 'updateSuccess'])->name('success-record');
Route::post('/molibden/success-records', [MolibdenController::class, 'updateRecord'])->name('success-records');
Route::post('/molibden/create-record', [MolibdenController::class, 'createRecord'])->name('create-record');
Route::post('/molibden/edit-record', [MolibdenController::class, 'editRecord'])->name('edit-record');
Route::post('/molibden/solved-record', [MolibdenController::class, 'solvedRecord'])->name('solved-record');
Route::auto('molibden', MolibdenController::class);

#pns1a
Route::post('/pns1a/success-record', [PNS1AController::class, 'updateSuccess'])->name('success-record');
Route::post('/pns1a/success-records', [PNS1AController::class, 'updateRecord'])->name('success-records');
Route::post('/pns1a/create-record', [PNS1AController::class, 'createRecord'])->name('create-record');
Route::post('/pns1a/edit-record', [PNS1AController::class, 'editRecord'])->name('edit-record');
Route::post('/pns1a/solved-record', [PNS1AController::class, 'solvedRecord'])->name('solved-record');
Route::auto('pns1a', PNS1AController::class);


#pns2a
Route::post('/pns2a/success-record', [PNS2AController::class, 'updateSuccess'])->name('success-record');
Route::post('/pns2a/success-records', [PNS2AController::class, 'updateRecord'])->name('success-records');
Route::post('/pns2a/create-record', [PNS2AController::class, 'createRecord'])->name('create-record');
Route::post('/pns2a/edit-record', [PNS2AController::class, 'editRecord'])->name('edit-record');
Route::post('/pns2a/solved-record', [PNS2AController::class, 'solvedRecord'])->name('solved-record');
Route::auto('pns2a', PNS2AController::class);


#presfilter
Route::post('/presfilter/success-record', [PresFilterController::class, 'updateSuccess'])->name('success-record');
Route::post('/presfilter/success-records', [PresFilterController::class, 'updateRecord'])->name('success-records');
Route::post('/presfilter/create-record', [PresFilterController::class, 'createRecord'])->name('create-record');
Route::post('/presfilter/edit-record', [PresFilterController::class, 'editRecord'])->name('edit-record');
Route::post('/presfilter/solved-record', [PresFilterController::class, 'solvedRecord'])->name('solved-record');
Route::auto('presfilter', PresFilterController::class);



#reagent
Route::post('/reagent/success-record', [ReagentController::class, 'updateSuccess'])->name('success-record');
Route::post('/reagent/success-records', [ReagentController::class, 'updateRecord'])->name('success-records');
Route::post('/reagent/create-record', [ReagentController::class, 'createRecord'])->name('create-record');
Route::post('/reagent/edit-record', [ReagentController::class, 'editRecord'])->name('edit-record');
Route::post('/reagent/solved-record', [ReagentController::class, 'solvedRecord'])->name('solved-record');
Route::auto('reagent', ReagentController::class);


#rmu
Route::post('/rmu/success-record', [RMUController::class, 'updateSuccess'])->name('success-record');
Route::post('/rmu/success-records', [RMUController::class, 'updateRecord'])->name('success-records');
Route::post('/rmu/create-record', [RMUController::class, 'createRecord'])->name('create-record');
Route::post('/rmu/edit-record', [RMUController::class, 'editRecord'])->name('edit-record');
Route::post('/rmu/solved-record', [RMUController::class, 'solvedRecord'])->name('solved-record');
Route::auto('rmu', RMUController::class);


#sector1
Route::post('/sector1/success-record', [Sector1Controller::class, 'updateSuccess'])->name('success-record');
Route::post('/sector1/success-records', [Sector1Controller::class, 'updateRecord'])->name('success-records');
Route::post('/sector1/create-record', [Sector1Controller::class, 'createRecord'])->name('create-record');
Route::post('/sector1/edit-record', [Sector1Controller::class, 'editRecord'])->name('edit-record');
Route::post('/sector1/solved-record', [Sector1Controller::class, 'solvedRecord'])->name('solved-record');
Route::auto('sector1', Sector1Controller::class);

#sector2
Route::post('/sector2/success-record', [Sector2Controller::class, 'updateSuccess'])->name('success-record');
Route::post('/sector2/success-records', [Sector2Controller::class, 'updateRecord'])->name('success-records');
Route::post('/sector2/create-record', [Sector2Controller::class, 'createRecord'])->name('create-record');
Route::post('/sector2/edit-record', [Sector2Controller::class, 'editRecord'])->name('edit-record');
Route::post('/sector2/solved-record', [Sector2Controller::class, 'solvedRecord'])->name('solved-record');
Route::auto('sector2', Sector2Controller::class);

#udii
Route::post('/udii/success-record', [UdiiController::class, 'updateSuccess'])->name('success-record');
Route::post('/udii/success-records', [UdiiController::class, 'updateRecord'])->name('success-records');
Route::post('/udii/create-record', [UdiiController::class, 'createRecord'])->name('create-record');
Route::post('/udii/edit-record', [UdiiController::class, 'editRecord'])->name('edit-record');
Route::post('/udii/solved-record', [UdiiController::class, 'solvedRecord'])->name('solved-record');
Route::auto('udii', UdiiController::class);



#udip
Route::post('/udip/success-record', [UdipController::class, 'updateSuccess'])->name('success-record');
Route::post('/udip/success-records', [UdipController::class, 'updateRecord'])->name('success-records');
Route::post('/udip/create-record', [UdipController::class, 'createRecord'])->name('create-record');
Route::post('/udip/edit-record', [UdipController::class, 'editRecord'])->name('edit-record');
Route::post('/udip/solved-record', [UdipController::class, 'solvedRecord'])->name('solved-record');
Route::auto('udip', UdipController::class);

#uiif
Route::post('/uiif/success-record', [UiifController::class, 'updateSuccess'])->name('success-record');
Route::post('/uiif/success-records', [UiifController::class, 'updateRecord'])->name('success-records');
Route::post('/uiif/create-record', [UiifController::class, 'createRecord'])->name('create-record');
Route::post('/uiif/edit-record', [UiifController::class, 'editRecord'])->name('edit-record');
Route::post('/uiif/solved-record', [UiifController::class, 'solvedRecord'])->name('solved-record');
Route::auto('uiif', UiifController::class);

Route::resources([
    'dc1' => DC1Controller::class,
    'asutp' => ASUTPController::class,
    'ec' => ESController::class,
    'fso' => FSOController::class,
    'molibden' => MolibdenController::class,
    'pns1a' => PNS1AController::class,
    'pns2a' => PNS2AController::class,
    'presfilter' => PresFilterController::class,
    'reagent' => ReagentController::class,
    'rmu' => RMUController::class,
    'sector1' => Sector1Controller::class,
    'sector2' => Sector2Controller::class,
    'udii' => UdiiController::class,
    'udip' => UdipController::class,
    'uiif' => UiifController::class
]);





Route::post('/api/user', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $credentials = $request->only('email', 'password');
    $user = User::where('email', $credentials['email'])->first();

    if (!$user || !\Illuminate\Support\Facades\Hash::check($credentials['password'], $user->password)) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    // Сохраняем пользователя в сессии
    Auth::login($user);
    Session::put('user_id', $user->id);

    return response()->json(['status' => 'success']);
});

Route::get('/dashboard', function (Request $request) {
    if (!Auth::check()) {
        return redirect('/login');
    }

    $user = Auth::user();
    $roleId = $user->role_id;

    return view('dashboard', compact('roleId'));
})->name('dashboard');
