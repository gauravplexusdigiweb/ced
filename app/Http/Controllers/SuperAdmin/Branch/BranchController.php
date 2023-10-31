<?php

namespace App\Http\Controllers\SuperAdmin\Branch;

use App\Http\Controllers\Controller;
use App\Http\Requests\SuperAdmin\Branch\BranchEditRequest;
use App\Http\Requests\SuperAdmin\Branch\BranchRequest;
use App\Models\SuperAdmin\Branch\BranchModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;

class BranchController extends Controller
{
    public function index(Request $request){
        $page=['main' => 'Branch', 'sub' => ''];
        if ($request->ajax()) {
            $data = BranchModel::select('*')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function($row){
                    $check = "";
                    if($row->status == 1){
                        $check = 'checked';
                    }
                    $btn = '<input type="checkbox" class="StatsButton toggle-one" sid="'.$row->id.'" id="customSwitch'.$row->id.'"  '.$check.'>';
                    return $btn;
                })
                ->addColumn('action', function($row){
                    $url = route('branch.edit',[$row->id]);
                    $delete_url = route('branch.destroy',[$row->id]);
                    $btn = '<a href="'.$url.'" class="btn-transparent " ><i class="fa fa-edit text-success"></i></a>';
                    if($row->branch_id == Auth::user()->user_id){
                        $btn .= '<form class="DELETEFORM" style="display:inline-block;"><input type="hidden" name="_method" value="DELETE"><input type="hidden" name="_token" value="' . csrf_token() . '"><input type="hidden" name="url" value="' . $delete_url . '"><button type="submit" class="btn-transparent"><i class="fa fa-trash text-danger"></i></button></form>';
                    }
                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
        return view('SuperAdmin.Branch.BranchList',compact('page'));
    }

    public function create()
    {
        $page=['main' => 'ControlPanel', 'sub' => 'Branch'];
        return view('SuperAdmin.Branch.Branch',compact('page'));
    }

    public function store(BranchRequest  $request)
    {

        DB::beginTransaction();
        try{
            $data = new BranchModel();

            $data->TPID = $request->TPID;
            $data->UserName = $request->UserName;
            $data->Password = Crypt::encrypt($request->Password);
            $data->TrainingPartnerName = $request->TrainingPartnerName;
            $data->mobile = $request->mobile;
            $data->city = $request->city;

            if(isset($request->status)) {
                $data->status = 1;
            }

            $data->created_at = date('Y-m-d H:i:s');
            $data->created_by = Auth::user()->id;

            $data->save();
            $password = substr(md5(uniqid(mt_rand(), true)), 0, 8);
            $user = new User();
            $user->branch_id = $data->id;
            $user->user_type = 'Admin';
            $user->username = $request->TrainingPartnerName;
            $user->password = Hash::make($password);
            $user->email = $request->UserName;
            $user->save();
            DB::commit();
            return array("status"=>true,"statusCode"=>'100',"data"=>$password,'message'=>'Successfully New Training Partner Created');

        }catch(Exception $exception) {
            DB::rollback();
            return array("status"=>false,"statusCode"=>'102',"data"=>'','message'=>$exception->getMessage());
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $page=['main' => 'ControlPanel', 'sub' => 'Branch'];
        $data = BranchModel::find($id);
        return view('SuperAdmin.Branch.Branch',compact('page','data'));

    }

    public function update(BranchEditRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $data = BranchModel::find($id);

            $data->mobile = $request->mobile;
            $data->city = $request->city;

            if (isset($request->status)) {
                $data->status = 1;
            }else{
                $data->status = 0;
            }

            $data->updated_at = date('Y-m-d H:i:s');
            $data->updated_by = Auth::user()->id;
            $data->save();

            DB::commit();
            return array("status" => true, "statusCode" => '100', "data" => '', 'message' => 'Successfully Branch Updated');
        }catch (Exception $exception) {
            DB::rollBack();
        }
    }

    public function destroy($id)
    {
        $data = BranchModel::find($id)->delete();
        if($data){
            return array("status"=>true,"statusCode"=>'100',"data"=>'','message'=>'Successfully Branch Deleted');
        }else{
            return array("status"=>false,"statusCode"=>'102',"data"=>'','message'=>'Something went wrong ! Please try again later');
        }
    }

    public function Status (Request $request){
        $data = BranchModel::find($request->id);
        if($data->status == 1){
            $data->status = 0;
        }else{
            $data->status = 1;
        }

        if($data->save()){
            return (array("status"=>true,"statusCode"=>'100',"data"=>'','message'=>'Status updated'));
        }else{
            return (array("status"=>false,"statusCode"=>'102',"data"=>'','message'=>'Something went wrong! Try Again'));
        }
    }
}
