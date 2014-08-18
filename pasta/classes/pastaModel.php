<?php 

class files_pastaModel extends classes\Model\SecurityCatModel{    
    public $tabela = "files_pasta";
    public $pkey   = 'cod_pasta';
    
    //dados especificos da categoria
    protected $categorized_model = "files/arquivo";
    protected $nome_cod          = "cod_pasta";
    protected $nome_cat          = "nome_pasta";
    protected $nome_pai          = "pasta_pai";
    protected $permission_model  = "files/acesso";
    protected $dados             = array(
        'url' => array(
            'name' 	=> 'url',
            'type' 	=> 'varchar',
            'size' 	=> '256',
            'private'   => true,
            //'display'   => true,
            'grid'      => true,
            'notnull'   => true
         ), 
        
        'arquivos' => array(
            'name'          => 'Arquivos',
            //'private'       => true,    
            'especial'      => 'hide',
            'display'       => true,
            'display_in'    => 'table',
            'fkey'      => array(
                //'sort'          => 'atualizacao DESC',
                'limit'         => 100,
                'model' 	=> 'files/arquivo',
                'keys'          => array('arquivo', 'arquivo'),
                'cardinalidade' => 'n1'//nn 1n 11
            )
        )
    );
    
    private $dir_default = "";
    public function __construct() {
        parent::__construct();
        $this->dados[$this->nome_pai]['especial']         = 'hide';
        $this->dados[$this->nome_pai]['fkey']['ondelete'] = 'cascade';
        $this->dados[$this->nome_pai]['name']             = 'Pasta Superior';
        $this->dados['acesso']['especial']                = 'hide';
        $this->dados['status']['especial']                = 'hide';
        $this->dados[$this->nome_cat]['especial']         = 'hide';
        $this->dados['button']                            = array('button' => 'Salvar Pasta');   
        $this->dir_default = DIR_FILES . "files";
    }
    
    public function getCodFolder($folder){
        
        $var = $this->selecionar(array($this->nome_cod), "where url = '$folder'");
        if(!empty ($var)){
            $var = array_shift ($var);
            return $var[$this->nome_cod];
        }
        
        $foldr = explode("/", $folder);
        if(empty ($foldr) || $folder == "") return false;
        
        $ps = $url = "";
        $cod_pai = "NULL";
        $this->disableCache();
        foreach($foldr as $fd){
            if($fd == "")continue;
            $url   .= "$fd/";
            $where  = "$this->nome_cat = '$fd' AND ";
            $where .= ($cod_pai == "NULL")? "ISNULL($this->nome_pai)" : "$this->nome_pai = '$cod_pai'";
            $var = $this->selecionar(array($this->nome_cod), $where);
            if(empty ($var)){
                $arr[$this->nome_cat] = $fd;
                $arr[$this->nome_pai] = ($cod_pai == "NULL")?"FUNC_NULL":$cod_pai;    
                $arr["cod_autor"]     = $this->cod_usuario;  
                $arr["url"]           = $url;  
                if(!$this->inserir($arr)) return 0;
                else $var = $this->selecionar(array($this->nome_cod), $where);
            }
            
            if(!empty ($var)){
                $var     = array_shift($var);
                $cod_pai = $var[$this->nome_cod];
            }
        }
        return $cod_pai;
    }
    
    public function userCanAccess($cod_pasta, $cod_usuario){
        
        //carrega a pasta e verifica se ela existe
        $item = $this->getItem($cod_pasta);
        if(empty ($item)) return false;

        //verifica se usuário é administrador
        $this->LoadModel('usuario/login', 'uobj');
        if($this->uobj->UserIsAdmin()) return true;
        
        //verifica se usuário é o dono da pasta
        if(is_array($item['cod_autor']) && array_key_exists($cod_usuario, $item['cod_autor'])) return true;
        
        //checa se qualquer usuário pode acessar
        $status = $item['status'];
        foreach($this->dados['status']['options'] as $key => $value){
            if($key == 'publico' || $key == 'nao_listado') return true;
        }
        
        //verifica se foi concedida a permissão ao usuário de ver o arquivo
        $this->LoadModel('files/acesso', 'acc');
        return ($this->acc->getUserPermission($cod_pasta, $this->cod_usuario) != "");
    }    
    
