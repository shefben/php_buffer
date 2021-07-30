<?php
/*
 *
 *  * Copyright (c) 2021. NuWON.Net
 *  * NuWON Networks
 *  * Benjamin S.
 *
 *  This class is meant as a packet buffer.  It is used to create packets
 *  which are sent through the internets.  But in all fairness this class
 *  has many more uses than packet creation..
 *
 *  TODO: Code Better
 *  TODO: Add exceptions to prevent fatal errors
 *
 */

require_once "endianness.php";
require_once "phpBuffer.php";

/**
 * Class writeBuffer
 */
class writeBuffer extends phpBuffer
{

    /**
     * writeBuffer constructor.
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * @param $bytes
     * @param int $size
     */
    public function writeBytes($bytes, int $size) : void
    {
        $i = 0;
        while ($i <= $size) {
            $this->buf .= $bytes[$i];
            $i++;
        }
        $this->bufSize += $size;
    }

    /**
     * @param $value
     */
    public function writeByte($value) : void
    {
        $this->buf .= $value[0];
        $this->bufSize++;
    }

    /**
     * @param $value
     */
    public function writeShort($value) : void
    {
        //if(!substr($value,0,3)) {
        $this->buf .= pack('v*', $value);
        $this->bufSize += 2;
        /* }
          else
            return -1;*/
    }

    /**
     * @param $value
     */
    public function writeLong($value) : void
    {
        //if(!substr($value,0,5)) {
        $this->buf .= pack('V*', $value);
        $this->bufSize += 4;
        /* }
         else
             return -1; */
    }

    /**
     * @param $value
     */
    public function writeInt64($value) : void
    {
        //if(!substr($value,0,9)) {
        $this->buf .= pack('P*', $value);
        $this->bufSize += 8;
        /* }
           else
              return -1;*/
    }

    /**
     * @param $theLen
     * @param $fieldSize
     * @return int
     */
    public function writeLength($theLen, $fieldSize)
    {
        switch ($fieldSize) {
            case 1:
                $this->writeByte($theLen);
                break;
            case 2:
                $this->writeShort($theLen);
                break;
            case 4:
                $this->writeLong($theLen);
                break;
            default:
                return -1;
        }
    }

    /**
     * @param $string
     * @param int $fieldSize
     */
    public function writeString($string, $fieldSize = 2) : void
    {
        $stringSize = strlen($string);
        $this->writeLength($stringSize, $fieldSize);
        $this->buf .= $string;
       /* $i = 0;
        while ($i <= $stringSize) {
            $this->buf .= $string[$i];
            $i++;
        } */
    }

    /**
     * @param $string
     * @param int $fieldsize
     */
    public function writeWString($string, $fieldsize = 2) : void
    {
        $wstringLength = mb_strlen($string, "UTF-8");

        $this->writeLength($wstringLength, $fieldsize);

        if (isLittleEndian())
            $this->writeBytes($string, $wstringLength * 2);
        else {
            for ($i = 0; $i < $wstringLength; $i++)
                $this->writeShort($string[$i]);
        }
    }
}

?>
