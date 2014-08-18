<?php

class fileComp extends classes\Component\Component{
    public function display($file){
        extract($file);
        $url = urlencode(URL_FILES. "$url/$name");
        echo "<iframe src='http://docs.google.com/viewer?url=$url&embedded=true' width='100%' height='' style='border: none;'></iframe>";
        //echo "Este arquivo não pode ser exibido pelo navegador <a href='$url'>clique aqui</a> para fazer download";
    }
}

?>