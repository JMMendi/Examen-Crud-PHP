<?php

namespace App\Db;

use \PDO;
use \PDOException;

require __DIR__."/../../vendor/autoload.php";

class Post extends Conexion {
    private int $id;
    private string $titulo;
    private string $contenido;
    private string $status;
    private int $categoria_id;

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

    public function create() : void {
        $q = "insert into posts (titulo, contenido, status, categoria_id) values (:t, :c, :s, :cid)";

        self::executeQuery($q, [
            ':t' => $this->titulo,
            ':c' => $this->contenido,
            ':s' => $this->status,
            ':cid' => $this->categoria_id,
        ], false, "create");
    }

    public static function read() : array {
        $q = "select posts.*, nombre from posts, categorias where categoria_id = categorias.id";

        $stmt = self::executeQuery($q, [], true, "read (de Posts)");

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Para recoger todos los datos de un post identificado por su Id
    public static function getPostById(int $id) : array {
        $q = "select * from posts where id=:i";

        $stmt = self::executeQuery($q, [':i' => $id], true, "getPostById");

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function delete(int $id) : void {
        $q = "delete from posts where id=:i";

        self::executeQuery($q, [':i' => $id], false, "delete (Post)");
    }

    public function update(int $id) : void {
        $q = "update posts set titulo=:t, contenido=:c, status=:s, categoria_id=:cid where id=:i";

        self::executeQuery($q, [
            ':t' => $this->titulo,
            ':c' => $this->contenido,
            ':s' => $this->status,
            ':cid' => $this->categoria_id,
            ':i' => $id,
        ], false, "update(Post)");
    }

    // Esta función la usaremos para los cambios rápidos entre PUBLICADO y BORRADOR en el index.php
    public static function cambiarStatus(string $status, int $id) : void {
        $q = "update posts set status=:s where id=:i";

        self::executeQuery($q, [
            ':s' => $status,
            ':i' => $id
        ], false, "CambiarStatus");
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
     * Get the value of titulo
     */
    public function getTitulo(): string
    {
        return $this->titulo;
    }

    /**
     * Set the value of titulo
     */
    public function setTitulo(string $titulo): self
    {
        $this->titulo = $titulo;

        return $this;
    }

    /**
     * Get the value of contenido
     */
    public function getContenido(): string
    {
        return $this->contenido;
    }

    /**
     * Set the value of contenido
     */
    public function setContenido(string $contenido): self
    {
        $this->contenido = $contenido;

        return $this;
    }

    /**
     * Get the value of status
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * Set the value of status
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get the value of categoria_id
     */
    public function getCategoriaId(): int
    {
        return $this->categoria_id;
    }

    /**
     * Set the value of categoria_id
     */
    public function setCategoriaId(int $categoria_id): self
    {
        $this->categoria_id = $categoria_id;

        return $this;
    }
}