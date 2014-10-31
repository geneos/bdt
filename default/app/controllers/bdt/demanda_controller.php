<?php
/**
 * Carga del modelo.
 */
Load::models('bdt/demanda');
Load::models('bdt/cuenta');
Load::models('bdt/oferta');
 
class DemandaController extends BackendController {
    
    /**
     * Método principal
     */
    public function index() {
        DwRedirect::toAction('listar');
    }
    
    /**
     * Método para listar las demandas a ofertas del socio
     */
    public function listar($order='demanda.actualizacion_in.asc', $page='pag.1') { 
        $page = (Filter::get($page, 'page') > 0) ? Filter::get($page, 'page') : 1;
        $item = new Demanda();
        $this->listItems = $item->getItems($page);             
        $this->order = $order;        
        $this->page_title = 'Listado de Demandas';
        $this->page_module = "Demanda";
        $this->set_title = "Listado de Demandas";
    } 

    /**
     * Método para listar las demandas que el usuario hace a ofertas de otros socios
     */
    public function listar_us($order='demanda.actualizacion_in.asc', $page='pag.1') {
    	$page = (Filter::get($page, 'page') > 0) ? Filter::get($page, 'page') : 1;
    	$item = new Demanda();
    	$this->listItems = $item->getItemsUsuario($page);
    	$this->order = $order;
    	$this->page_title = 'Listado de Demandas';
    	$this->page_module = "Demanda";
    	$this->set_title = "Listado de Demandas";
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
        if(Input::hasPost('demanda')){
            /**
             * se le pasa al modelo por constructor los datos del form y ActiveRecord recoge esos datos
             * y los asocia al campo correspondiente siempre y cuando se utilice la convención
             * model.campo
             */
            $item = new Demanda(Input::post('demanda'));
            $item->estado = "I";
            //En caso que falle la operación de guardar
            if($item->create()){
                DwMessage::valid('Item agregado correctamente!');
                return DwRedirect::toAction('listar');          
            }else{
                Flash::error('Falló Operación');
            }
        }
        $this->page_title = 'Agregar Demanda';
        $this->page_module = "Demanda";
        $this->set_title = "Agregar Demanda";
        
    }
 
    /**
     * Edita un Registro
     *
     * @param int $id (requerido)
     */
    public function edit($id)
    {
        $item = new Demanda();
        
        //se verifica si se ha enviado el formulario (submit)
        if(Input::hasPost('demanda')){
 
            if($item->update(Input::post('demanda'))){
                 Flash::valid('Operación exitosa');
                //enrutando por defecto al index del controller
                return Router::redirect();
            } else {
                Flash::error('Falló Operación');
            }
        } else {
            //Aplicando la autocarga de objeto, para comenzar la edición
            $this->demanda = $item->find_by_id((int)$id);
        }

        $this->page_title = 'Editar Demanda';
        $this->page_module = "Demanda";
        $this->set_title = "Editar Demanda";
        
    }

    /**
     * Rechazar una demanda
     *
     * @param int $id (requerido)
     */
    
    public function ko($id)
    {
    	$item = new Demanda();
    	//Aplicando la autocarga de objeto, para comenzar la edición
        $this->demanda = $item->find_by_id((int)$id);
    	$this->demanda->rechazar(); 
    	DwRedirect::toAction('listar');
    
    }

    /**
     * Aceptar una Demanda
     *
     * @param int $id (requerido)
     */
    
    public function ok($id)
    {
    	$item = new Demanda();
    	//Aplicando la autocarga de objeto, para comenzar la edición
        $this->demanda = $item->find_by_id((int)$id);
    	$this->demanda->aceptar();
    	DwRedirect::toAction('listar');
    
    }   

    /**
     * Rechazar una demanda
     *
     * @param int $id (requerido)
     */
    
    public function cancelar($id)
    {
    	$item = new Demanda();
    	//Aplicando la autocarga de objeto, para comenzar la edición
    	$this->demanda = $item->find_by_id((int)$id);
    	$this->demanda->cancelar();
    	DwRedirect::toAction('listar');
    
    }
    
    /**
     * Eliminar una categoria
     * 
     * @param int $id (requerido)
     */
    public function del($id)
    {
        $item = new Demanda();
        if ($item->delete((int)$id)) {
                Flash::valid('Operación exitosa');
        }else{
                Flash::error('Falló Operación'); 
        }
 
        //enrutando por defecto al index del controller
        return Router::redirect();
    }

    /**
     * Finalizar una demanda
     * Debe actualizar el saldo de horas de oferente y demandante
     *
     * @param int $id (requerido)
     */
    
    public function finalizar($id)
    {
    	$demanda = new Demanda();
    	$this->demanda = $demanda->find_by_id((int)$id);
    	$this->demanda->finalizar();
    	
    	$oferta = new Oferta();
    	$of = $oferta->find_by_id((int)$this->demanda->oferta_id);
    	$of->registrar_horas($this->demanda->horas);
    	
    	$cuenta_dem = new Cuenta();
    	$cuenta_dem->usuario_id = $this->demanda->usuario_id;
    	$cuenta_dem->demanda_id = $this->demanda->id;
    	$cuenta_dem->debe = $this->demanda->horas;
    	$cuenta_dem->haber = 0;
    	$cuenta_dem->saldo = 0;
    	$cuenta_dem->save();    	 
    	
    	$cuenta_of = new Cuenta();
    	$cuenta_of->usuario_id = $this->demanda->getOferta()->usuario_id;
    	$cuenta_of->oferta_id = $this->demanda->oferta_id;
    	$cuenta_of->haber = $this->demanda->horas;
    	$cuenta_of->debe = 0;
    	$cuenta_of->saldo = 0;
    	$cuenta_of->save();
    	 
    	
    	
    	DwRedirect::toAction('listar');
    
    }    
    
    
}
