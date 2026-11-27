<?php
declare(strict_types=1);

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Atributo extends Model
{
    protected $table = 'atributos';
    public $timestamps = false;
    protected $guarded = [];

    public function valoresProductos(): HasMany
    {
        return $this->hasMany(ValorProducto::class, 'atributo_id');
    }

    public static function idsForCategory(int $categoryId): array
    {
        return self::query()
            ->whereHas('valoresProductos.producto', fn ($query) => $query->where('id_categoria', $categoryId))
            ->distinct()
            ->orderBy('id')
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();
    }

    public static function nameById(int $id): string
    {
        return (string) (self::query()->whereKey($id)->value('nombre') ?: 'Atributo');
    }
}
