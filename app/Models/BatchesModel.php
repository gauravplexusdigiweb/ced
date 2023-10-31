<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BatchesModel extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'batches';

    public function course(){
        return $this->belongsTo(CoursesModel::class, 'CourseId', 'CourseId');
    }
}
