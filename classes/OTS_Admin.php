<?php

/**#@+
 * @version 0.1.2+SVN
 * @since 0.1.2+SVN
 */

/**
 * @package POT
 * @author Wrzasq <wrzasq@gmail.com>
 * @copyright 2007 (C) by Wrzasq
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 */

/**
 * OTAdmin protocol client.
 * 
 * @package POT
 */
class OTS_Admin
{
/**
 * User login.
 */
    const REQUEST_LOGIN = 1;
/**
 * Encryption packet.
 */
    const REQUEST_ENCRYPTION = 2;
/**
 * RSA key exchange.
 */
    const REQUEST_KEY_EXCHANGE = 3;
/**
 * OTAdmin commnd.
 */
    const REQUEST_COMMAND = 4;
/**
 * Ping.
 */
    const REQUEST_PING = 5;

/**
 * Hello respond.
 */
    const RESPOND_HELLO = 1;
/**
 * Keys exchange success.
 */
    const RESPOND_KEY_EXCHANGE_OK = 2;
/**
 * Keys exchange failed.
 */
    const RESPOND_KEY_EXCHANGE_FAILED = 3;
/**
 * Login success.
 */
    const RESPOND_LOGIN_OK = 4;
/**
 * Login incorrect.
 */
    const RESPOND_LOGIN_FAILED = 5;
/**
 * Command success.
 */
    const RESPOND_COMMAND_OK = 6;
/**
 * Command failed.
 */
    const RESPOND_COMMAND_FAILED = 7;
/**
 * Encryption initialization success.
 */
    const RESPOND_ENCRYPTION_OK = 8;
/**
 * Encryption initialization failed.
 */
    const RESPOND_ENCRYPTION_FAILED = 9;
/**
 * Ping success.
 */
    const RESPOND_PING_OK = 10;
/**
 * Message.
 */
    const RESPOND_MESSAGE = 11;
/**
 * Error.
 */
    const RESPOND_ERROR = 12;

/**
 * Broadcast message.
 */
    const COMMAND_BROADCAST = 1;
/**
 * Closes server.
 */
    const COMMAND_CLOSE_SERVER = 2;
/**
 * Pays all rented shouses.
 */
    const COMMAND_PAY_HOUSES = 3;
/**
 * Not supported in current OTAdmin imlpementation.
 */
    const COMMAND_OPEN_SERVER = 4;
/**
 * Shutdowns the server.
 */
    const COMMAND_SHUTDOWN_SERVER = 5;
/**
 * Not supported in current OTAdmin imlpementation.
 */
    const COMMAND_RELOAD_SCRIPTS = 6;
/**
 * Not supported in current OTAdmin imlpementation.
 */
    const COMMAND_PLAYER_INFO = 7;
/**
 * Not supported in current OTAdmin imlpementation.
 */
    const COMMAND_GETONLINE = 8;
/**
 * Not supported in current OTAdmin imlpementation.
 */
    const COMMAND_KICK = 9;
/**
 * Not supported in current OTAdmin imlpementation.
 */
    const COMMAND_BAN_MANAGER = 10;
/**
 * Not supported in current OTAdmin imlpementation.
 */
    const COMMAND_SERVER_INFO = 11;
/**
 * Not supported in current OTAdmin imlpementation.
 */
    const COMMAND_GETHOUSE = 12;

/**
 * Server requires login.
 */
    const REQUIRE_LOGIN = 1;
/**
 * Server requires encryption.
 */
    const REQUIRE_ENCRYPTION = 2;

/**
 * Server uses XTEA encryption, XTEA key is being sent in 1024bit RSA encrypted packet.
 */
    const ENCRYPTION_RSA1024XTEA = 1;

/**
 * Socket handle.
 * 
 * @var resource
 */
    private $socket;

/**
 * Server security policy.
 * 
 * @var int
 */
    private $policy;

/**
 * Protocol options.
 * 
 * @var int
 */
    private $options;

/**
 * Packets cipher.
 * 
 * @var IOTS_Cipher
 */
    private $cipher;

/**
 * Host address for session sleep/wakeup.
 * 
 * @var string
 */
    private $host;

/**
 * Port number for session sleep/wakeup.
 * 
 * @var int
 */
    private $port;

/**
 * Creates new connection to OTServ administration backend.
 * 
 * @param string $host Target server.
 * @param int $port Port (7171 by default).
 * @throws E_OTS_ErrorCode When receive failed respond or unexpected message.
 */
    public function __construct($host, $port = 7171)
    {
        // saves connection sessint
        $this->host = $host;
        $this->port = $port;

        // opens connection
        $this->socket = fsockopen($this->host, $this->port);

        // 254 = OTAdmin protocol identifier
        $message = new OTS_Buffer( chr(254) );

        // sends initial packet
        $respond = $this->send($message);
        $message->reset();

        $byte = $respond->getChar();

        // checks if it's HELLO packet
        if($byte != self::RESPOND_HELLO)
        {
            throw new E_OTS_ErrorCode($byte);
        }

        // skips respond signature and protocol version
        $respond->getLong();
        $respond->getString();

        // saves protocol settings
        $this->policy = $respond->getShort();
        $this->options = $respond->getLong();

        // handles encryption initialisation if required
        if( $this->requiresEncryption() && $this->usesRSA1024XTEA() )
        {
            // requests public key
            $message->putChar(self::REQUEST_KEY_EXCHANGE);
            $message->putChar(self::ENCRYPTION_RSA1024XTEA);
            $respond = $this->send($message);
            $message->reset();

            $byte = $respond->getChar();

            // checks if it succeded
            if($byte != self::RESPOND_KEY_EXCHANGE_OK)
            {
                throw new E_OTS_ErrorCode($byte, $respond->getString() );
            }

            // we support only RSA 1024bit encryption for XTEA key sending
            if( $respond->getChar() != self::ENCRYPTION_RSA1024XTEA)
            {
                throw new E_OTS_ErrorCode($byte);
            }

            // reads binary form of public key (128 bytes)
            $key = OTS_BinaryTools::bin2Int( strrev( $respond->getString(128) ) );

            // creates RSA cipher
            // as we have ready N computer here and we don't compute it by ourselves we can use a little hack, to save N as P * Q we will use P = N and Q = 1
            $rsa = new OTS_RSA($key, '1');

            $key = '';

            // generates random XTEA key
            for($i = 0; $i < 4; $i++)
            {
                $key .= pack('L', rand(0, 4294967295) );
            }

            $xtea = new OTS_XTEA($key);

            // sends XTEA key
            $message->putChar(self::REQUEST_ENCRYPTION);
            $message->putChar(self::ENCRYPTION_RSA1024XTEA);
            $message->putString( $rsa->encrypt( chr(0) . $key), false);

            // we can't bind cipher yet since only respnd will be XTEA encrypted
            $respond = new OTS_Buffer( $xtea->decrypt( $this->send($message)->getBuffer() ) );

            $byte = $respond->getChar();

            // checks if encryption negotation succeeded
            if($byte != self::RESPOND_ENCRYPTION_OK)
            {
                throw new E_OTS_ErrorCode($byte, $respond->getString());
            }

            // saves encryption/decryption cipher
            $this->cipher = $xtea;
        }
    }

/**
 * Checks if protocol requires login.
 * 
 * @return bool True if protocol requires user login.
 */
    public function requiresLogin()
    {
        return ($this->policy & self::REQUIRE_LOGIN) == self::REQUIRE_LOGIN;
    }

/**
 * Checks if protocol requires encryption.
 * 
 * @return bool True if protocol requires encryption.
 */
    public function requiresEncryption()
    {
        return ($this->policy & self::REQUIRE_ENCRYPTION) == self::REQUIRE_ENCRYPTION;
    }

/**
 * Checks if protocol requires XTEA encryption with RSA-encrypted key.
 * 
 * @return bool True if protocol requires that encryption.
 */
    public function usesRSA1024XTEA()
    {
        return ($this->options & self::ENCRYPTION_RSA1024XTEA) == self::ENCRYPTION_RSA1024XTEA;
    }

/**
 * Sends OTAdmin packet.
 * 
 * @param OTS_Buffer $message Packet to be sent.
 * @return OTS_Buffer Server respond.
 * @throws E_OTS_ErrorCode When receive RESPOND_ERROR message.
 */
    public function send(OTS_Buffer $message)
    {
        $message = $message->getBuffer();

        // encrypts message if required
        if( isset($this->cipher) )
        {
            $message = $this->cipher->encrypt($message);
        }

        $message = pack('v', strlen($message) ) . $message;

        fputs($this->socket, $message);

        // reads respond length
        $length = unpack('v', fgets($this->socket, 3) );

        $buffer = fgets($this->socket, $length[1] + 1);

        // decrypts buffer if required
        if(  isset($this->cipher) )
        {
            $buffer = $this->cipher->decrypt($buffer);
        }

        // checks for error code
        $respond = new OTS_Buffer($buffer);

        if( $respond->getChar() == self::RESPOND_ERROR)
        {
            throw new E_OTS_ErrorCode(self::RESPOND_ERROR, $respond->getString() );
        }

        // returns respond
        $respond->setPos(0);
        return $respond;
    }

/**
 * Closes connection.
 */
    public function __destruct()
    {
        fclose($this->socket);
    }

/*
    public function __get($name)
    {
        switch($name)
        {

            default:
                return parent::__get($name);
        }
    }

    public function __set($name, $value)
    {
        switch($name)
        {

            default:
                parent::__set($name, $value);
        }
    }
*/
/**
 * Logs into server.
 * 
 * Call this method if after connection is established login required flag is set.
 * 
 * @param string $password Admin password.
 * @throws E_OTS_ErrorCode When receive failed respond or unexpected message.
 */
    public function login($password)
    {
        // password packet
        $message = new OTS_Buffer();
        $message->putChar(self::REQUEST_LOGIN);
        $message->putString($password);

        // reads respond
        $message = $this->send($message);

        $byte = $message->getChar();

        // checks respond
        if($byte != self::RESPOND_LOGIN_OK)
        {
            throw new E_OTS_ErrorCode($byte, $message->getString());
        }
    }

/**
 * Ping command.
 * 
 * Note: This methods calculates ping time based on {@link OTS_Admin::send() OTS_Admin::send()} sub-call. This means ping time will be time used for entire seding operation including packet encryption, packing, unpacking and decryption.
 * 
 * @return int Ping time.
 * @throws E_OTS_ErrorCode When receive failed respond or unexpected message.
 */
    public function ping()
    {
        // constructs message
        $message = new OTS_Buffer();
        $message->putChar(self::REQUEST_PING);

        // start
        $ping = microtime(true);
        $message = $this->send($message);

        // stop
        $ping = microtime(true) - $ping;

        $byte = $message->getChar();

        // checks if command succeeded
        if($byte != self::RESPOND_PING_OK)
        {
            throw new E_OTS_ErrorCode($byte, $respond->getString());
        }

        return $ping;
    }

/**
 * Sends command message.
 * 
 * This method wraps another buffer within command byte and also checks for command success.
 * 
 * @param OTS_Buffer $message Command to be send.
 * @return OTS_Buffer Respond.
 * @throws E_OTS_ErrorCode If failure respond received.
 */
    private function sendCommand(OTS_Buffer $message)
    {
        // prepends command byte
        $buffer = new OTS_Buffer();
        $buffer->putChar(self::REQUEST_COMMAND);
        $buffer->putString( $message->getBuffer(), false);

        // sends command
        $buffer = $this->send($buffer);
        $byte = $buffer->getChar();

        // checks for error code
        if($byte != self::RESPOND_COMMAND_OK)
        {
            throw new E_OTS_ErrorCode($byte, $buffer->getString() );
        }

        // returns respond with reseted position
        $buffer->setPos(0);
        return $buffer;
    }

/**
 * Sends broadcast message to all players.
 * 
 * Sends COMMAND_BROADCAST command with given parameter.
 * 
 * @param string $message Broadcast to be sent.
 */
    public function broadcast($message)
    {
        // sends message
        $buffer = new OTS_Buffer();
        $buffer->putChar(self::COMMAND_BROADCAST);
        $buffer->putString($message);
        $this->sendCommand($buffer);
    }

/**
 * Closes server.
 * 
 * Sends COMMAND_CLOSE_SERVER command. This command closes server for connections to enable maintenance but doesn't shut it down.
 */
    public function close()
    {
        // sends message
        $buffer = new OTS_Buffer();
        $buffer->putChar(self::COMMAND_CLOSE_SERVER);
        $this->sendCommand($buffer);
    }

/**
 * Takes fees for all rented houses.
 * 
 * Sends COMMAND_PAY_HOUSES command.
 */
    public function payHouses()
    {
        // sends message
        $buffer = new OTS_Buffer();
        $buffer->putChar(self::COMMAND_PAY_HOUSES);
        $this->sendCommand($buffer);
    }

/**
 * Shutdowns server.
 * 
 * Sends COMMAND_SHUTDOWN_SERVER command. This command closes server thread.
 */
    public function shutdown()
    {
        // sends message
        $buffer = new OTS_Buffer();
        $buffer->putChar(self::COMMAND_SHUTDOWN_SERVER);
        $this->sendCommand($buffer);
    }

/**
 * Magic PHP5 method.
 * 
 * Allows object importing from {@link http://www.php.net/manual/en/function.var-export.php var_export()}.
 * 
 * @internal Magic PHP5 method.
 * @param array $properties List of object properties.
 */
    public static function __set_state($properties)
    {
        return new self($properties['host'], $properties['port']);
    }

/**
 * Magic PHP5 method.
 * 
 * Creates new socket connection to server.
 */
    public function __clone()
    {
        $this->__construct($this->host, $this->port);
    }

/**
 * Magic PHP5 method.
 * 
 * Allows object serialisation.
 * 
 * @return array List of properties that should be saved.
 * @internal Magic PHP5 method.
 */
    public function __sleep()
    {
        return array('host', 'port');
    }

/**
 * Magic PHP5 method.
 * 
 * Allows object unserialisation.
 * 
 * @internal Magic PHP5 method.
 */
    public function __wakeup()
    {
        $this->__construct($this->host, $this->port);
    }
}

/**#@-*/

?>
