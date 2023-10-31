<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Helper;
use App\Helper\Method;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Attendance\AttendanceRequest;
use App\Http\Requests\AttendanceImportRequest;
use App\Imports\AttendanceImport;
use App\Models\StudentAttendanceModel;
use App\Models\StudentsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;
use function request;
use function view;

class AttendanceController extends Controller
{
    public function Import(){
        $page = ['main' => 'Attendance', 'sub_menu' => 'Import', 'child_menu' => ''];
        return view('Admin.AttendanceImport',compact('page'));
    }

    public function ImportStore(AttendanceImportRequest $request){
        if (!$request->has('attendance_file')) {
            return array("status" => false, "data" => '', 'message' => 'Please choose any one excel file');
        }
        ini_set('max_execution_time', -1);
        ini_set('memory_limit', '512M');
        DB::beginTransaction();
        try {
            $data = Excel::toCollection(new AttendanceImport(), request()->file('attendance_file'));
            $fun = Helper::BranchDetails()->attendance_method;
            if(empty($fun)){
                $fun = 'DefaultMethod';
            }
            $method = Method::$fun($data);
            if(!$method['status']){
                return $method;
            }
//            return true;
            $response = array("status" => true, "statusCode" => '100', "data" => ['students' => $method['data']['students'], "present" => $method['data']['upload_count']], 'message' => 'Successfully Save');
            DB::commit();
        }catch (Exception $exception){
            $response = array("status" => false, "statusCode" => '102', "data" => '', 'message' => $exception->getMessage());
            DB::rollBack();
        }
        return $response;
    }

    public function AttendanceSingleStore(Request $request){
//        return $request->all();
        $student = [
            "TraineeID" => $request->TraineeID,
            "InTime" => $request->InTime,
            "OutTime" => $request->OutTime,
            "Duration" => $request->Duration,
            "date" => $request->attendance_date,
        ];
        $attendance = ["in_attendance" => false];
        $client = new \GuzzleHttp\Client([
            'headers' => ['Content-Type' => 'application/json']
        ]);
        $parameters = Helper::GetApiUrl($request->TraineeID, $request->InTime, $request->OutTime, $request->Duration, $request->attendance_date);
        if(!empty($request->InTime)) {
            if(!Helper::CheckStudentAttendance($request->TraineeID, $request->InTime, $request->OutTime, $request->Duration, $request->attendance_date)) {
                $response = $client->post(config('global.attendance_api_url'),['json' => $parameters]);
                $response = (json_decode($response->getBody()));
                if ($response->Status != 0) {
                    $attendance['in_attendance'] = true;
                    Helper::StudentAttendanceStore($request->TraineeID, $request->InTime, $request->OutTime, $request->Duration, $request->attendance_date);
                }
            }else{
                $attendance['in_attendance'] = true;
            }
        }

        return array("status" => true, "statusCode" => '100', "data" => ['student' => $student, "attendance" => $attendance], 'message' => 'Successfully Save');
    }

    public function AttendanceStore(Request $request){
        ini_set('max_execution_time', -1);
        ini_set('memory_limit', '512M');
        $unregistered_student   = [];
        $error_student_in = [];
        $error_student_out = [];
        $already_student_in = [];
        $already_student_out = [];
        $client = new \GuzzleHttp\Client();
        foreach ($request->TraineeID as $key => $value) {
            $student = StudentsModel::where(['TraineeID' => $request->TraineeID[$key]])->first();
            if (!$student) {
                array_push($unregistered_student, $request->TraineeID[$key]);
            }
        }
        if(count($unregistered_student) > 0){
            return array("status" => false, "statusCode" => '111', "data" => ['students' => $unregistered_student], 'message' => 'Attendance list has unregistered student. Please check all list before upload');
        }
        foreach ($request->TraineeID as $key => $value) {
            $url = Helper::GetApiUrl($request->TraineeID[$key], $request->InTime[$key], $request->OutTime[$key], $request->attendance_date);
            if(!empty($url['inTime_url'])) {
                if(!Helper::CheckStudentAttendance($request->TraineeID[$key], $request->InTime[$key], $request->attendance_date)) {
                    $response = $client->request('GET', $url['inTime_url']);
                    $response = (json_decode(json_decode($response->getBody())));
                    print_r($response);
                    if ($response == 0) {
                        array_push($error_student_in, $request->TraineeID[$key]);
                    } else {
                        Helper::StudentAttendanceStore($request->TraineeID[$key], $request->InTime[$key], $request->attendance_date, 'In');
                    }
                }else{
                    array_push($already_student_in, $request->TraineeID[$key]);
                }
            }
            if(!empty($url['outTime_url'])) {
                if(!Helper::CheckStudentAttendance($request->TraineeID[$key], $request->OutTime[$key], $request->attendance_date)) {
                    $response = $client->request('GET', $url['outTime_url']);
                    $response = (json_decode(json_decode($response->getBody())));
                    print_r($response);
                    if ($response == 0) {
                        array_push($error_student_out, $request->TraineeID[$key]);
                    } else {
                        Helper::StudentAttendanceStore($request->TraineeID[$key], $request->OutTime[$key], $request->attendance_date, 'Out');
                    }
                }else{
                    array_push($already_student_out, $request->TraineeID[$key]);
                }
            }
        }
        if(count($error_student_in) > 0 || count($error_student_out) > 0) {
            return array("status" => false, "statusCode" => '112', "data" => ['in_students' => $error_student_in, 'out_students' => $error_student_out, 'already_in_students' => $already_student_in, 'already_out_students' => $already_student_out], 'message' => 'Some students In/Out attendance has been not uploaded');
        }
        return array("status" => true, "statusCode" => '200', "data" => ['already_in_students' => $already_student_in, 'already_out_students' => $already_student_out], 'message' => 'Successfully uploaded students attendance');
    }

