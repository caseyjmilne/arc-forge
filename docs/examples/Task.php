<?php

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'wp_tasks';
    
    protected $fillable = ['project_id', 'title', 'status', 'due_date'];
    
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}