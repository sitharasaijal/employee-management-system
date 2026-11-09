<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class Employee extends Authenticatable
{
    use HasApiTokens, HasRoles, Notifiable;

    protected $fillable = [
        'first_name', 'last_name', 'email', 'mobile', 'gender',
        'date_of_birth', 'joining_date', 'department_id', 'designation_id',
        'employment_type', 'salary', 'address', 'profile_photo', 'status',
        'password', 'login_enabled',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'joining_date'  => 'date',
            'salary'        => 'decimal:2',
            'login_enabled' => 'boolean',
            'last_login_at' => 'datetime',
            'password'      => 'hashed',
        ];
    }

    // ---- Relationships ----

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function designation(): BelongsTo
    {
        return $this->belongsTo(Designation::class);
    }

    public function histories(): HasMany
    {
        return $this->hasMany(EmployeeHistory::class);
    }

    public function accessibleDepartments(): BelongsToMany
    {
        return $this->belongsToMany(Department::class, 'employee_department_access')
                    ->withTimestamps();
    }

    // ---- Access helpers ----

    public function canLogin(): bool
    {
        return $this->login_enabled && $this->status === 'active';
    }

    public function allowedDepartmentIds(): array
    {
        if ($this->can('departments.access_all')) {
            return Department::pluck('id')->all();
        }

        return $this->accessibleDepartments()->pluck('departments.id')->all();
    }
}