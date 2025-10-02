<?php

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table = 'wp_projects'; // or whatever your table is
    
    protected $fillable = ['name', 'description', 'user_id', 'status'];
    
    // Relationships
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}