<?php
class Cuenta extends ActiveRecord
{
   /**
     * Retorna los items para ser paginados
     *
     */
   public function getItemsUsuario($page, $ppage=20)
   {
    	// Recupero el id del usuario logueado para filtrar su cuenta corriente.
    
    	$conditions = 'cuenta.usuario_id = ' . Session::get('id_us') . ' and cuenta.id IS NOT NULL';
    	
    	if ($page) {
    		return $this->paginated("conditions: $conditions", "page: $page");
    	}
    	return $this->find("conditions: $conditions"); 
   }
   
   /**
    * Retorna el saldo de la cuenta corriente de un usuario
    *
    */
   public function getSaldoUsuario()
   {
   	// Recupero el id del usuario logueado para filtrar su cuenta corriente.
   
   	$resta = Load::model('bdt/cuenta')->sum("debe", "conditions: cuenta.usuario_id = " . Session::get('id_us'));
   	$suma = Load::model('bdt/cuenta')->sum("haber", "conditions: cuenta.usuario_id = " . Session::get('id_us'));
   	return $suma - $resta;
   }
   
}