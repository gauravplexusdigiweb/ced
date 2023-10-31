<?php

namespace App\Http\Controllers\Admin\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Profile\ProfileRequest;
use App\Models\Admin\Branch\BranchModel;
use App\Models\Admin\Candidate\CandidateModel;
use App\Models\Admin\Employee\EmployeeModel;
use App\Models\Admin\Franchise\FranchiseModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function Profile(){
        $data = User::find(Auth::user()->id);
        return view('Admin.Profile.Profile', compact('data'));
    }

    public function Store(ProfileRequest $request) {
        DB::beginTransaction();

        try {
            $user = User::find(Auth::user()->id);
            if(Auth::user()->user_type == "Branch"){
                $data = BranchModel::find(Auth::user()->user_id);
                $data->name = $request->name;
                $data->phone = $request->phone;
                $name = $request->name;
            }elseif(Auth::user()->user_type == "Franchise"){
                $data = FranchiseModel::find(Auth::user()->user_id);
                $data->name = $request->name;
                $data->phone = $request->phone;
                $name = $request->name;
            }elseif(Auth::user()->user_type == "Candidate"){
                $data = CandidateModel::find(Auth::user()->user_id);
                $data->first_name = $request->first_name;
                $data->middle_name = $request->middle_name;
                $data->last_name = $request->last_name;
                $name = $request->first_name." ".$request->middle_name." ".$request->last_name;
                $data->phone = $request->phone;
            }elseif(Auth::user()->user_type == "Employee"){
                $data = EmployeeModel::find(Auth::user()->user_id);
                $data->name = $request->name;
                $name = $request->name;
            }

            $user->username = $name;
            $user->save();

            $data->mobile = $request->mobile;
            $data->country = $request->country;
            $data->state = $request->state;
            $data->city = $request->city;
            $data->pincode = $request->pincode;
            $data->address = $request->address;

            $data->save();
            $response = array("status"=>true, "data"=>'','message'=>'Successfully updated profile details');
            DB::commit();
        }catch (\Exception $e){
            $response = array("status"=>false, "data"=>'','message'=>$e->getMessage());
            DB::rollBack();
        }
        return $response;
    }
}
