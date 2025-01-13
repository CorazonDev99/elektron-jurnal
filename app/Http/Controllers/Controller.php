<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Cache;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    private $data = [];

    public function __construct() {
//        $auth_user = \Auth::user();
//        if (\Auth::check() == true) {
//            if (!\Session::get('gid',false) || !\Session::get('rid',false)) {
//                $id_region = \DB::table('hotels')->where('id', $auth_user->id_hotel)->first();
//                \Session::put('uid', $auth_user->id);
//                \Session::put('gid', $auth_user->group_id);
//                \Session::put('rid', $id_region->id_region);
//                \Session::put('hid', $auth_user->id_hotel);
//                \Session::put('eid', $auth_user->email);
//                \Session::put('ll',  $auth_user->last_login);
//                \Session::put('fid', $auth_user->first_name . ' ' . $auth_user->last_name);
//                \Session::put('grant_type', $auth_user->grant_type);
//                // added from PIU-Computer
//                \Session::put('htlname', $id_region->name);
//                \Session::put('themes', 'sximo-light-blue');
//            }
//        }
//        if (!\Session::get('themes')) \Session::put('themes', 'sximo');
//
//        \App::setLocale(session('locale', 'ru'));
///*        if (defined('CNF_MULTILANG') && CNF_MULTILANG == '1') {
//            $lang = (\Session::get('lang') != "" ? \Session::get('lang','ru') : CNF_LANG);
//            \App::setLocale($lang);
//        }*/
//        $data = [
//            'last_activity' => strtotime(Carbon::now()),
//            'last_login' => date('Y-m-d H:i:s')
//        ];
//        \DB::table('tb_users')->where('id', \Session::get('uid',0))->update($data);
//        \Session::put('ll',  date('Y-m-d H:i:s'));
    }

    function getComboselect(Request $request) {
        if ($request->ajax() == true && \Auth::check() == true) {
            $param = explode(':', $request->input('filter'));
            $parent = (!is_null($request->input('parent')) ? $request->input('parent') : null);
            $limit = (!is_null($request->input('limit')) ? $request->input('limit') : null);
            if ('citizens:id:SP_NAME03' == $request->input('filter')) {
                $this->data['citezens'] = Cache::rememberForever('citezens__023', function () {
                    return \DB::select("select id, CONCAT(SP_NAME03, ' (', SP_IDN, ')') as name from citizens where id not in (242,243,245,161) and SP_ACTIVE=1 order by SP_NAME03");
                });
                $items = array();
                foreach ($this->data['citezens'] as $row) {
                    $value = "";
                    $value .= $row->name . " ";
                    $items[] = array($row->{$param['1']}, $value);
                }
                return json_encode($items);
            }
            $rows = self::comboselect($param, $limit, $parent);
            $items = array();
            $fields = explode("|", $param[2]);
            foreach ($rows as $row) {
                $value = "";
                foreach ($fields as $item => $val) {
                    if ($val != "") $value .= $row->{$val} . " ";
                }
                $items[] = array($row->{$param['1']}, $value);
            }
            return json_encode($items);
        } else {
            return json_encode(array('OMG' => " Ops .. Cant access the page !"));
        }
    }

    public static function comboselect( $params , $limit =null, $parent = null)
    {
        $limit = explode(':',$limit);
        $parent = explode(':',$parent);

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
                    $row = \DB::table($table)->where($parent[0],$parent[1])->orderby('name')->get();
                else
                    $row =  \DB::table($table)->where($parent[0],$parent[1])->get();
            } else  {

                if ($table == 'tb_hotels')
                    $row = \DB::table($table)->orderby('name')->get();
                else
                    $row =  \DB::table($table)->get();
            }
        }
        return $row;
    }

}
