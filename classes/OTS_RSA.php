<?php

/**#@+
 * @version 0.1.2+SVN
 * @since 0.1.2+SVN
 */

/**
 * @package POT
 * @author Wrzasq <wrzasq@gmail.com>
 * @author Alexander Valyalkin <valyala@gmail.com>
 * @copyright 2007 - 2008 (C) by Wrzasq
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 * @license http://www.php.net/license/3_0.txt PHP License, Version 3.0
 */

/**
 * RSA encryption/decryption mechanism.
 * 
 * This code bases in large part on Alexander Valyalkin'es Crypt_RSA's source code.
 * 
 * @package POT
 */
class OTS_RSA implements IOTS_Cipher
{
/**
 * OTServ key part.
 */
    const P = '14299623962416399520070177382898895550795403345466153217470516082934737582776038882967213386204600674145392845853859217990626450972452084065728686565928113';
/**
 * OTServ key part.
 */
    const Q = '7630979195970404721891201847792002125535401292779123937207447574596692788513647179235335529307251350570728407373705564708871762033017096809910315212884101';
/**
 * OTServ key part.
 */
    const D = '46730330223584118622160180015036832148732986808519344675210555262940258739805766860224610646919605860206328024326703361630109888417839241959507572247284807035235569619173792292786907845791904955103601652822519121908367187885509270025388641700821735345222087940578381210879116823013776808975766851829020659073';

/**
 * Keys modulus.
 * 
 * @var string
 */
    private $n;

/**
 * Private key exponent.
 * 
 * @var string
 */
    private $d;

/**
 * Public key exponent.
 * 
 * @var string
 */
    private $e = '65537';

/**
 * Length of keys modulus.
 * 
 * @var int
 */
    private $length;

/**
 * Initializes new encryption session.
 * 
 * If you won't pass any parameters default OTServ keys will be generated. It is recommended action for compatibility with oryginal Tibia servers and clients as well as default Open Tibia implementation.
 * 
 * Note: You must be sure your <i>p</i>, <i>q</i> and <i>d</i> values are proper for RSA keys generation as class won't change it for you.
 * 
 * @param string $p Key part.
 * @param string $q Key part.
 * @param string $d Key part.
 * @throws LogicException When BCMath extension is not loaded.
 */
    public function __construct($p = self::P, $q = self::Q, $d = self::D)
    {
        // checks if required BCMath library is loaded
        if( !extension_loaded('bcmath') )
        {
            throw new LogicException();
        }

        $this->d = $d;

        // computes keys modulus
        $this->n = bcmul($p, $q);

        // length of key
        $this->length = $this->bitLen($this->n);
    }

/**
 * Ecnrypts message with RSA algorithm.
 * 
 * @param string $message Message to be encrypted.
 * @return string Encrypted message.
 */
    public function encrypt($message)
    {
        // append tail \x01 to plain data. It needs for correctly decrypting of data
        $message = $this->bin2Int($message . chr(1) );

        // divide plain data into chunks
        $dataLength = $this->bitLen($message);
        $chunkLength = $this->length - 1;
        $blockLength = (int) ceil($chunkLength / 8);
        $pos = 0;
        $result = '';

        while($pos < $dataLength)
        {
            $byte = intval($pos / 8);
            $bit = $pos % 8;
            $byteLength = intval($chunkLength / 8);
            $bitLength = $chunkLength % 8;

            if($bitLength)
            {
                $byteLength++;
            }

            $tmp = str_pad( substr( $this->int2Bin( bcdiv($message, 1 << $bit) ), $byte, $byteLength), $byteLength, chr(0) );

            $result .= str_pad( $this->int2Bin( bcpowmod( $this->bin2Int( substr_replace($tmp, $tmp[$byteLength - 1] & chr(0xFF >> (8 - $bitLength) ), $byteLength - 1, 1) ), $this->e, $this->n) ), $blockLength, chr(0) );

            $pos += $chunkLength;
        }

        return $result;
    }

/**
 * Decrypts RSA-encrypted message.
 * 
 * @param string $message RSA-encrypted message.
 * @return string Decrypted content.
 */
    public function decrypt($message)
    {
        $dataLength = strlen($message);
        $chunkLength = $this->length - 1;
        $blockLength = (int) ceil($chunkLength / 8);
        $pos = 0;
        $current = 0;
        $result = '0';

        while($pos < $dataLength)
        {
            $byte = intval($current / 8);
            $bit = $current % 8;
            $tmp = $this->int2Bin($message);
            $tmp2 = $this->int2Bin( bcmul( bcpowmod( $this->bin2Int( substr($message, $pos, $blockLength) ), $this->d, $this->n), 1 << $bit) );

            if($byte < strlen($tmp) )
            {
                $tmp2 |= substr($tmp, $byte);
                $tmp = substr($tmp, 0, $byte) . $tmp2;
            }
            else
            {
                $tmp = str_pad($tmp, $byte, chr(0) ) . $tmp2;
            }

            $result = $this->bin2Int($tmp);

            $current += $chunkLength;
            $pos += $blockLength;
        }

        return substr( $this->int2Bin($result), 0, -1);
    }

/**
 * Transforms binary representation of large integer into string.
 * 
 * @param string $string Binary string.
 * @return string Numeric representation.
 */
    private function bin2Int($string)
    {
        $result = '0';
        $n = strlen($string);

        do
        {
            $result = bcadd( bcmul($result, '256'), ord($string[--$n]) );
        }
        while($n > 0);

        return $result;
    }

/**
 * Transforms large integer into binary string.
 * 
 * @param string $number Large integer.
 * @return string Binary string.
 */
    private function int2Bin($number)
    {
        $result = '';

        do
        {
            $result .= chr( bcmod($number, '256') );
            $number = bcdiv($number, '256');
        }
        while( bccomp($number, '0') );

        return $result;
    }

/**
 * Returns bit length of number $number.
 * 
 * @param string $number Large integer.
 * @return int Number of bits used.
 */
    private function bitLen($number)
    {
        $tmp = $this->int2Bin($number);
        $length = strlen($tmp) * 8;
        $tmp = ord($tmp[ strlen($tmp) - 1]);

        if($tmp == 0)
        {
            $length -= 8;
        }
        else
        {
            while(!($tmp & 0x80))
            {
                $length--;
                $tmp <<= 1;
            }
        }

        return $length;
    }
}

/**#@-*/

?>
