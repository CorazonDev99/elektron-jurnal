<?php

namespace App\Http\Controllers\Sectors;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ESController extends Controller
{

    public function index()
    {
        $userId = auth()->id();
        $roleId = \DB::table('ec')->where('id', $userId)->select('role_id')->first();
        return view('lisok.ec.index', compact('userId', 'roleId'));
    }

    public function getData(Request $request)
    {
        $d = \DB::table('ec');

        $d->selectRaw("'' as `empty`, ec.id,ec.user_id, ec.role_id, DATE_FORMAT(ec.created_at, '%d-%m-%Y %H:%i') AS created_at,
                DATE_FORMAT(ec.updated_at, '%d-%m-%Y %H:%i') AS updated_at, ec.equipment, ec.problem, ec.solution,
                ec.responsible_person, DATE_FORMAT(ec.deadline, '%Y-%m-%d') AS deadline, ec.acknowledgment, ec.resolved, ec.employee_name");
        if ($request->filled('fio')) {
            $d->where('ec.employee_name', 'like', '%' . $request->fio . '%');
        }
        if ($request->filled('created_at')) {
            $d->whereDate('ec.created_at', '=', $request->created_at);
        }
        if ($request->filled('equipment')) {
            $d->where('ec.equipment', 'like', '%' . $request->equipment . '%');
        }
        if ($request->filled('problem')) {
            $d->where('ec.problem', 'like', '%' . $request->problem . '%');
        }
        if ($request->filled('solution')) {
            $d->where('ec.solution', 'like', '%' . $request->solution . '%');
        }
        if ($request->filled('responsible_person')) {
            $d->where('ec.responsible_person', 'like', '%' . $request->responsible_person . '%');
        }
        if ($request->filled('deadline_from') && $request->filled('deadline_until')) {
            $d->whereBetween('ec.deadline', [$request->deadline_from, $request->deadline_until])
                ->orWhereBetween('ec.deadline', [$request->deadline_from, $request->deadline_until]);
        }

        elseif ($request->filled('deadline_from')) {
            $d->where('ec.deadline', '>=', $request->deadline_from)
                ->orWhere('ec.deadline', '>=', $request->deadline_until);
        }
        if ($request->filled('resolved')) {
            $d->where('ec.resolved', 'like', '%' . $request->resolved . '%');
        }

        return DataTables::of($d)
            ->addIndexColumn()
            ->make(true);
    }

    public function updateSuccess(Request $request)
    {
        $recordId = $request->input('id');

        \DB::table('ec')
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

            \DB::table('ec')->whereIn('id', $ids)->update([
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

        \DB::table('ec')->insert($data);

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

        $recordExists = \DB::table('ec')->where('id', $recordId)->exists();

        if (!$recordExists) {
            return response()->json(['message' => 'Запись с указанным ID не найдена.'], 404);
        }

        \DB::table('ec')->where('id', $recordId)->update($data);
        return response()->json(['status' => true]);


    }

    public function solvedRecord(Request $request)
    {

        $recordId = $request->input('id');

        \DB::table('ec')
            ->where('id', $recordId)
            ->update([
                'resolved' => $request->input('solution'),
            ]);

        return response()->json(['status' => true]);


    }

}





