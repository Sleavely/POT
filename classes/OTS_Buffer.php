<?php

/**#@+
 * @version 0.1.2
 * @since 0.1.2
 */

/**
 * This is generic class for classes that uses buffer-baser read-write operations (it can also emulate C-like pointers).
 * 
 * @package POT
 * @version 0.1.3+SVN
 * @author Wrzasq <wrzasq@gmail.com>
 * @copyright 2007 - 2008 (C) by Wrzasq
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 */

/**
 * Binary buffer container.
 * 
 * @package POT
 * @version 0.1.3+SVN
 * @property string $buffer Properties binary string.
 * @property int $char getChar()/putChar() method wrapper.
 * @property int $short getShort()/putShort() method wrapper.
 * @property int $long getLong()/putLong() method wrapper.
 * @property string $string getString(false)/putString(, true) call wrapper.
 * @property int $pos getPos()/setPos() method wrapper.
 * @property-read bool $valid isValid() method wrapper.
 * @property-read int $size getSize() method wrapper.
 */
class OTS_Buffer
{
/**
 * Node properties stream.
 * 
 * @var string
 */
    protected $buffer;
/**
 * Properties stream pointer.
 * 
 * @var int
 */
    protected $pos;

/**
 * Initializes new buffered reader.
 * 
 * @param string $buffer Buffer content.
 */
    public function __construct($buffer = '')
    {
        $this->buffer = $buffer;
        $this->pos = 0;
    }

/**
 * Magic PHP5 method.
 * 
 * Allows object importing from {@link http://www.php.net/manual/en/function.var-export.php var_export()}.
 * 
 * @param array $properties List of object properties.
 */
    public static function __set_state($properties)
    {
        $object = new self();

        // loads properties
        foreach($properties as $name => $value)
        {
            $object->$name = $value;
        }

        return $object;
    }

/**
 * Returs properties stream.
 * 
 * @return string Properties stream.
 */
    public function getBuffer()
    {
        return $this->buffer;
    }

/**
 * Sets properties stream.
 * 
 * @param string Properties stream.
 */
    public function setBuffer($buffer)
    {
        $this->buffer = $buffer;
        $this->pos = 0;
    }

/**
 * Checks if there is anything left in stream.
 * 
 * @return bool False if pointer is at the end of stream.
 */
    public function isValid()
    {
        return $this->pos < strlen($this->buffer);
    }

/**
 * Checks stream end state.
 * 
 * @param int $size Amount of bytes that are going to be read.
 * @throws E_OTS_OutOfBuffer When there is read attemp after end of stream.
 */
    protected function check($size = 1)
    {
        if( strlen($this->buffer) < $this->pos + $size)
        {
            throw new E_OTS_OutOfBuffer();
        }
    }

/**
 * Returns single byte.
 * 
 * @return int Byte (char) value.
 */
    public function getChar()
    {
        // checks buffer size
        $this->check();

        $value = ord($this->buffer[$this->pos]);
        $this->pos++;
        return $value;
    }

/**
 * Appends single byte to buffer.
 * 
 * @param int $char Byte (char) value.
 */
    public function putChar($char)
    {
        $this->buffer .= chr($char);
    }

/**
 * Returns double byte.
 * 
 * @return int Word (short) value.
 */
    public function getShort()
    {
        // checks buffer size
        $this->check(2);

        $value = unpack('v', substr($this->buffer, $this->pos, 2) );
        $this->pos += 2;
        return $value[1];
    }

/**
 * Appends double byte to buffer.
 * 
 * @param int $short Word (short) value.
 */
    public function putShort($short)
    {
        $this->buffer .= pack('v', $short);
    }

/**
 * Returns quater byte.
 * 
 * @return int Double word (long) value.
 */
    public function getLong()
    {
        // checks buffer size
        $this->check(4);

        $value = unpack('V', substr($this->buffer, $this->pos, 4) );
        $this->pos += 4;
        return $value[1];
    }

/**
 * Appends quater byte to buffer.
 * 
 * @param int $long Double word (long) value.
 */
    public function putLong($long)
    {
        $this->buffer .= pack('V', $long);
    }

/**
 * Returns string from buffer.
 * 
 * If length is not given then treats first byte from current buffer as string length.
 * 
 * @param int|bool $length String length.
 * @return string First substring.
 */
    public function getString($length = false)
    {
        // reads string length if not given
        if($length === false)
        {
            $length = $this->getShort();
        }

        // checks buffer size
        $this->check($length);

        // copies substring
        $value = substr($this->buffer, $this->pos, $length);
        $this->pos += $length;
        return $value;
    }

/**
 * Appends string to buffer.
 * 
 * @param string $string Binary length.
 * @param bool $dynamic Whether if string length is fixed or not (if it is dynamic then length will be inserted as short before string chunk).
 */
    public function putString($string, $dynamic = true)
    {
        // appends string length if requires
        if($dynamic)
        {
            $this->putShort( strlen($string) );
        }

        $this->buffer .= $string;
    }

/**
 * Empties buffer.
 */
    public function reset()
    {
        $this->__construct();
    }

/**
 * Returns current read position.
 * 
 * @return int Read position.
 */
    public function getPos()
    {
        return $this->pos;
    }

/**
 * Seeks current reading position.
 * 
 * @param int $pos Read position.
 */
    public function setPos($pos)
    {
        $this->pos = $pos;
    }

/**
 * Returns buffer size.
 * 
 * @return int Buffer length.
 */
    public function getSize()
    {
        return strlen($this->buffer);
    }

/**
 * Skips given amount of bytes.
 * 
 * @param int $n Bytes to skip.
 */
    public function skip($n)
    {
        $this->check($n);
        $this->pos += $n;
    }

/**
 * Magic PHP5 method.
 * 
 * @param string $name Property name.
 * @return mixed Property value.
 * @throws OutOfBoundsException For non-supported properties.
 */
    public function __get($name)
    {
        switch($name)
        {
            // simple properties
            case 'buffer':
                return $this->buffer;

            // isValid() wrapper
            case 'valid':
                return $this->isValid();

            // getChar() wrapper
            case 'char':
                return $this->getChar();

            // getShort() wrapper
            case 'short':
                return $this->getShort();

            // getLong() wrapper
            case 'long':
                return $this->getLong();

            // getString() wrapper
            case 'string':
                return $this->getString();

            // getPos() wrapper
            case 'pos':
                return $this->getPos();

            // getSize() wrapper
            case 'size':
                return $this->getSize();

            default:
                throw new OutOfBoundsException();
        }
    }

/**
 * Magic PHP5 method.
 * 
 * @version 0.1.3+SVN
 * @param string $name Property name.
 * @param mixed $value Property value.
 * @throws OutOfBoundsException For non-supported properties.
 */
    public function __set($name, $value)
    {
        switch($name)
        {
            // buffer needs to be reset
            case 'buffer':
                $this->setBuffer($value);
                break;

            // putChar() wrapper
            case 'char':
                $this->putChar($value);
                break;

            // putShort() wrapper
            case 'short':
                $this->putShort($value);
                break;

            // putLong() wrapper
            case 'long':
                $this->putLong($value);
                break;

            // putString() wrapper
            case 'string':
                $this->putString($value);
                break;

            // setPos() wrapper
            case 'pos':
                $this->setPos($value);
                break;

            default:
                throw new OutOfBoundsException();
        }
    }

/**
 * Returns string representation of buffer object.
 * 
 * @return string Object's buffer.
 */
    public function __toString()
    {
        return $this->buffer;
    }

/**
 * Resets pointer of cloned object.
 */
    public function __clone()
    {
        $this->pos = 0;
    }
}

/**#@-*/

?>
