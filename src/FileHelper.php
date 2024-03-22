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
    const ASFARASPOSSIBLE = "AFAP";
    const TOTERABYTES = "TTB";
    const TOGIGABYTES = "TGB";
    const TOMEGABYTES = "TMB";
    const TOKILOBYTES = "TKB";
    const TOBYTES = "TB";

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
        if ($this->filename !== "") {
            $this->open();
        }
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

    public function getRandomFileNameFromFolder($folder = ".", $getfullpath = false) {
        srand((double) microtime() * 1000000);
        $availableBkg = scandir($folder);
        $_idx = rand(0, count($availableBkg) - 3);
        if ($getfullpath == true) {
            return $folder . "/" . $availableBkg[$_idx + 2]; // +2 to avoid .. and . results. same for -3 in line above.
        } else {
            return $availableBkg[$_idx + 2]; // +2 to avoid .. and . results. same for -3 in line above.
        }
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

    public function convertSize($size, $converto = FileHelper::ASFARASPOSSIBLE, $includeunit = true) {
        // this converts bytes to a nicer displayable unit.
        $returnvalue = "0";
        $_kb = 1024;
        $_mb = $_kb * 1024;
        $_gb = $_mb * 1024;
        $_tb = $_gb * 1024;
        $freespace = "0";
        $freespaceunit = "KB";
        if (is_numeric($size)) {
            if ($converto == FileHelper::ASFARASPOSSIBLE)
                $freespacebytes = $size;
            if ($size >= $_tb) {
                $freespace = round($size / $_tb, 2);
                $freespaceunit = "TB";
            } else {
                if ($size >= $_gb) {
                    $freespace = round($size / $_gb, 2);
                    $freespaceunit = "GB";
                } else {
                    if ($size >= $_mb) {
                        $freespace = round($size / $_mb, 2);
                        $freespaceunit = "MB";
                    } else {
                        if ($size >= $_kb) {
                            $freespace = round($size / $_kb, 2);
                            $freespaceunit = "KB";
                        }
                    }
                }
            }
        } else {
            $divider = 1;
            if ($converto == File::TOTERABYTES) {
                $divider = $_tb;
                $freespaceunit = "TB";
            }
            if ($converto == File::TOGIGABYTES) {
                $divider = $_gb;
                $freespaceunit = "GB";
            }
            if ($converto == File::TOMEGABYTES) {
                $divider = $_mb;
                $freespaceunit = "MB";
            }
            if ($converto == File::TOKILOBYTES) {
                $divider = $_kb;
                $freespaceunit = "KB";
            }
            $freespace = round($size / $divider, 2);
        }
        if ($includeunit == true) {
            $returnvalue = $freespace . $freespaceunit;
        } else {
            $returnvalue = $freespace;
        }
        return $returnvalue;
    }

}
