<?php
/**
 * RLog class
 *
 * @auther: Raysmond
 */
class RLog
{
    const LEVEL_INFO = "info";
    const LEVEL_WARNING = "warning";
    const LEVEL_ERROR = "error";

    /**
     * @var array all log messages
     */
    private $logs = array();

    /**
     * @var int count number of log messages
     */
    private $logCount = 0;

    /**
     * @var int the to 0 will disable auto flushing
     */
    public $flushCount = 100;

    /**
     * The handler used to handle the 'onFlush' event, currently we only support a single
     * handler, but it's better to support multiple handlers.
     * @var object
     */
    private $handler;

    /**
     * Log a message
     * @param $message message string
     * @param string $level the level of the message
     * @param string $type the type of the message
     */
    public function log($message, $level = "info", $type = "system")
    {
        $this->logs[] = array('message' => $message, 'level' => $level, 'type' => $type, "timestamp" => date('Y-m-d H:i:s'));
        $this->logCount++;
        if ($this->flushCount > 0 && $this->logCount >= $this->flushCount) {
            $this->flush();
        }
    }

    /**
     * Get logs
     * @param string|array $levels
     * @param string|array $types
     * @return array
     */
    public function getLogs($levels = null, $types = null)
    {
        $result = array();

        if (is_string($types)) {
            $types = array($types);
        }

        if (is_string($levels)) {
            $levels = array($levels);
        }

        if (($types !== null && !is_array($levels)) || ($levels !== null && !is_array($types))) {
            return array();
        }

        foreach ($this->logs as $log) {
            if ($types === null || in_array($log['type'], $types)) {
                if ($levels === null || in_array($log['level'], $levels))
                    $result[] = $log;
            }
        }

        return $result;
    }

    /**
     * Remove all logged messages from memory
     */
    public function flush()
    {
        $this->onFlush();
        $this->logs = array();
        $this->logCount = 0;
    }

    /**
     * Raise 'onFlush' event
     */
    public function onFlush()
    {
        if (is_object($this->handler)) {
            $event = new REvent($this, $this->getLogs());
            if (method_exists($this->handler, "onFlush")) {
                $this->handler->onFlush($event);
            }
        }
    }

    /**
     * Attach a handler to handle the the flush event
     * @param array $handler
     */
    public function attachHandler($handler)
    {
        $this->handler = $handler;
    }

    /**
     * Detach the handler
     */
    public function detachHandler()
    {
        $this->handler = null;
    }

}