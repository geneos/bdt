<?php
/**
 * Carga del modelo Categoria de Trabajo...
 */
Load::models('bdt/categoriadetrabajo');
 
class CategoriadetrabajoController extends BackendController {
   
    /**
     * Método principal
     */
    public function index() {
        DwRedirect::toAction('listar');
    }
    
    /**
     * Método para listar
     */
    public function listar($order='categoriadetrabajo.actualizacion_in.asc', $page='pag.1') { 
        $page = (Filter::get($page, 'page') > 0) ? Filter::get($page, 'page') : 1;
        $item = new Categoriadetrabajo();
        $this->listItems = $item->getItems($page);             
        $this->order = $order;        
        $this->page_title = 'Listado de Categorías de Trabajo';
        $this->page_module = "Categoría de Trabajo";
        $this->set_title = "Listado de Categorías de Trabajo";
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
        if(Input::hasPost('categoriadetrabajo')){
            /**
             * se le pasa al modelo por constructor los datos del form y ActiveRecord recoge esos datos
             * y los asocia al campo correspondiente siempre y cuando se utilice la convención
             * model.campo
             */
            $item = new Categoriadetrabajo(Input::post('categoriadetrabajo'));
            //En caso que falle la operación de guardar
            if($item->create()){
                DwMessage::valid('Categoría agregada correctamente!');
                return DwRedirect::toAction('listar');          
            }else{
                Flash::error('Falló Operación');
            }
        }
        $this->page_title = 'Agregar Categoría de Trabajo';
        $this->page_module = "Categoría de Trabajo";
        $this->set_title = "Agregar Categoría de Trabajo";
    }
 
    /**
     * Edita un Registro
     *
     * @param int $id (requerido)
     */
    public function edit($id)
    {
        $item = new Categoriadetrabajo();
 
        //se verifica si se ha enviado el formulario (submit)
        if(Input::hasPost('categoriadetrabajo')){
 
            if($item->update(Input::post('categoriadetrabajo'))){
                 Flash::valid('Operación exitosa');
                //enrutando por defecto al index del controller
                return Router::redirect();
            } else {
                Flash::error('Falló Operación');
            }
        } else {
            //Aplicando la autocarga de objeto, para comenzar la edición
            $this->categoriadetrabajo = $item->find_by_id((int)$id);
        }
    }
 
    /**
     * Eliminar una categoria
     * 
     * @param int $id (requerido)
     */
    public function del($id)
    {
        $item = new Categoriadetrabajo();
        if ($item->delete((int)$id)) {
                Flash::valid('Operación exitosa');
        }else{
                Flash::error('Falló Operación'); 
        }
 
        //enrutando por defecto al index del controller
        return Router::redirect();
    }
}
