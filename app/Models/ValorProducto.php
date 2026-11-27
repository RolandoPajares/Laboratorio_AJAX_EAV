<?php
declare(strict_types=1);

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class ValorProducto extends Model
{
    protected $table = 'valores_productos';
    public $timestamps = false;
    protected $guarded = [];

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }

    public function atributo(): BelongsTo
    {
        return $this->belongsTo(Atributo::class, 'atributo_id');
    }

    public static function filters(int $categoryId, array $activeFilters = [], ?int $excludeAttribute = null): array
    {
        $query = self::query()
            ->with('atributo')
            ->whereHas('producto', function ($productQuery) use ($categoryId, $activeFilters, $excludeAttribute): void {
                $productQuery->where('id_categoria', $categoryId);
                
                foreach ($activeFilters as $attributeId => $values) {
                
                    if ($excludeAttribute !== null && (int) $attributeId === $excludeAttribute) continue;
                    $productQuery->whereHas('valoresProductos', fn ($valueQuery) => $valueQuery
                        ->where('atributo_id', (int) $attributeId)
                        ->whereIn('valor', $values));
                }
            })
            ->when($excludeAttribute !== null, fn ($query) => $query->where('atributo_id', $excludeAttribute))
            ->select(['atributo_id', 'valor'])
            ->selectRaw('COUNT(DISTINCT producto_id) AS conteo')
            ->groupBy('atributo_id', 'valor')
            ->orderBy('atributo_id')
            ->orderBy('valor');

        return $query->get()->map(fn (self $item): array => [
            'atributo_id' => (int) $item->atributo_id,
            'atributo' => (string) $item->atributo->nombre,
            'valor' => (string) $item->valor,
            'conteo' => (int) $item->conteo,
        ])->all();
    }
}
