<?php
declare(strict_types=1);

final class FiltroValidator
{
    public static function category(mixed $value): int
    {
        if (
            $value === null
            || is_array($value)
            || filter_var($value, FILTER_VALIDATE_INT) === false
            || (int) $value < 1
        ) {
            throw new InvalidArgumentException(
                'categoria_id debe ser un entero positivo.'
            );
        }

        return (int) $value;
    }

    public static function optionalCategory(mixed $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        return self::category($value);
    }

    public static function query(mixed $value): string
    {
        $value = is_string($value) ? trim($value) : '';

        if (mb_strlen($value) > 100) {
            throw new InvalidArgumentException(
                'La búsqueda no puede superar 100 caracteres.'
            );
        }

        return $value;
    }

    public static function pagination(mixed $page, mixed $limit): array
    {
        $page = filter_var($page ?? 1, FILTER_VALIDATE_INT);
        $limit = filter_var($limit ?? 12, FILTER_VALIDATE_INT);

        if (
            $page === false
            || $page < 1
            || $limit === false
            || $limit < 1
            || $limit > 100
        ) {
            throw new InvalidArgumentException('Paginación inválida.');
        }

        return [(int) $page, (int) $limit];
    }

    public static function filters(mixed $raw): array
    {
        if ($raw === null) {
            return [];
        }

        if (!is_array($raw) || count($raw) > 20) {
            throw new InvalidArgumentException(
                'La estructura de filtros es inválida.'
            );
        }

        $result = [];

        foreach ($raw as $attributeId => $values) {
            if (
                filter_var($attributeId, FILTER_VALIDATE_INT) === false
                || (int) $attributeId < 1
                || !is_array($values)
                || count($values) > 20
            ) {
                throw new InvalidArgumentException(
                    'Los filtros recibidos son inválidos.'
                );
            }

            $cleanValues = [];

            foreach ($values as $value) {
                if (
                    !is_string($value)
                    || mb_strlen($value) < 1
                    || mb_strlen($value) > 255
                ) {
                    throw new InvalidArgumentException(
                        'Los valores de filtro son inválidos.'
                    );
                }

                $cleanValues[] = $value;
            }

            if ($cleanValues !== []) {
                $result[(int) $attributeId] = array_values(
                    array_unique($cleanValues)
                );
            }
        }

        return $result;
    }
}
