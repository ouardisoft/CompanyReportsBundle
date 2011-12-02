<?php

namespace OS\CompanyReportsBundle;

/**
 * {inherite}
 *
 * @author abdou
 */
class CompanyReports extends Company
{

    /**
     * collect all of declared services
     * 
     * @var ServiceContainer $container
     */
    private $container;

    /**
     * Content of all declared webservices
     * 
     * @var array $webservices
     */
    private $webservices;

    /**
     * webservice class
     * 
     * @var array $webserviceClassName
     */
    private $webserviceClassName;

    /**
     * Selected locale (fr, es, it...)
     * 
     * @var string $locale
     */
    private $locale;

    /**
     * The country of selected webservice
     * 
     * @var string $country
     */
    private $country;

    /**
     * Path of cache directory
     * @var string
     */
    private $cacheDir;
    
    /**
     * 
     */
    private $cacheFile;
    
    /**
     * login to login into webservice
     * 
     * @var string $login
     */
    private $login;

    /**
     * user webservice password
     * 
     * @var string $password
     */
    private $password;

    public function __construct($container, $cacheDir = null)
    {
        $this->container = $container;
        $this->cacheDir = $cacheDir;
        
        $this->dispatch();
    }

    /**
     *
     * @return string $locale
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     *
     * @param string $locale 
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     *
     * @return string $country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     *
     * @param string $country 
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     *
     * @param array $webservices 
     */
    public function setWebservices($webservices)
    {
        $this->webservices = $webservices;
    }

    /**
     *
     * @return array $webservices
     */
    public function getWebservices()
    {
        return $this->webservices;
    }

    /**
     *
     * @return string $webserviceClassName
     */
    public function getWebserviceClassName()
    {
        return $this->webserviceClassName;
    }

    /**
     *
     * @param string $webserviceClassName 
     */
    public function setWebserviceClassName($webserviceClassName)
    {
        $this->webserviceClassName = $webserviceClassName;
    }

    /**
     *
     * @return string $login
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     *
     * @param string $login 
     */
    public function setLogin($login)
    {
        $this->login = $login;
    }

    /**
     *
     * @return string $password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     *
     * @param string $password 
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     *
     * @return type 
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     *
     * @param type $container 
     */
    public function setContainer($container)
    {
        $this->container = $container;
    }

    /**
     *
     * @return type 
     */
    public function getCacheDir()
    {
        return $this->cacheDir;
    }

    /**
     *
     * @param type $cacheDir 
     */
    public function setCacheDir($cacheDir)
    {
        $this->cacheDir = $cacheDir;
    }
    
    /**
     *
     * @return type 
     */
    public function getCacheFile()
    {
        if (!$this->cacheFile)
            $this->cacheFile = $this->getCacheDir () . DIRECTORY_SEPARATOR . $this->getSiret () . '.xml';
            
        return $this->cacheFile;
    }

    /**
     *
     * @param type $cacheFile 
     */
    public function setCacheFile($cacheFile)
    {
        $this->cacheFile = $cacheFile;
    }    
    /**
     * return webservice
     */
    public function getWS()
    {
        $class = $this->getWebserviceClassName();

        return new $class($this);
    }

    /**
     * execute and return results
     */
    public function execute($options = array())
    {
        $this->getWS()->execute($options);

        return $this;
    }

    /**
     * 
     */
    public function dispatch()
    {
        // list of declared webservices
        $this->setWebservices($this->container->getParameter('company.reports.webservices'));

        // Set the current locale
        $this->setLocale($this->container->get('request')->getLocale());

        // Set the current country
        $webservices = $this->getWebservices();

        if (!empty($webservices[$this->getLocale()])) {
            $webservice = $webservices[$this->getLocale()];
            $this->setCountry($webservice['country']);

            // select webservice class
            $this->setWebserviceClassName($webservice['webservice']);

            // login & password
            $this->setLogin($webservice['login']);
            $this->setPassword($webservice['password']);
        }
    }

}

?>
