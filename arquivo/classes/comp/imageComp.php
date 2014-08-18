<?php

class imageComp extends classes\Component\Component{
    public function display($image){
        extract($image);
        $img_url = URL_FILES. "$url/$name";
        echo "<img src='$img_url' width='100%'/>";
    }
}

?>