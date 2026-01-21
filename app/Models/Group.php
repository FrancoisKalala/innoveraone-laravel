<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Group extends Model
{
    use HasFactory;

    protected $fillable = ['created_by', 'name', 'description', 'slug', 'privacy'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'group_members')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function messageReads()
    {
        return $this->hasMany(GroupMessageRead::class);
    }

    public function messages()
    {
        return $this->hasMany(GroupMessage::class);
    }

    public function hasMember(User $user)
    {
        return $this->members()->where('user_id', $user->id)->exists();
    }

    public function isAdmin(User $user)
    {
        return $this->members()
            ->where('user_id', $user->id)
            ->wherePivot('role', 'admin')
            ->exists();
    }
}
