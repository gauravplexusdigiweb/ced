<?php

return [

//    'student_api_url' => 'http://www.cedmis.gujarat.gov.in/CEDWebService/Services/CEDStudentData.svc/DownloadTraineesData?',
    'student_api_url' => 'https://cedmisnew.gujarat.gov.in/WebService/DownloadTraineesData?',
//    'attendance_api_url' => 'http://www.cedmis.gujarat.gov.in/CEDWebService/Services/CEDStudentData.svc/UploadTraineesBioAttendanceData?',
    'attendance_api_url' => 'https://cedmisnew.gujarat.gov.in/WebService/UploadTraineesAttendanceData',
//    'attendance_api_TPID' => \App\Helper\Helper::BranchDetails()->TPID,
//    'attendance_api_username' => \App\Helper\Helper::BranchDetails()->UserName,
//    'attendance_api_password' => \Illuminate\Support\Facades\Crypt::decrypt(\App\Helper\Helper::BranchDetails()->Password),
]

?>
