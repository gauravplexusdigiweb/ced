<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    public function Courses(Request $request){
        $page = ['main' => 'Course', 'sub_menu' => '', 'child_menu' => ''];
        $data = DB::table('courses')
            ->where(['branch_id' => Auth::user()->branch_id])
            ->orderBy('CourseName','asc')
            ->get();
        return view('Admin.Course',compact('page','data'));
    }

    public function CourseBatch(Request $request){
        $data =  DB::table('batches')
            ->where(['branch_id' => Auth::user()->branch_id]);
        if($request->id != 0) {
            $data = $data->where(['CourseId' => $request->id]);
        }
        $data = $data->get();
        return $data;
    }
}
