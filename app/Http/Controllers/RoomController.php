<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class RoomController extends Controller
{
    private $data = [];
    public function index()
    {
        return view('room.index');
    }

    public function getData(Request $request)
    {
        $lang = session()->get('locale','ru');

        $where = '1=2';
        if (\Auth::user()->can('own_room')) $where = 'ru.id_hotel=' . \Auth::user()->id_hotel;
        if (\Auth::user()->can('all_room')) $where = '1=1';

        $d = \DB::table('tb_rooms as ru')
            ->join('tb_hotels as h', 'h.id', '=', 'ru.id_hotel')
            ->join('tb_room_types as rt', 'ru.id_room_type', '=', 'rt.id')
            ->selectRaw("ru.id, h.name as hotel,ru.room_floor,ru.room_numb,ru.beds,rt.$lang as tp,ru.tag,ru.wifi,ru.tvset,ru.aircond,ru.freezer,ru.created_at,ru.active,ru.id_hotel");
            //->selectRaw("ru.id, ru.room_floor,ru.room_numb,ru.beds,rt.$lang as tp,ru.tag,ru.wifi,ru.tvset,ru.aircond,ru.freezer,ru.created_at,ru.active,ru.hotel_id")
//            ->whereRaw($where);


//        if ($region_id = $request->get('region_id')) $d->where('h.id_region','=', $region_id);
//        if ($hotel_id = $request->get('hotel_id')) $d->where('h.id','=', $hotel_id);
//        if ($floor = $request->get('floor')) $d->where('ru.room_floor','=', $floor);
//        if ($room_type = $request->get('room_type')) $d->where('ru.room_type_id','=', $room_type);
//        if ($tag = $request->get('room_tag')) $d->where('ru.tag','like', "%$tag%");
//        if ($room_cap = $request->get('room_cap')) {
//            switch ($room_cap*1) {
//                case 1:
//                case 2:
//                case 3:
//                    $d->where("ru.beds",$room_cap);
//                    break;
//                case 99:
//                    $d->where("ru.beds",">", 3);
//                    break;
//            }
//        }

        return DataTables::of($d)
            ->editColumn('wifi', function ($row) {
                return  !empty($row->wifi) ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-times text-danger"></i>';
            })
            ->editColumn('tvset', function ($row) {
                return  !empty($row->tvset) ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-times text-danger"></i>';
            })
            ->editColumn('aircond', function ($row) {
                return  !empty($row->aircond) ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-times text-danger"></i>';
            })
            ->editColumn('freezer', function ($row) {
                return  !empty($row->freezer) ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-times text-danger"></i>';
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at ? date('d.m.Y H:i',strtotime($row->created_at)) : '';
            })
            ->editColumn('active', function ($row) {
                return $row->active*1 == 1 ?
                    '<span class="badge bg-soft-success text-success font-size-12">ACTIVE</span>' :
                    '<span class="badge bg-soft-danger text-danger font-size-12">NOT ACTIVE</span>';
            })
            ->setRowData(['data-id' => function($row) {return cryptId($row->id);}])
            ->addColumn('action', function ($row) {
                $id = cryptId($row->id);
                $url = asset('rooms/show/' . $id);
                $urlEdit = asset('rooms/update/' . $id);
                $r = "'" . str_replace(["'",'"'],"`",$row->room_numb) . "'";
                if ($row->hotel_id = \Session::get('hid',\Auth::user()->hotel_id) || \Auth::user()->can('allowedit')) {
                    $urlEdit = '&nbsp;<a href="' .  $urlEdit. '" class="btn btn-xs btn-default" title="Редактировать" onclick="editRoom(event,this.href,' . $r .'); return false;"><i class="fa fa-edit"></i></a>';
                }
                else
                    $urlEdit = '';
                return '<a href="' . $url . '" class="btn btn-xs btn-default" onclick="viewRoom(event,this.href,' . $r . '); return false;" title="Просмотр"><i class="fa fa-eye"></i></a>' . $urlEdit;
            })
            ->rawColumns(['action','wifi','tvset','aircond','freezer','active'])
            ->make(true);
    }

    function update(Request $request, $id) {

        if(empty($id))
            if (\Auth::user()->can('add_room')) return redirect('dashboard')->with('messagetext',\Lang::get('core.note_restric'))->with('msgstatus','error');
        else
            if (\Auth::user()->can('edit_room')) return redirect('dashboard')->with('messagetext',\Lang::get('core.note_restric'))->with('msgstatus','error');

        $this->data['pageModule'] = 'rooms';

        $lang = \Session::get('lang','en');
        $this->data['types'] = \DB::table('tb_room_types')->selectRaw("id,$lang as name")->get();

        if ($id) $id = cryptId($id,'dec');

        $where = '1=2';
        if (\Auth::user()->can('own_room')) $where = 'rooms.hotel_id=' . \Auth::user()->hotel_id;
        if (\Auth::user()->can('all_room')) $where = '1=1';

        $this->data['row'] = \DB::table('tb_rooms')->where('id',$id)->selectRaw('tb_rooms.*')->first();
        if(empty($row))
            $this->data['hotel']=\DB::table('tb_hotels')->select('id','name')->where('id',\Session::get('hid',\Auth::user()->id_hotel))->first();
        else
            $this->data['hotel']=\DB::table('tb_hotels')->select('id','name')->where('id',$this->data['row']->hotel_id)->first();

        return view('room.edit',$this->data);
    }

    public function save(Request $request)
    {
        try {
            $data = [
                'id_room_type' => $request->get('id_room_type'),
                'room_numb' => $request->get('room_numb'),
                'room_floor' => $request->get('room_floor'),
                'beds' => $request->get('beds'),
                'wifi' => $request->boolean('wifi') ? '1' : '0',
                'tvset' => $request->boolean('tvset') ? '1' : '0',
                'aircond' => $request->boolean('aircond') ? '1' : '0',
                'freezer' => $request->boolean('freezer') ? '1' : '0',
                'active' => $request->boolean('active') ? '1' : '0',
                'tag' => $request->get('tag') ?? null,
                'id_hotel' => 12,
            ];

            $id = $request->input('id');
            $updatedRecord = \DB::table('tb_rooms')->where('id', $id)->update($data);
            session()->flash('success', 'Данные успешно обновлены');

            return response()->json(['status' => 'success',  'updated_data' => $updatedRecord]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ошибка сохранения данных.',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }
}
