@extends('Admin.Layouts.App')

@section('Contents')

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Training Partner Info.</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('Admin.Dashboard')}}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{route('branch.index')}}">Training Partner List</a></li>
                            <li class="breadcrumb-item active">Training Partner Info.</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <form id="FormSubmit">
                            @csrf
                            @if(isset($data))
                                @method('put')
                            @endif
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Add Training Partner</h3>
                                    <a href="{{route('branch.index')}}" class="btn btn-secondary float-right btn-sm"><i class="fa fa-reply"></i> Back</a>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <div class="row">
                                        <h3 class="card-title theme-clr text-bold mb-3">Training Partner Details</h3>
                                    </div>

                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-2 col-form-label">Username</label>
                                        <div class="col-sm-4">
                                            <input type="text" name="UserName" id="UserName" class="form-control" placeholder="UserName" @if(isset($data->UserName)) value="{{$data->UserName}}" @endif>
                                        </div>
                                        <label for="inputEmail3" class="col-sm-2 col-form-label">Password</label>
                                        <div class="col-sm-4">
                                            <input type="text" name="Password" id="Password" class="form-control" placeholder="Password">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-2 col-form-label">TPID</label>
                                        <div class="col-sm-4">
                                            <input type="number" name="TPID" id="TPID" class="form-control" placeholder="TPID" @if(isset($data->TPID)) value="{{$data->TPID}}" @endif>
                                        </div>
                                        <div class="col-sm-2">
                                            <button type="button" id="GetPartnerName" class="btn btn-primary">Get Partner Name</button>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-2 col-form-label">Training Partner Name</label>
                                        <div class="col-sm-10">
                                            <input type="text" readonly name="TrainingPartnerName" id="TrainingPartnerName" class="form-control" placeholder="Training Partner Name" @if(isset($data->TrainingPartnerName)) value="{{$data->TrainingPartnerName}}" @endif>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-2 col-form-label">Mobile no.</label>
                                        <div class="col-sm-4">
                                            <input type="text" name="mobile" id="mobile" class="form-control" placeholder="Mobile no." @if(isset($data->mobile)) value="{{$data->mobile}}" @endif>
                                        </div>
                                        <label for="inputEmail3" class="col-sm-2 col-form-label">City</label>
                                        <div class="col-sm-4">
                                            <input type="text" name="city" id="city" class="form-control" placeholder="City" @if(isset($data->city)) value="{{$data->city}}" @endif>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                                <div class="card-body b-t">
                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-2 col-form-label">Status</label>
                                        <div class="col-sm-4">
                                            <input type="checkbox" value="1" data-onstyle="success" data-toggle="switchbutton" id="status" name="status" @if(isset($data)) @if($data->status==1) checked @endif @else checked @endif>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-success">Save</button>
                                    <a href="{{route('branch.index')}}" class="btn btn-default float-right">Close</a>
                                </div>
                            </div>
                            <!-- /.card -->
                        </form>
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>


@endsection


@section('StylesheetSection')
    <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">

@endsection

@section('ScriptSection')
    <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>

    <script>
        setTimeout(function() {$("#UserName").focus();},2000);
        $(function() {
            $('#status').bootstrapToggle({
                on: 'Active',
                off: 'Inactive'
            });
        });

        $("#GetPartnerName").on("click",function(e) {
           if(!$("#UserName").val()){
               $("#UserName").focus();
               CustomAlert('warning', 'Please enter UserName');
               return false;
           }
            if(!$("#Password").val()){
                $("#Password").focus();
                CustomAlert('warning', 'Please enter Password');
                return false;
            }
            if(!$("#TPID").val()){
                $("#TPID").focus();
                CustomAlert('warning', 'Please enter TPID');
                return false;
            }
            var url = "&Username="+$("#UserName").val()+"&Password="+$("#Password").val();
            $.get('{{config('global.student_api_url')}}'+url,function(response){
                response = JSON.parse(response);
                if(response != 0) {
                    $("#TrainingPartnerName").val((response[0]['TrainingPartnerName']));
                }else{
                    CustomAlert('danger', 'Wrong Credentials of Training Partner');
                }
            });
        });

        $("#FormSubmit").on('submit',function(e){
            if(e.isDefaultPrevented()){
            }else{
                e.preventDefault();
                $.ajax({
                    url : @if(isset($data))'{{route("branch.update",[$data->id])}}'@else'{{route("branch.store")}}'@endif,
                    type : 'post',
                    data : new FormData(this),
                    contentType : false,
                    cache : false,
                    processData : false,
                    error: function(xhr, status, error) {
                        if(xhr.status == 422){
                            var res = JSON.parse(xhr.responseText);
                            var text = '';
                            if('contact_person_city' in res.errors){$("#contact_person_city").focus();}
                            if('contact_person_state' in res.errors){$("#contact_person_state").focus();}
                            if('contact_person_country' in res.errors){$("#contact_person_country").focus();}
                            if('contact_person_mobile' in res.errors){$("#contact_person_mobile").focus();}
                            if('contact_person_name' in res.errors){$("#contact_person_name").focus();}
                            if('email' in res.errors){$("#email").focus();}
                            if('mobile' in res.errors){$("#mobile").focus();}
                            if('city' in res.errors){$("#city").focus();}
                            if('state' in res.errors){$("#state").focus();}
                            if('country' in res.errors){$("#country").focus();}
                            if('name' in res.errors){$("#name").focus();}
                            if('branch' in res.errors){$("#branch").focus();}
                            $.each(res.errors, function (key, value) {
                                $.each(value, function (k, v) {
                                    text += v+'<br>';
                                });
                            });
                            CustomAlert('warning', text);
                        }else{
                            CustomAlert('warning', JSON.parse(xhr.responseText).message);
                        }
                    },
                    success : function (data) {
                        if(data['status']){
                            CustomAlert('success', data.message);
                            $("#modal-default").modal('hide');
                            table.ajax.reload( null, false );
                        }else{
                            CustomAlert('danger', data.message);
                            $("#code").focus();
                        }
                    }
                });
            }
        });


    </script>
@endsection
