<?php 
 use classes\Controller\CController;
use classes\Classes\EventTube;
class pastaController extends CController{
    public $model_name = "files/pasta";
    
    public function AfterLoad() {
        parent::AfterLoad();
        $menu = $this->model->geraMenu();
        $this->LoadJsPlugin('menu/treeview', 'mt');
        $this->mt->imprime();
        $var = $this->mt->draw($menu);
        EventTube::addEvent('menu-lateral', "<h3>Pastas do Site</h3>$var");
    }
    
    public function show($display = true, $link = "") {
        $link = ($link == "")?LINK . "/show":$link;
        parent::show($display, $link);
    }
    
    public function garbage(){
        $bool = (!$this->model->garbageColector());
        $this->registerVar('status', $bool);
        $this->setVars($this->model->getMessages());
        $this->display('');
    }
}