<?php

namespace App\Core;

use mysqli;
use mysqli_stmt;
use RuntimeException;

defined('SMSGRAM') or die('Direct access is not allowed.');

/**
 * Yengil mysqli wrapper. PDO ishlatilmaydi — loyiha talabiga ko'ra faqat mysqli.
 */
final class Database
{
    private static ?Database $instance = null;

    private mysqli $conn;

    private function __construct()
    {
        $cfg = Config::get('database');

        mysqli_report(MYSQLI_REPORT_OFF);

        $this->conn = new mysqli(
            $cfg['host'],
            $cfg['username'],
            $cfg['password'],
            $cfg['database'],
            (int) $cfg['port']
        );

        if ($this->conn->connect_errno) {
            throw new RuntimeException('DB ulanish xatosi: ' . $this->conn->connect_error);
        }

        $this->conn->set_charset($cfg['charset'] ?? 'utf8mb4');
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function raw(): mysqli
    {
        return $this->conn;
    }

    /**
     * Prepared statement ijro etadi va mysqli_stmt qaytaradi.
     *
     * @param array<int, mixed> $params
     */
    public function run(string $sql, array $params = []): mysqli_stmt
    {
        $stmt = $this->conn->prepare($sql);

        if ($stmt === false) {
            throw new RuntimeException('SQL tayyorlashda xato: ' . $this->conn->error . ' | SQL: ' . $sql);
        }

        if (!empty($params)) {
            $types = '';
            foreach ($params as $param) {
                $types .= match (true) {
                    is_int($param) => 'i',
                    is_float($param) => 'd',
                    is_null($param) => 's',
                    default => 's',
                };
            }
            $stmt->bind_param($types, ...$params);
        }

        if (!$stmt->execute()) {
            throw new RuntimeException('SQL bajarishda xato: ' . $stmt->error . ' | SQL: ' . $sql);
        }

        return $stmt;
    }

    /** @return array<int, array<string, mixed>> */
    public function select(string $sql, array $params = []): array
    {
        $stmt = $this->run($sql, $params);
        $result = $stmt->get_result();
        $rows = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
        $stmt->close();

        return $rows;
    }

    public function selectOne(string $sql, array $params = []): ?array
    {
        $rows = $this->select($sql, $params);

        return $rows[0] ?? null;
    }

    public function insert(string $table, array $data): int
    {
        $columns = array_keys($data);
        $placeholders = implode(', ', array_fill(0, count($columns), '?'));
        $sql = sprintf(
            'INSERT INTO `%s` (`%s`) VALUES (%s)',
            $table,
            implode('`, `', $columns),
            $placeholders
        );

        $stmt = $this->run($sql, array_values($data));
        $id = $this->conn->insert_id;
        $stmt->close();

        return $id;
    }

    public function update(string $table, array $data, string $where, array $whereParams = []): int
    {
        $set = implode(', ', array_map(fn ($col) => "`$col` = ?", array_keys($data)));
        $sql = sprintf('UPDATE `%s` SET %s WHERE %s', $table, $set, $where);

        $stmt = $this->run($sql, [...array_values($data), ...$whereParams]);
        $affected = $stmt->affected_rows;
        $stmt->close();

        return $affected;
    }

    public function delete(string $table, string $where, array $whereParams = []): int
    {
        $sql = sprintf('DELETE FROM `%s` WHERE %s', $table, $where);
        $stmt = $this->run($sql, $whereParams);
        $affected = $stmt->affected_rows;
        $stmt->close();

        return $affected;
    }

    public function lastInsertId(): int
    {
        return (int) $this->conn->insert_id;
    }

    public function beginTransaction(): void
    {
        $this->conn->begin_transaction();
    }

    public function commit(): void
    {
        $this->conn->commit();
    }

    public function rollback(): void
    {
        $this->conn->rollback();
    }

    public function escape(string $value): string
    {
        return $this->conn->real_escape_string($value);
    }
}
