<?php
declare(strict_types=1);
//src/Data/DBConfig.php
namespace Webshop\Data;

use PDO;
use PDOException;
use Webshop\Data\DBConfig;

class DBConnection
{
    private ?PDO $dbh;

    /**
     * Establishes a connection to the database.
     *
     * @throws PDOException If unable to connect to the database.
     */
    protected function connect(): void
    {
        try {
            $this->dbh = new PDO(DBConfig::DB_CONNECTION, DBConfig::DB_USERNAME, DBConfig::DB_PASSWORD);
        } catch (PDOException $e) {
            throw $e;
        }
    }

    /**
     * Returns the PDO connection instance.
     *
     * @return PDO The PDO connection instance.
     */
    protected function connection(): PDO
    {
        return $this->dbh;
    }

    /**
     * Disconnects from the database by setting the PDO connection instance to null.
     */
    protected function disconnect(): void
    {
        $this->dbh = null;
    }

    /**
     * Begins a transaction by establishing a database connection and initiating a transaction.
     */
    public function beginTransaction(): void
    {
        $this->connect();
        $this->dbh->beginTransaction();
    }

    /**
     * Commits the current transaction and disconnects from the database.
     */
    public function commitTransaction(): void
    {
        $this->dbh->commit();
        $this->disconnect();
    }

    /**
     * Rolls back the current transaction and disconnects from the database.
     */
    public function rollBackTransaction(): void
    {
        $this->dbh->rollBack();
        $this->disconnect();
    }

    /**
     * Executes a SQL INSERT statement and returns the last inserted ID.
     *
     * @param string $sql The SQL query to execute.
     * @param array $params The parameters to bind to the query.
     * @return int The last inserted ID.
     * @throws PDOException If an error occurs while executing the query.
     */
    protected function create(string $sql, array $params): int
    {
        try {
            $stmt = $this->dbh->prepare($sql);
            $stmt->execute($params);
            return (int)$this->dbh->lastInsertId();
        } catch (PDOException $e) {
            throw $e;
        }
    }

    /**
     * Executes a SQL SELECT statement and returns all rows as an array.
     *
     * @param string $sql The SQL query to execute.
     * @param array $params The parameters to bind to the query.
     * @return array|bool The first row of the result set as an associative array, or false if no rows are returned.
     * @throws PDOException If an error occurs while executing the query.
     */
    protected function readAll(string $sql, array $params = []): array|bool
    {
        try {
            $stmt = $this->dbh->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw $e;
        }
    }

    /**
     * Executes a SQL SELECT statement and returns the first row as an associative array.
     *
     * @param string $sql The SQL query to execute.
     * @param array $params The parameters to bind to the query.
     * @return array|bool The first row of the result set as an associative array, or false if no rows are returned.
     * @throws PDOException If an error occurs while executing the query.
     */
    protected function readOne(string $sql, array $params): array|bool
    {
        try {
            $stmt = $this->dbh->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw $e;
        }
    }

    /**
     * Executes a SQL UPDATE statement and returns the number of affected rows.
     *
     * @param string $sql The SQL query to execute.
     * @param array $params The parameters to bind to the query.
     * @return int The number of rows affected by the UPDATE statement.
     * @throws PDOException If an error occurs while executing the query.
     */
    protected function update(string $sql, array $params): int
    {
        try {
            $stmt = $this->dbh->prepare($sql);
            $stmt->execute($params);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            throw $e;
        }
    }

    /**
     * Executes a SQL DELETE statement and returns the number of affected rows.
     *
     * @param string $sql The SQL query to execute.
     * @param array $params The parameters to bind to the query.
     * @return int The number of rows affected by the DELETE statement.
     * @throws PDOException If an error occurs while executing the query.
     */
    protected function delete(string $sql, array $params): int
    {
        try {
            $stmt = $this->dbh->prepare($sql);
            $stmt->execute($params);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            throw $e;
        }
    }
}
