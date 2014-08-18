<?php 

class files_permissionModel extends \classes\Model\Model{
    public $tabela = "files_permission";
    public $pkey   = array('cod_arquivo', 'cod_usuario');
    public $dados  = array(
         'cod_arquivo' => array(
	    'name'     => 'Arquivo',
	    'type'     => 'int',
	    'size'     => '11',
	    'pkey'    => true,
	    'notnull' => true,
	    'grid'    => true,
	    'display' => true,
            'fkey' => array(
	        'model' => 'files/arquivo',
	        'cardinalidade' => '1n',
	        'keys' => array('cod_arquivo', 'name'),
                'onupdate' => 'cascade',
                'ondelete' => 'cascade'
	    ),
        ),
        
        'cod_usuario' => array(
	    'name'     => 'Usuário',
	    'type'     => 'int',
	    'size'     => '11',
            'pkey'    => true,
	    'notnull' => true,
	    'grid'    => true,
	    'display' => true,
	    'fkey' => array(
	        'model' => 'usuario/login',
	        'cardinalidade' => '1n',
	        'keys' => array('cod_usuario', 'email'),
                'onupdate' => 'cascade',
                'ondelete' => 'cascade'
	    ),
         ),
         'adicionadoem' => array(
	    'name'     => 'adicionadoem',
	    'type'     => 'timestamp',
            'default' => "CURRENT_TIMESTAMP",
	    'notnull' => true,
	    'grid'    => true,
	    'display' => true,
        ),
        
       'permissao' => array(
           'name'    => 'Permissão',
           'type'    => 'enum',
           'default' => 'visualizar',
           'options' => array(
               'visualizar' => "Visualizar arquivo",
               'excluir'    => 'Excluir arquivo'
           )
        )
    );
    
    public function getUsersByFolder($cod_pasta){
        return $this->selecionar(array(), "cod_pasta = '$cod_pasta'");
    }
    
    public function getUsePermission($cod_pasta, $cod_usuario){
        $var = $this->selecionar(array('permissao'), "cod_pasta = '$cod_pasta' AND cod_usuario = '$cod_usuario'");
        if(empty ($var)) return "nenhuma";
        $var = array_shift($var);
        return $var['permissao'];
    }
    
}
?>