<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentAcademicHistory extends Model {
    protected $fillable = ['student_id','level','school_name','address','year_graduated','honors'];

    public function student(): BelongsTo {
        return $this->belongsTo(Student::class);
    }
}
