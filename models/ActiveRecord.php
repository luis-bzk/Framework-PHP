<?php

namespace Model;

class ActiveRecord {

    // DATA BASE
    protected static $db;
    protected static $table = '';
    protected static $columnsDB = [];

    // messages and alerts
    protected static $alerts = [];
    
    // Define DB connection - includes/database.php
    public static function setDB($database) 
    {
        self::$db = $database;
    }

    public static function setAlert($type, $message) 
    {
        static::$alerts[$type][] = $message;
    }

    // validation
    public static function getAlerts(): array
    {
        return static::$alerts;
    }

    public function validationAlerts(): array
    {
        static::$alerts = [];
        return static::$alerts;
    }

    // query the database for create a new object in memory
    public static function consultSQL($query): array
    {
        // query database
        $result = self::$db->query($query);

        // Iterate the results
        $array = [];
        while($registro = $result->fetch_assoc()) {
            $array[] = static::createObject($registro);
        }

        // set free the memory
        $result->free();

        // return results
        return $array;
    }

    // Create an object that is equals to the DB
    protected static function createObject($register)
    {
        $object = new static;

        foreach($register as $key => $value ) {
            if(property_exists( $object, $key  )) {
                $object->$key = $value;
            }
        }

        return $object;
    }

    // Identify and match the attributes of the database
    public function attributes()
    {
        $attributes = [];
        foreach(static::$columnsDB as $column) {
            if($column === 'id') {
                continue;
            }
            $attributes[$column] = $this->$column;
        }
        return $attributes;
    }

    // Sanitize the data before saves on the DB
    public function sanitizeAttributes()
    {
        $attributes = $this->attributes();
        $sanitized = [];
        foreach($attributes as $key => $value ) {
            $sanitized[$key] = self::$db->escape_string($value);
        }
        return $sanitized;
    }

    // Synchronize Db with memory objects
    public function synchronize($args=[])
    {
        foreach($args as $key => $value) {
          if(property_exists($this, $key) && !is_null($value)) {
            $this->$key = $value;
          }
        }
    }

    // Records - CRUD
    public function save()
    {
        $result = '';
        if(!is_null($this->id)) {
            // update
            $result = $this->update();
        } else {
            // Creando un nuevo registro
            $result = $this->create();
        }
        return $result;
    }

    // All records
    public static function all()
    {
        $query = "SELECT * FROM " . static::$table;
        return self::consultSQL($query);
    }

    // search record by ID
    public static function find($id)
    {
        $query = "SELECT * FROM " . static::$table  ." WHERE id = ${id}";
        $result = self::consultSQL($query);
        return array_shift( $result );
    }

    // Obtener Registros con cierta cantidad
    public static function get($limit)
    {
        $query = "SELECT * FROM " . static::$table . " LIMIT ${limit}";
        $result = self::consultSQL($query);
        return array_shift( $result ) ;
    }

     // Search a value in a column in a table where is a Reference
    public static function where($column, $value) {
        $query = "SELECT * FROM " . static::$table . " WHERE ${column} = '${value}'";
        $result = self::consultSQL($query);
        return array_shift( $result ) ;
    }

    // Pass your main consult SQL from a function
    public static function SQL($consult) {
        $query = $consult;
        $result = self::consultSQL($query);
        return $result;
    }


    // crea un nuevo registro
    public function create()
    {
        // Sanitize data
        $attributes = $this->sanitizeAttributes();

        // Insert on database
        $query = " INSERT INTO " . static::$table . " ( ";
        $query .= implode(', ', array_keys($attributes))
        ;
        $query .= " ) VALUES (' "; 
        $query .= implode("', '", array_values($attributes));
        $query .= " ') ";

        // query result
        $result = self::$db->query($query);
        return [
           'result' =>  $result,
           'id' => self::$db->insert_id
        ]
        ;
    }

    // Update record
    public function update()
    {
        // Sanitize data
        $attributes = $this->sanitizeAttributes();

        // Iterate to add each field of the DB
        $values = [];
        foreach($attributes as $key => $value) {
            $values[] = "{$key}='{$value}'";
        }

        // Consult SQL
        $query = "UPDATE " . static::$table ." SET ";
        $query .=  implode(', ', $values );
        $query .= " WHERE id = '" . self::$db->escape_string($this->id) . "' ";
        $query .= " LIMIT 1 "; 

        // Update DB
        return self::$db->query($query);
    }

    // Delete record by ID
    public function delete()
    {
        $query = "DELETE FROM "  . static::$table . " WHERE id = " .
            self::$db->escape_string($this->id) . " LIMIT 1";
        return self::$db->query($query)
        ;
    }

}