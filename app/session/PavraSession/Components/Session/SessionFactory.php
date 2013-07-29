<?php

namespace PavraSession\Components\Session;

/**
 * The session factory class creates an instance of a Session class. All session
 * classes share a common interface/api, so by instantiating a Session class in
 * one place, one can easily switch between Session types(native, Database etc).
 * 
 * Example:
 * 
 * $session = SessionFactory('native'); * 
 * 
 * @abstract
 */
abstract class SessionFactory
{
    /**
     * Creates an instance of a Session (e.g. native php session, database
     * based session etc).
     * 
     * Example:
     * SessionFactory::create('native') will result in creating an instance of
     * \Pavra\Components\Session\NativeSession 
     * 
     * @param string $sessionType The type of the session we want to create
     * @return Session class instancce or FALSE if $sessionType is invalid
     * @static
     */
    public static function create($sessionType)
    {        
        $sessionClass = __NAMESPACE__.'\\'.ucfirst(strtolower($sessionType)).'Session';
        
        if (class_exists($sessionClass))
        {
            return new $sessionClass();
        }
        
        return FALSE;
    }
}
