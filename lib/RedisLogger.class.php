<?php

class RedisLogger extends sfLogger
{
  protected $r;
  protected $maxlogs;
  protected $defaults = array('host' => 'localhost', 'port' => 6379, 'maxlogs' => 1000);
  
  public function initialize(sfEventDispatcher $dispatcher, $options = array())
  {
    parent::initialize($dispatcher, $options);
    
    $ops = array_merge($this->defaults, $options); 
    $this->r = new Redis($ops['host'], $ops['port']);
    $this->maxlogs = $ops['maxlogs'];
  }
  
  protected function doLog($message, $priority)
  {
    $value = array(
        'module' => sfContext::getInstance()->getModuleName(),
        'action' => sfContext::getInstance()->getActionName(),
        'message' => $message,
        'time' => time(),
        'priorityName' => $this->getPriorityName($priority)
      );
    
    $this->r->push('logs.'.gethostname(), serialize($value));
    $this->r->ltrim('logs.'.gethostname(), 0, $this->maxlogs);
  }
  
  public function shutdown()
  {
    $this->r->disconnect();
  }
}