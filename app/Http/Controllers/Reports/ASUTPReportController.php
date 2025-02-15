<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Jobs\LogUserActionJob;
use App\Repository\PersonInfo;
use ClickHouseDB\Client;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class ASUTPReportController extends Controller
{

    public function index(Request $request)
    {
        $userId = auth()->id();
        $roleId = DB::table('users')->where('id', $userId)->pluck('role_id')->first();
        $viewMode = $request->input('view', '6months');

        $query = DB::table('asutp')
            ->selectRaw("
            MONTH(created_at) as month,
            COUNT(*) as total,  -- Общее количество заявок
            SUM(CASE WHEN resolved IS NULL AND DATE(deadline) < CURDATE() THEN 1 ELSE 0 END) as not_completed,
            SUM(CASE WHEN DATE(deadline) >= CURDATE() AND resolved IS NULL THEN 1 ELSE 0 END) as in_progress,
            SUM(CASE WHEN resolved IS NOT NULL THEN 1 ELSE 0 END) as completed
        ")
            ->groupBy('month')
            ->orderBy('month');

        if ($viewMode === '1month') {
            $query->whereMonth('created_at', '=', now()->month)
                ->whereYear('created_at', '=', now()->year);
        } elseif ($viewMode === '6months') {
            $query->where('created_at', '>=', now()->subMonths(6));
        } else {
            $query->whereYear('created_at', '=', now()->year);
        }

        $data = $query->get();

        $months = [];
        $completed = [];
        $inProgress = [];
        $notCompleted = [];
        $total = [];

        foreach ($data as $row) {
            $monthName = date('F', mktime(0, 0, 0, $row->month, 1));
            $months[] = $monthName;
            $completed[] = $row->completed;
            $inProgress[] = $row->in_progress;
            $notCompleted[] = $row->not_completed;
            $total[] = $row->total;
        }

        return view('reports.asutp.index', compact('roleId', 'months', 'completed', 'inProgress', 'notCompleted', 'total', 'viewMode'));
    }


    public function getData(Request $request)
    {
        $month = $request->input('month');
        $reportType = $request->input('reportType');

        $query = DB::table('asutp')
            ->select(
                'asutp.id',
                'asutp.employee_name',
                'asutp.equipment',
                'asutp.problem',
                'asutp.solution',
                'asutp.resolved',
                'asutp.responsible_person',
                'asutp.deadline',
                DB::raw("DATE_FORMAT(asutp.created_at, '%d-%m-%Y %H:%i') AS created_at"),
                DB::raw("DATE_FORMAT(asutp.updated_at, '%d-%m-%Y %H:%i') AS updated_at"),
                DB::raw("SUM(CASE WHEN resolved IS NULL AND DATE(deadline) < CURDATE() THEN 1 ELSE 0 END) as not_completed"),
                DB::raw("SUM(CASE WHEN DATE(deadline) >= CURDATE() AND resolved IS NULL THEN 1 ELSE 0 END) as in_progress"),
                DB::raw("SUM(CASE WHEN resolved IS NOT NULL THEN 1 ELSE 0 END) as completed")
            )
            ->whereMonth('asutp.created_at', '=', date('n', strtotime($month)))
            ->whereYear('asutp.created_at', '=', now()->year)
            ->groupBy('asutp.id', 'asutp.employee_name', 'asutp.equipment', 'asutp.problem', 'asutp.solution', 'asutp.responsible_person', 'asutp.deadline', 'asutp.created_at', 'asutp.updated_at');

        // Фильтрация по типу отчета
        if ($reportType === 'Бажарилганлар') {
            $query->having('completed', '>', 0);
        } elseif ($reportType === 'Жарайонда') {
            $query->having('in_progress', '>', 0);
        } elseif ($reportType === 'Бажарилмаганлар (муддати отган)') {
            $query->having('not_completed', '>', 0);
        }

        $data = $query->get();

        return response()->json(['data' => $data]);
    }




    public function getReportByPeriod(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $startDate = \Carbon\Carbon::parse($startDate)->startOfDay();
        $endDate = \Carbon\Carbon::parse($endDate)->endOfDay();

        $data = DB::table('asutp')
            ->select(
                'id',
                'employee_name',
                'equipment',
                'problem',
                'solution',
                'responsible_person',
                'created_at',
                'deadline',
                'updated_at',
                'acknowledgment',
                'resolved',
                DB::raw("CASE WHEN resolved IS NULL AND DATE(deadline) < CURDATE() THEN 1 ELSE 0 END as not_completed"),
                DB::raw("CASE WHEN DATE(deadline) >= CURDATE() AND resolved IS NULL THEN 1 ELSE 0 END as in_progress"),
                DB::raw("CASE WHEN resolved IS NOT NULL THEN 1 ELSE 0 END as completed")
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        // Подсчет количества заявок по статусам
        $total = $data->count();
        $completed = $data->sum('completed'); // Выполненные
        $inProgress = $data->sum('in_progress'); // В процессе
        $notCompleted = $data->sum('not_completed'); // Не выполненные

        // Формируем ответ с данными
        return response()->json([
            'data' => $data,
            'total' => $total,
            'completed' => $completed,
            'inProgress' => $inProgress,
            'notCompleted' => $notCompleted,
            'months' => $this->getMonthsBetweenDates($startDate, $endDate)
        ]);
    }

    private function getMonthsBetweenDates($startDate, $endDate)
    {
        $start = \Carbon\Carbon::parse($startDate);
        $end = \Carbon\Carbon::parse($endDate);

        $months = [];
        while ($start->lte($end)) {
            $months[] = $start->format('F Y');
            $start->addMonth();
        }

        return $months;
    }


}





