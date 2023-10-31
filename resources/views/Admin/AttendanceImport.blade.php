@extends('Admin.Layouts.App')

@section('Contents')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Attendance Import</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('Admin.Dashboard')}}">Home</a></li>
                            <li class="breadcrumb-item">Attendance</li>
                            <li class="breadcrumb-item active">Import</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- COLOR PALETTE -->
                <div class="card card-default card-m-h-70">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-file-import"></i>
                            Import
                        </h3>
                    </div>
                    <div class="card-body">
                        <form id="FormSubmit">
                            @csrf
                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <div class="custom-file">
                                        <input type="file" name="attendance_file" id="attendance_file" class="custom-file-input">
                                        <label class="custom-file-label" for="customFile">Choose file</label>
                                    </div>
                                </div>
                                <div class="col-sm-1">
                                </div>
                                <div class="col-sm-3">
                                    <button type="submit" class="btn btn-success">View</button>
                                </div>
                                <div class="col-sm-2">
                                    <button type="button" id="AttendanceUpload" class="btn btn-success display-none">Upload [<span id="PresentCount">0</span>]</button>
                                </div>
                                <div class="col-sm-12">
                                    <hr>
                                </div>
                            </div>
                        </form>
                        <form id="AttendanceFormSubmit">
                            @csrf
{{--                            <input type="hidden" name="attendance_date" id="attendance_date" value="">--}}
                            <div class="row">
                                <div class="col-sm-12">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
{{--                                        <tr>--}}
{{--                                            <th>Date</th>--}}
{{--                                            <th id="AttendanceDate"></th>--}}
{{--                                            <th colspan="2"></th>--}}
{{--                                        </tr>--}}
                                        <tr>
                                            <th width="5%">Sr no</th>
                                            <th>TraineeID</th>
                                            <th>TraineeName</th>
                                            <th>Date</th>
                                            <th>InTime</th>
                                            <th>OutTime</th>
                                            <th>Duration</th>
                                        </tr>
                                        </thead>
                                        <tbody id="StudentTable">

                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-sm-12 text-center">

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection

