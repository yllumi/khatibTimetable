<?php

namespace PavraSession\Components\Autoload;

class Autoload
{
    // Holds the paths search by the autoload function in order to include
    // the class.
    protected $_searchPaths = array();
    /**
     * Adds a search path to the autoload function. After instantiating the
     * Autoload class, at least one search path must be set. The search path
     * is saved with a trailing slash.
     * 
     * @param string $path the path to add to the autoloader search path
     * @return boolean TRUE if the path added is an existing directory
     */
    public function addSearchPath($path)
    {
        if (is_dir($path))
        {
            $path = realpath(rtrim($path, '\\/'));
            $this->_searchPaths[] = $path . DIRECTORY_SEPARATOR;

            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    /**
     * Class autoloader that works with PHP 5.3 namespaces. The namespace
     * is used as the path to the class, while the class name is the filename
     * that includes the class definition. By using underscores in class names,
     * classes can be nested in folders.
     * 
     * Example: 
     * 
     * \Namespace\Subnamespace\Class_Name resolves to 
     * Namespace/Subnamespace/Class/Name.php
     * 
     * @param string $className The name of the class to be autoloaded
     * @return void
     * @static
     */
    public function defaultLoader($className)
    {
        $className = ltrim($className, '\\');

        // split the $className into its namespace part (if any) and filename path
        $lastNamespacePosition = strripos($className, '\\');

        $classPath = '';

        if ($lastNamespacePosition !== FALSE)
        {
            $namespace = substr($className, 0, $lastNamespacePosition);
            $className = substr($className, $lastNamespacePosition + 1);

            $classPath = str_replace('\\', DIRECTORY_SEPARATOR, $namespace)
                       . DIRECTORY_SEPARATOR;
        }

        // Split class name into subfolders if underscores are found.
        $classPath .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

        // require the first file that will be found within the search paths
        // (search paths added first have priority over the ones added later)
        foreach ($this->_searchPaths as $searchPath)
        {
            if (is_file($searchPath . $classPath))
            {
                require_once $searchPath . $classPath;
            }
        }
    }

}
