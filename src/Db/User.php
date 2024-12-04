<?php

namespace App\Db;

use \PDO;
use \PDOException;

require __DIR__."/../../vendor/autoload.php";

class User extends Conexion {
    private int $id;
    private string $email;
    private string $pass;

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
    
    // Buscamos en la tabla aquel registro que coincida con el email, validamos que la contraseña sea correcta y retornamos el email.
    public static function isLoginValido(string $email, string $pass) : bool|array {
        $q = "select email, pass from users where email=:e";

        $stmt = self::executeQuery($q, [':e' => $email], true, "isLoginValido");

        $resultado = $stmt->fetchAll(PDO::FETCH_OBJ);

        // Si no da ninguna fila/resultado, retornamos falso.
        if (count($resultado) === 0) {
            return false;
        }
        // si no es la contraseña correcta, retornamos falso.
        if (!password_verify($pass, $resultado[0]->pass)) {
            return false;
        }
        // si llegamos aquí, el login es válido.

        return [$email];
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
     * Get the value of email
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Set the value of email
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of pass
     */
    public function getPass(): string
    {
        return $this->pass;
    }

    /**
     * Set the value of pass
     */
    public function setPass(string $pass): self
    {
        $this->pass = password_verify($pass, PASSWORD_BCRYPT);

        return $this;
    }
}