<?php

class NovComet {

    const COMET_OK = 0;
    const COMET_CHANGED = 1;
 
    private $_try;
    private $_var;
    private $_sleep;
    private $_ids = [];
    private $_callback = null;
 
    public function  __construct($try = 2, $sleep = 2)
    {
        $this->_try = $try;
        $this->_sleep = $sleep;
    }
 
    public function setVar($k, $v)
    {
        $this->_vars[$k] = $v;
    }
 
    public function setTries($try)
    {
        $this->_try = $try;
    }
 
    public function setSleepTime($sleep)
    {
        $this->_sleep = $sleep;
    }
 
    public function setCallbackCheck($callback)
    {
        $this->_callback = $callback;
    }
 
    const DEFAULT_COMET_PATH = "../dev/shm/%s.comet";
 
    public function run() 
    {
        if (is_null($this->_callback)) {
            $defaultCometPAth = self::DEFAULT_COMET_PATH;
            $callback = function($id) use ($defaultCometPAth) {
                $cometFile = sprintf($defaultCometPAth, $id);          
                $this->createFile($cometFile);
                return (is_file($cometFile)) ? filemtime($cometFile) : 0;
            };
        } 
        else $callback = $this->_callback;
 
        $out = [];
        for ($i = 0; $i < $this->_try; $i++) {
            foreach ($this->_vars as $id => $timestamp) {
                if ((int) $timestamp == 0) $timestamp = time();
                $fileTimestamp = $callback($id);
                if ($fileTimestamp > $timestamp) $out[$id] = $fileTimestamp;            
                clearstatcache();
            }
            if (count($out) > 0) {
                return json_encode(['status' => self::COMET_CHANGED, 'result' => $out]);
            }
            sleep($this->_sleep);
        }
        return json_encode(['status' => self::COMET_OK]);
    }
 
    public function publish($id)
    {
        return json_encode(['published' => touch(sprintf(self::DEFAULT_COMET_PATH, $id)), 'channel' => $id]);
    }

    private function createFile(string $pathFile)
    {
        $myfile = fopen($pathFile, "w") or die("Unable to open file!");   
        fclose($myfile);
    }
}