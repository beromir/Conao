<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Archive extends Model
{
    protected $table = 'archivables';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'archivable_id',
        'archivable_type',
    ];

    public function archivable()
    {
        return $this->morphTo();
    }
}
