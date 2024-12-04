<?php

namespace App\Db;

use \PDO;
use \PDOException;

require __DIR__."/../../vendor/autoload.php";

class Categoria extends Conexion {
    private int $id;
    private string $nombre;

    public static function executeQuery(string $q, array $options = [], bool $devolverAlgo = false, string $error) {
        $stmt = parent::getConexion()->prepare($q);

        try {
            $stmt->execute($options);
        } catch (PDOException $ex) {
            throw new PDOException("Error en el ".$error. " :" . $ex->getMessage(), -1);
        } finally {
            parent::cerrarConexion();
        }

        if ($devolverAlgo) {
            return $stmt;
        }
    }

    // Para devolver un array de las Ids de las Categorias
    public static function devolverIdsCategorias() : array {
        $q = "select id from categorias";

        $stmt = self::executeQuery($q, [], true, "devolverIdsCategorias");

        $ids = [];
        while ($fila = $stmt->fetch(PDO::FETCH_OBJ)) {
            $ids[] = $fila->id;
        }
        return $ids;
    }

    public static function read() : array {
        $q = "select * from categorias order by id";

        $stmt = self::executeQuery($q, [], true, "read(de Categoria)");

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }


    /**
     * Get the value of id
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of nombre
     */
    public function getNombre(): string
    {
        return $this->nombre;
    }

    /**
     * Set the value of nombre
     */
    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }
}