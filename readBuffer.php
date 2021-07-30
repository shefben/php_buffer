<?php
/*
 *
 *  * Copyright (c) 2021. NuWON.Net
 *  * NuWON Networks
 *  * Benjamin S.
 *
 *  This class is used to dissect/deconstruct a byte buffer.  It has many uses
 *  from deconstructing packets to deconstructing binary objects (blob).
 *
 *  TODO: Code Better
 *  TODO: Add exceptions to prevent fatal errors
 *
 */

require_once "endianness.php";
require_once "phpBuffer.php";

/**
 * Class readBuffer
 */
class readBuffer extends phpBuffer
{

    /**
     * readBuffer constructor.
     * @param $buffer
     */
    function  __construct($buffer)
    {
        parent::__construct($buffer);
    }

    /**
     * @param $size
     * @return false|string
     */
    public function readBytes($size)
    {
        $value = substr($this->buf, 0, $size);
        $this->setBuf(substr($this->buf, $size));
        return $value;
    }

    /**
     * @return false|string
     */
    public function readByte()
    {
        $value = substr($this->buf, 0, 0x01);
        $this->setBuf(substr($this->buf, 1));
        return bin2hex($value);
    }

    /**
     * @return bool
     */
    public function readBool() : bool
    {
        $value = (bool)$this->buf[0];
        $this->setBuf(substr($this->buf, 1));
        return (string)$value[1];
    }

    /**
     * @return int
     */
    public function readShort()
    {
        $value = unpack('v*', substr($this->buf, 0, 2));
        $this->setBuf(substr($this->buf, 2));
        return (string)$value[1];
    }

    /**
     * @return int
     */
    public function readLong()
    {
        $value = unpack('V*', substr($this->buf, 0, 4));
        $this->setBuf(substr($this->buf, 4));
        return (string)$value[1];
    }

    /**
     * @return array|false
     */
    public function writeInt64()
    {
        $value = unpack('P*', substr($this->buf, 0, 8));
        $this->setBuf(substr($this->buf, 8));
        return (string)$value[1];
    }

    /**
     * @param int $fieldSize
     * @return int
     */
    private function getStringLength(int $fieldSize)
    {
        switch ($fieldSize)
        {
            case 1:
                return $this->readByte();
            case 2:
                return $this->readShort();
            case 4:
                return $this->readLong();
            default:
                return -1;
        }
    }

    /**
     * @param int $fieldsize
     * @return string
     */
    public function readString(int $fieldSize)
    {
        $stringLength = $this->getStringLength($fieldSize);//bindec(substr($this->buf, 0, $fieldSize));
        $value = substr($this->buf, $fieldSize, $stringLength + 1);
        $this->setBuf(substr($this->buf, $stringLength+$fieldSize));
        return $value;
    }

    /**
     * @param int $fieldSize
     * @return string
     */
    public function readWString(int $fieldSize = 2)
    {
        $wStringLength = $this->getStringLength($fieldSize);//bindec(substr($this->buf, 0, $fieldSize));
        $theWStr = mb_strcut($this->buf, $fieldSize, $wStringLength, "UTF-8");
        $this->setBuf(substr($this->buf, $wStringLength+$fieldSize));
        return $theWStr;
    }

    /**
     * @param $str
     * @param string $end
     * @param int $base
     * @return int|string
     * string to unsigned long
     */
    public function strtoul($str, &$end = '', $base = 10)
    {
        $end = $str;
        if (!is_int($base) || $base > 36 || $base < 2)
            return 0;

        $chars = "0123456789abcdefghijklmnopqrstuvwxyz";
        $chars = substr($chars, 0, $base);

        $num = strspn($str, $chars);

        if ($num == 0)
            return 0;

        $first_str = substr($str, 0, $num);
        $end = substr($str, $num, strlen($str) - $num);
        return base_convert($first_str, $base, 10);
    }
}
?>
