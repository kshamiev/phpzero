<?php

/**
 * Сессия.
 *
 * Realizuet centralizovannoe sokhranenie ob``ektov i drugikh tipov danny`kh v sessii.
 * Ispol`zuet pattern odinochka
 *
 * @package Zero.Component
 * @author Konstantin Shamiev aka ilosa <konstantin@shamiev.ru>
 * @date 2015.01.01
 */
class Zero_Session extends ArrayObject
{
    /**
     * Zero_Session object provides storage for shared objects.
     *
     * @var Zero_Session
     */
    private static $_Session = null;

    /**
     * Инициализация сессии в виде реестра (одиночка).
     */
    public static function Init($token = '')
    {
        // проверяем запущена ли сессия
        if ( !session_id() )
        {
            if ( isset($_REQUEST['token']) )
                session_id($_REQUEST['token']);
            else if ( isset($_SERVER['HTTP_X_ACCESS_TOKEN']) )
                session_id($_SERVER['HTTP_X_ACCESS_TOKEN']);
            if ( $token )
                session_name($token);
            session_start();
        }
        if ( !isset($_SESSION['Session']) || !$_SESSION['Session'] instanceof Zero_Session )
        {
            if ( self::$_Session === null )
                self::$_Session = new self;
            $_SESSION['Session'] = self::$_Session;
        }
        else
        {
            self::$_Session = &$_SESSION['Session'];
        }
    }

    /**
     * Retrieves the default Zero_Session instance.
     *
     * @return Zero_Session
     */
    public static function Get_Instance()
    {
        if ( self::$_Session === null )
            self::$_Session = new self;
        return self::$_Session;
    }

    /**
     * Set the default Zero_Session instance to a specified instance.
     *
     * @param Zero_Session $Session An object instance of type Session, or a subclass.
     */
    public static function Set_Instance(Zero_Session $Session)
    {
        if ( self::$_Session !== null )
            Zero_Logs::Set_Message_Error('Session is already initialized');
        self::$_Session = $Session;
    }

    /**
     * Unset the default Session instance.
     * Primarily used in tearDown() in unit tests.
     *
     */
    public static function Unset_Instance()
    {
        self::$_Session = null;
    }

    /**
     * getter method, basically same as offsetGet().
     *
     * This method can be called from an object of type Zero_Session, or it
     * can be called statically.  In the latter case, it uses the default
     * static instance stored in the class.
     *
     * @param string $index get the value associated with $index
     * @return mixed
     */
    public static function Get($index)
    {
        if ( !self::$_Session->offsetExists($index) )
            return false;
        return self::$_Session->offsetGet($index);
    }

    /**
     * setter method, basically same as offsetSet().
     *
     * This method can be called from an object of type Zero_Session, or it
     * can be called statically.  In the latter case, it uses the default
     * static instance stored in the class.
     *
     * @param string $index The location in the ArrayObject in which to store the value.
     * @param mixed $value The object to store in the ArrayObject.
     */
    public static function Set($index, $value)
    {
        self::$_Session->offsetSet($index, $value);
    }

    /**
     * Udalenie iz reestra po indeksu
     *
     * @param string $index
     */
    public static function Rem($index)
    {
        if ( self::$_Session->offsetExists($index) )
            self::$_Session->offsetUnset($index);
    }
}