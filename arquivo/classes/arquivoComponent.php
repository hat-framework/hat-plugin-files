<?php
class arquivoComponent extends classes\Component\Component{

    
    public function show($model, $item) {
        $this->drawDestaque($item, 'file_label', 'file_descricao', 'adicionadoem');
        parent::show($model, $item);
    }
    
    private $images_ext = array('jpg', 'gif', 'png', 'ico');
    private $videos_ext = array('mp4');
    public function showAnexos($model, $itens, $title = "", $class = ''){
        if(empty($itens)) return "";
        $this->LoadModel('usuario/login', 'uobj');
        $is_admin = $this->uobj->UserIsAdmin();
        $cod_usuario = $this->uobj->getCodUsuario();
        $this->LoadModel('usuario/perfil', 'perf');
        $has_permission = $this->perf->HasPermissionByName('Files_ARQ');
        $this->LoadResource('upload', "up");
        $upurl   = $this->up->getDownloadUrl();
        $editurl = $this->Html->getLink('files/arquivo/edit');
        $this->Html->LoadCss('files');
        $id = str_replace("/", "_", $model);
        echo "<div$class> ";
            echo "<div class='title'>$title</div>";
            echo "<ul id='$id'>";
            foreach($itens as $item){
                
                extract($item);
                $img = "";
                $canedit       = (!$is_admin && ($item['cod_autor'] == $cod_usuario && $has_permission) )?true:$is_admin;
                $visu_link     = "<a href ='".URL_FILES. "$url$name"."' target='_blank'>Visualizar</a>";
                $link          = "$upurl?file=$cod_arquivo&folder=$folder";
                $download_link = "<a href='$link' class='form_item download'>Download</a>";
                $ext = strtolower($ext);
                if(in_array($ext, $this->images_ext)){
                    
                    $img_url = URL_FILES. "$url/thumb/$name";
                    $img = "<img src='$img_url' />";
                }
                elseif(in_array($ext, $this->videos_ext)){
                    $ur = URL_FILES."$url$name";
                    $img = "
                    <video width='320' height='200' controls>
                        <source src='$ur' type='video/mp4'>
                        Your browser does not support the video tag.
                    </video>";
                    $visu_link = $download_link = "";
                }
                else{
                    if($ext != 'pdf') $visu_link = "";
                    $img = $this->Html->LoadImage("filetypes/$ext.png", "", false);
                }
                
                $size = files_arquivoModel::convertSizeUnity($size);
                $name = ($file_label     == "")?$name:$file_label;
                $desc = ($file_descricao == "")?'':$file_descricao;
                
                $var = $edit_link = $drop_link = "";
                
                if($canedit){
                    $var = "
                     <div class='file_form'>
                        <form class='form hide' action='$editurl/$cod_arquivo' id='file_$cod_arquivo'>     
                            <span class='ldescricao'><b>Nome</b>: </span> <br/>
                            <input type='text' id='file_label' name='file_label' class='form_value' value='$name'/>
                            <span class='ldescricao'><br/><b>Descrição</b>: </span> <br/>
                            <textarea type='text' id='file_descricao' name='file_descricao' class='form_value' cols='23' rows='4'>$desc</textarea>
                            <input type='hidden' id='ajax' name='ajax' value='true'/>
                        </form>
                     </div>
                    ";
                    $edit_link = " <a href='$editurl/$cod_arquivo' class='edit_file_form'>Editar</a> ";
                }
                
                if($is_admin){
                    $dropurl = $this->up->getDeleteUrl($cod_arquivo, $folder);
                    $drop_link = "<a href='$dropurl' class='drop_file'>Apagar</a>";
                }
                
                echo "
                    <li class='list-item list_$id'>
                        <div class='img'>$img</div>
                        <div class='file_content'>
                            <div class='name'>
                                <span class='form_item name'>$name</span>
                            </div>
                            <div class='descricao'>
                                <span class='form_item descricao'>$desc</span>
                            </div>
                        </div>
                        $var";
                        //<div class='size'>Tamanho: $size</div>";
                         $this->gui->clear();
                        echo"<hr/><div class='file_actions'>$visu_link $download_link $edit_link $drop_link</div>";
                echo "</li>
                ";
            }
            echo "</ul>";
        echo "</div>"; 
        $this->gui->clear();
        $this->scripts();
        
    }
    
    public function scripts(){
        $this->Html->LoadCss('modulos/files');
        $this->Html->LoadJs('plugins/files/arquivo');
    }
    
    public function format_size($size, $dados){
        return files_arquivoModel::convertSizeUnity($size);
    }
    
    public function info($msg){
        $this->gui->info($msg);
    }
    
    public function showDetailedFile($anexo){
        extract($anexo);
        $type = $this->detectType($ext);
        $gui = new \classes\Component\GUI();
        $gui->infotitle($file_label);
        $gui->paragraph($file_descricao);
        $this->LoadClassFromPlugin("files/arquivo/comp/{$type}Comp", 'cls');
        $this->cls->display($anexo);
    }
    
    private function detectType($ext){
        $ext = strtolower($ext);
        if(in_array($ext, $this->images_ext)) return "image";
        if(in_array($ext, $this->videos_ext)) return "video";
        if($ext == 'pdf') return "pdf";
        return 'file';
    }
}
?>