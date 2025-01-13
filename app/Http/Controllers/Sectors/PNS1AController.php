<?php

namespace App\Http\Controllers\Sectors;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PNS1AController extends Controller
{

    public function index()
    {
        $userId = auth()->id();
        $roleId = \DB::table('pns1a')->where('id', $userId)->select('role_id')->first();
        return view('lisok.pns1a.index', compact('userId', 'roleId'));
    }

    public function getData(Request $request)
    {
        $d = \DB::table('pns1a');

        $d->selectRaw("'' as `empty`, pns1a.id,pns1a.user_id, pns1a.role_id, DATE_FORMAT(pns1a.created_at, '%d-%m-%Y %H:%i') AS created_at,
                DATE_FORMAT(pns1a.updated_at, '%d-%m-%Y %H:%i') AS updated_at, pns1a.equipment, pns1a.problem, pns1a.solution,
                pns1a.responsible_person, DATE_FORMAT(pns1a.deadline, '%Y-%m-%d') AS deadline, pns1a.acknowledgment, pns1a.resolved, pns1a.employee_name");
        if ($request->filled('fio')) {
            $d->where('pns1a.employee_name', 'like', '%' . $request->fio . '%');
        }
        if ($request->filled('created_at')) {
            $d->whereDate('pns1a.created_at', '=', $request->created_at);
        }
        if ($request->filled('equipment')) {
            $d->where('pns1a.equipment', 'like', '%' . $request->equipment . '%');
        }
        if ($request->filled('problem')) {
            $d->where('pns1a.problem', 'like', '%' . $request->problem . '%');
        }
        if ($request->filled('solution')) {
            $d->where('pns1a.solution', 'like', '%' . $request->solution . '%');
        }
        if ($request->filled('responsible_person')) {
            $d->where('pns1a.responsible_person', 'like', '%' . $request->responsible_person . '%');
        }
        if ($request->filled('deadline_from') && $request->filled('deadline_until')) {
            $d->whereBetween('pns1a.deadline', [$request->deadline_from, $request->deadline_until])
                ->orWhereBetween('pns1a.deadline', [$request->deadline_from, $request->deadline_until]);
        }

        elseif ($request->filled('deadline_from')) {
            $d->where('pns1a.deadline', '>=', $request->deadline_from)
                ->orWhere('pns1a.deadline', '>=', $request->deadline_until);
        }
        if ($request->filled('resolved')) {
            $d->where('pns1a.resolved', 'like', '%' . $request->resolved . '%');
        }

        return DataTables::of($d)
            ->addIndexColumn()
            ->make(true);
    }

    public function updateSuccess(Request $request)
    {
        $recordId = $request->input('id');

        \DB::table('pns1a')
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

            \DB::table('pns1a')->whereIn('id', $ids)->update([
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

        \DB::table('pns1a')->insert($data);

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

        $recordExists = \DB::table('pns1a')->where('id', $recordId)->exists();

        if (!$recordExists) {
            return response()->json(['message' => 'Запись с указанным ID не найдена.'], 404);
        }

        \DB::table('pns1a')->where('id', $recordId)->update($data);
        return response()->json(['status' => true]);


    }

    public function solvedRecord(Request $request)
    {

        $recordId = $request->input('id');

        \DB::table('pns1a')
            ->where('id', $recordId)
            ->update([
                'resolved' => $request->input('solution'),
            ]);

        return response()->json(['status' => true]);


    }

}





