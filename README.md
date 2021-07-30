# php_buffer
php library for reading and writing to a buffer. Personal use is for reading and writing packets, can be used for many things.


This project was supposed to help me build a structured packet for sending to a server and then
unpack the recieved packet into human readable format.

This is currently a work in progress and it is one of my first attempts at writing a full and independant php library/class.
There are some issues with readstring and writestring.  Im also not sure how to approach unicode strings, so it is a very basic function for
readwstring and writewstring

example:
```php
<?php
    include "writebuffer.php";
    include "readbuffer.php";

    $fieldlength = 2;
    $writeBuf = new writeBuffer();
    $writeBuf->writeByte("\xff");
    $writeBuf->writeLong(166);
    $writeBuf->writeShort(182);
    $writeBuf->writeString('this is a test', $fieldlength);
    $bytes = '\0x05\0xf1\0x12\0xff\0x05\0xf1\0x12';
    $writeBuf->writeBytes($bytes, strlen($bytes));
    
    echo $writeBuf->getBuf();
    
    $readbuf = new readBuffer($writeBuf->getBuf());
    echo '<br>' . $readbuf->readByte();
    echo '<br>' . $readbuf->readLong();
    echo '<br>' . $readbuf->readShort();
    echo '<br>' . $readbuf->readString();
    echo '<br>' . $readbuf->readBytes(strlen($bytes));
?>
```
