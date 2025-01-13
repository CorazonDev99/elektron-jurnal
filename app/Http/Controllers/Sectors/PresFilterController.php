<?php

namespace App\Http\Controllers\Sectors;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PresFilterController extends Controller
{

    public function index()
    {
        $userId = auth()->id();
        $roleId = \DB::table('presfilter')->where('id', $userId)->select('role_id')->first();
        return view('lisok.presfilter.index', compact('userId', 'roleId'));
    }

    public function getData(Request $request)
    {
        $d = \DB::table('presfilter');

        $d->selectRaw("'' as `empty`, presfilter.id,presfilter.user_id, presfilter.role_id, DATE_FORMAT(presfilter.created_at, '%d-%m-%Y %H:%i') AS created_at,
                DATE_FORMAT(presfilter.updated_at, '%d-%m-%Y %H:%i') AS updated_at, presfilter.equipment, presfilter.problem, presfilter.solution,
                presfilter.responsible_person, DATE_FORMAT(presfilter.deadline, '%Y-%m-%d') AS deadline, presfilter.acknowledgment, presfilter.resolved, presfilter.employee_name");
        if ($request->filled('fio')) {
            $d->where('presfilter.employee_name', 'like', '%' . $request->fio . '%');
        }
        if ($request->filled('created_at')) {
            $d->whereDate('presfilter.created_at', '=', $request->created_at);
        }
        if ($request->filled('equipment')) {
            $d->where('presfilter.equipment', 'like', '%' . $request->equipment . '%');
        }
        if ($request->filled('problem')) {
            $d->where('presfilter.problem', 'like', '%' . $request->problem . '%');
        }
        if ($request->filled('solution')) {
            $d->where('presfilter.solution', 'like', '%' . $request->solution . '%');
        }
        if ($request->filled('responsible_person')) {
            $d->where('presfilter.responsible_person', 'like', '%' . $request->responsible_person . '%');
        }
        if ($request->filled('deadline_from') && $request->filled('deadline_until')) {
            $d->whereBetween('presfilter.deadline', [$request->deadline_from, $request->deadline_until])
                ->orWhereBetween('presfilter.deadline', [$request->deadline_from, $request->deadline_until]);
        }

        elseif ($request->filled('deadline_from')) {
            $d->where('presfilter.deadline', '>=', $request->deadline_from)
                ->orWhere('presfilter.deadline', '>=', $request->deadline_until);
        }
        if ($request->filled('resolved')) {
            $d->where('presfilter.resolved', 'like', '%' . $request->resolved . '%');
        }

        return DataTables::of($d)
            ->addIndexColumn()
            ->make(true);
    }

    public function updateSuccess(Request $request)
    {
        $recordId = $request->input('id');

        \DB::table('presfilter')
            ->where('id', $recordId)
            ->update([
                'acknowledgment' => 1,
            ]);


        return response()->json(['status' => true]);
    }

    public function updateRecord(Request $request)
    {
        $ids = $request->input('ids');

        if (empty($ids) || !is_array($ids)) {
            return response()->json(['error' => 'Не выбраны элементы для ознакомления.'], 400);
        }

        try {

            \DB::table('presfilter')->whereIn('id', $ids)->update([
                'acknowledgment' => 1,
            ]);

            return response()->json(['success' => 'Элементы успешно ознакомлени.']);
        } catch (\Exception $e) {
            \Log::error("Ошибка при ознакомлении элементов: " . $e->getMessage());

            return response()->json(['error' => 'Ошибка при ознакомлении элементов.'], 500);
        }
    }

    public function createRecord(Request $request)
    {
        $data = [
            'employee_name' => $request->input('fullname'),
            'equipment' => $request->input('equipment'),
            'problem' => $request->input('problem'),
            'solution' => $request->input('solution'),
            'responsible_person' => $request->input('responsible'),
            'deadline' => $request->input('deadline'),
            'user_id' => auth()->id(),
        ];

        \DB::table('presfilter')->insert($data);

        return response()->json(['message' => 'Запрос успешно создан'], 201);
    }

    public function editRecord(Request $request)
    {
        $recordId = $request->input('id');

        $data = [
            'employee_name' => $request->input('fullname'),
            'equipment' => $request->input('equipment'),
            'problem' => $request->input('problem'),
            'solution' => $request->input('solution'),
            'responsible_person' => $request->input('responsible'),
            'deadline' => $request->input('deadline'),
        ];

        $recordExists = \DB::table('presfilter')->where('id', $recordId)->exists();

        if (!$recordExists) {
            return response()->json(['message' => 'Запись с указанным ID не найдена.'], 404);
        }

        \DB::table('presfilter')->where('id', $recordId)->update($data);
        return response()->json(['status' => true]);


    }

    public function solvedRecord(Request $request)
    {

        $recordId = $request->input('id');

        \DB::table('presfilter')
            ->where('id', $recordId)
            ->update([
                'resolved' => $request->input('solution'),
            ]);

        return response()->json(['status' => true]);


    }

}





