<?php

namespace App\Repository;

use Carbon\Carbon;

class PersonInfo
{

    public static function gotPersonID_SGB_REMOTE($data)
    {
        if (env('IS_IDE', 0) == 1) return json_decode('{"person_id":3121321,"name":"KUNISHEVA MUHABAT PALONCHIEVNA","birth_date":"03.02.1990","sex": 2,"visaNumber":"2878117", "dateVisaOn":"2017-03-31", "dateVisaOff":"2017-05-02", "visaIssuedBy": "Paris"}', true);
        try {
            $data = json_encode($data, JSON_UNESCAPED_UNICODE);
            $url = "http://10asdasd/insotranec-info";
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($data)));
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $output = curl_exec($ch);
            curl_close($ch);
            return $output;
        } catch (\Exception $ex) {
            \Log::info('GOT_PERSON_ID: Exception catched - ' . $ex->getMessage());
        }
    }



    public static function gotLAST_KOGG_REMOTE($person_id)
    {
        $arr = [];
        if (env('IS_IDE', 0) == 1) {
            $arr = [
                "last_checkin" => ["id" => 263572864, "reg_date" => "2023-04-28 19:31:00", "person_id" => 6040482, "point_code" => "59", "direction_country" => 179, "full_name" => "SABUROVA FARZONA", "birth_date" => "2016-01-01", "sex" => 2, "citizenship" => 179, "document" => "403528102", "date_end_document" => "2025-09-21"
                ],
                "last_checkout" => ["id" => 262871109, "reg_date" => "2023-04-23 18:12:49", "person_id" => 6040482, "point_code" => "175", "direction_country" => 179, "full_name" => "SABUROVA FARZONA", "birth_date" => "2016-01-01", "sex" => 2, "citizenship" => 179, "document" => "403528102", "date_end_document" => "2025-09-21"]
            ];
            return $arr;
        }

        $start_time = microtime(true);


        $person = [];
        $d = [];
        try {
            $url = "http:/qweqwe/insotranec-kogg/$person_id";
            $ch = curl_init();
            \Log::debug('MVD_IC_RESULT_GET: .' . $url);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $result = curl_exec($ch);
            $returnCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if (!$result) {
                \Log::error('LAST_KOGG_REMOTE: ' . $person_id . ' -> not found');
                return null;
            }
            curl_close($ch);
            $d = json_decode($result, true);
            if ($returnCode == 200 && $d && isset($d['Data'])) $d = $d['Data'];

        } catch (\Exception $ex) {
            \Log::info('GOT_PERSON_ID: Exception catched - ' . $ex->getMessage());
        } finally {
            $end_time = microtime(true);
            $execution_time = ($end_time - $start_time);
            \Log::debug('LAST_KOGG_REMOTE: ' . $person_id . ' -> ' . $execution_time);
        }
        \Log::debug('LAST_KOGG_REMOTE: ' . $person_id . ' -> ' . json_encode($d));


        $sql = "select vp.visa_id, vs.visa_number, vs.type, vs.number_of_entries, vs.date_from, vs.date_until, vs.period_at_days, vs.give_date from visa_persons as vp join visa_sync as vs on vp.visa_id = vs.visa_id where vp.person_id = $person_id order by vp.visa_id desc limit 1";

        $person['border_in'] = '';
        $person['border_code'] = '';
        $person['country_from'] = '';
        $person['border_out'] = '';
        $person['date_end_document'] = '';
        $person['last_checkin'] = '';
        $person['last_checkout'] = '';

        if ($d && $d['last_checkin']) {
            $person['border_in'] = Carbon::createFromFormat("Y-m-d H:i:s", $d['last_checkin']['reg_date'])->format('d-m-Y');
            $person['border_code'] = $d['last_checkin']['point_code'];
            $person['country_from'] = self::sgbCTZ_R($d['last_checkin']['direction_country']);
            $person['border_out'] = $d['last_checkout'];
            if (isset($d['last_checkin']['date_end_document']) && $d['last_checkin']['date_end_document']) {
                $person['date_end_document'] = Carbon::createFromFormat("Y-m-d", $d['last_checkin']['date_end_document'])->format('d-m-Y');
            }
            $person['last_checkin'] = $d['last_checkin'];
            $person['last_checkout'] = $d['last_checkout'];
        }

        $q = \DB::connection('mysql_ison')->select(\DB::raw($sql));

        if ($q && count($q) > 0) {
            $visa = $q[0];
            $visaId = \DB::table('tb_visa')->select('id')->where("name", $visa->type)->first();
            if ($visaId) $visa->type = $visaId->id;

            if ($visa && isset($visa->type)) {
                $person['visa_number'] = $visa->visa_number;
                $person['visa_type'] = $visa->type;
                $person['visa_krat'] = $visa->number_of_entries;
                $person['visa_from'] = Carbon::createFromFormat("Y-m-d", $visa->date_from)->format('d-m-Y');
                $person['visa_until'] = Carbon::createFromFormat("Y-m-d", $visa->date_until)->format('d-m-Y');
                $person['visa_days'] = $visa->period_at_days;
                $person['visa_give'] = Carbon::createFromFormat("Y-m-d", $visa->give_date)->format('d-m-Y');
            }

        }
        return $person;
    }


}
