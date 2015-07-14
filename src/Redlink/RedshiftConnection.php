<?php

namespace Redlink;

class RedshiftConnection
{
    /**
     * @var array Default configuration
     */
    protected $config = array(
        'host'      => 'localhost',
        'port'      => 5439,
        'dbname'    => 'default',
        'options'   => '--client_encoding=UTF8',
    );

    /**
     * @var string
     */
    protected $dsn;

    protected $username;

    protected $password;

    /**
     * @var \PDO
     */
    protected $PdoConnection;

    public function __construct(array $config = array(), $username = '', $password = '')
    {
        $this->config = array_replace($this->config, $config);
        $this->username = $username;
        $this->password = $password;
    }

    public function createDsn()
    {
        $params = array();
        foreach ($this->config as $key => $value) {
            $params[] = sprintf('%s=%s', $key, $value);
        }

        $this->dsn = 'pgsql:' . implode(' ', $params);

        return $this->dsn;
    }

    public function connect()
    {
        if ($this->PdoConnection) {
            return $this->PdoConnection;
        }

        $this->createDsn();
        $this->PdoConnection = new \PDO($this->dsn, $this->username, $this->password);

        return $this->PdoConnection;
    }

    public function close()
    {
        unset($this->PdoConnection);
    }

    public function reset()
    {
        $this->close();
        $this->connect();
    }

    public function getPDO()
    {
        return $this->PdoConnection;
    }

    public function createQuery()
    {
        return new RedshiftQuery($this);
    }
}