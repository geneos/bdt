<?php
/**
 * Carga del modelo.
 */
Load::models('bdt/respuesta');
Load::models('bdt/cuenta');
Load::models('bdt/solicitud');
 
class RespuestaController extends BackendController {
    
    /**
     * Método principal
     */
    public function index() {
        DwRedirect::toAction('listar');
    }
    
    /**
     * Método para listar las respuestas a solicituds del socio
     */
    public function listar($order='respuesta.actualizacion_in.asc', $page='pag.1') { 
        $page = (Filter::get($page, 'page') > 0) ? Filter::get($page, 'page') : 1;
        $item = new Respuesta();
        $this->listItems = $item->getItems($page);             
        $this->order = $order;        
        $this->page_title = 'Listado de Respuestas';
        $this->page_module = "Respuesta";
        $this->set_title = "Listado de Respuestas";
    } 

    /**
     * Método para listar las respuestas que el usuario hace a solicituds de otros socios
     */
    public function listar_us($order='respuesta.actualizacion_in.asc', $page='pag.1') {
    	$page = (Filter::get($page, 'page') > 0) ? Filter::get($page, 'page') : 1;
    	$item = new Respuesta();
    	$this->listItems = $item->getItemsUsuario($page);
    	$this->order = $order;
    	$this->page_title = 'Listado de Respuestas';
    	$this->page_module = "Respuesta";
    	$this->set_title = "Listado de Respuestas";
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
            $item->estado = "I";
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
     * Rechazar una respuesta
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

    /**
     * Rechazar una respuesta
     *
     * @param int $id (requerido)
     */
    
    public function cancelar($id)
    {
    	$item = new Respuesta();
    	//Aplicando la autocarga de objeto, para comenzar la edición
    	$this->respuesta = $item->find_by_id((int)$id);
    	$this->respuesta->cancelar();
    	DwRedirect::toAction('listar');
    
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
     * Finalizar una respuesta
     * Debe actualizar el saldo de horas de oferente y respuestante
     *
     * @param int $id (requerido)
     */
    
    public function finalizar($id)
    {
    	$respuesta = new Respuesta();
    	$this->respuesta = $respuesta->find_by_id((int)$id);
    	$this->respuesta->finalizar();
    	
    	$solicitud = new Solicitud();
    	$of = $solicitud->find_by_id((int)$this->respuesta->solicitud_id);
    	$of->registrar_horas($this->respuesta->horas);
    	
    	$cuenta_dem = new Cuenta();
    	$cuenta_dem->usuario_id = $this->respuesta->usuario_id;
    	$cuenta_dem->respuesta_id = $this->respuesta->id;
    	$cuenta_dem->debe = 0;
    	$cuenta_dem->haber = $this->respuesta->horas;
    	$cuenta_dem->saldo = 0;
    	$cuenta_dem->save();    	 
    	
    	$cuenta_of = new Cuenta();
    	$cuenta_of->usuario_id = $this->respuesta->getSolicitud()->usuario_id;
    	$cuenta_of->solicitud_id = $this->respuesta->solicitud_id;
    	$cuenta_of->haber = 0;
    	$cuenta_of->debe = $this->respuesta->horas;
    	$cuenta_of->saldo = 0;
    	$cuenta_of->save();
    	 
    	
    	
    	DwRedirect::toAction('listar');
    
    }    
    
    
}
