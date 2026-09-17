<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Panel;
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'email',
        'telefono',
        'estado',
        'password',
        'rol',
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
        ];
    }

    // Esta es la función nativa de Filament que controla el acceso
    public function canAccessPanel(Panel $panel): bool
    {
        // Si intentan entrar a /admin, solo pasan los que tienen rol 'admin'
        if ($panel->getId() === 'admin') {
            return $this->rol === 'admin';
        }

        // Si intentan entrar a /residente, solo pasan los residentes
        if ($panel->getId() === 'residente') {
            return $this->rol === 'residente';
        }

        return false;
    }
    public function propiedades()
    {
        return $this->belongsToMany(Propiedad::class)->withPivot('rol_en_propiedad')->withTimestamps();
    }

    // Un usuario puede realizar muchos pagos
    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }


}
