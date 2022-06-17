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
    private $filedata = array();

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
        $this->open();
    }

    public function open($mode = "a+") {
        $this->filepointer = fopen($this->filename, $mode);
        if ($this->filepointer == false) {
            throw new \Exception("FLPHPFile: could not open file '" . $this->filename . "'.");
        }
        $this->filedata = file($this->filename);
        return $this;
    }

    public function getFilePointer() {
        return $this->filepointer;
    }

    public function getFileData() {
        return $this->filedata;
    }

    public function close() {
        $returnvalue = fclose($this->filepointer);
        if ($returnvalue == false) {
            throw new \Exception("FLPHPFile: could not close file '" . $this->filename . "'.");
        }
        return $this;
    }

    public function save() {
        file_put_contents($this->filename, implode("", $this->filedata));
        return $this;
    }

    public function findFirstLineNumberContaining($searchstring) {
        foreach ($this->filedata as $index => $string) {
            if (strpos($string, $searchstring) !== FALSE)
                return $index;
        }
        return false;
    }

    public function removeEverythingBetweenLines($startline, $endline) {
        $removelines = array();
        for ($i = $startline; $i <= $endline; $i++) {
            $removelines[$i] = "";
        }
        $this->filedata = \array_diff_key($this->filedata, $removelines);
        return $this;
    }

    public function insertAfterLineNumber($linenr, $linedata) {
        if ($linenr < count($this->filedata)) {
            $linenr += 1;
        }
        array_splice($this->filedata, $linenr, 0, $linedata);
        return $this;
    }

    public function insertLinesAt($linenr, $linedata) {
        
    }

}
