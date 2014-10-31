<?php

Load::models('sistema/usuario','bdt/solicitud');

class Respuesta extends ActiveRecord
{
    
    
    public function initialize()
    {
        //Relaciones
        $this->belongs_to('usuario');
        $this->belongs_to('solicitud');
    }    
    
    /**
     * Retorna los items para ser paginados
     *
     */
    public function getItems($page = null, $ppage=20)
    {
    	$conditions = 'respuesta.id IS NOT NULL and respuesta.solicitud_id in (select of.id from solicitud of where of.usuario_id = ' . Session::get('id_us') . ')';
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
    	 
    	// Recupero el id del usuario logueado para filtrar sus respuestas.
    
    	$conditions = 'respuesta.usuario_id = ' . Session::get('id_us') . ' and respuesta.id IS NOT NULL';
    	 
    	if ($page) {
    		return $this->paginated("conditions: $conditions", "page: $page");
    	}
    	return $this->find("conditions: $conditions");
    
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
     * Cambia estado a finalizar
     *
     */
    public function finalizar()
    {
    	$this->estado = 'F';
    	$this->save();
    }  

    /**
     * Cambia estado a finalizar
     *
     */
    public function cancelar()
    {
    	$this->estado = 'C';
    	$this->save();
    }    
   
    
}