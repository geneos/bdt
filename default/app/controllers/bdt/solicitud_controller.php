<?php
/**
 * Carga del modelo.
 */
Load::models('bdt/solicitud');
 
class SolicitudController extends BackendController {
    
    /**
     * Método principal
     */
    public function index() {
        DwRedirect::toAction('listar');
    }
    
    /**
     * Método para listar
     */
    public function listar($order='solicitud.actualizacion_in.asc', $page='pag.1') { 
        $page = (Filter::get($page, 'page') > 0) ? Filter::get($page, 'page') : 1;
        $item = new Solicitud();
        $this->listItems = $item->getItems($page);
        $this->order = $order;        
        $this->page_title = 'Listado de Solicitudes';
        $this->page_module = "Solicitud";
        $this->set_title = "Listado de Solicitudes";
    }    

    
    /**
     * Método para listar
     */
    public function listar_us($order='solicitud.actualizacion_in.asc', $page='pag.1') {
    	$page = (Filter::get($page, 'page') > 0) ? Filter::get($page, 'page') : 1;
    	$item = new Solicitud();
    	//$this->listItems = $item->getItems($page);
    	$this->listItems = $item->getItemsUsuario($page);
    	$this->order = $order;
    	$this->page_title = 'Listado de Solicitudes';
    	$this->page_module = "Solicitud";
    	$this->set_title = "Listado de Solicitudes";
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
        if(Input::hasPost('solicitud')){
            /**
             * se le pasa al modelo por constructor los datos del form y ActiveRecord recoge esos datos
             * y los asocia al campo correspondiente siempre y cuando se utilice la convención
             * model.campo
             */
            $item = new Solicitud(Input::post('solicitud'));
            $item->estado = "I";
            //En caso que falle la operación de guardar
            if($item->create()){
                DwMessage::valid('Item agregado correctamente!');
                return DwRedirect::toAction('listar');          
            }else{
                Flash::error('Falló Operación');
            }
        }
        $this->page_title = 'Agregar Solicitud';
        $this->page_module = "Solicitud";
        $this->set_title = "Agregar Solicitud";
        
    }
 
    /**
     * Edita un Registro
     *
     * @param int $id (requerido)
     */
    public function edit($id)
    {
        $item = new Solicitud();
        
        //se verifica si se ha enviado el formulario (submit)
        if(Input::hasPost('solicitud')){
 
            if($item->update(Input::post('solicitud'))){
                 Flash::valid('Operación exitosa');
                //enrutando por defecto al index del controller
                return Router::redirect();
            } else {
                Flash::error('Falló Operación');
            }
        } else {
            //Aplicando la autocarga de objeto, para comenzar la edición
            $this->solicitud = $item->find_by_id((int)$id);
        }
    }
 
    /**
     * Eliminar una categoria
     * 
     * @param int $id (requerido)
     */
    public function del($id)
    {
        $item = new Solicitud();
        if ($item->delete((int)$id)) {
                Flash::valid('Operación exitosa');
        }else{
                Flash::error('Falló Operación'); 
        }
 
        //enrutando por defecto al index del controller
        return Router::redirect();
    }

    /**
     * Rechazar una Solicitud
     *
     * @param int $id (requerido)
     */
    
    public function ko($id)
    {
    	$item = new Solicitud();
    	//Aplicando la autocarga de objeto, para comenzar la edición
    	$this->solicitud = $item->find_by_id((int)$id);
    	$this->solicitud->rechazar();
    	DwRedirect::toAction('listar');
    
    }
    
    /**
     * Aceptar una Solicitud
     *
     * @param int $id (requerido)
     */
    
    public function ok($id)
    {
    	$item = new Solicitud();
    	//Aplicando la autocarga de objeto, para comenzar la edición
    	$this->solicitud = $item->find_by_id((int)$id);
    	$this->solicitud->aceptar();
    	
    	// Paso como variables de sesion los datos de la solicitud aceptada
    	
    	Session::set('id_solicitud', (int)$id);
    	
    	DwRedirect::to('bdt/respuesta/create');
    
    }
    
    /**
     * Lista las categorias
     *
     * @param int $id (requerido)
     */
    
    public function categorias($cat = 0, $order='solicitud.actualizacion_in.asc', $page='pag.1')
    {
    	if($cat==0) {
    		$page = (Filter::get($page, 'page') > 0) ? Filter::get($page, 'page') : 1;
    		$item = new Solicitud();
    		$this->listItems = $item->getItems($page);
    		$this->order = $order;
    		$this->page_title = 'Listado de Solicitudes';
    		$this->page_module = "Solicitud";
    		$this->set_title = "Listado de Solicitudes";
    	} else {
    		$page = (Filter::get($page, 'page') > 0) ? Filter::get($page, 'page') : 1;
    		$item = new Solicitud();
    		$this->listItems = $item->getItemsCategoriadetrabajo($cat, $page);
    		$this->order = $order;
    		$this->page_title = 'Listado de Solicitudes';
    		$this->page_module = "Solicitud";
    		$this->set_title = "Listado de Solicitudes";
    		
    	}
    	
    }  
    
    
}
