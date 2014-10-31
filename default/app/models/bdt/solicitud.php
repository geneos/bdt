<?php

Load::models('sistema/usuario','bdt/categoriadetrabajo');

class Solicitud extends ActiveRecord
{
    
    
    public function initialize()
    {
        //Relaciones
        $this->belongs_to('usuario');
        $this->belongs_to('categoriadetrabajo');
    }    
    
    
   /**
     * Retorna los items para ser paginados
     *
     */
   public function getItems($page = null, $ppage=20)
   {
        $conditions = 'solicitud.usuario_id != ' . Session::get('id_us') . ' and solicitud.id IS NOT NULL';
        
        if ($page) {
            return $this->paginated("conditions: $conditions", "page: $page");
        }
        return $this->find("conditions: $conditions");
   }

   
    /**
     * Retorna los items para los servicios
     *
     */
   
    public function getItemsCategoriadetrabajo($cat, $page = null, $ppage=20)
    {

        $conditions = 'solicitud.usuario_id != ' . Session::get('id_us') . ' and solicitud.categoriadetrabajo_id = ' . $cat . ' and solicitud.id IS NOT NULL';
        
        if ($page) {
            return $this->paginated("conditions: $conditions", "page: $page");
        }
        return $this->find("conditions: $conditions");
        

    }
    
    /**
     * Retorna los items para los servicios
     *
     */
     
    public function getItemsUsuario($page)
    {
    	
    	// Recupero el id del usuario logueado para filtrar sus solicituds.
    
    	$conditions = 'solicitud.usuario_id = ' . Session::get('id_us') . ' and solicitud.id IS NOT NULL';
    	
    	if ($page) {
    		return $this->paginated("conditions: $conditions", "page: $page");
    	}
    	return $this->find("conditions: $conditions"); 
    
    }
    
    
    /**
     * Retorna los items para el Dashboard
     *
     */
     
    public function getItemsDashboard()
    {
    
    	$conditions = 'solicitud.id IS NOT NULL';
    	return $this->find("conditions: $conditions");
    
    }    
    

   
    /**
     * Método para obtener el nombre del item
     */
    
    public static function getName($id) {
        $item = Load::model('solicitud')->find_first($id);
        return $item->nombre;
    }
    
    /**
     * Cambia estado a aceptada
     *
     */
    public function aceptar()
    {
    	$this->estado = 'A';
    	$this->save();
    }
     
    /**
     * Cambia estado a rechazada
     *
     */
    public function rechazar()
    {
    	$this->estado = 'R';
    	$this->save();
    }
    
    /**
     * Cambia estado a rechazada
     *
     */
    public function registrar_horas($horas)
    {
    	$this->horas -= $horas;
    	$this->save();
    }
        
}