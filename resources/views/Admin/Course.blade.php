@extends('Admin.Layouts.App')

@section('Contents')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>All Courses</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('Admin.Dashboard')}}">Home</a></li>
                            <li class="breadcrumb-item active">All Courses</li>
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
                            <i class="fas fa-tasks"></i>
                            Courses
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="col-12">
                            {{--                            <h5>Theme Colors</h5>--}}
                        </div>
                        <!-- /.col-12 -->
                        <div class="row">
                            @foreach($data as $dt)
                                <div class="col-sm-12 col-md-4 mb-3">

                                    <a href="{{route('Admin.Batch',['course'=>$dt->CourseId])}}" class="btn btn-outline-danger btn-block">
                                        <h3>{{$dt->CourseId}}</h3>
                                        {{$dt->CourseName}}
                                    </a>
                                </div>
                            @endforeach
                            <!-- /.col -->
                            <!-- /.col -->
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection


