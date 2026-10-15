<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Persona = usuario del sistema (cliente o administrador).
 * Es el modelo que usa Auth (ver config/auth.php).
 */
class Persona extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, SoftDeletes;

    protected $table = 'personas';
    protected $primaryKey = 'id_persona';

    protected $fillable = ['nombre', 'ap', 'am', 'email', 'telefono', 'contrasena', 'id_rol'];

    protected $hidden = ['contrasena'];

    // La tabla no tiene columna remember_token.
    protected $rememberTokenName = '';

    protected function casts(): array
    {
        return [
            'contrasena' => 'hashed',
            'email_verified_at' => 'datetime',
        ];
    }

    /** Auth busca la contraseña con este método; la columna se llama "contrasena". */
    public function getAuthPasswordName(): string
    {
        return 'contrasena';
    }

    public function getAuthPassword(): string
    {
        return $this->contrasena;
    }

    public function rol(): BelongsTo
    {
        return $this->belongsTo(Rol::class, 'id_rol', 'id_rol');
    }

    public function solicitudes(): HasMany
    {
        return $this->hasMany(Solicitud::class, 'id_persona', 'id_persona');
    }

    public function solicitudesConsultoria(): HasMany
    {
        return $this->hasMany(SolicitudConsultoria::class, 'id_persona', 'id_persona');
    }

    public function esAdmin(): bool
    {
        return $this->rol?->nombre === Rol::ADMINISTRADOR;
    }

    protected function nombreCompleto(): Attribute
    {
        return Attribute::get(fn () => trim("{$this->nombre} {$this->ap} {$this->am}"));
    }
}
