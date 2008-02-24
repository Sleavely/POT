<?php

/**#@+
 * @version 0.1.2+SVN
 * @since 0.1.2+SVN
 */

/**
 * @package POT
 * @author Wrzasq <wrzasq@gmail.com>
 * @author Jeroen Derks <jeroen@derks.it>
 * @copyright 2007 - 2008 (C) by Wrzasq
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 * @license http://www.php.net/license/2_02.txt PHP License, Version 2.02
 */

/**
 * XTEA encryption/decryption mechanism.
 * 
 * This code bases in large part on Jeroen Derks'es Crypt_Xtea's source code.
 * 
 * @package POT
 */
class OTS_XTEA
{
/**
 * Encryption key.
 * 
 * @var array
 */
    private $key;

/**
 * Initializes new encryption session.
 * 
 * Note: Your key must be exacly 128bit length (16 characters)! Class will not resize it for you.
 * 
 * @param string $key Encryption key to be used.
 */
    public function __construct($key)
    {
        $this->key = array_values( unpack('N4', $key) );
    }

/**
 * Encrypt a string with XTEA algorithm.
 * 
 * @param string $message Data to encrypt.
 * @return string Encrypted message.
 */
    public function encrypt($message)
    {
        // resize data to 64 bits (2 longs of 32 bits)
        // leaves first long space for message length
        $n = strlen($message);
        $message = str_pad($message, $n - $n % 4 + 8, chr(0) );

        // convert data to long integers
        $message = unpack('N*', $message);
        $message[0] = $n;
        $length = count($message);

        $result = '';

        // encrypt the long data with the key
        for($i = 0; $i < $length; $i++)
        {
            $sum = 0;
            $delta = 0x9E3779B9;
            $y = $message[$i];
            $z = $message[++$i];

            for($j = 0; $j < 32; $j++)
            {
                $y = $this->unsignedAdd($y, $this->unsignedAdd( ($z << 4) ^ $this->unsignedRightShift($z, 5), $z) ^ $this->unsignedAdd($sum, $this->key[$sum & 3]) );
                $sum = $this->unsignedAdd($sum, $delta);
                $z = $this->unsignedAdd($z, $this->unsignedAdd( ($y << 4) ^ $this->unsignedRightShift($y, 5), $y) ^ $this->unsignedAdd($sum, $this->key[ $this->unsignedRightShift($sum, 11) & 3]) );
            }

            // append the enciphered longs to the result
            $result .= pack('NN', $y, $z);
        }

        return $result;
    }

/**
 * Decrypt XTEA-encrypted string.
 * 
 * @param string $message Encrypted message.
 * @return string Decrypted string.
 */
    public function decrypt($message)
    {
        // convert data to long
        $message = array_values( unpack('N*', $message) );
        $length = count($message);

        // decrypt the long data with the key
        $result = '';
        $offset = 0;

        for ($i = 0; $i < $length; $i++)
        {
            $sum = 0xC6EF3720;
            $delta = 0x9E3779B9;
            $y = $message[$i];
            $z = $message[++$i];

            for($j = 0; $j < 32; $j++)
            {
                $z = $this->unsignedAdd($z, -( $this->unsignedAdd( ($y << 4) ^ $this->unsignedRightShift($y, 5), $y) ^ $this->unsignedAdd($sum, $this->key[ $this->unsignedRightShift($sum, 11) & 3]) ) );
                $sum = $this->unsignedAdd($sum, -$delta);
                $y = $this->unsignedAdd($y, -( $this->unsignedAdd( ($z << 4) ^ $this->unsignedRightShift($z, 5), $z) ^ $this->unsignedAdd($sum, $this->key[$sum & 3]) ) );
            }

            // append the deciphered longs to the result data (remove padding)
            if(1 == $i)
            {
                $offset = $y;
                $result .= pack('N', $z);
            }
            else
            {
                $result .= pack('NN', $y, $z);
            }
        }

        return substr($result, 0, $offset);
    }

/**
 * Handle proper unsigned right shift, dealing with PHP's signed shift.
 * 
 * @param int $integer Number to be shifted.
 * @param int $n Number of bits to shift.
 * @return int Shifted integer.
 */
    private function unsignedRightShift($integer, $n)
    {
        // convert to 32 bits
        if(0xffffffff < $integer || -0xffffffff > $integer)
        {
            $integer = fmod($integer, 0xffffffff + 1);
        }

        // convert to unsigned integer
        if(0x7fffffff < $integer)
        {
            $integer -= 0xffffffff + 1;
        }
        elseif(-0x80000000 > $integer)
        {
            $integer += 0xffffffff + 1;
        }

        // do right shift
        if (0 > $integer)
        {
            // remove sign bit before shift
            $integer &= 0x7fffffff;
            // right shift
            $integer >>= $n;
            // set shifted sign bit
            $integer |= 1 << (31 - $n);
        }
        else
        {
            // use normal right shift
            $integer >>= $n;
        }

        return $integer;
    }

/**
 * Handle proper unsigned add, dealing with PHP's signed add.
 * 
 * @param int $a First number.
 * @param int $b Second number.
 * @return int Unsigned sum.
 */
    private function unsignedAdd($a, $b)
    {
        // remove sign if necessary
        if($a < 0)
        {
            $a -= 1 + 0xffffffff;
        }

        if($b < 0)
        {
            $b -= 1 + 0xffffffff;
        }

        $result = $a + $b;

        // convert to 32 bits
        if(0xffffffff < $result || -0xffffffff > $result)
        {
            $result = fmod($result, 0xffffffff + 1);
        }

        // convert to signed integer
        if(0x7fffffff < $result)
        {
            $result -= 0xffffffff + 1;
        }
        elseif(-0x80000000 > $result)
        {
            $result += 0xffffffff + 1;
        }

        return $result;
    }
}

/**#@-*/

?>
