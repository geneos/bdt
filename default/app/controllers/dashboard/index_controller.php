<?php
/**
 * Dailyscript - Web | App | Media
 *
 * Descripcion: Controlador para el panel principal de los usuarios logueados
 *
 * @category    
 * @package     Controllers 
 * @author      Iván D. Meléndez (ivan.melendez@dailycript.com.co)
 * @copyright   Copyright (c) 2013 Dailyscript Team (http://www.dailyscript.com.co) 
 */

/**
 * Carga del modelos.
 */

Load::models('bdt/oferta');
Load::models('bdt/solicitud');


class IndexController extends BackendController {
    
    public $page_title = 'Dashboard';
    
    public $page_module = 'Dashboard';
    
    public function index($page='pag.1') {
    	
    	$page = (Filter::get($page, 'page') > 0) ? Filter::get($page, 'page') : 1;
    	
    	$itemOf = new Oferta();
    	$this->listItemsOfertas = $itemOf->getItems($page);
    	
    	$itemSol = new Solicitud();
    	$this->listItemsSolicitudes = $itemSol->getItems($page);
        
    }

}
