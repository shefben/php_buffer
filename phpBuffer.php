<?php
/*
 *
 *  * Copyright (c) 2021. NuWON.Net
 *  * NuWON Networks
 *  * Benjamin S.
 *
 *  This is the base class for the buffer.  It holds the base object and
 *  object size.  This class can then be used for both reading and writing
 *  to the buffer.
 *
 */

/**
 * Class phpBuffer
 */
class phpBuffer
{

    /**
     * @var mixed|string
     */
    protected $buf;

    /**
     * @var int
     */
    protected int $bufSize;

    /**
     * phpBuffer constructor.
     * @param string $buffer
     */
    function __construct($buffer = '')
    {
        $this->buf = $buffer;
        $this->bufSize = sizeof($this->buf);
    }

    /**
     *phpBuffer destructor.
     */
    function __destruct()
    {
        unset($this->buf, $this->bufSize);
    }

    /**
     * @return int
     */
    public function getBufSize() : int
    {
        return $this->bufSize;
    }

    /**
     * @return mixed|string
     */
    public function getBuf()
    {
        return $this->buf;
    }

    /**
     * @param mixed|string $buf
     */
    final protected function setBuf($buf) : void
    {
        $this->buf = $buf;
        $this->bufSize = sizeof($buf);
    }
}
?>
