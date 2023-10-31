<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BatchesModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BatchController extends Controller
{
    public function Batches(Request $request){
        $page = ['main' => 'Batch', 'sub_menu' => '', 'child_menu' => ''];
        $data = BatchesModel::with('course');
        if(isset($request->course)){
            $data = $data->where(['CourseId' => $request->course]);
        }
        $data = $data->where(['branch_id' => Auth::user()->branch_id])
            ->orderBy('BatchID','desc')
            ->get();
        return view('Admin.Batch',compact('page','data'));
    }
}