    public function Attendance(Request $request){
        $page = ['main' => 'Attendance', 'sub_menu' => 'Attendance', 'child_menu' => ''];
        $batches = DB::table('batches')->where(['branch_id' => Auth::user()->branch_id])->orderBy('created_at','desc')->get();
        $courses = DB::table('courses')->where(['branch_id' => Auth::user()->branch_id])->orderBy('created_at','desc')->get();
        if ($request->ajax()) {
            $data = StudentAttendanceModel::select('*')
                ->where(['branch_id' => Auth::user()->branch_id])
                ->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
                    if ($request->has('start_date')) {
                        if($request->get('start_date') != 0) {
                            $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                                $timestamp = explode('/',$row['PunchDate']);
                                $timestamp = strtotime($timestamp[2]."-".$timestamp[1]."-".$timestamp[0]);
                                $PunchDate = date('Y-m-d', $timestamp);
                                $startDate = date('Y-m-d', strtotime($request->start_date));
                                if (($startDate <= $PunchDate)){
                                    return true;
                                }else{
                                    return false;
                                }
                            });
                        }
                    }
                    if ($request->has('end_date')) {
                        if ($request->get('end_date') != 0) {
                            $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                                $timestamp = explode('/',$row['PunchDate']);
                                $timestamp = strtotime($timestamp[2]."-".$timestamp[1]."-".$timestamp[0]);
                                $PunchDate = date('Y-m-d', $timestamp);
                                $startDate = date('Y-m-d', strtotime($request->end_date));
                                if (($startDate >= $PunchDate)) {
                                    return true;
                                } else {
                                    return false;
                                }
                            });
                        }
                    }
                    if ($request->has('course')) {
                        if($request->get('course') != "0") {
                            $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                                return Str::contains($row['CourseId'], $request->get('course')) ? true : false;
                            });
                        }
                    }
                    if ($request->has('batch')) {
                        if($request->get('batch') != "0") {
                            $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                                return Str::contains($row['BatchID'], $request->get('batch')) ? true : false;
                            });
                        }
                    }
                },true)
                ->editColumn('created_at', '{{date("d/m/Y H:i",strtotime($created_at))}}')
                ->editColumn('PunchDate', '{{date("d/m/Y",strtotime($PunchDate))}}')
                ->editColumn('PunchIn', '{{date("H:i",strtotime($PunchIn))}}')
                ->editColumn('PunchOut', '{{date("H:i",strtotime($PunchOut))}}')
                ->editColumn('Duration', '{{date("H:i",strtotime($Duration))}}')
                ->make(true);
        }
        return view('Admin.Attendance',compact('page','batches','courses'));
    }

    public function Direct(){
        $page = ['main' => 'Attendance', 'sub_menu' => 'Direct', 'child_menu' => ''];
        $trainees = DB::table('students')
            ->where(['branch_id' => Auth::user()->branch_id])
            ->orderBy('TraineeName', 'ASC')
            ->get();
        return view('Admin.AttendanceDirect',compact('page', 'trainees'));
    }

    public function DirectStore(AttendanceRequest $request){
        DB::beginTransaction();
        try{
            $client = new \GuzzleHttp\Client();
            $url = Helper::getAttendanceUploadURL($request->trainee, $request->punch_time, $request->punch_date);
            if(!Helper::CheckStudentAttendance($request->trainee, $request->punch_time, $request->punch_date)) {
                $response = $client->request('GET', $url);
                $response = (json_decode(json_decode($response->getBody())));
                if ($response != 0) {
                    Helper::StudentAttendanceStore($request->trainee, $request->punch_time, $request->punch_date, $request->punch_type);
                }else {
                    return array("status" => false, "statusCode" => '100', "data" => '', 'message' => 'Attendance not uploaded,Try again later');
                }
            }else{
                return array("status" => false, "statusCode" => '100', "data" => '', 'message' => 'Attendance has already taken for this student');
            }
            $response = array("status" => true, "statusCode" => '100', "data" => '', 'message' => 'Attendance successfully uploaded');
            DB::commit();
        }catch (\Exception $exception){
            $response = array("status" => false, "statusCode" => '102', "data" => '', 'message' => $exception->getMessage());
            DB::rollBack();
        }
        return $response;
    }
}
