<?php
        
class filesConfigurations extends \classes\Classes\Options{
          
    protected $menu = array(
        array(
            'menuid' => 'arquivos',
            'menu'   => 'Arquivos',
            'url'    => 'files/index/index',
            'ordem'  => '9',
        ),
    );
    
    protected $files   = array(
        'files/dropbox' => array(
            'title'        => 'Dropbox API',
            'descricao'    => 'Configurações da API do Dropbox',
            'visibilidade' => 'webmaster', //'usuario', 'admin', 'webmaster'
            'grupo'        => 'Opções de arquivos',
            'path'         => 'files/dropbox',
            'configs'      => array(
                'DROPBOX_APP_KEY' => array(
                    'name'          => 'DROPBOX_APP_KEY',
                    'label'         => 'Chave do aplicativo Dropbox',
                    'type'          => 'varchar',//varchar, text, enum
                    'value'         => 'qyxm05xulocumnr',
                    'value_default' => 'qyxm05xulocumnr'
                ),
                'DROPBOX_APP_KEY' => array(
                    'name'          => 'DROPBOX_APP_SECRET',
                    'label'         => 'Chave secreta do Dropbox',
                    'type'          => 'varchar',//varchar, text, enum
                    'value'         => '2epboay2cx30luj',
                    'value_default' => '2epboay2cx30luj'
                ),
            ),
        ),
    );
}

?>