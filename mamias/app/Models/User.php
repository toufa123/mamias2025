<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Models\Contracts\HasName;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Filament\Panel;


class User extends Authenticatable implements FilamentUser, HasAvatar, HasName
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'name',
        'email',
        'password',
        'phone',
        'bio',
        'country',
        'title',
        'taxonomic_area',
        'geographic_area'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'taxonomic_area' => 'array',
            'geographic_area' => 'array',
        ];
    }
    
    public function getFilamentAvatarUrl(): ?string
    {
        $name = trim($this->getFilamentName());
        if ($name === '') {
            return null;
        }
        return 'https://ui-avatars.com/api/?color=FFFFFF&background=07A0C4&bold=true&name=' . urlencode($name);
    }
    
    public function getFilamentName(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
    
    public function canAccessPanel(Panel $panel): bool
    {
//        if ($panel->getId() === 'admin') {
//            return str_ends_with($this->email, '@yourdomain.com') && $this->hasVerifiedEmail();
//        }
        
        return true;
    }
    
}
