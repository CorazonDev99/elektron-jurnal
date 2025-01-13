<?php

namespace App\Http\Controllers;

use App\Repository\PersonInfo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class SelfListokController extends Controller
{
    private $data = [];
    public function index()
    {
        $grp_id = \Session::get('rid', \Auth::user()->roles->pluck('id')->first()) * 1;
        $this->data['showHotel'] = $grp_id == 6 || $grp_id == 8;
        return view('selflistok.index', $this->data);
    }


    public function create() {
        return view('selflistok.form');
    }

    public function datarow(Request $request)
    {
        $rowData = $request->input('rowData');

        session(['editRowData' => $rowData]);

        return response()->json(['success' => true]);
    }


    public function edit()
    {

        $id = session('editRowData')['id'];
        $rowdata = \DB::table('tb_self_listok')
            ->join('countries', 'tb_self_listok.id_country', '=', 'countries.country_id')
            ->join('tb_citizens', 'tb_self_listok.id_countryFrom', '=', 'tb_citizens.id')
            ->leftJoin('tb_self_children', 'tb_self_children.id_listok', '=', 'tb_self_listok.id' )
            ->join('tb_visittype', 'tb_self_listok.id_visitType', '=', 'tb_visittype.id')
            ->join('tb_guests', 'tb_self_listok.id_guest', '=', 'tb_guests.id')
            ->leftJoin('tb_visa', 'tb_self_listok.id_visa', '=', 'tb_visa.id')
            ->where('tb_self_listok.id', $id)
            ->select('tb_self_listok.*', 'countries.country_name', 'tb_citizens.sp_name04', "tb_visittype.name_uz", 'tb_visa.name as visa_name', 'tb_guests.guesttype_uz as guesttype_name', "tb_self_children.name as children_name", "tb_self_children.gender as gender", "tb_self_children.dateBirth as children_dateBirth")
            ->first();

        return view('selflistok.edit', compact('rowdata'));
    }


    public function getData(Request $request)
    {

        $grp_id = \Session::get('rid', \Auth::user()->roles->pluck('id')->first()) * 1;
        $where = "tb_self_listok.id_hotel=" . \Session::get('hid', \Auth::user()->id_hotel);
        $rememberCriteria = [];

        $d = \DB::table('tb_self_listok')
            ->join('tb_users', 'tb_self_listok.entry_by', '=', 'tb_users.id')
            ->join('tb_citizens', 'tb_self_listok.id_citizen', '=', 'tb_citizens.id')
            ->leftjoin('tb_visa', 'tb_visa.id', '=', 'tb_self_listok.id_visa')
            ->join('tb_hotels', 'tb_users.id_hotel', '=', 'tb_hotels.id')
            ->join('tb_region as rr', 'rr.id', '=', 'tb_hotels.id_region');

        $d->selectRaw("STRAIGHT_JOIN '' as `empty`,tb_self_listok.id,tb_self_listok.regnum, tb_self_listok.id_citizen as ctz,
        tb_citizens.SP_NAME03 as ctzn,tb_self_listok.wdays,tb_self_listok.summa as summa,tb_hotels.name as htl,rr.name as region,
        DATE_FORMAT(tb_self_listok.dateVisitOn,'%d.%m.%Y %H:%i') as dt,CONCAT(tb_self_listok.surname,' ',tb_self_listok.firstname,'
            ',tb_self_listok.lastname) as guest,CONCAT(UPPER(LEFT(tb_users.first_name,1)),'. ',tb_users.last_name) as adm,
            DATE_FORMAT(tb_self_listok.datebirth,'d.m.Y') as datebirth,tb_visa.name as tb_visa,tb_self_listok.visanumber as tb_visanm,
            tb_self_listok.datevisaon as tb_visafrom, tb_self_listok.datevisaoff as tb_visato, tb_self_listok.kppnumber,tb_self_listok.datekpp,
            DATEDIFF('day', tb_self_listok.datevisaoff - NOW()) as expired");

        $dQty = \DB::table('tb_self_listok');


        $rememberCriteria['from'] = '';
        $rememberCriteria['in_from'] = '';
        $rememberCriteria['in_till'] = '';
        $rememberCriteria['dbrth'] = '';
        $rememberCriteria['fname'] = '';
        $rememberCriteria['sname'] = '';
        $rememberCriteria['lname'] = '';
        $rememberCriteria['ps'] = '';
        $rememberCriteria['pn'] = '';
        $rememberCriteria['ctz'] = '';
        $rememberCriteria['htl'] = '';

        if ($pn = $request->get('pn')) {
            $d->where('tb_self_listok.passportNumber', '=', $pn);
            $dQty->where('tb_self_listok.passportNumber', '=', $pn);
            $rememberCriteria['pn'] = $pn;
        }
        if ($where == "2=2" or $grp_id == 6 or $grp_id == 8) {
            if ($htl = $request->get('hid')) {
                $d = $d->where('tb_self_listok.id_hotel', '=', $htl);
                $dQty = $dQty->where('tb_self_listok.id_hotel', '=', $htl);
                $rememberCriteria['htl'] = $htl;
            }
            if ($reg = $request->get('rid')) {
                if (empty($htl)) {
                    $d = $d->where('tb_self_listok.id_region', '=', $reg);
                    $dQty = $dQty->where('tb_self_listok.id_region', '=', $reg);
                }
                $rememberCriteria['reg'] = $reg;
            }
        }
        if ($room = $request->get('room')) {
            $d = $d->where('tb_self_listok.propiska', '=', $room);
            $dQty = $dQty->where('tb_self_listok.propiska', '=', $room);
        }
        if ($regNumber = $request->get('regNum')) {
            $d->where('tb_self_listok.regnum', 'like', "$regNumber%");
            $dQty->where('tb_self_listok.regnum', 'like', "$regNumber%");
        }
        if ($payed = $request->get('payed')) {
            $d->where('tb_self_listok.payed', $payed);
            $dQty->where('tb_self_listok.payed', $payed);
        }
        if ($fname = $request->get('fName')) {
            $d->where('tb_self_listok.firstname', 'like', "$fname%");
            $dQty->where('tb_self_listok.firstname', 'like', "$fname%");
            $rememberCriteria['fname'] = $fname;
        }
        if ($sname = $request->get('sName')) {
            $d->where('tb_self_listok.surname', 'like', "$sname%");
            $dQty->where('tb_self_listok.surname', 'like', "$sname%");
            $rememberCriteria['sname'] = $sname;
        }
        if ($lname = $request->get('lName')) {
            $d->where('tb_self_listok.lastname', 'like', "$lname%");
            $dQty->where('tb_self_listok.lastname', 'like', "$lname%");
            $rememberCriteria['lname'] = $lname;
        }
        if ($ctz = $request->get('ctz')) {
            $d->where('tb_self_listok.id_citizen', $ctz);
            $dQty->where('tb_self_listok.id_citizen', $ctz);
            $rememberCriteria['ctz'] = $ctz;
        }
        try { // check date values ...
            if ($from = $request->get('from')) {
                $datFrom = date_format(DateTime::createFromFormat('d-m-Y', $from), 'Y-m-d');
                $rememberCriteria['from'] = $from;
            }
            if ($till = $request->get('till')) {
                $datTill = date_format(DateTime::createFromFormat('d-m-Y', $till), 'Y-m-d');
                $rememberCriteria['till'] = $till;
            }
            if ($in_from = $request->get('in_from')) {
                $datFrom = date_format(DateTime::createFromFormat('d-m-Y', $from), 'Y-m-d');
                $rememberCriteria['in_from'] = $from;
            }
            if ($in_till = $request->get('in_till')) {
                $datTill = date_format(DateTime::createFromFormat('d-m-Y', $till), 'Y-m-d');
                $rememberCriteria['in_till'] = $till;
            }
            if ($birth = $request->get('dbrth')) {
                $datBirth = date_format(DateTime::createFromFormat('d-m-Y', $birth), 'Y-m-d');
                $rememberCriteria['dbrth'] = $birth;
            }
            if (!empty($from) && !empty($till)) {
                $d->whereRaw("tb_self_listok.datevisiton between '$datFrom 00:00:00' and '$datTill 23:59:59' ");
                $dQty->whereRaw("tb_self_listok.datevisiton between '$datFrom 00:00:00' and '$datTill 23:59:59' ");
            } elseif (!empty($from)) {
                $d->whereRaw("tb_self_listok.datevisiton between '$datFrom 00:00:00' and '$datFrom 23:59:59' ");
                $dQty->whereRaw("tb_self_listok.datevisiton between '$datFrom 00:00:00' and '$datFrom 23:59:59' ");
            } elseif (!empty($till)) {
                $d->whereRaw("tb_self_listok.datevisiton between '$datTill 00:00:00' and '$datTill 23:59:59' ");
                $dQty->whereRaw("tb_self_listok.datevisiton between '$datTill 00:00:00' and '$datTill 23:59:59' ");
            }
            if (!empty($birth)) {
                $d->where('tb_self_listok.datebirth', '=', $datBirth);
                $dQty->where('tb_self_listok.datebirth', '=', $datBirth);
            }
        } catch (\Exception $ex) {}
        $qty = $dQty->count();
        if (count($rememberCriteria) > 0)
            \Session::put('searchListovka', $rememberCriteria);
        return DataTables::of($d)
            ->setTotalRecords($qty)
            ->editColumn('regNum', function ($row) {
                return $row->regnum;
            })
            ->editColumn('ctz', function ($row) {
                return '<img src="' . asset('uploads/flags/' . $row->ctz . '.png') . '" title=\'' . $row->ctzn . '\' width=\'40px\' height=\'24px\' style="text-shadow: 1px 1px;border:1px solid #777;" /><span style="color:transparent;font-size:1px">' . $row->ctzn . '</span>';
            })
            ->setRowData(['data-id' => function ($row) {
                return cryptId($row->id);
            }, 'data-name' => function ($row) {
                return $row->regnum;
            }, 'data-guest' => function ($row) {
                return $row->guest;
            }, 'data-ctz' => function ($row) {
                return $row->ctz;
            }])

            ->filterColumn('guest', function ($query, $keyword) {
                $query->whereRaw("CONCAT(tb_self_listok.surname,' ',tb_self_listok.firstname,' ',tb_self_listok.lastname) like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('adm', function ($query, $keyword) {
                $query->whereRaw("CONCAT(tb_users.first_name,' ',tb_users.last_name) like ?", ["%{$keyword}%"]);
            })

            ->setRowClass(function ($row) {
                if (!$row->tb_visato || $row->expired * 1 > 2)
                    return ' ';
                if ($row->expired * 1 > 0)
                    return 'tb_visa-twodays';

                if ($row->expired * 1 == 0)
                    return 'tb_visa-lastday';

                return 'tb_visa-expired';
            })
            ->rawColumns(['ctz'])
            ->make(true);
    }

    public function getBookTable(Request $request)
    {
        $data['start'] = $start = $request->input('start');
        $data['end'] = $end = $request->input('end');

        $data['dates'] = [];
        $data['month'] = [];

        for ($date = Carbon::parse($start); $date->lte(Carbon::parse($end)); $date->addDay()) {
            $data['dates'][] = $date->format('Y-m-d');
            if (!isset($data['month'][$date->format('m-Y')])){
                $data['month'][$date->format('m-Y')] = 1;
            } else {
                $data['month'][$date->format('m-Y')]++;
            }
        }

        $data['room_types'] = collect(\DB::table('room_types')->get());

        foreach ($data['room_types'] as $room_type)
            $room_type->rooms = collect(\DB::table('rooms')->where('room_type_id', $room_type->id)->where('hotel_id',session('hid', auth()->user()->id_hotel))->orderBy('room_numb','asc')->get());

        // get tb_self_listok where datevisitoff and datevisiton between start and end or datevisitoff is null
        $data['tb_self_listoks'] = \DB::table('tb_self_listok')->where('id_hotel',session('hid', auth()->user()->id_hotel))
            ->join('tb_self_listok_rooms', 'tb_self_listok.id', '=', 'tb_self_listok_rooms.reg_id')
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('datevisitoff', [$start, $end])
                    ->orWhereBetween('datevisiton', [$start, $end])
                    ->orWhere('datevisitoff', null);
            })->select('tb_self_listok.regnum as tb_self_listok_id','tb_self_listok_rooms.room_id', 'datevisiton', 'datevisitoff')->get();

        return view('tb_self_listok.book-table',$data);
    }

    public function show($regnum)
    {
        $regnum = base64_decode($regnum);
        $data['tb_self_listok'] = \DB::table('tb_self_listok')->where('regnum', $regnum)->first();
        $data['tb_citizens'] = collect(self::getComboSelect('tb_citizens:id:SP_NAME03'));
        return view('tb_self_listok.show', $data);
    }

    public function getForm()
    {
        return view('tb_self_listok.form');
    }

    public function postSave(Request $request)
    {
        $data = $request->except(['_token','room']);
        $data['entry_by'] = auth()->user()->id;
        $data['datevisiton'] = Carbon::parse($data['datevisiton'])->format('Y-m-d H:i:s');
        $data['datevisitoff'] = Carbon::parse($data['datevisitoff'])->format('Y-m-d H:i:s');
        $data['id_hotel'] = session('hid', auth()->user()->id_hotel);
        $data['id_region'] = session('rid', 11);
//        $data['id_citizen'] = $data['id_citizen'];
//        $data['entry_by'] = auth()->user()->id;

        $tb_self_listok = \DB::table('tb_self_listok')->insertGetId($data);

        $data['regnum'] = $tb_self_listok.'-'.$data['id_hotel'].'-'.date('Y');
        \DB::table('tb_self_listok')->where('id', $tb_self_listok)->update(['regnum' => $data['regnum']]);
        if ($tb_self_listok) {
            $room = $request->input('room');
            \DB::table('tb_self_listok_rooms')->insert([
                'reg_id' => $tb_self_listok,
                'room_id' => $room,
                'hotel_id' => $data['id_hotel'],
            ]);
            return response()->json(['status' => 'success', 'message' => 'tb_self_listok created successfully']);
        }
        return response()->json(['status' => 'error', 'message' => 'tb_self_listok not created']);
    }

    public function getComboSelect(Request $request)
    {

        $params = $request->input('filter');
        $params = explode(':',$params);
        /*        $limit = explode(':',$limit);
                $parent = explode(':',$parent);*/

        $limit = $parent = [0];

        if(count($limit) >=3)
        {
            $table = strtolower($params[0]);
            $condition = $limit[0]." `".$limit[1]."` ".$limit[2]." ".$limit[3]." ";
            if(count($parent)>=2 )
            {
                //$row =  \DB::table($table)->where($parent[0],$parent[1])->get();
                $row =  \DB::select( "SELECT * FROM ".$table." ".$condition ." AND ".$parent[0]." = '".$parent[1]."'"  . ($table == 'tb_hotels' ? ' order by name' : ''));
            } else  {
                $row =  \DB::select( "SELECT * FROM ".$table." ".$condition . ($table == 'tb_hotels' ? ' order by name' : ''));
            }
        }else{

            $table = $params[0];
            if(count($parent)>=2 )
            {
                if ($table == 'tb_hotels')
                    $row = \DB::table($table)->where($parent[0],$parent[1])
                        ->select($params[1], $params[2])
                        ->orderby('name')->get();
                else
                    $row =  \DB::table($table)->select($params[1], $params[2])->where($parent[0],$parent[1])->get();
            } else  {

                if ($table == 'tb_hotels')
                    $row = \DB::table($table)->select($params[1], $params[2])->orderby('name')->get();
                else
                    $row =  \DB::table($table)->select($params[1], $params[2])->get();
            }
        }
        // $row change keys to index massive numbers
        $i = 0;
        $data = [];
        foreach ($row as $key => $value) {
            $k=0;
            foreach ($value as $v) {
                $data[$i][$k] = $v;
                $k++;
            }
            $i++;
        }


        return $data;
    }


    public function getWizard1()
    {
        return view('tb_self_listok.wizard1');
    }

    public function postCheckout($regnum)
    {
        $regnum = base64_decode($regnum);
        \DB::table('tb_self_listok')->where('regnum', $regnum)->update([
            // date minus one day
            'datevisitoff' => Carbon::parse(now())->subDay()->format('Y-m-d H:i:s'),
        ]);
        $tb_self_listok = \DB::table('tb_self_listok')->where('regnum', $regnum)->first();
        \DB::table('tb_self_listok_rooms')->where('reg_id', $tb_self_listok->id)->where('hotel_id',$tb_self_listok->id_hotel)->delete();
        return response()->json(['status' => 'success', 'message' => 'Checkout success Regnum: '.$regnum]);
    }

    public function check(Request $request)
    {
        $data = $request->all();
        if (isset($data['citezenID'])) $data['country'] = $data['citezenID'];

        if (isset($data['passportNumber'])) {
            $data['passport'] = $data['passportNumber'];
            $data['psp'] = $data['passportNumber'];
            $data['dtb'] = $data['dtb'];
        }

        $PersonID_SGB = PersonInfo::gotPersonID_SGB_REMOTE($data);

        if (!$PersonID_SGB)  return response()->json(['message' => 'Данный гость по Вашему запросу не найден! Просим Вас сделать корректировку.', 'status' => 'error', 'person' => ['checking' => 0]]);
        if (!is_array($PersonID_SGB)) $PersonID_SGB = json_decode($PersonID_SGB, true);
        if (!(isset($PersonID_SGB['person_id']) && $PersonID_SGB['person_id'])) return response()->json(['message' => 'Данный гость по Вашему запросу не найден! Просим Вас сделать корректировку.', 'status' => 'error', 'person' => ['checking' => 0]]);

        $p_name = explode(" ", $PersonID_SGB['name']);
        $PersonID_SGB['surname'] = $p_name[0];
        $PersonID_SGB['firstname'] = isset($p_name[1]) ? $p_name[1] : 'XXX';
        $PersonID_SGB['lastname'] = implode(" ", array_slice($p_name, 2, 2));

        $sgbArr = [
            'person_id' => base64_encode($PersonID_SGB['person_id']),
            'checking' => 1,
            'firstname' => $PersonID_SGB['firstname'],
            'surname' => $PersonID_SGB['surname'],
            'lastname' => $PersonID_SGB['lastname'],
            'birth_date' => $PersonID_SGB['birth_date'],
            'sex' => $PersonID_SGB['sex']
        ];

        $gotKOGG = PersonInfo::gotLAST_KOGG_REMOTE($PersonID_SGB['person_id']);

        if (!$gotKOGG) {
            return response()->json(['message' => 'Данный гость по Вашему запросу не найден в базе Погран.службы! Просим Вас сделать корректировку.', 'status' => 'error', 'person' => ['checking' => 0]]);
        }


        return response()->json(['message' => 'Гость найден!', 'status' => 'success', 'person' => array_merge($sgbArr, $gotKOGG)]);

    }


    public function savedata(Request $request)
    {
        $data = [
            'PassportIssuedBy' => $request->get('passportissuedby'),
            'surname' => $request->get('surname'),
            'firstname' => $request->get('firstname'),
            'lastname' => $request->get('lastname'),
            'id_country' => 1,
            'id_countryFrom' => 1,
            'id_passporttype' => 1,
            'dateVisitOn' => $request->get('datevisiton'),

            'regNum' => '000000',
            'datebirth' => '1900-01-01',
            'id_citizen' => 1,
            'sex' => 'M',
            'id_visitType' => 1,
            'dateVisitOff' => '1900-01-01',
            'passportSerial' => '',
            'passportNumber' => '000000',
            'datePassport' => '1900-01-01',
            'id_visa' => 1,
            'visaNumber' => '',
            'dateVisaOn' => '1900-01-01',
            'dateVisaOff' => '1900-01-01',
            'visaIssuedBy' => '',
            'kppNumber' => '',
            'dateKPP' => '1900-01-01',
            'id_guest' => 1,
            'amount' => 0.00,
            'entry_by' => 1,
            'a_id_region' => null,
            'a_id_district' => null,
            'a_address' => '',
            'a_route' => '',
            'created_at' => now(),
            'updated_at' => now(),
            'wdays' => 0,
            'id_hotel' => 1,
            'id_region' => 1,
            'summa' => 0.00,
            'canceled' => null,
            'person_id' => null,
            'hsh' => null,
        ];

        if (\DB::table('tb_self_listok')->insert($data)) {
            session()->flash('success', 'Данные успешно сохранены');
            return response()->json(['status' => 'success']);
        } else {
            return response()->json(['status' => 'error']);
        }
    }

    public function destroy(Request $request)
    {
        $ids = $request->input('ids');
        if (empty($ids) || !is_array($ids)) {
            return response()->json(['error' => 'Не выбраны элементы для удаления.'], 400);
        }

        try {

            \DB::table('tb_self_listok')->whereIn('id', $ids)->delete();

            return response()->json(['success' => 'Элементы успешно удалены.']);
        } catch (\Exception $e) {
            \Log::error("Ошибка при удалении элементов: " . $e->getMessage());

            return response()->json(['error' => 'Ошибка при удалении элементов.'], 500);
        }

    }


    public function update(Request $request)
    {
        $data = $request->all();
        if (empty($data['id'])) {
            return response()->json(['error' => 'Не передан идентификатор регистрации.'], 400);
        }
        $currentRecord = \DB::table('tb_self_listok')->where('id', $data['id'])->first();
        if ($currentRecord) {
            $isUpdated = false;
            foreach ($data as $key => $value) {
                if (isset($currentRecord->$key) && $currentRecord->$key != $value) {
                    $isUpdated = true;
                    break;
                }
            }
            if (!$isUpdated) {
                return response()->json(['status' => 'success', 'message' => 'Данные не были изменены']);
            }
        }

        $affected = \DB::table('tb_self_listok')
            ->where('id', $data['id'])
            ->update([
                'datePassport' => $data['passportissuedby'],
                'surname' => $data['surname'],
                'firstname' => $data['firstname'],
                'lastname' => $data['lastname'],
                'id_country' => $data['id_country'],
                'id_countryFrom' => $data['id_countryfrom'],
                'sex' => $data['sex'],
                'dateVisitOn' => $data['datevisiton'],
                'wdays' => $data['stay_days'],
                'id_visitType' => $data['id_visitType'],
                'id_visa' => $data['id_visa'],
                'kppNumber' => $data['kpp_number'],
                'dateKPP' => $data['kpp_date'],
                'summa' => $data['payment_amount'],
                'id_guest' => $data['id_guest']
            ]);

        if ($affected) {
            $updatedRecord = \DB::table('tb_self_listok')->where('id', $data['id'])->first();
            session()->flash('success', 'Данные успешно обновлены');
            return response()->json(['status' => 'success', 'updated_data' => $updatedRecord]);
        } else {
            return response()->json(['error' => 'Запись не найдена'], 400);
        }
    }




}
