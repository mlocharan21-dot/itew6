<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentViolation extends Model {
    protected $fillable = ['student_id','description','date','penalty','status','remarks'];
    protected $casts = ['date' => 'date:Y-m-d'];

    public function student(): BelongsTo {
        return $this->belongsTo(Student::class);
    }
}
