<?php

class MySQLConnection {
    private $host;
    private $port;
    private $username;
    private $password;
    private $db;
    private $pdo;
    private $cachedQueries = array();
    
    public function __construct() {
        $this->host = 'localhost' ; 
        $this->port = '3306';
        $this->username= 'root';
        $this->password='';
        $this->db='egovt';
    }
    
    private function connect() {

        if ($this->pdo === null) {
            $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->db};charset=utf8mb4";
            $this->pdo = new PDO($dsn, $this->username, $this->password, array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ));
        }
        return $this->pdo;
    }
    
    public function execute($sql, $params= array()) {
        $pdo = $this->connect();
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
    
    public function query($sql, $params = array(), $cacheKey = '', $useCache = true) {
        $cacheDuration = 86400; // Duración máxima del caché en segundos (1 día)
        if ($useCache && !empty($cacheKey) && isset($this->cachedQueries[$cacheKey])) {
            $cachedQuery = $this->cachedQueries[$cacheKey];
            // Comprobamos si la consulta está caducada
            if (time() - $cachedQuery['timestamp'] <= $cacheDuration) {
                return $cachedQuery['result'];
            } else {
                // Eliminamos la consulta de la caché si ha caducado
                unset($this->cachedQueries[$cacheKey]);
            }
        }
        
        if (empty($params)) {
            $stmt = $this->execute($sql);
        } else {
            $stmt = $this->execute($sql, $params);
        }

        if ($useCache && !empty($cacheKey)) {
            // Almacenamos la consulta en la caché con una marca de tiempo
            $this->cachedQueries[$cacheKey] = array(
                'timestamp' => time(),
                'result' => $stmt
            );
        }
        return $stmt;
    }
    
    public function close() {
        $this->pdo = null;
    }
    
    public function __destruct() {
        $this->close();
    }
}