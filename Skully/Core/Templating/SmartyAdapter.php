<?php
/**
 * Created by Trio Design Team (jay@tgitriodesign.com).
 * Date: 12/29/13
 * Time: 5:00 PM
 */

namespace Skully\Core\Templating;
require_once dirname(__FILE__).'../../../'.'Library/Smarty/libs/Smarty.class.php';

use Skully\Exceptions\InvalidTemplateException;

/**
 * Class SmartyAdapter
 * @package Skully\Core\Templating
 */

class SmartyAdapter implements TemplateEngineAdapterInterface {
    /**
     * @var \Smarty
     */
    private $smarty;

    /**
     * @var
     */
    private $app;

    /**
     * @var
     */
    private $theme;

    /**
     * @var int
     */
    private $caching = 1;

    /**
     * @param string $basePath Application's base path ending with DIRECTORY_SEPARATOR
     * @param string $theme
     * @param string $app
     * @param array $additionalPluginsDir
     * @param int $caching
     */
    public function __construct($basePath, $theme = 'default', $app = 'App', $additionalPluginsDir = array(), $caching = 1)
    {
        $skullyBasePath = realpath(dirname(__FILE__).'/../../../').DIRECTORY_SEPARATOR;
        $this->smarty = new \Smarty;
        $this->smarty->caching = $caching;
        $this->caching = $caching;
        $this->smarty->setCompileDir($basePath . implode(DIRECTORY_SEPARATOR, array($app, 'smarty', 'templates_c')).DIRECTORY_SEPARATOR);
        $this->smarty->setConfigDir($basePath . implode(DIRECTORY_SEPARATOR, array($app, 'smarty', 'configs')).DIRECTORY_SEPARATOR);
        $this->smarty->setCacheDir($basePath . implode(DIRECTORY_SEPARATOR, array($app, 'smarty', 'cache')).DIRECTORY_SEPARATOR);
        $this->smarty->setTemplateDir(array(
            'main' => $basePath.implode(DIRECTORY_SEPARATOR, array('public',$theme,$app,'views')),
            'default' => $basePath.implode(DIRECTORY_SEPARATOR, array('public','default',$app,'views')),
            'skully' => $skullyBasePath.implode(DIRECTORY_SEPARATOR, array('public','default','App','views'))
        ));
        $plugins = array_merge($additionalPluginsDir, array(
            realpath(dirname(__FILE__).'/../../').'/App/smarty/plugins/',
            realpath(dirname(__FILE__).'/../../').'/Library/Smarty/libs/plugins/'
        ));
        $this->smarty->setPluginsDir($plugins);
    }

    /**
     * @param null $index
     * @return array|string
     */
    public function getTemplateDir($index = null)
    {
        return $this->smarty->getTemplateDir($index);
    }

    /**
     * @return array
     */
    public function getPluginsDir()
    {
        return $this->smarty->getPluginsDir();
    }

    /**
     * @param null $template
     * @param null $cache_id
     * @param null $compile_id
     * @param null $parent
     * @throw InvalidTemplateException
     * See Smarty documentation
     */
    public function display($template = null, $cache_id = null, $compile_id = null, $parent = null)
    {
        if ($this->caching == false) {
            $this->smarty->clearAllCache();
        }
        try {
            $this->smarty->display($template, $cache_id, $compile_id, $parent);
        }
        catch (\Exception $e) {
            InvalidTemplateException::throwError($e, $template);
        }
    }

    /**
     * @param null $template
     * @param null $cache_id
     * @param null $compile_id
     * @param null $parent
     * @param bool $display
     * @param bool $merge_tpl_vars
     * @param bool $no_output_filter
     * @return string
     * See Smarty documentation
     */
    public function fetch($template = null, $cache_id = null, $compile_id = null, $parent = null, $display = false, $merge_tpl_vars = true, $no_output_filter = false)
    {
        if ($this->caching == false) {
            $this->smarty->clearAllCache();
        }
        return $this->smarty->fetch($template, $cache_id, $compile_id, $parent, $display, $merge_tpl_vars, $no_output_filter);
    }

    /**
     * @param $tpl_var
     * @param null $value
     * @param bool $nocache
     * @return \Smarty_Internal_Data
     * See smarty documentation
     */
    public function assign($tpl_var, $value = null, $nocache = false)
    {
//        $this->smarty->clearAssign($tpl_var);
        return $this->smarty->assign($tpl_var, $value, $nocache);
    }

    /**
     * @param null $template
     * @param null $cache_id
     * @param null $compile_id
     * @param null $parent
     * @return bool
     */
    public function isCached($template = null, $cache_id = null, $compile_id = null, $parent = null)
    {
        try {
            return $this->smarty->isCached($template, $cache_id, $compile_id, $parent);
        }
        catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @param $object_name
     * @param $object_impl
     * @param array $allowed
     * @param bool $smarty_args
     * @param array $block_methods
     * @return \Smarty_Internal_TemplateBase
     */
    public function registerObject($object_name, $object_impl, $allowed = array(), $smarty_args = true, $block_methods = array())
    {
        return $this->smarty->registerObject($object_name, $object_impl, $allowed, $smarty_args, $block_methods);
    }

    /**
     * @param $name
     * @return object
     */
    public function getRegisteredObject($name)
    {
        return $this->smarty->getRegisteredObject($name);
    }

    /**
     * Takes unknown classes and loads plugin files for them
     * class name format: Smarty_PluginType_PluginName
     * plugin filename format: plugintype.pluginname.php
     *
     * @param  string $plugin_name class plugin name to load
     * @param  bool   $check       check if already loaded
     * @return string |boolean filepath of loaded file or false
     */
    public function loadPlugin($plugin_name, $check = true)
    {
        return $this->smarty->loadPlugin($plugin_name, $check);
    }

    /**
     * @param null $value
     * @return string
     */
    public function getTemplateVars($value=null) {
        return $this->smarty->getTemplateVars($value);
    }

    /**
     * @param int $value
     */
    public function setCacheLifetime($value=3600) {
        $this->smarty->cache_lifetime = $value;
    }

}