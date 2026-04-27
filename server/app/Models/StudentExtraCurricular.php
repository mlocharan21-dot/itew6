<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentExtraCurricular extends Model {
    protected $fillable = ['student_id','name','role','organization','start_year','end_year'];

    public function student(): BelongsTo {
        return $this->belongsTo(Student::class);
    }
}
