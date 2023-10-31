<?php

namespace App\Helper;

use App\Models\BatchesModel;
use App\Models\StudentAttendanceModel;
use App\Models\StudentsModel;
use App\Models\SuperAdmin\Branch\BranchModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class Helper {
    public static function GetApiUrl($traineeId, $inTime, $outTime, $Duration, $attendance_date){
        $parameters =[];
        $attendance_date = date('Y-m-d H:i:s', strtotime($attendance_date));
        $student = StudentsModel::where(['TraineeID' => $traineeId])->first();
        if($student) {
            $para = [];
            if(!empty($inTime)) {
                $para['TrainingCenterID'] = $student->TrainingCenterID;
                $para['BatchID'] = $student->BatchID;
                $para['TraineeID'] = $student->TraineeID;
                $para['PunchDate'] = $attendance_date;
                $para['PunchIn'] = $inTime;
                $para['PunchOut'] = $outTime;
                $para['Duration'] = $Duration;
                array_push($parameters, $para);
            }
        }
        return $parameters;
    }

    public static function getAttendanceUploadURL($traineeId, $time, $attendance_date){
        $parameter = "";
        $student = StudentsModel::where(['TraineeID' => $traineeId])->first();
        if($student) {
            if(!empty($time)) {
                $time = date('H:i:s', strtotime($time));
                $parameter .= "TrainingCenterID=" . $student->TrainingCenterID;
                $parameter .= "&BatchID=" . $student->BatchID;
                $parameter .= "&TrainingCenterName=" . $student->TrainingCenterName;
                $parameter .= "&BatchPrefix=" . $student->BatchPrefix;
                $parameter .= "&UserName=" . self::BranchDetails()->UserName;
                $parameter .= "&Password=" . Crypt::decrypt(self::BranchDetails()->Password);
                $parameter .= "&PunchDate=" . $attendance_date;
                $parameter .= "&PunchTime=" . $time;
                $parameter .= "&Field1=" . null;
                $parameter .= "&Field2=" . null;
                $parameter .= "&CreateUserID=0";
                $parameter .= "&CourseId=" . $student->CourseId;
                $parameter .= "&CourseName=" . $student->CourseName;
                $parameter .= "&TraineeID=" . $traineeId;
                $parameter .= "&TraineeName=" . $student->TraineeName;
                $parameter .= "&EnrollmentNo=" . $student->EnrollmentNo;

            }
        }
        return $request_url = config('global.attendance_api_url') . $parameter;;
    }

    public static function StudentAttendanceStore($traineeId, $PunchIn, $PunchOut, $Duration, $attendance_date){
        $student = StudentsModel::where(['TraineeID' => $traineeId])->first();
        if($student){
            $data = new StudentAttendanceModel();
            $data->branch_id = Auth::user()->branch_id;
            $data->TrainingCenterID = $student->TrainingCenterID;
            $data->BatchID = $student->BatchID;
            $data->TrainingCenterName = $student->TrainingCenterName;
            $data->BatchPrefix = $student->BatchPrefix;
            $data->PunchDate = $attendance_date;
            $data->PunchIn = $PunchIn;
            $data->PunchOut = $PunchOut;
            $data->Duration = $Duration;
            $data->CourseId = $student->CourseId;
            $data->CourseName = $student->CourseName;
            $data->TraineeID = $student->TraineeID;
            $data->TraineeName = $student->TraineeName;
            $data->EnrollmentNo = $student->EnrollmentNo;
            $data->created_at = date('Y-m-d H:i:s');
            $data->created_by = Auth::user()->id;
            $data->save();
        }
    }

    public static function CheckStudentAttendance($traineeId, $PunchIn, $PunchOut, $Duration, $attendance_date){
        $student = StudentsModel::where(['TraineeID' => $traineeId])->first();
        if($student){
            $in_check = DB::table('attendance')
                ->where([
                    'TrainingCenterID' =>$student->TrainingCenterID,
                    'BatchID' =>$student->BatchID,
                    'TrainingCenterName' =>$student->TrainingCenterName,
                    'BatchPrefix' =>$student->BatchPrefix,
                    'PunchDate' =>$attendance_date,
                    'PunchIn' =>$PunchIn,
                    'PunchOut' =>$PunchOut,
                    'Duration' =>$Duration,
                    'CourseId' =>$student->CourseId,
                    'CourseName' =>$student->CourseName,
                    'TraineeID' =>$student->TraineeID,
                    'TraineeName' =>$student->TraineeName,
                    'EnrollmentNo' =>$student->EnrollmentNo,
                    ])->count();
           if($in_check > 0){
               return true;
           }else{
               return false;
           }
        }
    }

    public static function Duration($date1, $date2) {
        $datetime1 = new \DateTime($date1);
        $datetime2 = new \DateTime($date2);
        $interval = $datetime1->diff($datetime2);
        return $interval->h.":".$interval->i;
    }

    public static function TotalCourse(){
        $data = DB::table('courses');
        if(Auth::user()->user_type == 'Admin'){
            $data = $data->where(['branch_id' => Auth::user()->branch_id]);
        }
        $data = $data->count();
        return $data;
    }

    public static function TotalBatch(){
        $data = DB::table('batches');
        if(Auth::user()->user_type == 'Admin'){
            $data = $data->where(['branch_id' => Auth::user()->branch_id]);
        }
        $data = $data->count();
        return $data;
    }

    public static function TotalStudent(){
        $data = DB::table('students');
        if(Auth::user()->user_type == 'Admin'){
            $data = $data->where(['branch_id' => Auth::user()->branch_id]);
        }
        $data = $data->count();
        return $data;
    }

    public static function BranchDetails(){
//        echo Auth::user()->branch_id;
        return DB::table('branch')->where(['id' => Auth::user()->branch_id])->first();
//        return BranchModel::find(Auth::user()->branch_id);
    }


    public static function getStringBetween($str, $start, $end)
    {
        $pos1 = strpos($str, $start);
        $pos2 = strpos($str, $end);
        return substr($str, $pos1+7, $pos2-($pos1+7));
    }

    public static function NewStudentAvailable(){
        $count = 0;
        $client = new \GuzzleHttp\Client();
        $request_url = config('global.student_api_url')."Username=".Helper::BranchDetails()->UserName."&Password=".Crypt::decrypt(Helper::BranchDetails()->Password);
        $response = $client->request('GET', $request_url);
        $students = (json_decode($response->getBody()));
//        print_r($students);
        if ($students != 0) {
            foreach ($students as $key => $student){
                $check = StudentsModel::where(['TraineeID' => $student->TraineeID])->count();
                if($check == 0) {
                    $count++;
                }
            }
        }
        return $count;
    }

    public static function ShrimadRajchandraMissionMethod($data){
        $students = [];
        if($data[0][0][1] != 'Daily Attendance Report (Basic Report)'){
            return array("status" => false, "data" => '', 'message' => 'Please choose valid attendance excel file');
        }
        $attendance_date = date('Y-m-d',strtotime($data[0][5][4]));
        $size = count($data[0]);
        $upload_count=$size - 9;
        for($i = 9; $i < $size; $i++){
            $Is_InAttendance = "";
            $attendance = false;
            $PunchIn = date('Y-m-d H:i:s', strtotime($attendance_date." ".$data[0][$i][6]));
            $PunchOut = date('Y-m-d H:i:s', strtotime($attendance_date." ".$data[0][$i][7]));

            $Duration = Helper::Duration($PunchIn, $PunchOut);
            $Duration = date('Y-m-d H:i:s', strtotime($attendance_date." ".$Duration));
            if(Helper::CheckStudentAttendance($data[0][$i][2], $PunchIn, $PunchOut, $Duration, $attendance_date)){
                $attendance = true;
                $upload_count--;
                $Is_InAttendance = "already-attendance";
            }

            if(empty($data[0][$i][6]) || $data[0][$i][6] == "00:00"){
                $Is_InAttendance = "absent-attendance";
                $attendance = true;
                $upload_count--;
                $PunchIn = "";
            }

            if(empty($data[0][$i][7]) || $data[0][$i][7] == "00:00") {
                $Is_InAttendance = "absent-attendance";
                $attendance = true;
                $PunchOut = "";
                $Duration = "";
                if(!(empty($data[0][$i][6]) || $data[0][$i][6] == "00:00")) {
                    $upload_count--;
                }
            }
            $std = [
                "TraineeID" => $data[0][$i][2],
                "Name" => $data[0][$i][3],
                "InTime" => $PunchIn,
                "OutTime" => $PunchOut,
                "Duration" => $Duration,
                "Date" => $attendance_date,
                "ShowDate" => date('d/m/Y',strtotime($attendance_date)),
                "Is_InAttendance" => $Is_InAttendance,
                "Attendance" => $attendance,
            ];
            array_push($students, $std);
        }
        $std = ["students" => $students, "upload_count" => $upload_count];
        return array("status" => true, "data" => $std, 'message' => 'Students Attendance Data');
    }
}
