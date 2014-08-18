<?php

class filesInstall extends classes\Classes\InstallPlugin{
    
    protected $dados = array(
        'pluglabel' => 'Arquivos',
        'isdefault' => 'n',
        'detalhes'  => 'Gerencie os arquivos do seu site de forma independente. Com este plugin instalado
            você pode visualizar e pesquisar os arquivos dentro do site',
        'system'    => 'n',
    );
    
    public function install(){
        return true;
    }
    
    public function unstall(){
        return true;
    }
}

