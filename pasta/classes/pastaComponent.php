<?php
class pastaComponent extends classes\Component\Component{
    public    $list_in_table = true;
    
    public function show($model, $item) {
        
        if(CURRENT_MODULE == 'files'){
            $size = 0;
            if(!empty($item['arquivos'])){          
                foreach($item['arquivos'] as $arq)
                    $size += $arq['size'];
            }
            $this->LoadModel($model, 'obj');
            $this->dados = $this->obj->getDados();
            $item['size'] = "Espaço em disco: ". files_arquivoModel::convertSizeUnity($size);
            $this->drawTitle($item, 'size');
            parent::show($model, $item);
            return;
        }
        if(empty($item)) return;
        $this->LoadModel('files/arquivo', 'arq');
        $files = $this->arq->getAllFilesFromFolder($item['cod_pasta']);
        
        $this->LoadComponent('files/arquivo', 'arcomp');
        $this->arcomp->showAnexos('files/arquivo',$files);
    }
    
}
?>