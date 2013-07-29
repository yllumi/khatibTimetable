<?php

namespace PavraSession\Components\Session;

class NativeSession implements ISession
{ 
    /**
     * {@inheritdoc}
     */
    public function start()
    {
        session_start();
        
        return session_id();
    }
    
    /**
     * {@inheritdoc} 
     */
    public function destroy()
    {
        $_SESSION = array();        
        
        if (isset($_COOKIE[session_name()]))
        {
            $params = session_get_cookie_params();
            
            setcookie(session_name(),'',time()-86400,
                      $params['path'],
                      $params['domain'],
                      $params['secure'],
                      $params['httponly']
                     );
        }
        
        session_destroy();
    }
    
    /**
     * {@inheritdoc} 
     */
    public function regenerateId()
    {
        session_regenerate_id();
        
        return session_id();
    }
    
    /**
     * {@inheritdoc} 
     */
    public function get($key, $default = NULL)
    {
        if (isset($_SESSION[$key]))
        {
            return $_SESSION[$key];
        }
        
        return $default;
    }
    
    /**
     * {@inheritdoc} 
     */
    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }
    
    /**
     * {@inheritdoc} 
     */
    public function delete($key)
    {
        if (isset($_SESSION[$key]))
        {
            unset($_SESSION[$key]);
            
            return TRUE;
        }
        
        return FALSE;
    }
}

