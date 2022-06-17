<?php

namespace FL;

class FileHelper {
    /**
     * Helper function to the constructor.
     * This allows chaining multiple commands in one line:
     * $file = FileHelper::getInstance($filename)->open()->insertLinesAt(10, $linedata)->save()->close();
     * getInstance takes the exact same parameters as the __construct method.
     * @param string $filename  filename to open
     * @return object the FileHelper instance
     */
    private $filename;
    private $filepointer;
    
    public static function getInstance($filename = "") {
        $class = __CLASS__;
        return new $class($filename);
    }

    // --------------------------------------------------------------------------------------//
    // __ FUNCTIONS                                                                      //
    // --------------------------------------------------------------------------------------//

    /**
     * Initializes a FileHelper instance. 
     */
    function __construct($filename = "") {
        $this->filename = $filename;
        
    }
    
    function open($mode = "a+" ) {
        $this->filepointer = fopen($this->filename);
        if ($this->filepointer == false) {
            throw new Exception("FLPHPFile: could not open file '" . $this->filename . "'.");
        }
        return $this;
    }
    
    function getFilePointer() {
        return $this->filepointer;
    }
    
    function close() {
        $returnvalue = fclose($this->filepointer);
        if ($returnvalue == false) {
            throw new Exception("FLPHPFile: could not close file '" . $this->filename . "'.");
        }
        return $this;
    }
 
    function save() {
        
    }
    
    function insertLinesAt($linenr, $linedata) {
        
    }
    
}
