<?php

namespace Core\Library;

class ModelMain
{
    public $db;
    public $validationRules = [];
    protected $table;
    protected $primaryKey = "id"; // valor padrão (caso não seja sobrescrito no model)

    /**
     * construct
     */
    public function __construct()
    {
        $this->db = new Database(
            $_ENV['DB_CONNECTION'],
            $_ENV['DB_HOST'],
            $_ENV['DB_PORT'],
            $_ENV['DB_DATABASE'],
            $_ENV['DB_USER'],
            $_ENV['DB_PASSWORD']
        );

        if (!$this->table) {
            // Se não foi definido no model, usa o nome da classe em minúsculo
            $class = get_class($this);
            $parts = explode("\\", $class);
            $this->table = strtolower(end($parts));
        }

        $this->db->table($this->table);
    }

    /**
     * getById
     *
     * @param int $id 
     * @return array
     */
    public function getById($id)
    {
        if (empty($id)) {
            return [];
        }

        return $this->db->where($this->primaryKey, $id)->first();
    }

    /**
     * find
     *
     * @param int $id 
     * @return array
     */
    public function find($id)
    {
        return $this->getById($id);
    }

    /**
     * lista
     *
     * @param string $orderby 
     * @param string $direction
     * @return array
     */
    public function lista($orderby = 'descricao', $direction = "ASC")
    {   
        return $this->db->orderBy($orderby, $direction)->findAll();
    }

    /**
     * insert
     *
     * @param array $dados 
     * @return bool|int
     */
    public function insert($dados)
    {
        if (Validator::make($dados, $this->validationRules)) {
            return 0;
        }

        return $this->db->insert($dados) > 0;
    }

    /**
     * update
     *
     * @param int|string $id
     * @param array $dados 
     * @return bool|int
     */
    public function update($id, $dados)
    {
        if (Validator::make($dados, $this->validationRules)) {
            return 0;
        }

        return $this->db->where($this->primaryKey, $id)->update($dados) > 0;
    }

    /**
     * delete
     *
     * @param array $dados 
     * @return bool
     */
    public function delete($dados)
    {
        if (!isset($dados[$this->primaryKey])) {
            return false;
        }

        return $this->db->where($this->primaryKey, $dados[$this->primaryKey])->delete() > 0;
    }
}
