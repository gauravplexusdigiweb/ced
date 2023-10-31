<li class="nav-item">
    <a href="{{route('Admin.Dashboard')}}" class="nav-link @if(isset($page['main'])) @if($page['main'] == 'Dashboard') active @endif @endif">
        <i class="nav-icon fas fa-tachometer-alt"></i>
        <p>
            Dashboard
        </p>
    </a>
</li>
<li class="nav-item">
    <a href="{{route('Admin.Student.Download')}}" class="nav-link @if(isset($page['main'])) @if($page['main'] == 'StudentDownload') active @endif @endif">
        <i class="nav-icon fas fa-download"></i>
        <p>
            Download
        </p>
    </a>
</li>
<li class="nav-item">
    <a href="{{route('Admin.Course')}}" class="nav-link @if(isset($page['main'])) @if($page['main'] == 'Course') active @endif @endif">
        <i class="nav-icon fas fa-book-open"></i>
        <p>
            Courses
        </p>
    </a>
</li>
<li class="nav-item">
    <a href="{{route('Admin.Batch')}}" class="nav-link @if(isset($page['main'])) @if($page['main'] == 'Batch') active @endif @endif">
        <i class="nav-icon fas fa-tasks"></i>
        <p>
            Batches
        </p>
    </a>
</li>
<li class="nav-item">
    <a href="{{route('Admin.Student')}}" class="nav-link @if(isset($page['main'])) @if($page['main'] == 'Student') active @endif @endif">
        <i class="nav-icon fas fa-user-graduate"></i>
        <p>
            Students
        </p>
    </a>
</li>
<li class="nav-item @if(isset($page['main'])) @if($page['main'] == 'Attendance') menu-open @endif @endif">
    <a href="#" class="nav-link @if(isset($page['main'])) @if($page['main'] == 'Attendance') active @endif @endif">
        <i class="nav-icon fas fa-calendar"></i>
        <p>
            Attendance
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{route('Admin.Attendance.Import')}}" class="nav-link @if(isset($page['sub_menu'])) @if($page['sub_menu'] == 'Import') active @endif @endif">
                <i class="far fa-circle nav-icon"></i>
                <p>Upload</p>
            </a>
        </li>
{{--        <li class="nav-item">--}}
{{--            <a href="{{route('Admin.Attendance.Direct')}}" class="nav-link @if(isset($page['sub_menu'])) @if($page['sub_menu'] == 'Direct') active @endif @endif">--}}
{{--                <i class="far fa-circle nav-icon"></i>--}}
{{--                <p>Insert</p>--}}
{{--            </a>--}}
{{--        </li>--}}
        <li class="nav-item">
            <a href="{{route('Admin.Attendance')}}" class="nav-link @if(isset($page['sub_menu'])) @if($page['sub_menu'] == 'Attendance') active @endif @endif">
                <i class="far fa-circle nav-icon"></i>
                <p>Attendance</p>
            </a>
        </li>
    </ul>
</li>
