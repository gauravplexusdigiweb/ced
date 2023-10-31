@extends('Admin.Layouts.App')

@section('Contents')

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Training Partner</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('Admin.Dashboard')}}">Home</a></li>
                            <li class="breadcrumb-item active">Training Partner</li>
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
                                <h3 class="card-title">Training Partner</h3>
                                <a href="{{route('branch.create')}}" class="btn btn-sm btn-info float-right">Add New</a>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            {{--                                            <th>Sr no</th>--}}
                                            <th>TPID</th>
                                            <th>UserName</th>
                                            <th>TrainingCenterName</th>
                                            <th>Mobile</th>
                                            <th>City</th>
                                            <th>Status</th>
                                            <th>Action</th>
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
                url : "{{ route('branch.index') }}",
                data: function (d) {

                }
            },
            columns: [
                {data: 'TPID', name: 'TPID'},
                {data: 'UserName', name: 'UserName'},
                {data: 'TrainingCenterName', name: 'TrainingCenterName'},
                {data: 'mobile', name: 'mobile'},
                {data: 'city', name: 'city'},
                {data: 'status', name: 'status', orderable: false, searchable: false},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            drawCallback: function () {
                $('.toggle-one').bootstrapToggle({
                    on: 'Active',
                    off: 'Inactive',
                });
            }
        });
        $('#search-form').on('submit', function(e) {
            table.draw();
            e.preventDefault();
        });



        $(document).on('change', '.StatsButton',function (e) {
            var ts = this;
            $.post("{{route('branch.status')}}",{_token:'{{csrf_token()}}',id:$(this).attr('sid')},function (data) {
                if(data['status']){
                    if($(ts).closest('tr').find('.EditButton').attr('city_status') == '1'){
                        $(ts).closest('tr').find('.EditButton').attr('city_status','0');
                    }else{
                        $(ts).closest('tr').find('.EditButton').attr('city_status','1');
                    }
                    CustomAlert('success', data.message);
                }else{
                    CustomAlert('warning', data.message);
                }
            });
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
