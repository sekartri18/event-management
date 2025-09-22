<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organizer extends Model
{
    public function events() {
        return $this->hasMany(Event::class, 'id_organizer');
    }
    
}
