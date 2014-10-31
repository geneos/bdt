<?php
/**
 * Carga del modelo.
 */
Load::models('bdt/cuenta');
 
class CuentaController extends BackendController {
    
    /**
     * Método principal
     */
    public function index() {
        DwRedirect::toAction('listar');
    }
    
    /**
     * Método para listar
     */
    public function listar($order='cuenta.ingreso_at.asc', $page='pag.1') { 
        $page = (Filter::get($page, 'page') > 0) ? Filter::get($page, 'page') : 1;
        $item = new Cuenta();
        $this->listItems = $item->getItemsUsuario($page);
        $this->saldo = $item->getSaldoUsuario();
        $this->order = $order;        
        $this->page_title = 'Cuenta Corriente de horas';
        $this->page_module = "Cuenta Corriente";
        $this->set_title = "Cuenta Corriente de horas";
    } 
 
    /**
     * Crea un Registro
     */
    public function create ()
    {
        
        
        /**
         * Se verifica si el usuario envio el form (submit) y si ademas 
         * dentro del array POST existe uno llamado "menus"
         * el cual aplica la autocarga de objeto para guardar los 
         * datos enviado por POST utilizando autocarga de objeto
         */
        if(Input::hasPost('respuesta')){
            /**
             * se le pasa al modelo por constructor los datos del form y ActiveRecord recoge esos datos
             * y los asocia al campo correspondiente siempre y cuando se utilice la convención
             * model.campo
             */
            $item = new Respuesta(Input::post('respuesta'));
            //En caso que falle la operación de guardar
            if($item->create()){
                DwMessage::valid('Item agregado correctamente!');
                return DwRedirect::toAction('listar');          
            }else{
                Flash::error('Falló Operación');
            }
        }
        $this->page_title = 'Agregar Respuesta';
        $this->page_module = "Respuesta";
        $this->set_title = "Agregar Respuesta";
        
    }
 
    /**
     * Edita un Registro
     *
     * @param int $id (requerido)
     */
    public function edit($id)
    {
        $item = new Respuesta();
        
        //se verifica si se ha enviado el formulario (submit)
        if(Input::hasPost('respuesta')){
 
            if($item->update(Input::post('respuesta'))){
                 Flash::valid('Operación exitosa');
                //enrutando por defecto al index del controller
                return Router::redirect();
            } else {
                Flash::error('Falló Operación');
            }
        } else {
            //Aplicando la autocarga de objeto, para comenzar la edición
            $this->respuesta = $item->find_by_id((int)$id);
        }

        $this->page_title = 'Editar Respuesta';
        $this->page_module = "Respuesta";
        $this->set_title = "Editar Respuesta";
        
    }
 
    /**
     * Eliminar una categoria
     * 
     * @param int $id (requerido)
     */
    public function del($id)
    {
        $item = new Respuesta();
        if ($item->delete((int)$id)) {
                Flash::valid('Operación exitosa');
        }else{
                Flash::error('Falló Operación'); 
        }
 
        //enrutando por defecto al index del controller
        return Router::redirect();
    }
    
    /**
     * Rechazar una Respuesta
     *
     * @param int $id (requerido)
     */
    
    public function ko($id)
    {
    	$item = new Respuesta();
    	//Aplicando la autocarga de objeto, para comenzar la edición
    	$this->respuesta = $item->find_by_id((int)$id);
    	$this->respuesta->rechazar();
    	DwRedirect::toAction('listar');
    
    }
    
    /**
     * Aceptar una Respuesta
     *
     * @param int $id (requerido)
     */
    
    public function ok($id)
    {
    	$item = new Respuesta();
    	//Aplicando la autocarga de objeto, para comenzar la edición
    	$this->respuesta = $item->find_by_id((int)$id);
    	$this->respuesta->aceptar();
    	DwRedirect::toAction('listar');
    
    }
        
    
}
