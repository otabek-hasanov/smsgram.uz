<?php

namespace App\Core;

defined('SMSGRAM') or die('Direct access is not allowed.');

/**
 * Yengil Active-Record uslubidagi base model (mysqli ustida, PDO emas).
 */
abstract class Model
{
    protected static string $table;
    protected static string $primaryKey = 'id';

    protected static function db(): Database
    {
        return Database::getInstance();
    }

    public static function all(string $orderBy = 'id DESC'): array
    {
        return static::db()->select('SELECT * FROM `' . static::$table . '` ORDER BY ' . $orderBy);
    }

    public static function find(int $id): ?array
    {
        return static::db()->selectOne(
            'SELECT * FROM `' . static::$table . '` WHERE `' . static::$primaryKey . '` = ? LIMIT 1',
            [$id]
        );
    }

    public static function findBy(string $column, mixed $value): ?array
    {
        return static::db()->selectOne(
            'SELECT * FROM `' . static::$table . '` WHERE `' . $column . '` = ? LIMIT 1',
            [$value]
        );
    }

    public static function where(string $column, mixed $value, string $orderBy = 'id DESC'): array
    {
        return static::db()->select(
            'SELECT * FROM `' . static::$table . '` WHERE `' . $column . '` = ? ORDER BY ' . $orderBy,
            [$value]
        );
    }

    public static function create(array $data): int
    {
        return static::db()->insert(static::$table, $data);
    }

    public static function updateById(int $id, array $data): int
    {
        return static::db()->update(
            static::$table,
            $data,
            '`' . static::$primaryKey . '` = ?',
            [$id]
        );
    }

    public static function deleteById(int $id): int
    {
        return static::db()->delete(
            static::$table,
            '`' . static::$primaryKey . '` = ?',
            [$id]
        );
    }

    public static function count(string $where = '1', array $params = []): int
    {
        $row = static::db()->selectOne(
            'SELECT COUNT(*) as total FROM `' . static::$table . '` WHERE ' . $where,
            $params
        );

        return (int) ($row['total'] ?? 0);
    }

    public static function paginate(int $page = 1, int $perPage = 20, string $orderBy = 'id DESC'): array
    {
        $page = max(1, $page);
        $offset = ($page - 1) * $perPage;

        $items = static::db()->select(
            'SELECT * FROM `' . static::$table . '` ORDER BY ' . $orderBy . ' LIMIT ? OFFSET ?',
            [$perPage, $offset]
        );

        $total = static::count();

        return [
            'items'       => $items,
            'total'       => $total,
            'page'        => $page,
            'per_page'    => $perPage,
            'last_page'   => (int) max(1, ceil($total / $perPage)),
        ];
    }
}
