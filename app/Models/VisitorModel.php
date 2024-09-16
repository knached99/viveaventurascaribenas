<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitorModel extends Model
{
    use HasFactory;

    protected $table = 'visitors';

    protected $primaryKey = 'visitor_uuid';

    protected $fillable = [
        'visitor_uuid',
        'visitor_ip_address',
        'visitor_user_agent',
        'visited_url',
        'visitor_referrer',
        'visited_at',
        'unique_identifier'
    ];
}
