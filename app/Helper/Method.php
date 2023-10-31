<?php
namespace App\Helper;

use App\Models\StudentsModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;

class Method {
    // Default Method

    public static function DefaultMethod($data) {
//        print_r($data[0]);
        $students = [];
        if($data[0][0][0] != 'CED Daily Attendance'){
            return array("status" => false, "data" => '', 'message' => 'Please choose valid attendance excel file');
        }
        $attendance_date = date('Y-m-d',strtotime($data[0][2][1]));
        $size = count($data[0]);
        $upload_count=$size - 5;
        for($i = 5; $i < $size; $i++) {
            $Is_InAttendance = "";
            $Is_OutAttendance = "";
            $attendance = false;
//            print_r($data[0][$i]);
            $PunchIn = date('Y-m-d H:i:s', strtotime($attendance_date." ".$data[0][$i][4]));
            $PunchOut = date('Y-m-d H:i:s', strtotime($attendance_date." ".$data[0][$i][5]));

            $Duration = Helper::Duration($PunchIn, $PunchOut);
            $Duration = date('Y-m-d H:i:s', strtotime($attendance_date." ".$Duration));

            if(Helper::CheckStudentAttendance($data[0][$i][1], $PunchIn, $PunchOut, $Duration, $attendance_date)){
                $attendance = true;
                $upload_count--;
                $Is_InAttendance = "already-attendance";
            }

            if(empty($data[0][$i][4]) || $data[0][$i][4] == "00:00"){
                $Is_InAttendance = "absent-attendance";
                $attendance = true;
                $upload_count--;
                $PunchIn = "";
            }

            if(empty($data[0][$i][5]) || $data[0][$i][5] == "00:00") {
                $Is_InAttendance = "absent-attendance";
                $attendance = true;
                $PunchOut = "";
                $Duration = "";
                if(!(empty($data[0][$i][4]) || $data[0][$i][4] == "00:00")) {
                    $upload_count--;
                }
            }

            $std = [
                "TraineeID" => $data[0][$i][1],
                "Name" => $data[0][$i][2],
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


    // Shrimad Ranchchandra Mission Method of Attendance Data Fetches =================================================================

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

    // Pragna Dyechem Method of Attendance Data Fetches =================================================================

    public static function PragnaDyechemMethod($data){
        $students = [];
        if($data[0][0][0] != 'Attn Date'){
            return array("status" => false, "data" => '', 'message' => 'Please choose valid attendance excel file');
        }
        $size = count($data[0]);
        $upload_count=$size - 2;
        $previous_date="";
        for($i = 3; $i < $size; $i++) {
            if(str_contains($data[0][$i][3], 'EmpID:-')){
                $traineeId = trim(Helper::getStringBetween($data[0][$i][3], 'EmpID:-', 'EmpName:-'));
                $pos1 = strpos($data[0][$i][3], 'EmpName:-');
                $name = substr($data[0][$i][3], $pos1+10);
                $upload_count--;
            }else{
                $attendance_date = date('Y-m-d',strtotime($data[0][$i][3]));
                if($previous_date == $attendance_date && $previous_traineeId == $traineeId){
                    $students[count($students)-1]['OutTime'] = $data[0][$i][5];
                    if(Helper::CheckStudentAttendance($traineeId, $data[0][$i][5], $attendance_date)){
                        $students[count($students)-1]['Is_OutAttendance'] = "already-attendance";
                    }
                    $upload_count--;
                }else {
                    $Is_InAttendance = "";
                    $Is_OutAttendance = "";
                    $attendance = false;

                    $In_Out_Attendance = "";
                    if (empty($data[0][$i][5]) || $data[0][$i][5] == "null") {
                        $Is_InAttendance = "absent-attendance";
                        $Is_OutAttendance = "absent-attendance";
                        $attendance = true;
                        $upload_count--;
                    } else {
                        $in_check = Helper::CheckStudentAttendance($traineeId, $data[0][$i][5], $attendance_date);
                        $out_check = Helper::CheckStudentAttendance($traineeId, $data[0][$i][7], $attendance_date);
                        if ($in_check) {
                            $Is_InAttendance = "already-attendance";
                        }
                        if ($out_check) {
                            $Is_OutAttendance = "already-attendance";
                        }
                        if ($in_check && $out_check) {
                            $In_Out_Attendance = "already-attendance";
                            $attendance = true;
                            $upload_count--;
                        }
                    }

                    $std = [
                        "TraineeID" => $traineeId,
                        "Name" => $name,
                        "InTime" => $data[0][$i][5],
                        "OutTime" => $data[0][$i][7],
                        "Date" => $attendance_date,
                        "ShowDate" => date('d/m/Y', strtotime($attendance_date)),
                        "Is_InAttendance" => $Is_InAttendance,
                        "Is_OutAttendance" => $Is_OutAttendance,
//                        "In_Out_Attendance" => $In_Out_Attendance,
                        "Attendance" => $attendance,
                    ];
                    array_push($students, $std);
                }
                $previous_date = $attendance_date;
                $previous_traineeId = $traineeId;
            }

        }
        $std = ["students" => $students, "upload_count" => $upload_count];
        return array("status" => true, "data" => $std, 'message' => 'Students Attendance Data');
    }

    // Ambuja Cement Foundation Method of Attendance Data Fetches =================================================================

    public static function AmbujaCementFoundationMethod($data){
        $students = [];
        if($data[0][0][12] != 'Daily Performance  Report'){
            return array("status" => false, "data" => '', 'message' => 'Please choose valid attendance excel file');
        }
        $pos1 = strpos($data[0][0][4], 'Report Date :');
        $attendance_date = substr($data[0][0][4], $pos1+14);
        $attendance_date = date('Y-m-d',strtotime($attendance_date));
        $size = count($data[0]);
        $upload_count=$size - 13;
        for($i = 13; $i < $size; $i++){
            $count = StudentsModel::where(['TraineeID' => $data[0][$i][6]])->where(['branch_id' => Auth::user()->branch_id])->count();
            if($count > 0) {
                $Is_InAttendance = "";
                $attendance = false;
                $PunchIn = date('Y-m-d H:i:s', strtotime($attendance_date." ".$data[0][$i][15]));
                $PunchOut = date('Y-m-d H:i:s', strtotime($attendance_date." ".$data[0][$i][17]));

                $Duration = Helper::Duration($PunchIn, $PunchOut);
                $Duration = date('Y-m-d H:i:s', strtotime($attendance_date." ".$Duration));
                if (Helper::CheckStudentAttendance($data[0][$i][6], $PunchIn, $PunchOut, $Duration, $attendance_date)) {
                    $attendance = true;
                    $upload_count--;
                    $Is_InAttendance = "already-attendance";
                }

                if (empty($data[0][$i][15]) || $data[0][$i][15] == "00:00") {
                    $Is_InAttendance = "absent-attendance";
                    $attendance = true;
                    $upload_count--;
                    $PunchIn = "";
                }
                if(empty($data[0][$i][17]) || $data[0][$i][17] == "00:00") {
                    $Is_InAttendance = "absent-attendance";
                    $attendance = true;
                    $PunchOut = "";
                    $Duration = "";
                    if(!(empty($data[0][$i][15]) || $data[0][$i][15] == "00:00")) {
                        $upload_count--;
                    }
                }

                $std = [
                    "TraineeID" => $data[0][$i][6],
                    "Name" => $data[0][$i][9],
                    "InTime" => $PunchIn,
                    "OutTime" => $PunchOut,
                    "Duration" => $Duration,
                    "Date" => $attendance_date,
                    "ShowDate" => date('d/m/Y', strtotime($attendance_date)),
                    "Is_InAttendance" => $Is_InAttendance,
                    "Attendance" => $attendance,
                ];
                array_push($students, $std);
            }
        }
        $std = ["students" => $students, "upload_count" => $upload_count];
        return array("status" => true, "data" => $std, 'message' => 'Students Attendance Data');
    }

    // Deepak Foundation Dahej Method of Attendance Data Fetches =================================================================

    public static function DeepakFoundationMethod($data){
        $students = [];
        if($data[0][0][0] != 'Daily IN/OUT Report'){
            return array("status" => false, "data" => '', 'message' => 'Please choose valid attendance excel file');
        }
        $attendance_date =  str_replace( '/', '-', $data[0][0][7]);
        $attendance_date = date('Y-m-d',strtotime($attendance_date));
        $size = count($data[0]);
        $upload_count=$size - 4;
        for($i = 4; $i < $size; $i++){
            $Is_InAttendance = "";
            $Is_OutAttendance = "";
            $attendance = false;
            if(Helper::CheckStudentAttendance($data[0][$i][0], $data[0][$i][3], $attendance_date)){
                $Is_InAttendance = "already-attendance";
            }
            if(Helper::CheckStudentAttendance($data[0][$i][0], $data[0][$i][18], $attendance_date)){
                $Is_OutAttendance = "already-attendance";
            }
            $In_Out_Attendance = "";
            if(empty($data[0][$i][3]) || $data[0][$i][3] == "00:00" || $data[0][$i][3] == "--:--"){
                $Is_InAttendance = "absent-attendance";
                $Is_OutAttendance = "absent-attendance";
                $attendance = true;
                $upload_count--;
            }else {
                if (Helper::CheckStudentAttendance($data[0][$i][0], $data[0][$i][3], $attendance_date) && Helper::CheckStudentAttendance($data[0][$i][0], $data[0][$i][18], $attendance_date)) {
                    $In_Out_Attendance = "already-attendance";
                    $attendance = true;
                    $upload_count--;
                }
            }

            $std = [
                "TraineeID" => $data[0][$i][0],
                "Name" => $data[0][$i][1],
                "InTime" => $data[0][$i][3],
                "OutTime" => $data[0][$i][18],
                "Date" => $attendance_date,
                "ShowDate" => date('d/m/Y',strtotime($attendance_date)),
                "Is_InAttendance" => $Is_InAttendance,
                "Is_OutAttendance" => $Is_OutAttendance,
                "In_Out_Attendance" => $In_Out_Attendance,
                "Attendance" => $attendance,
            ];
            array_push($students, $std);
        }
        $std = ["students" => $students, "upload_count" => $upload_count];
        return array("status" => true, "data" => $std, 'message' => 'Students Attendance Data');
    }

    public static function ArihantMethod($data) {
        $students = [];
        if(str_contains($data[0][0][1], 'ARIHANT COLLEGE OF PHARMACY')){
            return array("status" => false, "data" => '', 'message' => 'Please choose valid attendance excel file');
        }
        $size = count($data[0]);
        $upload_count=($size - 9)/3;
        for($i = 9; $i < $size; $i++){
//            print_r($data[0][$i]);
            $Is_InAttendance = "";
            $attendance = false;
            if(!empty($data[0][$i][0])){
                if($data[0][$i][0] != 'Emp ID') {
                    $traineeId = $data[0][$i][0];
                    $traineeName = $data[0][$i][1];
                }
            }else{
                if(!empty($data[0][$i][2])) {
                    if(gettype($data[0][$i][2]) == 'integer'){
                        $data[0][$i][2] = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($data[0][$i][2])->format('Y-m-d');
                    }
                    if(gettype($data[0][$i][3]) == 'double'){
                        $data[0][$i][3] = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($data[0][$i][3])->format('H:i');
                    }
                    if(gettype($data[0][$i][4]) == 'double'){
                        $data[0][$i][4] = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($data[0][$i][4])->format('H:i');
                    }
                    $attendance_date = date('Y-m-d',strtotime($data[0][$i][2]));
                    $PunchIn = date('Y-m-d H:i:s', strtotime($attendance_date." ".$data[0][$i][3]));
                    $PunchOut = date('Y-m-d H:i:s', strtotime($attendance_date." ".$data[0][$i][4]));

                    $Duration = Helper::Duration($PunchIn, $PunchOut);
                    $Duration = date('Y-m-d H:i:s', strtotime($attendance_date." ".$Duration));

                    if(Helper::CheckStudentAttendance($traineeId, $PunchIn, $PunchOut, $Duration, $attendance_date)){
                        $attendance = true;
                        $upload_count--;
                        $Is_InAttendance = "already-attendance";
                    }

                    if(empty($data[0][$i][3]) || $data[0][$i][3] == "00:00"){
                        $Is_InAttendance = "absent-attendance";
                        $attendance = true;
                        $upload_count--;
                        $PunchIn = "";
                    }
                    if(empty($data[0][$i][4]) || $data[0][$i][4] == "00:00") {
                        $Is_InAttendance = "absent-attendance";
                        $attendance = true;
                        $PunchOut = "";
                        $Duration = "";
                        if(!(empty($data[0][$i][3]) || $data[0][$i][3] == "00:00")) {
                            $upload_count--;
                        }
                    }
                    $std = [
                        "TraineeID" => $traineeId,
                        "Name" => $traineeName,
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
            }
        }

        $std = ["students" => $students, "upload_count" => $upload_count];
        return array("status" => true, "data" => $std, 'message' => 'Students Attendance Data');
    }

    // IISFT Ghandhidham Method of Attendance Data Fetches =================================================================

    public static function IISFTMethod($data){
        $students = [];
        if($data[0][3][4] != 'Daily Basic Attendance Report'){
            return array("status" => false, "data" => '', 'message' => 'Please choose valid attendance excel file');
        }
        if(gettype($data[0][9][5]) == 'integer'){
            $data[0][9][5] = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($data[0][9][5])->format('Y-m-d');
        }
        $attendance_date = date('Y-m-d',strtotime($data[0][9][5]));
        $size = count($data[0]);
        $upload_count=($size - 12);
        for($i = 12; $i < $size; $i++){
//            print_r($data[0][$i]);
            $Is_InAttendance = "";
            $attendance = false;
            $traineeId = $data[0][$i][3];
            $traineeName = $data[0][$i][5];

            if(gettype($data[0][$i][8]) == 'double'){
                $data[0][$i][8] = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($data[0][$i][8])->format('H:i');
            }
            if(gettype($data[0][$i][9]) == 'double'){
                $data[0][$i][9] = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($data[0][$i][9])->format('H:i');
            }
            $PunchIn = date('Y-m-d H:i:s', strtotime($attendance_date." ".$data[0][$i][8]));
            $PunchOut = date('Y-m-d H:i:s', strtotime($attendance_date." ".$data[0][$i][9]));

            $Duration = Helper::Duration($PunchIn, $PunchOut);
            $Duration = date('Y-m-d H:i:s', strtotime($attendance_date." ".$Duration));

            if(Helper::CheckStudentAttendance($traineeId, $PunchIn, $PunchOut, $Duration, $attendance_date)){
                $attendance = true;
                $upload_count--;
                $Is_InAttendance = "already-attendance";
            }

            if(empty($data[0][$i][8]) || $data[0][$i][8] == "00:00"){
                $Is_InAttendance = "absent-attendance";
                $attendance = true;
                $upload_count--;
                $PunchIn = "";
            }
            if(empty($data[0][$i][9]) || $data[0][$i][9] == "00:00") {
                $Is_InAttendance = "absent-attendance";
                $attendance = true;
                $PunchOut = "";
                $Duration = "";
                if(!(empty($data[0][$i][8]) || $data[0][$i][8] == "00:00")) {
                    $upload_count--;
                }
            }

            $std = [
                "TraineeID" => $traineeId,
                "Name" => $traineeName,
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
