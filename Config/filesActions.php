<?php 
use classes\Classes\Actions;
class filesActions extends Actions{
    protected $permissions = array(        
        
        "FilesAccArquivo" => array(
            "nome"      => "Files_GER_ARQ",
            "label"     => "Gerenciar arquivos",
            "descricao" => "Permite que o usuário gerencie os arquivos do sistema",
            'default'   => "n"
        ),
        
        "FilesArquivo" => array(
            "nome"      => "Files_ARQ",
            "label"     => "Editar arquivos",
            "descricao" => "Permite que o usuário edite o nome e a descrição dos arquivos do sistema",
            'default'   => "n"
        ),
        
        "FilesSendArquivo" => array(
            "nome"      => "Files_SendARQ",
            "label"     => "Enviar arquivos",
            "descricao" => "Permite que o usuário envie arquivos para o sistema, caso ele acesse algum plugin auxiliar que utilize
                o upload de arquivos",
            'default'   => "s"
        ),
        
        "FilesPasta" => array(
            "nome"      => "files/pasta",
            "label"     => "Gerenciar Pastas",
            "descricao" => "Permite que o usuário gerencie as pastas do sistema",
            'default'   => "n"
        )
    );
    
    protected $actions = array( 
        'files/arquivo/index' => array(
            'label' => 'Arquivos', 'publico' => 's', 'default_yes' => 's','default_no' => 's',
            'permission' => 'Files_GER_ARQ',
            'menu' => array()
        ),
        'files/arquivo/show' => array(
            'label' => 'Exibir Arquivo', 'publico' => 's', 'default_yes' => 's','default_no' => 's',
            'permission' => 'Files_GER_ARQ', 'needcod' => true,
            'menu' => array(
                'Voltar para a Pasta' => 'files/pasta/show',
                'Opções do Arquivo' => array('Editar' => 'files/arquivo/edit', 'Excluir' => 'files/arquivo/apagar')
             )
        ),
        'files/arquivo/formulario' => array(
            'label' => 'Novo Arquivo', 'publico' => 'n', 'default_yes' => 's','default_no' => 'n',
            'permission' => 'Files_GER_ARQ',
            'menu' => array()
        ),
        'files/arquivo/edit' => array(
            'label' => 'Editar Arquivo', 'publico' => 'n', 'default_yes' => 's','default_no' => 'n',
            'permission' => 'Files_ARQ', 'needcod' => true,
            'menu' => array('Voltar' => 'files/arquivo/show')
        ),
        'files/arquivo/apagar' => array(
                'label' => 'Apagar Arquivo', 'publico' => 'n', 'default_yes' => 's','default_no' => 'n',
                'permission' => 'Files_SendARQ', 'needcod' => true,
                'menu' => array()
        ),
        
        
        'files/pasta/index' => array(
            'label' => 'Pastas', 'publico' => 's', 'default_yes' => 's','default_no' => 's',
            'permission' => 'files/pasta',
            'menu' => array('Nova Pasta' => 'files/pasta/formulario')
        ),
        'files/pasta/show' => array(
            'label' => 'Exibir Pasta', 'publico' => 's', 'default_yes' => 's','default_no' => 's',
            'permission' => 'files/pasta', 'needcod' => true,
            'menu' => array(
                'Todas as Pastas' => 'files/pasta/index',
                'Opções da Pasta' => array(
                    'Novo Arquivo' => 'files/arquivo/formulario',
                    'Editar'       => 'files/pasta/edit', 
                    'Excluir'      => 'files/pasta/apagar'
                )
             )
        ),
        'files/pasta/formulario' => array(
            'label' => 'Nova Pasta', 'publico' => 'n', 'default_yes' => 's','default_no' => 'n',
            'permission' => 'files/pasta',
            'menu' => array('Todas as Pastas' => 'files/pasta/index')
        ),
        'files/pasta/edit' => array(
            'label' => 'Editar Pasta', 'publico' => 'n', 'default_yes' => 's','default_no' => 'n',
            'permission' => 'files/pasta', 'needcod' => true,
            'menu' => array('Voltar' => 'files/pasta/show')
        ),
        'files/pasta/apagar' => array(
                'label' => 'Apagar Pasta', 'publico' => 'n', 'default_yes' => 's','default_no' => 'n',
                'permission' => 'files/pasta', 'needcod' => true,
                'menu' => array()
        ),
        
        
        
    );
    
}
?>