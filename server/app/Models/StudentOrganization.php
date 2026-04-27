<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentOrganization extends Model {
    protected $fillable = ['student_id','organization_name','position','type','start_year','end_year','is_active'];
    protected $casts = ['is_active' => 'boolean'];

    public function student(): BelongsTo {
        return $this->belongsTo(Student::class);
    }
}
