<?php

class Model{

    public string $created_at;
    public string $updated_at;

    function __construct($values = NULL){
        if(!is_null($values)){
            foreach($values as $attribut => $valeur){
                $this->set($attribut, $valeur);
            }
        }
    }

    public function get($attribut){
        return $this->$attribut;
    }

    public function set($attribut, $valeur){
        $this->$attribut = $valeur;
    }
}

?>