    public function garbageColector(){
        $this->LoadResource('files/dir', 'dir');
        $realdir = $this->colector($this->dir_default);
        if(!$this->dropUnexistentFoldersFromDataBase($realdir)) return false;
        if(!$this->dropUnexistentFoldersInDatabase($realdir))   return false;
        $this->setSuccessMessage('Arquivos sem referências excluídos corretamente!');
        return true;
    }
    
    public function getSizeOfFolder($folder){
        
        $var = $this->selecionar(array("cod_pasta"), "url = '$folder'");
        if(empty ($var)) return 0;
        $var = array_shift($var);
        $cod_pasta = $var['cod_pasta'];
        
        $this->LoadModel('files/arquivo', 'arq');
        $var = $this->arq->selecionar(array("SUM(size) as soma"), "folder = '$cod_pasta'");
        if(empty ($var)) return 0;
        $var = array_shift($var);
        return files_arquivoModel::convertSizeUnity($var['soma']);
    }
    
    //apaga os diretórios que não estão registrados no banco de dados
    private function dropUnexistentFoldersInDatabase($realdir){
        if(empty ($realdir))return true;
        $logicfiles = implode("/','", $realdir) . "/";
        $logicfiles = $this->selecionar(array('url'), "url IN ('$logicfiles')");
        
        $lg = array();
        foreach($logicfiles as $logic) $lg[] = $logic['url'];
        unset($logicfiles);
        $erros = array();
        foreach($realdir as $real){
            if(in_array($real."/", $lg)) continue;
            if(!file_exists($this->dir_default."/".$real)) continue;
            if(!$this->dir->remove($this->dir_default."/".$real))
                $erros[] = $this->dir->getErrorMessage();
        }
        if(!empty ($erros)){
            $erros = implode("<br/>", $erros);
            $var = "Execussão do Coletor de lixo<hr/> Relatório de erros: <br/><br/>$erros";
            $this->setErrorMessage($var);
            return false;
        }
        return true;
    }
    
    /*Exclui do banco de dados as pastas que não existem no diretório*/
    private function dropUnexistentFoldersFromDataBase($realdir){
        if(empty ($realdir))return true;
        $logicfiles = implode("/','", $realdir) . "/";
        $this->db->executeQuery("DELETE from `$this->tabela` WHERE url NOT IN ('$logicfiles')");
        /*
        if(!$this->db->executeQuery("DELETE from `$this->tabela` WHERE url NOT IN ('$logicfiles')")){
            $this->setErrorMessage($this->db->getErrorMessage());
            return false;
        }
         */
        return true;
    }
    
    private function colector($folder){
        $temp_folder = $this->dir->getPastas($folder);
        if(empty ($temp_folder)) return $temp_folder;
        foreach($temp_folder as $t){
            $out = $this->colector($folder."/".$t);
            foreach($out as $o){
                if($o == 'thumb') continue;
                $temp_folder[] = "$t/$o";
            }
        }
        return $temp_folder;
    }
    
    public function unstall($plugin){
        $where = "url LIKE '$plugin/%'";
        if(!$this->db->Delete($this->tabela, $where)){
            $this->setErrorMessage($this->db->getErrorMessage());
            return false;
        }
        return $this->garbageColector();
    }
    
    public function selecionar($campos = array(), $where = "", $limit = "", $offset = "", $orderby = "") {
        $orderby = ($orderby == "$this->nome_cat ASC")?"":$orderby;
        return parent::selecionar($campos, $where, $limit, $offset, $orderby);
    }

}
?>