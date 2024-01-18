<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    protected $table = 'status';

    protected $fillable = ['is_published'];

    public function user()
    {
        return $this->BelongsTo(User::class, 'id_user');
    }
}