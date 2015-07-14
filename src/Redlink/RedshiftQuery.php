<?php

namespace Redlink;

use Redlink\Exception\PostgreException;
use Redlink\Exception\RedshiftLoadException;

class RedshiftQuery
{
    /**
     * @var RedshiftConnection
     */
    protected $connection;

    public function __construct(RedshiftConnection $connection)
    {
        $this->connection = $connection;
    }

    public function execute($rawSQL, array $params = array())
    {
        $query = $this->connection->getPDO()->prepare($rawSQL);

        if ($query->execute($params)) {
            return $query;
        }

        throw $this->handleError($query);
    }

    public function handleError(\PDOStatement $query)
    {
        $errorInfo = $query->errorInfo();

        // Error during massive load operations
        // http://docs.aws.amazon.com/redshift/latest/dg/r_STL_LOAD_ERRORS.html
        if (strstr($errorInfo[2], 'stl_load_errors') !== false) {
            return new RedshiftLoadException($errorInfo[2], $errorInfo[0], $errorInfo[1]);
        }

        return new PostgreException($errorInfo[2], $errorInfo[0], $errorInfo[1]);
    }
}