<?php

class videoComp extends classes\Component\Component{
    public function display($video){
        extract($video);
        $ur = URL_FILES."$url$name";
        echo "<video width='100%' controls>
            <source src='$ur' type='video/mp4'>
            Your browser does not support the video tag.
        </video>";
    }
}

?>