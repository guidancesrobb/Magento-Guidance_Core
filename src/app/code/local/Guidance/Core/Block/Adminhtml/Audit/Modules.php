<?php
/**
 * Guidance Core Module
 *
 * @author    Guidance Magento Team <magento@guidance.com>
 * @category  Guidance
 * @package   Guidance_Core
 * @copyright Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Guidance_Core_Block_Adminhtml_Audit_Modules
    extends Mage_Adminhtml_Block_Widget_Container
{
    /**
     * Rewrite types
     * @var array
     */
    protected $_rewriteTypes = array(
        'blocks',
        'controllers',
        'helpers',
        'models'
    );

    /**
     * Cached module codepool data
     * @var array
     */
    protected $_moduleCodepools;

    /**
     * Cached system rewrite data
     * @var array
     */
    protected $_systemRewrites;

    /**
     * Cached module rewrite data
     * @var array
     */
    protected $_moduleRewrites;

    /**
     * Get module codepool data
     * 
     * @return array
     */
    public function getModuleCodepools($sorted = true)
    {
        if (is_null($this->_moduleCodepools)) {
            $config                 = Mage::getConfig();
            $configNode             = 'modules';
            $modules                = $config->getNode($configNode)->asArray();
            $this->_moduleCodepools = array();
            foreach ($modules as $package => $config) {
                if (isset($config['codePool'])) {
                    $this->_moduleCodepools[$config['codePool']][] = $package;
                }
            }
            if ($sorted) {
                foreach (array_keys($this->_moduleCodepools) as $codePool) {
                    sort($this->_moduleCodepools[$codePool]);
                }
            }
        }
        return $this->_moduleCodepools;
    }

    /**
     * Get system rewrite data
     * 
     * @return array
     */
    public function getSystemRewrites()
    {
        if (is_null($this->_systemRewrites)) {
            $this->_systemRewrites = array();
            foreach ($this->_rewriteTypes as $rewriteType) {
                $this->_systemRewrites[$rewriteType] = $this->_getRewrites($rewriteType);
            }
        }
        return $this->_systemRewrites;
    }

    /**
     * Get rewrite data
     * 
     * @return array
     */
    protected function _getRewrites($classType, $sorted = true)
    {
        $config = Mage::getConfig();
        $rewrites = array();
        switch ($classType) {
            case 'controllers':
                foreach (array('admin', 'frontend') as $type) {
                    $controllers = $config->getNode($type . '/routers')->asArray();
                    foreach ($controllers as $router => $args) {
                        if (!isset($args['args']['modules'])) {
                            continue;
                        }
                        foreach ($args['args']['modules'] as $modules) {
                            if (
                                !isset($modules['@'])
                                || !isset($modules['@']['before'])
                                || strpos($modules[0], 'Mage_') === 0
                                || strpos($modules[0], 'Enterprise_') === 0
                            ) {
                                continue;
                            }
                            $moduleName = implode(
                                '_',
                                array_slice(
                                    explode(
                                        '_',
                                        $modules[0]
                                    ), 0, 2
                                )
                            );
                            $files = $this->_getControllerFiles(
                                Mage::getModuleDir('controllers', $moduleName)
                            );
                            foreach ($files as $file) {
                                preg_match_all(
                                    '/class\s([a-z_]+)\sextends\s([a-z_]+)/i',
                                    file_get_contents($file),
                                    $matches,
                                    PREG_PATTERN_ORDER
                                );
                                if (
                                    !isset($matches[1][0])
                                    || !isset($matches[2][0])
                                ) {
                                    continue;
                                }
                                $class   = trim($matches[1][0]);
                                $extends = trim($matches[2][0]);
                                if (strpos(
                                    $extends,
                                    $modules['@']['before']
                                ) !== false) {
                                    $rewrites[$class] = array(
                                        'alias' => $extends,
                                        'class' => $class
                                    );
                                    include_once $file;
                                }
                            }
                        }
                    }
                }
                break;
            default:
                $configNode = 'global/' . $classType;
                $models = $config->getNode($configNode)->asArray();
                foreach ($models as $package => $config) {
                    if (isset($config['rewrite'])) {
                        foreach ($config['rewrite'] as $alias => $class) {
                            $classAlias = $package . '/' . $alias;
                            $rewrites[$classAlias] = array(
                                'alias' => $classAlias,
                                'class' => $class
                            );
                        }
                    }
                }
        }
        if ($sorted) {
            ksort($rewrites);
        }
        return $rewrites;
    }

    /**
     * Get ordered list of module rewrites
     * 
     * @return array
     */
    public function getModuleRewrites()
    {
        if (is_null($this->_moduleRewrites)) {
            $this->_moduleRewrites = array();
            $systemRewrites = $this->getSystemRewrites();
            foreach ($this->_rewriteTypes as $rewriteType) {
                foreach ($systemRewrites[$rewriteType] as $rewrite) {
                    $module = explode('_', $rewrite['class']);
                    $module = $module[0] . '_' . $module[1];
                    $this->_moduleRewrites[$rewriteType][$module][] = $rewrite;
                }
            }
        }
        return $this->_moduleRewrites;
    }

    /**
     * Get defined methods in a class
     * 
     * @param  string $className
     * @return array
     */
    public function getOverridenMethods($className)
    {
        $overridenMethods = array();
        $class = new ReflectionClass($className);
        foreach ($class->getMethods() as $method) {
            if ($method->getDeclaringClass()->getName() == $className) {
                $overridenMethods[] = $method->getName();
            }
        }
        sort($overridenMethods);
        return $overridenMethods;
    }

    /**
     * Get controller files from a module
     * 
     * @param  string $dir
     * @param  array  $files
     * @return array
     */
    protected function _getControllerFiles($dir, $files = array())
    {
        if (is_dir($dir)) {
            $contents = scandir($dir);
            foreach ($contents as $file) {
                if (in_array($file, array('.', '..'))) {
                    continue;
                }
                $file = $dir . '/' . $file;
                if (substr($file, -14) == 'Controller.php') {
                    $files[] = $file;
                } else if (is_dir($file)) {
                    $files += $this->_getControllerFiles($file, $files);
                }
            }
        }
        return $files;
    }
}
