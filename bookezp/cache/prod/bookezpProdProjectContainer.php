<?php

use Symfony\Components\DependencyInjection\Container;
use Symfony\Components\DependencyInjection\Reference;
use Symfony\Components\DependencyInjection\Parameter;


class bookezpProdProjectContainer extends Container
{
    protected $shared = array();

    
    public function __construct()
    {
        parent::__construct();

        $this->parameters = $this->getDefaultParameters();
    }

    
    protected function getEventDispatcherService()
    {
        if (isset($this->shared['event_dispatcher'])) return $this->shared['event_dispatcher'];

        $instance = new Symfony\Foundation\EventDispatcher($this);
        $this->shared['event_dispatcher'] = $instance;

        return $instance;
    }

    
    protected function getErrorHandlerService()
    {
        if (isset($this->shared['error_handler'])) return $this->shared['error_handler'];

        $instance = new Symfony\Foundation\Debug\ErrorHandler($this->getParameter('error_handler.level'));
        $this->shared['error_handler'] = $instance;
        $instance->register();

        return $instance;
    }

    
    protected function getHttpKernelService()
    {
        if (isset($this->shared['http_kernel'])) return $this->shared['http_kernel'];

        $instance = new Symfony\Components\HttpKernel\HttpKernel($this->getEventDispatcherService());
        $this->shared['http_kernel'] = $instance;

        return $instance;
    }

    
    protected function getRequestService()
    {
        if (isset($this->shared['request'])) return $this->shared['request'];

        $instance = new Symfony\Components\HttpKernel\Request();
        $this->shared['request'] = $instance;

        return $instance;
    }

    
    protected function getResponseService()
    {
        $instance = new Symfony\Components\HttpKernel\Response();

        return $instance;
    }

    
    protected function getControllerManagerService()
    {
        if (isset($this->shared['controller_manager'])) return $this->shared['controller_manager'];

        $instance = new Symfony\Framework\FoundationBundle\Controller\ControllerManager($this, $this->getService('logger', Container::NULL_ON_INVALID_REFERENCE));
        $this->shared['controller_manager'] = $instance;

        return $instance;
    }

    
    protected function getControllerLoaderService()
    {
        if (isset($this->shared['controller_loader'])) return $this->shared['controller_loader'];

        $instance = new Symfony\Framework\FoundationBundle\Listener\ControllerLoader($this->getControllerManagerService(), $this->getService('logger', Container::NULL_ON_INVALID_REFERENCE));
        $this->shared['controller_loader'] = $instance;

        return $instance;
    }

    
    protected function getRequestParserService()
    {
        if (isset($this->shared['request_parser'])) return $this->shared['request_parser'];

        $instance = new Symfony\Framework\FoundationBundle\Listener\RequestParser($this, $this->getRouterService(), $this->getService('logger', Container::NULL_ON_INVALID_REFERENCE));
        $this->shared['request_parser'] = $instance;

        return $instance;
    }

    
    protected function getRouterService()
    {
        if (isset($this->shared['router'])) return $this->shared['router'];

        $instance = new Symfony\Components\Routing\Router(array(0 => $this->getService('kernel'), 1 => 'registerRoutes'), array('cache_dir' => $this->getParameter('kernel.cache_dir'), 'debug' => $this->getParameter('kernel.debug'), 'matcher_cache_class' => $this->getParameter('kernel.name').'UrlMatcher', 'generator_cache_class' => $this->getParameter('kernel.name').'UrlGenerator'));
        $this->shared['router'] = $instance;

        return $instance;
    }

    
    protected function getEsiService()
    {
        if (isset($this->shared['esi'])) return $this->shared['esi'];

        $instance = new Symfony\Components\HttpKernel\Cache\Esi();
        $this->shared['esi'] = $instance;

        return $instance;
    }

    
    protected function getEsiFilterService()
    {
        if (isset($this->shared['esi_filter'])) return $this->shared['esi_filter'];

        $instance = new Symfony\Components\HttpKernel\Listener\EsiFilter($this->getService('esi', Container::NULL_ON_INVALID_REFERENCE));
        $this->shared['esi_filter'] = $instance;

        return $instance;
    }

    
    protected function getResponseFilterService()
    {
        if (isset($this->shared['response_filter'])) return $this->shared['response_filter'];

        $instance = new Symfony\Components\HttpKernel\Listener\ResponseFilter();
        $this->shared['response_filter'] = $instance;

        return $instance;
    }

    
    protected function getExceptionHandlerService()
    {
        if (isset($this->shared['exception_handler'])) return $this->shared['exception_handler'];

        $instance = new Symfony\Framework\FoundationBundle\Listener\ExceptionHandler($this, $this->getService('logger', Container::NULL_ON_INVALID_REFERENCE), $this->getParameter('exception_handler.controller'));
        $this->shared['exception_handler'] = $instance;

        return $instance;
    }

    
    protected function getTemplating_EngineService()
    {
        if (isset($this->shared['templating.engine'])) return $this->shared['templating.engine'];

        $instance = new Symfony\Framework\FoundationBundle\Templating\Engine($this, $this->getTemplating_Loader_FilesystemService(), array(), $this->getParameter('templating.output_escaper'));
        $this->shared['templating.engine'] = $instance;
        $instance->setCharset($this->getParameter('kernel.charset'));

        return $instance;
    }

    
    protected function getTemplating_Loader_FilesystemService()
    {
        if (isset($this->shared['templating.loader.filesystem'])) return $this->shared['templating.loader.filesystem'];

        $instance = new Symfony\Components\Templating\Loader\FilesystemLoader($this->getParameter('templating.loader.filesystem.path'));
        $this->shared['templating.loader.filesystem'] = $instance;
        if ($this->hasService('templating.debugger')) {
            $instance->setDebugger($this->getService('templating.debugger', Container::NULL_ON_INVALID_REFERENCE));
        }

        return $instance;
    }

    
    protected function getTemplating_Loader_CacheService()
    {
        if (isset($this->shared['templating.loader.cache'])) return $this->shared['templating.loader.cache'];

        $instance = new Symfony\Components\Templating\Loader\CacheLoader($this->getService('templating.loader.wrapped'), $this->getParameter('templating.loader.cache.path'));
        $this->shared['templating.loader.cache'] = $instance;
        if ($this->hasService('templating.debugger')) {
            $instance->setDebugger($this->getService('templating.debugger', Container::NULL_ON_INVALID_REFERENCE));
        }

        return $instance;
    }

    
    protected function getTemplating_Loader_ChainService()
    {
        if (isset($this->shared['templating.loader.chain'])) return $this->shared['templating.loader.chain'];

        $instance = new Symfony\Components\Templating\Loader\ChainLoader();
        $this->shared['templating.loader.chain'] = $instance;
        if ($this->hasService('templating.debugger')) {
            $instance->setDebugger($this->getService('templating.debugger', Container::NULL_ON_INVALID_REFERENCE));
        }

        return $instance;
    }

    
    protected function getTemplating_Helper_JavascriptsService()
    {
        if (isset($this->shared['templating.helper.javascripts'])) return $this->shared['templating.helper.javascripts'];

        $instance = new Symfony\Components\Templating\Helper\JavascriptsHelper($this->getTemplating_Helper_AssetsService());
        $this->shared['templating.helper.javascripts'] = $instance;

        return $instance;
    }

    
    protected function getTemplating_Helper_StylesheetsService()
    {
        if (isset($this->shared['templating.helper.stylesheets'])) return $this->shared['templating.helper.stylesheets'];

        $instance = new Symfony\Components\Templating\Helper\StylesheetsHelper($this->getTemplating_Helper_AssetsService());
        $this->shared['templating.helper.stylesheets'] = $instance;

        return $instance;
    }

    
    protected function getTemplating_Helper_SlotsService()
    {
        if (isset($this->shared['templating.helper.slots'])) return $this->shared['templating.helper.slots'];

        $instance = new Symfony\Components\Templating\Helper\SlotsHelper();
        $this->shared['templating.helper.slots'] = $instance;

        return $instance;
    }

    
    protected function getTemplating_Helper_AssetsService()
    {
        if (isset($this->shared['templating.helper.assets'])) return $this->shared['templating.helper.assets'];

        $instance = new Symfony\Components\Templating\Helper\AssetsHelper($this->getParameter('request.base_path'), '', $this->getParameter('templating.assets.version'));
        $this->shared['templating.helper.assets'] = $instance;

        return $instance;
    }

    
    protected function getTemplating_Helper_RequestService()
    {
        if (isset($this->shared['templating.helper.request'])) return $this->shared['templating.helper.request'];

        $instance = new Symfony\Framework\FoundationBundle\Helper\RequestHelper($this->getRequestService());
        $this->shared['templating.helper.request'] = $instance;

        return $instance;
    }

    
    protected function getTemplating_Helper_UserService()
    {
        if (isset($this->shared['templating.helper.user'])) return $this->shared['templating.helper.user'];

        $instance = new Symfony\Framework\FoundationBundle\Helper\UserHelper($this->getService('user'));
        $this->shared['templating.helper.user'] = $instance;

        return $instance;
    }

    
    protected function getTemplating_Helper_RouterService()
    {
        if (isset($this->shared['templating.helper.router'])) return $this->shared['templating.helper.router'];

        $instance = new Symfony\Framework\FoundationBundle\Helper\RouterHelper($this->getRouterService());
        $this->shared['templating.helper.router'] = $instance;

        return $instance;
    }

    
    protected function getTemplating_Helper_ActionsService()
    {
        if (isset($this->shared['templating.helper.actions'])) return $this->shared['templating.helper.actions'];

        $instance = new Symfony\Framework\FoundationBundle\Helper\ActionsHelper($this->getControllerManagerService());
        $this->shared['templating.helper.actions'] = $instance;

        return $instance;
    }

    
    protected function getDoctrine_Dbal_Logger_DebugService()
    {
        if (isset($this->shared['doctrine.dbal.logger.debug'])) return $this->shared['doctrine.dbal.logger.debug'];

        $instance = new Doctrine\DBAL\Logging\DebugStack();
        $this->shared['doctrine.dbal.logger.debug'] = $instance;

        return $instance;
    }

    
    protected function getDoctrine_Dbal_LoggerService()
    {
        if (isset($this->shared['doctrine.dbal.logger'])) return $this->shared['doctrine.dbal.logger'];

        $instance = new Symfony\Framework\DoctrineBundle\Logger\DbalLogger($this->getService('logger', Container::NULL_ON_INVALID_REFERENCE));
        $this->shared['doctrine.dbal.logger'] = $instance;

        return $instance;
    }

    
    protected function getDoctrine_DataCollectorService()
    {
        if (isset($this->shared['doctrine.data_collector'])) return $this->shared['doctrine.data_collector'];

        $instance = new Symfony\Framework\DoctrineBundle\DataCollector\DoctrineDataCollector($this);
        $this->shared['doctrine.data_collector'] = $instance;

        return $instance;
    }

    
    protected function getDoctrine_Dbal_DefaultConnection_ConfigurationService()
    {
        if (isset($this->shared['doctrine.dbal.default_connection.configuration'])) return $this->shared['doctrine.dbal.default_connection.configuration'];

        $instance = new Doctrine\DBAL\Configuration();
        $this->shared['doctrine.dbal.default_connection.configuration'] = $instance;
        $instance->setSqlLogger($this->getDoctrine_Dbal_LoggerService());

        return $instance;
    }

    
    protected function getDoctrine_Dbal_DefaultConnection_EventManagerService()
    {
        if (isset($this->shared['doctrine.dbal.default_connection.event_manager'])) return $this->shared['doctrine.dbal.default_connection.event_manager'];

        $instance = new Doctrine\Common\EventManager();
        $this->shared['doctrine.dbal.default_connection.event_manager'] = $instance;

        return $instance;
    }

    
    protected function getDoctrine_Dbal_DefaultConnectionService()
    {
        if (isset($this->shared['doctrine.dbal.default_connection'])) return $this->shared['doctrine.dbal.default_connection'];

        $instance = call_user_func(array('Doctrine\\DBAL\\DriverManager', 'getConnection'), array('driverClass' => 'Doctrine\\DBAL\\Driver\\PDOMySql\\Driver', 'driverOptions' => array(), 'dbname' => 'xxxxxxxx', 'host' => 'localhost', 'user' => 'root'), $this->getDoctrine_Dbal_DefaultConnection_ConfigurationService(), $this->getDoctrine_Dbal_DefaultConnection_EventManagerService());
        $this->shared['doctrine.dbal.default_connection'] = $instance;

        return $instance;
    }

    
    protected function getDoctrine_Orm_MetadataDriver_AnnotationService()
    {
        if (isset($this->shared['doctrine.orm.metadata_driver.annotation'])) return $this->shared['doctrine.orm.metadata_driver.annotation'];

        $instance = new Doctrine\ORM\Mapping\Driver\AnnotationDriver($this->getDoctrine_Orm_MetadataDriver_Annotation_ReaderService(), $this->getParameter('doctrine.orm.entity_dirs'));
        $this->shared['doctrine.orm.metadata_driver.annotation'] = $instance;

        return $instance;
    }

    
    protected function getDoctrine_Orm_MetadataDriver_Annotation_ReaderService()
    {
        if (isset($this->shared['doctrine.orm.metadata_driver.annotation.reader'])) return $this->shared['doctrine.orm.metadata_driver.annotation.reader'];

        $instance = new Doctrine\Common\Annotations\AnnotationReader($this->getDoctrine_Orm_Cache_ArrayService());
        $this->shared['doctrine.orm.metadata_driver.annotation.reader'] = $instance;
        $instance->setDefaultAnnotationNamespace('Doctrine\\ORM\\Mapping\\');

        return $instance;
    }

    
    protected function getDoctrine_Orm_MetadataDriver_XmlService()
    {
        if (isset($this->shared['doctrine.orm.metadata_driver.xml'])) return $this->shared['doctrine.orm.metadata_driver.xml'];

        $instance = new Doctrine\ORM\Mapping\Driver\XmlDriver($this->getParameter('doctrine.orm.metadata_driver.mapping_dirs'));
        $this->shared['doctrine.orm.metadata_driver.xml'] = $instance;

        return $instance;
    }

    
    protected function getDoctrine_Orm_MetadataDriver_YmlService()
    {
        if (isset($this->shared['doctrine.orm.metadata_driver.yml'])) return $this->shared['doctrine.orm.metadata_driver.yml'];

        $instance = new Doctrine\ORM\Mapping\Driver\YamlDriver($this->getParameter('doctrine.orm.metadata_driver.mapping_dirs'));
        $this->shared['doctrine.orm.metadata_driver.yml'] = $instance;

        return $instance;
    }

    
    protected function getDoctrine_Orm_Cache_ArrayService()
    {
        if (isset($this->shared['doctrine.orm.cache.array'])) return $this->shared['doctrine.orm.cache.array'];

        $instance = new Doctrine\Common\Cache\ArrayCache();
        $this->shared['doctrine.orm.cache.array'] = $instance;

        return $instance;
    }

    
    protected function getDoctrine_Orm_Cache_ApcService()
    {
        if (isset($this->shared['doctrine.orm.cache.apc'])) return $this->shared['doctrine.orm.cache.apc'];

        $instance = new Doctrine\Common\Cache\ApcCache();
        $this->shared['doctrine.orm.cache.apc'] = $instance;

        return $instance;
    }

    
    protected function getDoctrine_Orm_Cache_MemcacheService()
    {
        if (isset($this->shared['doctrine.orm.cache.memcache'])) return $this->shared['doctrine.orm.cache.memcache'];

        $instance = new Doctrine\Common\Cache\MemcacheCache();
        $this->shared['doctrine.orm.cache.memcache'] = $instance;
        $instance->setMemcache($this->getDoctrine_Orm_Cache_Memcache_InstanceService());

        return $instance;
    }

    
    protected function getDoctrine_Orm_Cache_Memcache_InstanceService()
    {
        if (isset($this->shared['doctrine.orm.cache.memcache.instance'])) return $this->shared['doctrine.orm.cache.memcache.instance'];

        $instance = new Memcache();
        $this->shared['doctrine.orm.cache.memcache.instance'] = $instance;
        $instance->connect($this->getParameter('doctrine.orm.cache.memcache.host'), $this->getParameter('doctrine.orm.cache.memcache.port'));

        return $instance;
    }

    
    protected function getDoctrine_Orm_Cache_XcacheService()
    {
        if (isset($this->shared['doctrine.orm.cache.xcache'])) return $this->shared['doctrine.orm.cache.xcache'];

        $instance = new Doctrine\Common\Cache\XcacheCache();
        $this->shared['doctrine.orm.cache.xcache'] = $instance;

        return $instance;
    }

    
    protected function getDoctrine_Orm_DefaultConfigurationService()
    {
        if (isset($this->shared['doctrine.orm.default_configuration'])) return $this->shared['doctrine.orm.default_configuration'];

        $instance = new Doctrine\ORM\Configuration();
        $this->shared['doctrine.orm.default_configuration'] = $instance;
        $instance->setEntityNamespaces(array());
        $instance->setMetadataCacheImpl($this->getDoctrine_Orm_MetadataCacheService());
        $instance->setQueryCacheImpl($this->getDoctrine_Orm_QueryCacheService());
        $instance->setResultCacheImpl($this->getDoctrine_Orm_ResultCacheService());
        $instance->setMetadataDriverImpl($this->getDoctrine_Orm_MetadataDriverService());
        $instance->setProxyDir($this->getParameter('kernel.cache_dir').'/doctrine/Proxies');
        $instance->setProxyNamespace('Proxies');
        $instance->setAutoGenerateProxyClasses(true);

        return $instance;
    }

    
    protected function getDoctrine_Orm_MetadataCacheService()
    {
        if (isset($this->shared['doctrine.orm.metadata_cache'])) return $this->shared['doctrine.orm.metadata_cache'];

        $instance = new Doctrine\Common\Cache\ArrayCache();
        $this->shared['doctrine.orm.metadata_cache'] = $instance;
        $instance->setNamespace('doctrine_metadata_');

        return $instance;
    }

    
    protected function getDoctrine_Orm_QueryCacheService()
    {
        if (isset($this->shared['doctrine.orm.query_cache'])) return $this->shared['doctrine.orm.query_cache'];

        $instance = new Doctrine\Common\Cache\ArrayCache();
        $this->shared['doctrine.orm.query_cache'] = $instance;
        $instance->setNamespace('doctrine_query_');

        return $instance;
    }

    
    protected function getDoctrine_Orm_ResultCacheService()
    {
        if (isset($this->shared['doctrine.orm.result_cache'])) return $this->shared['doctrine.orm.result_cache'];

        $instance = new Doctrine\Common\Cache\ArrayCache();
        $this->shared['doctrine.orm.result_cache'] = $instance;
        $instance->setNamespace('doctrine_result_');

        return $instance;
    }

    
    protected function getDoctrine_Orm_MetadataDriverService()
    {
        if (isset($this->shared['doctrine.orm.metadata_driver'])) return $this->shared['doctrine.orm.metadata_driver'];

        $instance = new Doctrine\ORM\Mapping\Driver\DriverChain();
        $this->shared['doctrine.orm.metadata_driver'] = $instance;

        return $instance;
    }

    
    protected function getDoctrine_Orm_DefaultEntityManagerService()
    {
        if (isset($this->shared['doctrine.orm.default_entity_manager'])) return $this->shared['doctrine.orm.default_entity_manager'];

        $instance = call_user_func(array('Doctrine\\ORM\\EntityManager', 'create'), $this->getDoctrine_Dbal_DefaultConnectionService(), $this->getDoctrine_Orm_DefaultConfigurationService());
        $this->shared['doctrine.orm.default_entity_manager'] = $instance;

        return $instance;
    }

    
    protected function get_05470b73fbb733331d09ccaeea16de963Service()
    {
        if (isset($this->shared['_05470b73fbb733331d09ccaeea16de96_3'])) return $this->shared['_05470b73fbb733331d09ccaeea16de96_3'];

        $instance = new Swift_Transport_Esmtp_Auth_PlainAuthenticator();
        $this->shared['_05470b73fbb733331d09ccaeea16de96_3'] = $instance;

        return $instance;
    }

    
    protected function get_05470b73fbb733331d09ccaeea16de962Service()
    {
        if (isset($this->shared['_05470b73fbb733331d09ccaeea16de96_2'])) return $this->shared['_05470b73fbb733331d09ccaeea16de96_2'];

        $instance = new Swift_Transport_Esmtp_Auth_LoginAuthenticator();
        $this->shared['_05470b73fbb733331d09ccaeea16de96_2'] = $instance;

        return $instance;
    }

    
    protected function get_05470b73fbb733331d09ccaeea16de961Service()
    {
        if (isset($this->shared['_05470b73fbb733331d09ccaeea16de96_1'])) return $this->shared['_05470b73fbb733331d09ccaeea16de96_1'];

        $instance = new Swift_Transport_Esmtp_Auth_CramMd5Authenticator();
        $this->shared['_05470b73fbb733331d09ccaeea16de96_1'] = $instance;

        return $instance;
    }

    
    protected function getSwiftmailer_MailerService()
    {
        require_once $this->getParameter('swiftmailer.init_file');

        if (isset($this->shared['swiftmailer.mailer'])) return $this->shared['swiftmailer.mailer'];

        $instance = new Swift_Mailer($this->getSwiftmailer_Transport_SmtpService());
        $this->shared['swiftmailer.mailer'] = $instance;

        return $instance;
    }

    
    protected function getSwiftmailer_Transport_SmtpService()
    {
        if (isset($this->shared['swiftmailer.transport.smtp'])) return $this->shared['swiftmailer.transport.smtp'];

        $instance = new Swift_Transport_EsmtpTransport($this->getSwiftmailer_Transport_BufferService(), array(0 => $this->getSwiftmailer_Transport_AuthhandlerService()), $this->getSwiftmailer_Transport_EventdispatcherService());
        $this->shared['swiftmailer.transport.smtp'] = $instance;
        $instance->setHost($this->getParameter('swiftmailer.transport.smtp.host'));
        $instance->setPort($this->getParameter('swiftmailer.transport.smtp.port'));
        $instance->setEncryption($this->getParameter('swiftmailer.transport.smtp.encryption'));
        $instance->setUsername($this->getParameter('swiftmailer.transport.smtp.username'));
        $instance->setPassword($this->getParameter('swiftmailer.transport.smtp.password'));
        $instance->setAuthMode($this->getParameter('swiftmailer.transport.smtp.auth_mode'));

        return $instance;
    }

    
    protected function getSwiftmailer_Transport_SendmailService()
    {
        if (isset($this->shared['swiftmailer.transport.sendmail'])) return $this->shared['swiftmailer.transport.sendmail'];

        $instance = new Swift_Transport_SendmailTransport($this->getSwiftmailer_Transport_BufferService(), $this->getSwiftmailer_Transport_EventdispatcherService());
        $this->shared['swiftmailer.transport.sendmail'] = $instance;

        return $instance;
    }

    
    protected function getSwiftmailer_Transport_MailService()
    {
        if (isset($this->shared['swiftmailer.transport.mail'])) return $this->shared['swiftmailer.transport.mail'];

        $instance = new Swift_Transport_MailTransport($this->getSwiftmailer_Transport_MailinvokerService(), $this->getSwiftmailer_Transport_EventdispatcherService());
        $this->shared['swiftmailer.transport.mail'] = $instance;

        return $instance;
    }

    
    protected function getSwiftmailer_Transport_FailoverService()
    {
        if (isset($this->shared['swiftmailer.transport.failover'])) return $this->shared['swiftmailer.transport.failover'];

        $instance = new Swift_Transport_FailoverTransport();
        $this->shared['swiftmailer.transport.failover'] = $instance;

        return $instance;
    }

    
    protected function getSwiftmailer_Transport_MailinvokerService()
    {
        if (isset($this->shared['swiftmailer.transport.mailinvoker'])) return $this->shared['swiftmailer.transport.mailinvoker'];

        $instance = new Swift_Transport_SimpleMailInvoker();
        $this->shared['swiftmailer.transport.mailinvoker'] = $instance;

        return $instance;
    }

    
    protected function getSwiftmailer_Transport_BufferService()
    {
        if (isset($this->shared['swiftmailer.transport.buffer'])) return $this->shared['swiftmailer.transport.buffer'];

        $instance = new Swift_Transport_StreamBuffer($this->getSwiftmailer_Transport_ReplacementfactoryService());
        $this->shared['swiftmailer.transport.buffer'] = $instance;

        return $instance;
    }

    
    protected function getSwiftmailer_Transport_AuthhandlerService()
    {
        if (isset($this->shared['swiftmailer.transport.authhandler'])) return $this->shared['swiftmailer.transport.authhandler'];

        $instance = new Swift_Transport_Esmtp_AuthHandler(array(0 => $this->get_05470b73fbb733331d09ccaeea16de961Service(), 1 => $this->get_05470b73fbb733331d09ccaeea16de962Service(), 2 => $this->get_05470b73fbb733331d09ccaeea16de963Service()));
        $this->shared['swiftmailer.transport.authhandler'] = $instance;

        return $instance;
    }

    
    protected function getSwiftmailer_Transport_EventdispatcherService()
    {
        if (isset($this->shared['swiftmailer.transport.eventdispatcher'])) return $this->shared['swiftmailer.transport.eventdispatcher'];

        $instance = new Swift_Events_SimpleEventDispatcher();
        $this->shared['swiftmailer.transport.eventdispatcher'] = $instance;

        return $instance;
    }

    
    protected function getSwiftmailer_Transport_ReplacementfactoryService()
    {
        if (isset($this->shared['swiftmailer.transport.replacementfactory'])) return $this->shared['swiftmailer.transport.replacementfactory'];

        $instance = new Swift_StreamFilters_StringReplacementFilterFactory();
        $this->shared['swiftmailer.transport.replacementfactory'] = $instance;

        return $instance;
    }

    
    protected function getSwiftmailer_Transport_SpoolService()
    {
        if (isset($this->shared['swiftmailer.transport.spool'])) return $this->shared['swiftmailer.transport.spool'];

        $instance = new Swift_Transport_SpoolTransport($this->getSwiftmailer_Transport_EventdispatcherService(), $this->getSwiftmailer_Spool_FileService());
        $this->shared['swiftmailer.transport.spool'] = $instance;

        return $instance;
    }

    
    protected function getSwiftmailer_Transport_NullService()
    {
        if (isset($this->shared['swiftmailer.transport.null'])) return $this->shared['swiftmailer.transport.null'];

        $instance = new Swift_Transport_NullTransport($this->getSwiftmailer_Transport_EventdispatcherService());
        $this->shared['swiftmailer.transport.null'] = $instance;

        return $instance;
    }

    
    protected function getSwiftmailer_Spool_FileService()
    {
        if (isset($this->shared['swiftmailer.spool.file'])) return $this->shared['swiftmailer.spool.file'];

        $instance = new Swift_FileSpool($this->getParameter('swiftmailer.spool.file.path'));
        $this->shared['swiftmailer.spool.file'] = $instance;

        return $instance;
    }

    
    protected function getSwiftmailer_Plugin_RedirectingService()
    {
        if (isset($this->shared['swiftmailer.plugin.redirecting'])) return $this->shared['swiftmailer.plugin.redirecting'];

        $instance = new Swift_Plugins_RedirectingPlugin($this->getParameter('swiftmailer.single_address'));
        $this->shared['swiftmailer.plugin.redirecting'] = $instance;

        return $instance;
    }

    
    protected function getSwiftmailer_Plugin_BlackholeService()
    {
        if (isset($this->shared['swiftmailer.plugin.blackhole'])) return $this->shared['swiftmailer.plugin.blackhole'];

        $instance = new Swift_Plugins_BlackholePlugin();
        $this->shared['swiftmailer.plugin.blackhole'] = $instance;

        return $instance;
    }

    
    protected function getTemplating_LoaderService()
    {
        return $this->getTemplating_Loader_FilesystemService();
    }

    
    protected function getTemplatingService()
    {
        return $this->getTemplating_EngineService();
    }

    
    protected function getDatabaseConnectionService()
    {
        return $this->getDoctrine_Dbal_DefaultConnectionService();
    }

    
    protected function getDoctrine_Orm_EntityManagerService()
    {
        return $this->getDoctrine_Orm_DefaultEntityManagerService();
    }

    
    protected function getDoctrine_Orm_CacheService()
    {
        return $this->getDoctrine_Orm_Cache_ArrayService();
    }

    
    protected function getSwiftmailer_TransportService()
    {
        return $this->getSwiftmailer_Transport_SmtpService();
    }

    
    protected function getSwiftmailer_SpoolService()
    {
        return $this->getSwiftmailer_Spool_FileService();
    }

    
    protected function getMailerService()
    {
        return $this->getSwiftmailer_MailerService();
    }

    
    public function findAnnotatedServiceIds($name)
    {
        static $annotations = array (
  'kernel.listener' => 
  array (
    'controller_loader' => 
    array (
      0 => 
      array (
      ),
    ),
    'request_parser' => 
    array (
      0 => 
      array (
      ),
    ),
    'esi_filter' => 
    array (
      0 => 
      array (
      ),
    ),
    'response_filter' => 
    array (
      0 => 
      array (
      ),
    ),
    'exception_handler' => 
    array (
      0 => 
      array (
      ),
    ),
  ),
  'templating.helper' => 
  array (
    'templating.helper.javascripts' => 
    array (
      0 => 
      array (
        'alias' => 'javascripts',
      ),
    ),
    'templating.helper.stylesheets' => 
    array (
      0 => 
      array (
        'alias' => 'stylesheets',
      ),
    ),
    'templating.helper.slots' => 
    array (
      0 => 
      array (
        'alias' => 'slots',
      ),
    ),
    'templating.helper.assets' => 
    array (
      0 => 
      array (
        'alias' => 'assets',
      ),
    ),
    'templating.helper.request' => 
    array (
      0 => 
      array (
        'alias' => 'request',
      ),
    ),
    'templating.helper.user' => 
    array (
      0 => 
      array (
        'alias' => 'user',
      ),
    ),
    'templating.helper.router' => 
    array (
      0 => 
      array (
        'alias' => 'router',
      ),
    ),
    'templating.helper.actions' => 
    array (
      0 => 
      array (
        'alias' => 'actions',
      ),
    ),
  ),
  'data_collector' => 
  array (
    'doctrine.data_collector' => 
    array (
      0 => 
      array (
      ),
    ),
  ),
);

        return isset($annotations[$name]) ? $annotations[$name] : array();
    }

    
    protected function getDefaultParameters()
    {
        return array(
            'kernel.root_dir' => '/Applications/MAMP/htdocs/sandbox/bookezp',
            'kernel.environment' => 'prod',
            'kernel.debug' => false,
            'kernel.name' => 'bookezp',
            'kernel.cache_dir' => '/Applications/MAMP/htdocs/sandbox/bookezp/cache/prod',
            'kernel.logs_dir' => '/Applications/MAMP/htdocs/sandbox/bookezp/logs',
            'kernel.bundle_dirs' => array(
                'Application' => '/Applications/MAMP/htdocs/sandbox/bookezp/../src/Application',
                'Bundle' => '/Applications/MAMP/htdocs/sandbox/bookezp/../src/Bundle',
                'Symfony\\Framework' => '/Applications/MAMP/htdocs/sandbox/bookezp/../src/vendor/symfony/src/Symfony/Framework',
            ),
            'kernel.bundles' => array(
                0 => 'Symfony\\Foundation\\Bundle\\KernelBundle',
                1 => 'Symfony\\Framework\\FoundationBundle\\FoundationBundle',
                2 => 'Symfony\\Framework\\ZendBundle\\ZendBundle',
                3 => 'Symfony\\Framework\\DoctrineBundle\\DoctrineBundle',
                4 => 'Symfony\\Framework\\SwiftmailerBundle\\SwiftmailerBundle',
                5 => 'Application\\BookezpBundle\\BookezpBundle',
            ),
            'kernel.charset' => 'UTF-8',
            'templating.loader.filesystem.path' => array(
                0 => '/Applications/MAMP/htdocs/sandbox/bookezp/views/%bundle%/%controller%/%name%%format%.%renderer%',
                1 => '/Applications/MAMP/htdocs/sandbox/bookezp/../src/Application/%bundle%/Resources/views/%controller%/%name%%format%.%renderer%',
                2 => '/Applications/MAMP/htdocs/sandbox/bookezp/../src/Bundle/%bundle%/Resources/views/%controller%/%name%%format%.%renderer%',
                3 => '/Applications/MAMP/htdocs/sandbox/bookezp/../src/vendor/symfony/src/Symfony/Framework/%bundle%/Resources/views/%controller%/%name%%format%.%renderer%',
            ),
            'doctrine.orm.metadata_driver.mapping_dirs' => array(

            ),
            'doctrine.orm.entity_dirs' => array(

            ),
            'event_dispatcher.class' => 'Symfony\\Foundation\\EventDispatcher',
            'http_kernel.class' => 'Symfony\\Components\\HttpKernel\\HttpKernel',
            'request.class' => 'Symfony\\Components\\HttpKernel\\Request',
            'response.class' => 'Symfony\\Components\\HttpKernel\\Response',
            'error_handler.class' => 'Symfony\\Foundation\\Debug\\ErrorHandler',
            'error_handler.level' => NULL,
            'kernel.include_core_classes' => false,
            'kernel.compiled_classes' => array(
                0 => 'Symfony\\Components\\Routing\\Router',
                1 => 'Symfony\\Components\\Routing\\RouterInterface',
                2 => 'Symfony\\Components\\EventDispatcher\\Event',
                3 => 'Symfony\\Components\\Routing\\Matcher\\UrlMatcherInterface',
                4 => 'Symfony\\Components\\Routing\\Matcher\\UrlMatcher',
                5 => 'Symfony\\Components\\HttpKernel\\HttpKernel',
                6 => 'Symfony\\Components\\HttpKernel\\Request',
                7 => 'Symfony\\Components\\HttpKernel\\Response',
                8 => 'Symfony\\Components\\HttpKernel\\Listener\\ResponseFilter',
                9 => 'Symfony\\Components\\Templating\\Loader\\LoaderInterface',
                10 => 'Symfony\\Components\\Templating\\Loader\\Loader',
                11 => 'Symfony\\Components\\Templating\\Loader\\FilesystemLoader',
                12 => 'Symfony\\Components\\Templating\\Engine',
                13 => 'Symfony\\Components\\Templating\\Renderer\\RendererInterface',
                14 => 'Symfony\\Components\\Templating\\Renderer\\Renderer',
                15 => 'Symfony\\Components\\Templating\\Renderer\\PhpRenderer',
                16 => 'Symfony\\Components\\Templating\\Storage\\Storage',
                17 => 'Symfony\\Components\\Templating\\Storage\\FileStorage',
                18 => 'Symfony\\Framework\\FoundationBundle\\Controller',
                19 => 'Symfony\\Framework\\FoundationBundle\\Listener\\RequestParser',
                20 => 'Symfony\\Framework\\FoundationBundle\\Listener\\ControllerLoader',
                21 => 'Symfony\\Framework\\FoundationBundle\\Templating\\Engine',
            ),
            'request_parser.class' => 'Symfony\\Framework\\FoundationBundle\\Listener\\RequestParser',
            'controller_manager.class' => 'Symfony\\Framework\\FoundationBundle\\Controller\\ControllerManager',
            'controller_loader.class' => 'Symfony\\Framework\\FoundationBundle\\Listener\\ControllerLoader',
            'router.class' => 'Symfony\\Components\\Routing\\Router',
            'response_filter.class' => 'Symfony\\Components\\HttpKernel\\Listener\\ResponseFilter',
            'exception_handler.class' => 'Symfony\\Framework\\FoundationBundle\\Listener\\ExceptionHandler',
            'exception_handler.controller' => 'FoundationBundle:Exception:exception',
            'esi.class' => 'Symfony\\Components\\HttpKernel\\Cache\\Esi',
            'esi_filter.class' => 'Symfony\\Components\\HttpKernel\\Listener\\EsiFilter',
            'templating.engine.class' => 'Symfony\\Framework\\FoundationBundle\\Templating\\Engine',
            'templating.loader.filesystem.class' => 'Symfony\\Components\\Templating\\Loader\\FilesystemLoader',
            'templating.loader.cache.class' => 'Symfony\\Components\\Templating\\Loader\\CacheLoader',
            'templating.loader.chain.class' => 'Symfony\\Components\\Templating\\Loader\\ChainLoader',
            'templating.helper.javascripts.class' => 'Symfony\\Components\\Templating\\Helper\\JavascriptsHelper',
            'templating.helper.stylesheets.class' => 'Symfony\\Components\\Templating\\Helper\\StylesheetsHelper',
            'templating.helper.slots.class' => 'Symfony\\Components\\Templating\\Helper\\SlotsHelper',
            'templating.helper.assets.class' => 'Symfony\\Components\\Templating\\Helper\\AssetsHelper',
            'templating.helper.actions.class' => 'Symfony\\Framework\\FoundationBundle\\Helper\\ActionsHelper',
            'templating.helper.router.class' => 'Symfony\\Framework\\FoundationBundle\\Helper\\RouterHelper',
            'templating.helper.request.class' => 'Symfony\\Framework\\FoundationBundle\\Helper\\RequestHelper',
            'templating.helper.user.class' => 'Symfony\\Framework\\FoundationBundle\\Helper\\UserHelper',
            'templating.output_escaper' => 'htmlspecialchars',
            'templating.assets.version' => 'SomeVersionScheme',
            'doctrine.data_collector.class' => 'Symfony\\Framework\\DoctrineBundle\\DataCollector\\DoctrineDataCollector',
            'doctrine.dbal.default_connection' => 'default',
            'doctrine.orm.cache_driver' => 'array',
            'doctrine.orm.cache.memcache.host' => 'localhost',
            'doctrine.orm.cache.memcache.port' => 11211,
            'doctrine.orm.default_entity_manager' => 'default',
            'swiftmailer.class' => 'Swift_Mailer',
            'swiftmailer.transport.name' => 'smtp',
            'swiftmailer.transport.smtp.class' => 'Swift_Transport_EsmtpTransport',
            'swiftmailer.transport.sendmail.class' => 'Swift_Transport_SendmailTransport',
            'swiftmailer.transport.mail.class' => 'Swift_Transport_MailTransport',
            'swiftmailer.transport.smtp.host' => 'smtp.gmail.com',
            'swiftmailer.transport.smtp.port' => 465,
            'swiftmailer.transport.smtp.encryption' => 'ssl',
            'swiftmailer.transport.smtp.username' => 'xxxxxxxx',
            'swiftmailer.transport.smtp.password' => 'xxxxxxxx',
            'swiftmailer.transport.smtp.auth_mode' => 'login',
            'swiftmailer.transport.failover.class' => 'Swift_Transport_FailoverTransport',
            'swiftmailer.spool.file.class' => 'Swift_FileSpool',
            'swiftmailer.init_file' => '/Applications/MAMP/htdocs/sandbox/src/vendor/swiftmailer/lib/swift_init.php',
            'swiftmailer.plugin.redirecting.class' => 'Swift_Plugins_RedirectingPlugin',
            'swiftmailer.plugin.blackhole.class' => 'Swift_Plugins_BlackholePlugin',
            'swiftmailer.base_dir' => '/Applications/MAMP/htdocs/sandbox/src/vendor/swiftmailer/lib',
        );
    }
}