@section('ScriptSection')

    <script>
        $("#file").focus();
        $("#FormSubmit").on('submit',function(e){
            if(e.isDefaultPrevented()){
            }else{
                e.preventDefault();
                // $("#AttendanceSubmit").css('display', 'none');
                $.ajax({
                    url : '{{route("Admin.Attendance.Import.Store")}}',
                    type : 'post',
                    data : new FormData(this),
                    contentType : false,
                    cache : false,
                    processData : false,
                    error: function(xhr, status, error) {
                        if(xhr.status == 422){
                            var res = JSON.parse(xhr.responseText);
                            var text = '';
                            if('file' in res.errors){$("#file").focus();}
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
                    success : function (response) {
                        if(response['status']){
                            if(response.data.students) {
                                // $("#attendance_date").val(response.data.date);
                                // $("#AttendanceDate").html(response.data.date);
                                $("#StudentTable").html('');
                                $.each(response.data.students, function (key, value) {
                                    var row = '<tr id="rowId'+value.TraineeID+value.Date+'">';
                                    row += '<input type="hidden" name="TraineeID" value="'+value.TraineeID+'">';
                                    row += '<input type="hidden" name="Date" value="'+value.Date+'">';
                                    row += '<input type="hidden" name="InTime" value="'+value.InTime+'">';
                                    row += '<input type="hidden" name="OutTime" value="'+value.OutTime+'">';
                                    row += '<input type="hidden" name="Duration" value="'+value.Duration+'">';
                                    row += '<input type="hidden" name="Attendance" value="'+value.Attendance+'">';
                                    row += '<td>' + (parseInt(key)+1) + '</td>';
                                    row += '<td>' + value.TraineeID + '</td>';
                                    row += '<td>' + value.Name + '</td>';
                                    row += '<td>' + value.ShowDate + '</td>';
                                    row += '<td id="rowIn'+value.TraineeID+value.Date+'" class="'+value.Is_InAttendance+'">' + value.InTime + '</td>';
                                    row += '<td id="rowOut'+value.TraineeID+value.Date+'" class="'+value.Is_InAttendance+'">' + value.OutTime + '</td>';
                                    row += '<td id="rowDuration'+value.TraineeID+value.Date+'" class="'+value.Is_InAttendance+'">' + value.Duration + '</td>';
                                    row += '</tr>';
                                    $("#StudentTable").append(row);
                                });
                                $("#PresentCount").html(response.data.present)
                                $("#AttendanceUpload").css('display', 'block');
                            }
                            // CustomAlert('success', response.message);
                        }else{
                            CustomAlert('danger', response.message);
                        }
                    }
                });
            }
        });

        $("#AttendanceFormSubmit").on('submit',function(e){
            if(e.isDefaultPrevented()){
            }else{
                e.preventDefault();
                $("#AttendanceSubmit").prop('disabled', 'false');
                $("#AttendanceSubmit").html('Uploading...');
                $.ajax({
                    url : '{{route("Admin.Attendance.Store")}}',
                    type : 'post',
                    data : new FormData(this),
                    contentType : false,
                    cache : false,
                    processData : false,
                    error: function(xhr, status, error) {
                        if(xhr.status == 422){
                            var res = JSON.parse(xhr.responseText);
                            var text = '';
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
                    success : function (response) {
                        if(response['status']){
                            $("#AttendanceSubmit").removeAttr('disabled');
                            $("#AttendanceSubmit").html('Upload');
                            CustomAlert('success', response.message);
                        }else{
                            $("#AttendanceSubmit").removeAttr('disabled');
                            $("#AttendanceSubmit").html('Upload');
                            if(response.statusCode == '111'){
                                $.each(response.data.students, function (key, value) {
                                   $("#rowId"+value).css('background','#dc3745');
                                });
                            }else if(response.statusCode == '112'){

                            }
                            CustomAlert('danger', response.message);
                        }
                    }
                });
            }
        });


        $("#AttendanceUpload").on('click', function(){
            const atndArray = [];
            var PresentCount = parseInt($("#PresentCount").html());
            $('#StudentTable  > tr').each(function(index, tr) {
                var TraineeID = $(this).find('input[name="TraineeID"]').val();
                var Date = $(this).find('input[name="Date"]').val();
                var InTime = $(this).find('input[name="InTime"]').val();
                var OutTime = $(this).find('input[name="OutTime"]').val();
                var Duration = $(this).find('input[name="Duration"]').val();
                var Attendance = $(this).find('input[name="Attendance"]').val();
                atndArray[index] = {"_token": '{{csrf_token()}}', "attendance_date" : Date,"TraineeID" : TraineeID, "InTime" : InTime, "OutTime" : OutTime, "Duration" : Duration, "Attendance" : Attendance};
            });

            const sleep = ms => new Promise(resolve => setTimeout(resolve, ms));

            (async () => {
                for (let i = 0; i < atndArray.length; i++) {
                    // console.log(atndArray[i]["Attendance"]);
                    if(atndArray[i]["Attendance"] == "false") {
                        // console.log(PresentCount);
                        await doAjax(atndArray[i]);
                        PresentCount--;
                        $("#PresentCount").html(PresentCount);
                    }
                    // await sleep(1000);
                }
                $("#AttendanceUpload").html('Upload [<span id="PresentCount">0</span>]');
                $("#AttendanceUpload").css('display', 'none');

                console.log("done");
            })();

        });


        async function doAjax(args) {
            let result;

            try {
                result = await $.ajax({
                    url: '{{route("Admin.Attendance.Single.Store")}}',
                    type: 'POST',
                    data: args
                });
                if(result.status) {
                    // console.log(result.data.student.TraineeID);
                    // if(result.data.attendance.in_attendance && result.data.attendance.out_attendance){
                    //     $("#rowId"+result.data.student.TraineeID).addClass('already-attendance');
                    // }
                    if(result.data.attendance.in_attendance){
                        $("#rowIn"+result.data.student.TraineeID+result.data.student.date).addClass('already-attendance');
                        $("#rowOut"+result.data.student.TraineeID+result.data.student.date).addClass('already-attendance');
                        $("#rowDuration"+result.data.student.TraineeID+result.data.student.date).addClass('already-attendance');
                    }else{
                        $("#rowIn"+result.data.student.TraineeID+result.data.student.date).addClass('bg-danger');
                        $("#rowOut"+result.data.student.TraineeID+result.data.student.date).addClass('bg-danger');
                        $("#rowDuration"+result.data.student.TraineeID+result.data.student.date).addClass('bg-danger');
                    }
                }else{
                    $("#rowId"+result.data.student.TraineeID+result.data.student.date).addClass('bg-danger');
                    CustomAlert('danger', result.message);
                }
                return true;
            } catch (error) {
                console.error(error);
            }
        }



    </script>

@endsection

