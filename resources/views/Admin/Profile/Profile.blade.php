@extends('Admin.Layouts.App')

@section('Contents')

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Profile</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('Admin.Dashboard')}}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{route('Admin.User.Profile')}}">Profile</a></li>
                            <li class="breadcrumb-item active">Profile Update</li>
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
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Profile Details</h3>
                                    <a href="" class="btn btn-secondary float-right btn-sm"><i class="fa fa-reply"></i> Back</a>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    @if($data->user_type == 'Candidate')
                                        <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-2 col-form-label">First Name</label>
                                            <div class="col-sm-4">
                                                <input type="text" name="first_name" id="first_name" class="form-control" placeholder="Name" value="{{$data->first_name}}">
                                            </div>
                                            <label for="inputEmail3" class="col-sm-2 col-form-label">Middle Name</label>
                                            <div class="col-sm-4">
                                                <input type="text" name="middle_name" id="middle_name" class="form-control" placeholder="Name" value="{{$data->middle_name}}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-2 col-form-label">Last Name</label>
                                            <div class="col-sm-4">
                                                <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Name" value="{{$data->last_name}}">
                                            </div>
                                        </div>
                                    @else
                                        <div class="form-group row">
                                            <label for="inputEmail3" class="col-sm-2 col-form-label">Name</label>
                                            <div class="col-sm-4">
                                                <input type="text" name="name" id="name" class="form-control" placeholder="Name" value="{{$data->name}}">
                                            </div>
                                        </div>
                                    @endif

                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-2 col-form-label">Address</label>
                                        <div class="col-sm-10">
                                            <textarea class="form-control" name="address" id="address" placeholder="Address" rows="2">{{$data->address}}</textarea>
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-2 col-form-label">Mobile no.</label>
                                        <div class="col-sm-4">
                                            <input type="text" name="mobile" id="mobile" class="form-control" placeholder="Mobile no." value="{{$data->mobile}}">
                                        </div>
                                        @if($data->user_type != 'Employee')
                                        <label for="inputEmail3" class="col-sm-2 col-form-label">Phone no.</label>
                                        <div class="col-sm-4">
                                            <input type="text" name="phone" id="phone" class="form-control" placeholder="Phone no."  value="{{$data->phone}}">
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="card-footer">
{{--                                    <button type="submit" class="btn btn-success">Save</button>--}}
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
        @if($data->user_type == 'Candidate')
        $("#first_name").focus();
        @else
        $("#name").focus();
        @endif





        $("#FormSubmit").on('submit',function(e){
            if(e.isDefaultPrevented()){
            }else{
                e.preventDefault();
                $.ajax({
                    url : '{{route("Admin.User.Profile.Store")}}',
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

        $(document).on('submit', '.DELETEFORM',(function(e) {
            //console.log(e);
            if(e.isDefaultPrevented())
            {}
            else
            {
                if(!confirm('Are sure you want to delete ?')){
                    return false;
                }
                var url = $(this).find("input[name=url]").val();
                e.preventDefault();
                $.ajax({
                    url:url, // Url to which the request is send
                    type: "POST",             // Type of request to be send, called as method
                    data:new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
                    contentType: false,       // The content type used when sending data to the server.
                    cache:false,             // To unable request pages to be cached
                    processData:false,        // To send DOMDocument or non processed data file it is set to false
                    success: function(data)   // A function to be called if request succeeds
                    {
                        if(data['status']){
                            CustomAlert('success', data.message);
                            $("#modal-default").modal('hide');
                            table.ajax.reload( null, false );
                        }else{
                            CustomAlert('warning', data.message);
                        }
                    }
                });
            }
        }));

    </script>
@endsection
