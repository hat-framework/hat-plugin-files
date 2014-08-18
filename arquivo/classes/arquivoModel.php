<?php 
class files_arquivoModel extends \classes\Model\Model{
    public $tabela = "files_arquivo";
    public $pkey   = 'cod_arquivo';
    public $dados  = array(
         'cod_arquivo' => array(
	    'name'     => 'cod_arquivo',
	    'type'     => 'int',
	    'size'     => '11',
	    'pkey'    => true,
	    'ai'      => true,
	    'notnull' => true,
            'private' => true,
	    'grid'    => true,
	    'display' => true,
        ),
        
        'cod_autor' => array(
	    'name'     => 'Autor',
	    'type'     => 'int',
	    'size'     => '11',
	    'notnull' => true,
	    'grid'    => true,
	    'display' => true,
            'especial' => 'autentication',
            'autentication' => array(
                'needlogin' => true
            ),
	    'fkey' => array(
	        'model' => 'usuario/login',
	        'cardinalidade' => '1n',
	        'keys' => array('cod_usuario', 'email'),
                'onupdate' => 'cascade',
                'ondelete' => 'set null'
	    ),
         ),
        
        'file_label' => array(
	    'name'     => 'Nome do Arquivo',
	    'type'     => 'varchar',
	    'size'     => '64',
	    'notnull' => true,
	    'grid'    => true,
	    'display' => true,
        ),
        
        'file_descricao' => array(
	    'name'     => 'Descrição',
	    'type'     => 'varchar',
	    'size'     => '256',
	    'notnull' => true,
	    'grid'    => true,
	    'display' => true,
        ),
        
        'name' => array(
	    'name'     => 'Nome do Arquivo em Disco',
	    'type'     => 'varchar',
	    'size'     => '64',
	    'notnull' => true,
            'private' => true,
	    'grid'    => true,
	    'display' => true,
        ),
        
        'folder' => array(
	    'name'     => 'Pasta',
	    'type'     => 'int',
	    'size'     => '11',
	    'notnull' => true,
            'private' => true,
	    'grid'    => true,
	    'display' => true,
	    'fkey' => array(
	        'model' => 'files/pasta',
	        'cardinalidade' => '1n',
	        'keys' => array('cod_pasta', 'url'),
                'onupdate' => 'cascade',
                'ondelete' => 'cascade',
	    )
        ),
        
        'url' => array(
	    'name'     => 'Diretório',
	    'type'     => 'varchar',
	    'size'     => '128',
            'private' => true,
	    'notnull' => true,
	    'grid'    => true,
	    'display' => true,
        ),
        'size' => array(
	    'name'     => 'Tamanho',
	    'type'     => 'int',
	    'size'     => '11',
            'especial' => 'hide',
	    'notnull' => true,
	    'grid'    => true,
	    'display' => true,
        ),
        'type' => array(
	    'name'     => 'Tipo',
	    'type'     => 'varchar',
            'private' => true,
	    'size'     => '16',
            'especial' => 'hide',
	    'notnull' => true,
	    'grid'    => true,
	    'display' => true,
        ),
        
        'ext' => array(
	    'name'     => 'Extensão',
	    'type'     => 'varchar',
	    'size'     => '5',
            'especial' => 'hide',
	    'notnull' => true,
	    'grid'    => true,
	    'display' => true,
        ),

        'adicionadoem' => array(
	    'name'     => 'adicionadoem',
	    'type'     => 'timestamp',
            'default' => "CURRENT_TIMESTAMP",
            'especial' => 'hide',
	    'notnull' => true,
	    'grid'    => true,
	    'display' => true,
        ),
        'show_files' => array(
           'name'      => 'Exibir no upload',
           'descricao' => 'Exibe este arquivo ao enviar novos arquivos',
           'type'      => 'enum',
           'default'   => 's',
           'notnull'   => true,
           'private' => true,
           'options' => array(
               's'   => "Sim",
               'n'   => 'Não',
           )
        )
    );
    
    public function __construct() {
        parent::__construct();
        $this->LoadModel('usuario/login', 'uobj');
        $this->cod_usuario = $this->uobj->getCodUsuario();
    }
    
    public function inserir($dados) {
        $dados['cod_autor'] = $this->cod_usuario;
        return parent::inserir($dados);
    }
    
    public function editar($id, $post, $camp = "") {
        if(array_key_exists('cod_autor', $post)) unset($post['cod_autor']);
        //print_r($post);die();
        return parent::editar($id, $post, $camp);
    }
    
    public function getFileByFolderAndName($cod_pasta, $nome_arquivo){
        $var = $this->getFileByFilther($cod_pasta, "name = '$nome_arquivo'");
        $var = empty($var)?$var:array_shift($var);
        return $var;
    }
    
    public function getAllFilesFromFolder($cod_pasta){
        return $this->getFileByFilther($cod_pasta);
    }
    
    public function getFilesByFolder($cod_pasta){
        $var = $this->getFileByFilther($cod_pasta, "show_files = 's'");
        return $var;
    }
    
    public function getFilesByUser($cod_pasta){
        $var = $this->getFileByFilther($cod_pasta, "show_files = 's' AND cod_autor = '$this->cod_usuario'");
        return $var;
    }
    
    public function getFile($cod_pasta, $cod_arquivo){
        $var = $this->getFileByFilther($cod_pasta, "cod_arquivo = '$cod_arquivo'");
        return $var;
    }
    
    public function getFileByName($cod_pasta, $nome_arquivo){
        $var = $this->getFileByFilther($cod_pasta, "name = '$nome_arquivo'");
        return $var;
    }
    
    public static function convertSizeUnity($size){
        $byte_size = 1000;
        $unit_arr  = array('bytes', 'Kb', "Mb", 'Gb', 'Tb');
        $unidade = 0;
        while($size > $byte_size){
            $size = ($size/$byte_size);
            $unidade++;
        }
        $size = number_format($size, 2);
        $unidade = $unit_arr[$unidade];
        return " $size $unidade";
    }
    
    public function changeShowFile($cod_arquivo){
        $item = $this->getSimpleItem($cod_arquivo, array('folder', 'show_files'));
        if(empty ($item)){
            $this->setErrorMessage("Arquivo não existe!");
            return false;
        }
        if(!$this->checkUserCanAccess($item['folder'], $cod_arquivo)){
            $this->setErrorMessage("Você não tem permissão de alterar este arquivo!");
            return false;
        }
        
        if($item['show_files'] == 's') $arr['show_files'] = 'n';
        else                           $arr['show_files'] = 's';
        
        return $this->editar($cod_arquivo, $arr);
    }
    
    private function getFileByFilther($cod_pasta, $where = ""){
        $where = ($where == "")?"folder = '$cod_pasta'":"folder = '$cod_pasta' AND $where";
        $this->LoadModel('files/pasta', 'pasta');
        if(!$this->pasta->userCanAccess($cod_pasta, $this->cod_usuario)){
            //$this->LoadModel('files/permission', 'perm');
            //$this->db->Join($this->tabela, $this->perm->getTable());
            //$where .= "AND (cod_autor = '$this->cod_usuario' OR cod_usuario = '$this->cod_usuario')";
        }
        
        $var = $this->selecionar(array(), "$where");
        return $var;
    }
    
    private function checkUserCanAccess($cod_pasta, $cod_arquivo){
        $this->LoadModel('files/pasta', 'pasta');
        return $this->pasta->userCanAccess($cod_pasta, $this->cod_usuario);
    }
    
}
?>