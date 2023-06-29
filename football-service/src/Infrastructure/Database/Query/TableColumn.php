<?php


namespace Sportal\FootballApi\Infrastructure\Database\Query;


class TableColumn
{
    private string $tableName;

    private string $columnName;

    /**
     * TableColumn constructor.
     * @param string $tableName
     * @param string $columnName
     */
    public function __construct(string $tableName, string $columnName)
    {
        $this->tableName = $tableName;
        $this->columnName = $columnName;
    }

    /**
     * @return string
     */
    public function getTableName(): string
    {
        return $this->tableName;
    }

    /**
     * @return string
     */
    public function getColumnName(): string
    {
        return $this->columnName;
    }
}