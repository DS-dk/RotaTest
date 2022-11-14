<?php

namespace App\Http\Controllers;

use App\Models\Rota;
use App\Models\Shift;
use App\Models\Staff;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Throwable;

class RotaController extends Controller
{
    public function __construct()
    {
        $this->WeekDate = date('Y-m-d', strtotime(now()));
    }

    public function index(){

        $data['employee'] = Staff::all();
        $data['rotaData'] = Rota::all();
        $data['calculatedData'] = $this->CalculateManningMinutes();
        return view('rotaCalculate',$data);
    }

    private function CalculateManningMinutes(){
        $result = array();
        $getData = Rota::all();
        if($getData){
            foreach ($getData as $ky=>$rotaData){
                $getShiftData = Shift::where('rota_id',$rotaData->id)->get();
                if($getShiftData != null){
                    $totalMinutes = 0;
                    foreach ($getShiftData as $key=>$goingToSort){
                        $result[$ky][$key]['week_commence_date'] = $rotaData->week_commence_date;
                        $result[$ky][$key]['name'] = $this->getStaffName($goingToSort->staff_id);
                        $start_date = new DateTime($goingToSort->start_time);
                        $since_start = $start_date->diff(new DateTime($goingToSort->end_time));
                        $minutes = $since_start->h * 60;
                        $minutes += $since_start->i;
                        $totalMinutes += $minutes;
                        $result[$ky][$key]['minutes'] = $minutes;
                        $result[$ky][$key]['startTime'] = $goingToSort->start_time;
                        $result[$ky][$key]['EndTime'] = $goingToSort->end_time;
                    }
                }
                if($totalMinutes > 0){
                    $result[$ky]['totalMinutes'] = $totalMinutes;
                }
            }
        }
        return  $result;
    }

    private function getStaffName($id){
        $data = Staff::where('id',$id)->first();
        return $data->first_name.' '.$data->surname;
    }

    public function getEmployee(){
        return  Staff::all();
    }

    public function getCalculatedData(){
        return $this->CalculateManningMinutes();
    }

    public function storeShifts(Request $request){

        $validator = Validator::make( $request->all(), [
            'staff_id' => ['bail', 'required'],
            'rota_id' => ['bail', 'required'],
            'shiftStartTime' => ['required','bail'],
            'shiftEndTime' => ['required','bail']
        ],
            [
                'staff_id.required'=> 'Staff Name is required',
                'rota_id.required'=> 'Rota Date is required',
                'shiftStartTime.required'=> 'Shift Start Time is required',
                'shiftEndTime.required'=> 'Shift End Time is Required'
            ]
        );
        $req = $request->all();
        if($validator->stopOnFirstFailure()->fails()){
            $flattened = Arr::flatten($validator->getMessageBag()->getMessages());
            return Redirect::back()->withErrors(array_shift($flattened));
        }
        //Store Shift
        try {
            Shift::Create([
                'rota_id' => $req['rota_id'],
                'staff_id' => $req['staff_id'],
                'start_time' => date('Y-m-d H:i:s', strtotime($req['shiftStartTime'])),
                'end_time' => date('Y-m-d H:i:s', strtotime($req['shiftEndTime'])),
            ]);
            return redirect()->back()->with('message','Details have been processed successfully');
        } catch (Throwable $e) {
            return Redirect::back()->withErrors($e);
        }
    }

    public function postData(Request $request){

        $req = $request->all();
        if(count($req) > 0 ){

            $result = array();
            $totalMinutes = 0;
            foreach ($req as $ky=>$rotaData){
                $result[$ky] = $rotaData;
                $start_date = new DateTime($rotaData['shiftStartTime']);
                $since_start = $start_date->diff(new DateTime($rotaData['shiftEndTime']));
                $minutes = $since_start->h * 60;
                $minutes += $since_start->i;
                $totalMinutes += $minutes;
                $result[$ky]['SingleManning'] = $minutes;
                $result[$ky]['week_commence_date'] = date('Y-m-d', strtotime($rotaData['shiftStartTime']));
            }

            if($totalMinutes > 0){
                $result['TotalManning'] = $totalMinutes;
            }

            return $result;
        }
        return false;
    }
}
