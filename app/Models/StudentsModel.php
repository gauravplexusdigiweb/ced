<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentsModel extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'students';

    public function course(){
        return $this->belongsTo(CoursesModel::class, 'CourseId', 'CourseId');
    }

    public function batch(){
        return $this->belongsTo(BatchesModel::class, 'BatchID', 'BatchID');
    }
}
