@extends('Admin.Layouts.App')

@section('Contents')

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>All Students</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('Admin.Dashboard')}}">Home</a></li>
                            <li class="breadcrumb-item active">All Students</li>
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

                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">All Students</h3>
                                <a href="{{route('Admin.Attendance.Import')}}" class="btn btn-sm btn-info float-right">Upload</a>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <form id="search-form">

                                    <div class="row">
                                        <div class="col-md-3 mb-2">
                                            <select class="form-control select2 select2-danger" data-dropdown-css-class="select2-danger" name="search_course" id="search_course">
                                                <option value="0">All Course</option>
                                                @foreach($courses as $course)
                                                <option value="{{$course->CourseId}}" @if(isset($_GET['course'])) @if($_GET['course'] == $course->CourseId) selected @endif @endif>{{$course->CourseName}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <select class="form-control select2 select2-danger" data-dropdown-css-class="select2-danger" name="search_batch" id="search_batch">
                                                <option value="0">All Batch</option>
                                                @foreach($batches as $batch)
                                                    <option value="{{$batch->BatchID}}"  @if(isset($_GET['batch'])) @if($_GET['batch'] == $batch->BatchID) selected @endif @endif>{{$batch->BatchPrefix}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-3 mb-2">
                                            <button type="submit" class="btn btn-primary" value="Search">Search</button>
                                        </div>
                                        <div class="col-md-12 mb-2">
                                            <hr>
                                        </div>
                                    </div>
                                </form>
                                <div class="table-responsive">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <th>TraineeID</th>
                                            <th>TraineeName</th>
                                            <th>EnrollmentNo</th>
{{--                                            <th>TrainingCenterID</th>--}}
                                            <th>BatchID</th>
{{--                                            <th>TrainingCenterName</th>--}}
{{--                                            <th>BatchPrefix</th>--}}
                                            <th>BatchPrefix</th>
                                            <th>CourseId</th>
                                            <th>CourseName</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
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

    <!-- DataTables -->
    <link rel="stylesheet" href="{{asset('panel/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('panel/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('panel/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">

@endsection

@section('ScriptSection')
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

    <!-- DataTables  & Plugins -->
    <script src="{{asset('panel/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('panel/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('panel/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('panel/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
    <script src="{{asset('panel/plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('panel/plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
    <script src="{{asset('panel/plugins/jszip/jszip.min.js')}}"></script>
    <script src="{{asset('panel/plugins/pdfmake/pdfmake.min.js')}}"></script>
    <script src="{{asset('panel/plugins/pdfmake/vfs_fonts.js')}}"></script>
    <script src="{{asset('panel/plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
    <script src="{{asset('panel/plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
    <script src="{{asset('panel/plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>


    <script>
        var table = $('#example1').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url : "{{ route('Admin.Student') }}",
                data: function (d) {
                    d.batch = $("#search_batch").val();
                    d.course = $("#search_course").val();
                }
            },
            columns: [
                {data: 'TraineeID', name: 'TraineeID'},
                {data: 'TraineeName', name: 'TraineeName'},
                {data: 'EnrollmentNo', name: 'EnrollmentNo'},
                {data: 'BatchID', name: 'BatchID'},
                {data: 'BatchPrefix', name: 'BatchPrefix'},
                {data: 'CourseId', name: 'CourseId'},
                {data: 'CourseName', name: 'CourseName'}
            ],
            dom: 'lBfrtip',
            buttons: [
                'excel','csv','pdf'
            ],
            drawCallback: function () {

            }
        });
        $('#search-form').on('submit', function(e) {
            table.draw();
            e.preventDefault();
        });

        $("#search_course").on("change", function() {
            $("#search_batch").html('');
            $("#search_batch").append('<option value="">Please Select</option>');
            $.post("{{route('course.batch')}}", {_token: '{{csrf_token()}}', id: $(this).val()}, function(data) {
                $(data).each(function(key, val) {
                    $("#search_batch").append('<option value="'+val.BatchID+'">'+val.BatchPrefix+'</option>');
                });
            });
        });

    </script>
@endsection
