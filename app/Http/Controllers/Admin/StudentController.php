<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudentsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use function view;

class StudentController extends Controller
{
    public function Students(Request $request){
        $page = ['main' => 'Student', 'sub_menu' => '', 'child_menu' => ''];
        $batches = DB::table('batches')->where(['branch_id' => Auth::user()->branch_id])->orderBy('created_at','desc')->get();
        $courses = DB::table('courses')->where(['branch_id' => Auth::user()->branch_id])->orderBy('created_at','desc')->get();
        if ($request->ajax()) {
            $data = StudentsModel::select('*')
                ->with('course', 'batch')
                ->where(['branch_id' => Auth::user()->branch_id])
                ->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->filter(function ($instance) use ($request) {
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
                ->make(true);
        }
        return view('Admin.Students',compact('page','batches','courses'));
    }
}
