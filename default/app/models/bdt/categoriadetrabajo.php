<?php

class Categoriadetrabajo extends ActiveRecord {

    /**
     * Retorna los items para ser paginados
     *
     */
    public function getItems($page=null) {
        $conditions = 'categoriadetrabajo.id IS NOT NULL'; 
        if($page) {            
            return $this->paginated("conditions: $conditions", "page: $page");
        }
        return $this->find("conditions: $conditions");
        
        //return $this->paginate("page: $page", "per_page: $ppage", 'order: id desc');
    }

    /**
     * Método para obtener el nombre del item
     */
    public static function getName($id) {
        $item = Load::model('categoriadetrabajo')->find_first($id);
        return $item->nombre;
    }

}
