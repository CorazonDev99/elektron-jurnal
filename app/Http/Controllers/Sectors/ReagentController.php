<?php

namespace App\Http\Controllers\Sectors;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ReagentController extends Controller
{

    public function index()
    {
        $userId = auth()->id();
        $roleId = \DB::table('users')->where('id', $userId)->pluck('role_id')->first();
        return view('lisok.reagent.index', compact('userId', 'roleId'));
    }

    public function deleteRecord(Request $request)
    {
        $ids = $request->input('ids');

        if (empty($ids) || !is_array($ids)) {
            return response()->json(['error' => 'Не выбраны элементы для удаления.'], 400);
        }

        try {

            \DB::table('reagent')->whereIn('id', $ids)->delete();

            return response()->json(['success' => 'Элементы успешно удалены.']);
        } catch (\Exception $e) {
            \Log::error("Ошибка при удалении элементов: " . $e->getMessage());

            return response()->json(['error' => 'Ошибка при удалении элементов.'], 500);
        }
    }

    public function getData(Request $request)
    {
        $d = \DB::table('reagent');

        $d->selectRaw("'' as `empty`, reagent.id,reagent.user_id, reagent.role_id, DATE_FORMAT(reagent.created_at, '%d-%m-%Y %H:%i') AS created_at,
                DATE_FORMAT(reagent.updated_at, '%d-%m-%Y %H:%i') AS updated_at, reagent.equipment, reagent.problem, reagent.solution,
                reagent.responsible_person, DATE_FORMAT(reagent.deadline, '%d-%m-%Y') AS deadline, reagent.acknowledgment, reagent.resolved, reagent.reason, reagent.employee_name");
        if ($request->filled('fio')) {
            $d->where('reagent.employee_name', 'like', '%' . $request->fio . '%');
        }
        if ($request->filled('created_at')) {
            $d->whereDate('reagent.created_at', '=', $request->created_at);
        }
        if ($request->filled('equipment')) {
            $d->where('reagent.equipment', 'like', '%' . $request->equipment . '%');
        }
        if ($request->filled('problem')) {
            $d->where('reagent.problem', 'like', '%' . $request->problem . '%');
        }
        if ($request->filled('solution')) {
            $d->where('reagent.solution', 'like', '%' . $request->solution . '%');
        }
        if ($request->filled('responsible_person')) {
            $d->where('reagent.responsible_person', 'like', '%' . $request->responsible_person . '%');
        }
        if ($request->filled('deadline_from') && $request->filled('deadline_until')) {
            $d->whereBetween('reagent.deadline', [$request->deadline_from, $request->deadline_until])
                ->orWhereBetween('reagent.deadline', [$request->deadline_from, $request->deadline_until]);
        }

        elseif ($request->filled('deadline_from')) {
            $d->where('reagent.deadline', '>=', $request->deadline_from)
                ->orWhere('reagent.deadline', '>=', $request->deadline_until);
        }
        if ($request->filled('resolved')) {
            $d->where('reagent.resolved', 'like', '%' . $request->resolved . '%');
        }
        if ($request->filled('acknowledgment')) {
            $d->where('reagent.acknowledgment', $request->acknowledgment);
        }

        return DataTables::of($d)
            ->addIndexColumn()
            ->make(true);
    }

    public function updateSuccess(Request $request)
    {
        $recordId = $request->input('id');

        \DB::table('reagent')
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

            \DB::table('reagent')->whereIn('id', $ids)->update([
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
            'role_id' => $request->input('roleId'),

            'user_id' => auth()->id(),
        ];

        \DB::table('reagent')->insert($data);

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

        $recordExists = \DB::table('reagent')->where('id', $recordId)->exists();

        if (!$recordExists) {
            return response()->json(['message' => 'Запись с указанным ID не найдена.'], 404);
        }

        \DB::table('reagent')->where('id', $recordId)->update($data);
        return response()->json(['status' => true]);


    }

    public function solvedRecord(Request $request)
    {

        $recordId = $request->input('id');

        \DB::table('reagent')
            ->where('id', $recordId)
            ->update([
                'resolved' => $request->input('solution'),
            ]);

        return response()->json(['status' => true]);


    }


    public function reasonRecord(Request $request)
    {

        $reasondId = $request->input('id');

        \DB::table('reagent')
            ->where('id', $reasondId)
            ->update([
                'reason' => $request->input('reason'),
            ]);

        return response()->json(['status' => true]);


    }


    public function updateAcknowledgment(Request $request)
    {
        $updated = \DB::table('reagent')
            ->where('id', $request->id)
            ->update(['acknowledgment' => $request->acknowledgment]);

        if ($updated) {
            return response()->json([
                'success' => true,
                'message' => 'Оповещение отправлено!'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Ошибка при обновлении статуса или данные не изменились.'
        ], 500);
    }

}





