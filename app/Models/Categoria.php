<?php
declare(strict_types=1);

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Categoria extends Model
{
    protected $table = 'categorias';
    public $timestamps = false;
    protected $guarded = [];

    public function productos(): HasMany
    {
        return $this->hasMany(Producto::class, 'id_categoria');
    }
}
