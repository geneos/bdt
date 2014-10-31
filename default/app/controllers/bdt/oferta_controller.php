<?php
/**
 * Carga del modelo.
 */
Load::models('bdt/oferta');
Load::models('sistema/usuario');
Load::models('personas/persona');
Load::lib('email');
 
class OfertaController extends BackendController {
    
    /**
     * Método principal
     */
    public function index() {
        DwRedirect::toAction('listar');
    }
    
    /**
     * Método para listar
     */
    public function listar($order='oferta.actualizacion_in.asc', $page='pag.1') { 
        $page = (Filter::get($page, 'page') > 0) ? Filter::get($page, 'page') : 1;
        $item = new Oferta();
        $this->listItems = $item->getItems($page);
        $this->order = $order;        
        $this->page_title = 'Listado de Ofertas';
        $this->page_module = "Oferta";
        $this->set_title = "Listado de Ofertas";
    }    

    
    /**
     * Método para listar
     */
    public function listar_us($order='oferta.actualizacion_in.asc', $page='pag.1') {
    	$page = (Filter::get($page, 'page') > 0) ? Filter::get($page, 'page') : 1;
    	$item = new Oferta();
    	//$this->listItems = $item->getItems($page);
    	$this->listItems = $item->getItemsUsuario($page);
    	$this->order = $order;
    	$this->page_title = 'Listado de Ofertas';
    	$this->page_module = "Oferta";
    	$this->set_title = "Listado de Ofertas";
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
        if(Input::hasPost('oferta')){
            /**
             * se le pasa al modelo por constructor los datos del form y ActiveRecord recoge esos datos
             * y los asocia al campo correspondiente siempre y cuando se utilice la convención
             * model.campo
             */
            $item = new Oferta(Input::post('oferta'));
            $item->estado = "I";
            //En caso que falle la operación de guardar
            if($item->create()){
                DwMessage::valid('Item agregado correctamente!');
                $usuario = new Usuario();
                $us = $usuario->find_by_id((int)Session::get('id_us'));
                $email = new Email();
                $destinatario = $us->email;
                $nombre = $us->getPersona()->nombre . ' ' . $us->getPersona()->apellido;
                $titulo = "Alta de Oferta";
                $accion = "Creado";
                $item = "Oferta";
                $estado = "Ingresada";
                
                $email->enviar($destinatario, $nombre, $titulo, $accion, $item, $estado);
                return DwRedirect::toAction('listar');          
            }else{
                Flash::error('Falló Operación');
            }
        }
        $this->page_title = 'Agregar Oferta';
        $this->page_module = "Oferta";
        $this->set_title = "Agregar Oferta";
        
    }
 
    /**
     * Edita un Registro
     *
     * @param int $id (requerido)
     */
    public function edit($id)
    {
        $item = new Oferta();
        
        //se verifica si se ha enviado el formulario (submit)
        if(Input::hasPost('oferta')){
 
            if($item->update(Input::post('oferta'))){
                 Flash::valid('Operación exitosa');
                //enrutando por defecto al index del controller
                return Router::redirect();
            } else {
                Flash::error('Falló Operación');
            }
        } else {
            //Aplicando la autocarga de objeto, para comenzar la edición
            $this->oferta = $item->find_by_id((int)$id);
        }
    }
 
    /**
     * Eliminar una categoria
     * 
     * @param int $id (requerido)
     */
    public function del($id)
    {
        $item = new Oferta();
        if ($item->delete((int)$id)) {
                Flash::valid('Operación exitosa');
        }else{
                Flash::error('Falló Operación'); 
        }
 
        //enrutando por defecto al index del controller
        return Router::redirect();
    }

    /**
     * Rechazar una Oferta
     *
     * @param int $id (requerido)
     */
    
    public function ko($id)
    {
    	$item = new Oferta();
    	//Aplicando la autocarga de objeto, para comenzar la edición
    	$this->oferta = $item->find_by_id((int)$id);
    	$this->oferta->rechazar();
    	DwRedirect::toAction('listar');
    
    }
    
    /**
     * Aceptar una Oferta
     *
     * @param int $id (requerido)
     */
    
    public function ok($id)
    {
    	$item = new Oferta();
    	//Aplicando la autocarga de objeto, para comenzar la edición
    	$this->oferta = $item->find_by_id((int)$id);
    	$this->oferta->aceptar();
    	
    	// Paso como variables de sesion los datos de la oferta aceptada
    	
    	Session::set('id_oferta', (int)$id);
    	
    	DwRedirect::to('bdt/demanda/create');
    
    }
    
    /**
     * Lista las categorias
     *
     * @param int $id (requerido)
     */
    
    public function categorias($cat = 0, $order='oferta.actualizacion_in.asc', $page='pag.1')
    {
    	if($cat==0) {
    		$page = (Filter::get($page, 'page') > 0) ? Filter::get($page, 'page') : 1;
    		$item = new Oferta();
    		$this->listItems = $item->getItems($page);
    		$this->order = $order;
    		$this->page_title = 'Listado de Ofertas';
    		$this->page_module = "Oferta";
    		$this->set_title = "Listado de Ofertas";
    	} else {
    		$page = (Filter::get($page, 'page') > 0) ? Filter::get($page, 'page') : 1;
    		$item = new Oferta();
    		$this->listItems = $item->getItemsCategoriadetrabajo($cat, $page);
    		$this->order = $order;
    		$this->page_title = 'Listado de Ofertas';
    		$this->page_module = "Oferta";
    		$this->set_title = "Listado de Ofertas";
    		
    	}
    	
    }  
    
    
}
