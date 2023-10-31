@extends('Admin.Layouts.App')

@section('Contents')

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Students</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('Admin.Dashboard')}}">Home</a></li>
                            <li class="breadcrumb-item active">Students</li>
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
                                <h3 class="card-title">Students</h3>
                                @if($new_student > 0)
                                    <a href="{{route('Admin.Student.New.Import')}}" class="btn btn-sm btn-warning float-right ml-2">Import New Student ({{$new_student}})</a>
                                @endif
                                @if(!isset($_GET['find']))
                                    <a href="{{route('Admin.Student.Download',['find' => 'true'])}}" class="btn btn-sm btn-info float-right">Download</a>
                                @endif
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <th>SrNo</th>
                                            <th>TrainingCenterID</th>
                                            <th>BatchID</th>
                                            <th>TrainingCenterName</th>
                                            <th>BatchPrefix</th>
                                            <th>CourseId</th>
                                            <th>CourseName</th>
                                            <th>TraineeID</th>
                                            <th>TraineeName</th>
                                            <th>EnrollmentNo</th>
                                            <th>Status</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php $i=1 @endphp
                                            @foreach($students as $student)
                                                <tr>
                                                    <td>{{$i}}</td>
                                                    <td>{{$student->TrainingCenterID}}</td>
                                                    <td>{{$student->BatchID}}</td>
                                                    <td>{{$student->TrainingCenterName}}</td>
                                                    <td>{{$student->BatchPrefix}}</td>
                                                    <td>{{$student->CourseId}}</td>
                                                    <td>{{$student->CourseName}}</td>
                                                    <td>{{$student->TraineeID}}</td>
                                                    <td>{{$student->TraineeName}}</td>
                                                    <td>{{$student->EnrollmentNo}}</td>
                                                    <td>
                                                        @if($student->status == 'New')
                                                            <button type="button" class="btn btn-sm btn-outline-success">New Student</button>
                                                        @else
                                                            <button type="button" class="btn btn-sm btn-outline-secondary">Old Student</button>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @php $i++; @endphp
                                            @endforeach
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
