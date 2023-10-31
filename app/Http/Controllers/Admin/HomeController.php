<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\BatchesModel;
use App\Models\CoursesModel;
use App\Models\StudentsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function Dashboard(){
        $page = ['main' => 'Dashboard', 'sub_menu' => '', 'child_menu' => ''];
        return view('Admin.Dashboard',compact('page'));
    }

    public function Expire(){
        $page = ['main' => 'Expire', 'sub_menu' => '', 'child_menu' => ''];
        return view('Admin.Expire',compact('page'));
    }


    public function StudentDownload(Request $request) {
        $page = ['main' => 'StudentDownload', 'sub_menu' => '', 'child_menu' => ''];
        $students = [];
        $new_student = 0;
        if(isset($request->find)) {
            if($request->find == 'true') {
                $client = new \GuzzleHttp\Client();
                $request_url = config('global.student_api_url')."Username=".Helper::BranchDetails()->UserName."&Password=".Crypt::decrypt(Helper::BranchDetails()->Password);
                $response = $client->request('GET', $request_url);
                $students = (json_decode($response->getBody()));
                if ($students == 0) {
                    $students = [];
                }else{
//                    echo count($students);
                    foreach ($students as $key => $student){
//                        print_r($student);
//                        break;
//                        echo $student->CourseId." ".$student->CourseName;
                        $check = StudentsModel::where(['TraineeID' => $student->TraineeID, 'CourseId' => $student->CourseId])->count();
//                        echo $check;
                        if($check == 0) {
                            $new_student++;
                            $student->status = 'New';
                        }else{
                            unset($students[$key]);
                            $student->status = 'Old';
                        }
                    }
                }
            }
        }
        return view('Admin.StudentDownload',compact('page','students', 'new_student'));
    }

    public function ImportNewStudent(Request $request){
        $page = ['main' => 'StudentDownload', 'sub_menu' => '', 'child_menu' => ''];
        $client = new \GuzzleHttp\Client();
        $request_url = config('global.student_api_url')."&Username=".Helper::BranchDetails()->UserName."&Password=".Crypt::decrypt(Helper::BranchDetails()->Password);
        $response = $client->request('GET', $request_url);
        $students = (json_decode($response->getBody()));
        if ($students != 0) {
            foreach ($students as $student){
                $course_check = CoursesModel::where(['CourseId' => $student->CourseId])->count();
                if($course_check == 0) {
                    $course_data = new CoursesModel();
                    $course_data->branch_id = Auth::user()->branch_id;
                    $course_data->CourseId = $student->CourseId;
                    $course_data->CourseName = $student->CourseName;
                    $course_data->save();
                }
                $batch_check = BatchesModel::where(['BatchID' => $student->BatchID])->count();
                if($batch_check == 0) {
                    $batch_data = new BatchesModel();
                    $batch_data->branch_id = Auth::user()->branch_id;
                    $batch_data->CourseId = $student->CourseId;
                    $batch_data->BatchID = $student->BatchID;
                    $batch_data->BatchPrefix = $student->BatchPrefix;
                    $batch_data->save();
                }
                $student_check = StudentsModel::where(['TraineeID' => $student->TraineeID])->count();
                if($student_check == 0) {
                    $data = new StudentsModel();
                    $data->branch_id = Auth::user()->branch_id;
                    $data->TrainingCenterID = $student->TrainingCenterID;
                    $data->BatchID = $student->BatchID;
                    $data->TrainingCenterName = $student->TrainingCenterName;
                    $data->BatchPrefix = $student->BatchPrefix;
                    $data->CourseId = $student->CourseId;
                    $data->CourseName = $student->CourseName;
                    $data->TraineeID = $student->TraineeID;
                    $data->TraineeName = $student->TraineeName;
                    $data->EnrollmentNo = $student->EnrollmentNo;
                    $data->save();
                }
            }
        }
        return redirect(route('Admin.Student.Download',['find' => 'true']));
    }

    public function StudentDetails(Request $request){
        return DB::table('students')
            ->where(['branch_id' => Auth::user()->branch_id, 'TraineeID' => $request->id])
            ->first();

    }
}
