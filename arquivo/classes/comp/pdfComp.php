<?php

class pdfComp extends classes\Component\Component{
    public function display($pdf){
        $this->LoadJsPlugin('pdf/pdfview', 'pview');
        extract($pdf);
        //print_r($pdf);
        $this->pview->showPdf(URL_FILES."$url/$name");
        
    }
}

?>