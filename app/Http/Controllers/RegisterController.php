<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    public function create()
    {
        $hotels = DB::table('tb_hotels')->get();
        $countries = DB::table('countries')->get();
        $pasports = DB::table('tb_passporttype')->get();
        return view('reg_listok.create', compact('hotels', 'countries', 'pasports'));
    }

    public function index()
    {
        $records = DB::table('tb_listok')
            ->leftJoin('tb_hotels', 'tb_listok.id_hotel', '=', 'tb_hotels.id')
            ->select('tb_listok.*', 'tb_hotels.name as hotel_name')
            ->paginate(200);

        return view('reg_listok.index', compact('records'));
    }

    public function deleteSelected(Request $request)
    {
        $selectedIds = $request->input('selected_ids', []);

        if (!empty($selectedIds)) {
            DB::table('tb_listok')->whereIn('id', $selectedIds)->delete();
            return redirect()->route('register.index')->with('success', 'Выбранные записи были успешно удалены.');
        }

        return redirect()->route('register.index')->with('error', 'Пожалуйста, выберите хотя бы одну запись для удаления.');
    }

}


