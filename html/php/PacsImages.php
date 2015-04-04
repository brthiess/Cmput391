<?php

class PacsImages{
    public $recordID = NULL;
    public $imageID = NULL;
    public $thumbnail = NULL;
    public $regularSize = NULL;
    public $fullSize = NULL;
    
    public function __construct($recordID, $imageID, $thumbnail, $regularSize, $fullSize){
        $this->recordID = $recordID;
        $this->imageID = $imageID;
        $this->thumbnail = $thumbnail;
        $this->regularSize = $regularSize;
        $this->fullSize = $fullSize;
    }
}

?>