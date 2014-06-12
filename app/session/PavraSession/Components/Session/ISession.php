<?php

namespace PavraSession\Components\Session;

interface ISession
{
    /**
     * Get a session variable. If the key does not exist, the default value
     * will be returned instead.
     * 
     * @access public
     * @param string $key The key of the session variable (case-sensitive)
     * @param mixed $default The default value returned if the key is not found
     * @return string The value of the session variable
     */
    public function get($key,$default);

    /**
     * Set a session variable. If the key already exists, it replaces the
     * existing value with the new one. The keys are case-sensitive.
     * 
     * @access public
     * @param string $key The name of the session variable
     * @param string $value The value of the session variable
     * @return void;
     */
    public function set($key,$value);
    
    /**
     * Deletes a session variable and returns TRUE if the variable existed or
     * FALSE for inexistent key.
     * 
     * @access public
     * @param string $key the name of the session variable
     * @return boolean TRUE if the variable was found and deleted
     */
    public function delete($key);
    
    /**
     * Starts a session or resumes the current one based on session identifier
     * passed via GET, POST request or via Cookie.
     * 
     * @access public
     * @return string the session Id 
     */    
    public function start();
            
    /**
     * Regenerates the Id of the session. It should be used for security reasons
     * (e.g. if a user logs into his/her account, the session Id should be 
     * regenerated in order to avoid session fixation attacks impact).
     * 
     * @access public
     * @return string the new Id 
     */
    public function regenerateId();
    
    /**
     * Unsets all the session variables and destroys the session.
     * 
     * @access public
     * @return void 
     */
    public function destroy();
}
