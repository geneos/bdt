<?php
class Aceptacion extends ActiveRecord
{
   /**
     * Retorna los items para ser paginados
     *
     */
   public function getItems($page, $ppage=20)
   {
       return $this->paginate("page: $page", "per_page: $ppage", 'order: id desc');
   }
}