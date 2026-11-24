<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/** Precio de un grupo de una experiencia. El precio es el TOTAL del grupo (MXN), no por persona. */
class ExperienciaGrupo extends Model
{
    use SoftDeletes;

    protected $table = 'experiencia_grupos';
    protected $primaryKey = 'id_experiencia_grupo';

    protected $fillable = ['id_experiencia', 'personas', 'personas_hasta', 'precio'];

    /** "de 4 a 8 personas", "de 2 personas" */
    public function rangoTexto(): string
    {
        $hasta = (int) ($this->personas_hasta ?? $this->personas);

        if ($hasta > (int) $this->personas) {
            return "de {$this->personas} a {$hasta} personas";
        }

        return (int) $this->personas === 1 ? 'de 1 persona' : "de {$this->personas} personas";
    }

    protected function casts(): array
    {
        return [
            'personas' => 'integer',
            'personas_hasta' => 'integer',
            'precio' => 'decimal:2',
        ];
    }
}
