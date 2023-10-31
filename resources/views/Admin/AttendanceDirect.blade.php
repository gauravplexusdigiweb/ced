@extends('Admin.Layouts.App')

@section('Contents')

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Attendance Insert</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('Admin.Dashboard')}}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{route('Admin.Attendance')}}">Attendance</a></li>
                            <li class="breadcrumb-item active"> Insert</li>
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
                                    <h3 class="card-title">Add Attendance</h3>
                                    <a href="{{route('Admin.Attendance')}}" class="btn btn-secondary float-right btn-sm"><i class="fa fa-reply"></i> Back</a>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-2 col-form-label">Trainee Name</label>
                                        <div class="col-sm-4">
                                            <select class="form-control select2" name="trainee" id="trainee">
                                                <option value="">Select Trainee</option>
                                                @foreach($trainees as $trainee)
                                                <option value="{{$trainee->TraineeID}}">{{$trainee->TraineeName}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <label for="inputEmail3" class="col-sm-2 col-form-label">Enrollment No</label>
                                        <div class="col-sm-4">
                                            <input type="text" id="EnrollmentNo" disabled class="form-control" placeholder="Enrollment No">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-2 col-form-label">Batch Name</label>
                                        <div class="col-sm-4">
                                            <input type="text" id="BatchPrefix" disabled class="form-control" placeholder="Batch Name">
                                        </div>
                                        <label for="inputEmail3" class="col-sm-2 col-form-label">Batch Prefix</label>
                                        <div class="col-sm-4">
                                            <input type="text" id="BatchPrefix" disabled class="form-control" placeholder="Batch Prefix">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-2 col-form-label">Course Name</label>
                                        <div class="col-sm-4">
                                            <input type="text" id="CourseName" disabled class="form-control" placeholder="Course Name">
                                        </div>
                                        <label for="inputEmail3" class="col-sm-2 col-form-label">Course ID</label>
                                        <div class="col-sm-4">
                                            <input type="text" id="CourseId" disabled class="form-control" placeholder="Course ID">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-2 col-form-label">Punch date</label>
                                        <div class="col-sm-4">
                                            <input type="date" name="punch_date" id="punch_date" class="form-control" placeholder="Punch Date" value="{{date('Y-m-d')}}">
                                        </div>
                                        <label for="inputEmail3" class="col-sm-2 col-form-label">Punch Time</label>
                                        <div class="col-sm-4">
                                            <input type="time" name="punch_time" id="punch_time" class="form-control" placeholder="Punch Time" value="{{date('H:i')}}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-2 col-form-label">Punch Type</label>
                                        <div class="col-sm-4">
                                            <select class="form-control" name="punch_type" id="punch_type">
                                                <option value="">Select Type</option>
                                                <option value="In">In</option>
                                                <option value="Out">Out</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-success">Save</button>
                                    <a href="{{route('Admin.Attendance')}}" class="btn btn-default float-right">Close</a>
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


@section('ScriptSection')
<script>
    setTimeout(function() {$("#trainee").focus();},1000);

    $("#trainee").on("change", function() {
        $.post("{{route('student.details')}}", {_token: '{{csrf_token()}}', id: $(this).val()}, function(response) {
            $("#EnrollmentNo").val(response.EnrollmentNo);
            $("#BatchPrefix").val(response.BatchPrefix);
            $("#BatchPrefix").val(response.BatchPrefix);
            $("#CourseName").val(response.CourseName);
            $("#CourseId").val(response.CourseId);
        });
    });

    $("#FormSubmit").on('submit',function(e){
        if(e.isDefaultPrevented()){
        }else{
            e.preventDefault();
            $.ajax({
                url : '{{route("Admin.Attendance.Direct.Store")}}',
                type : 'post',
                data : new FormData(this),
                contentType : false,
                cache : false,
                processData : false,
                error: function(xhr, status, error) {
                    if(xhr.status == 422){
                        var res = JSON.parse(xhr.responseText);
                        var text = '';
                        if('punch_type' in res.errors){$("#punch_type").focus();}
                        if('punch_time' in res.errors){$("#punch_time").focus();}
                        if('punch_date' in res.errors){$("#punch_date").focus();}
                        if('trainee' in res.errors){$("#trainee").focus();}
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
