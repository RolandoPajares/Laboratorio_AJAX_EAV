<?php
declare(strict_types=1);

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Producto extends Model
{
    protected $table = 'productos';
    public $timestamps = false;
    protected $guarded = [];

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'id_categoria');
    }

    public function valoresProductos(): HasMany
    {
        return $this->hasMany(ValorProducto::class, 'producto_id');
    }

    public static function search(?int $categoryId, string $query, array $filters, int $page, int $limit): array
    {
        $term = '%' . self::escapeLike($query) . '%';
        $builder = self::query()
            ->with(['categoria', 'valoresProductos.atributo'])
            ->when($categoryId !== null, fn ($products) => $products->where('id_categoria', $categoryId))
            ->when($query !== '', function ($products) use ($term): void {
                $products->where(function ($search) use ($term): void {
                    $search->where('nombre', 'LIKE', $term)
                        ->orWhereHas('categoria', fn ($category) => $category->where('nombre', 'LIKE', $term));
                });
            });

        foreach ($filters as $attributeId => $values) {
            $builder->whereHas('valoresProductos', fn ($valueQuery) => $valueQuery
                ->where('atributo_id', (int) $attributeId)
                ->whereIn('valor', $values));
        }

        $total = (clone $builder)->count();
        $products = $builder->orderBy('nombre')->orderBy('id')->forPage($page, $limit)->get();

        $items = $products->map(function (self $product): array {
            $attributes = [];
            foreach ($product->valoresProductos as $value) {
                $attributes[$value->atributo->nombre][] = $value->valor;
            }

            return [
                'nombre' => (string) $product->nombre,
                'categoria_id' => (int) $product->categoria->id,
                'categoria' => (string) $product->categoria->nombre,
                'atributos' => $attributes,
            ];
        })->all();

        return [
            'productos' => $items,
            'total' => $total,
            'pagina' => $page,
            'limite' => $limit,
            'total_paginas' => $total === 0 ? 0 : (int) ceil($total / $limit),
        ];
    }

    private static function escapeLike(string $value): string
    {
        return str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $value);
    }
}
