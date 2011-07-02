<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    /*
     * _initConfig
     *
     * Initializes the config
     *
     * @param void
     * @return void
     */
    protected function _initConfig()
    {
        #Configs
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/default.ini', APPLICATION_SRV);
        $this->setAppNamespace($config->appnamespace);
        $this->getContainer()->set('config', $config);
    }
    
    /*
     * _initDate
     *
     * Initializes the default timezone for the php ENV
     *
     * @param void
     * @return void
     */
    protected function _initDate() {
        $config = $this->getContainer()->get('config');
    	date_default_timezone_set($config->settings->application->datetime);
    }

    /*
     * _initLog
     *
     * Initializes Logging.
     *
     * @param void
     * @return void
    */
    protected function _initLog() {
    	
        $config = $this->getContainer()->get('config');
        $logPath = $config->resources->log->path;
        $filelog = new Zend_Log_Writer_Stream($logPath);
        $filter = new Zend_Log_Filter_Priority(Zend_Log::ERR);
        $filelog->addFilter($filter);
        $logger = new Zend_Log($filelog);
        if(APPLICATION_ENV != 'production') {
            $writer = new Zend_Log_Writer_Firebug();
            $fbfilter = new Zend_Log_Filter_Priority(Zend_Log::INFO);
            $writer->addFilter($fbfilter);
            $logger->addWriter($writer);
        }
        $logger->info(get_class($this).'::_initLog['.APPLICATION_ENV.']');
        $this->getContainer()->set('logger', $logger);
    }

    /*
     * _initRoutes
     *
     * Initializes Application Routes.
     *
     * @param void
     * @return void
    */
    protected function _initRoutes()
    {
    	#Set up router.
    	$this->bootstrap('FrontController');
    	$front = $this->getResource('FrontController');
        $router = $front->getInstance()->getRouter();
        foreach ($front->getControllerDirectory() as $module => $path) {
            $modulePath = dirname($path);
            $configPath = $modulePath.'/configs/default.ini';
            $moduleRoutes = new Zend_Config_Ini($configPath, 'default');
            //$moduleRoutes
            if($moduleRoutes->module->enabled) {
            	$router->addConfig($moduleRoutes, 'routes');
            }
        }//module.enabled
        //$routescfg = new Zend_Config_Ini(APPLICATION_PATH . '/configs/routes.ini', APPLICATION_SRV);
        //$router->addConfig($routescfg, 'routes');
    }

    /*
     * _initAutoload
     *
     * Initializes Autoloading for modules, entities, proxies & forms.
     *
     * @param void
     * @return void
    */
    protected function _initAutoload() {
        $front = $this->getResource('FrontController');
        $config = $this->getContainer()->get('config');

        $proxyPathes = array();
        $proxyPath = APPLICATION_PATH . $config->resources->doctrine->orm->manager->proxy->dir;
        $entitiesPathes = array();
        $formPathes = array();

        #This is a fallback to autoload our own classes in library
        $autoLoader = Zend_Loader_Autoloader::getInstance();
        $autoLoader->setFallbackAutoloader(true);
        foreach ($front->getControllerDirectory() as $module => $path) {
            #dirname goes back a folder from the controller directory so we have the root folder of the module.
            $modulePath = dirname($path);
	        $configPath = $modulePath.'/configs/default.ini';
            $moduleConfig = new Zend_Config_Ini($configPath, 'default');
        	if($moduleConfig->module->enabled == true) {
	            $entitiesPathes[] = $modulePath.'/entities';
	            $proxyPathes[] = $modulePath.'/entities/generated';
	            $formPathes[] = $modulePath.'/forms';
	            #TODO Move this lot to bootstrap, and get doctrine to load the proxy & entity pathes from a container.
	            #This is for loading form classes in the specified module. Modules MUST be stored in a folder of the same name as the class prefix.
	            #for windows ensure that there are no '\' before explode.
	            $modulePath=str_replace(DIRECTORY_SEPARATOR,'/',$modulePath);
	            $exPaths = explode('/',$modulePath);
	            $moduleName = ucfirst($exPaths[count($exPaths)-1]);
	            $autoloader = new Zend_Application_Module_Autoloader(array(
	                'namespace' => $moduleName.'_',
	                'basePath'  => $modulePath,
	                'resourceTypes' => array(
	                    'form' => array('path'=>'forms/', 'namespace'=>'Form'),
	                    'plugin' => array('path'=>'plugins/', 'namespace'=>'Plugin')
	                )
	            ));
			}
        }
        $autoloader = (object) array(
            'entities'=>$entitiesPathes,
            'proxies'=>$proxyPathes,
            'proxyPath'=>$proxyPath,
            'forms'=>$formPathes
        );
        #Store Pathes for later use.
        $this->getContainer()->set('autoload.pathes', $autoloader);
    }

}