<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SetupDiagram extends Model
{
    protected $table = 'setup_diagrams';

    protected $fillable = ['use_case_path', 'class_diagram_path', 'student_notes'];
}