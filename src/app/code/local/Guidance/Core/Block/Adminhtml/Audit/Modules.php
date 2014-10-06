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
                    $controllers = $config->getNode($type . '/routers')
                        ->asArray();
                    foreach ($controllers as $router => $args) {
                        if (!isset($args['args']['modules'])) {
                            continue;
                        }
                        foreach ($args['args']['modules'] as $modules) {
                            if (
                                !isset($modules['@'])
                                || !isset($modules['@']['before'])
                                || strpos($modules[0], 'Mage_') === 0
                            ) {
                                continue;
                            }
                            $rewrites[$modules[0]] = array(
                                'alias' => $modules['@']['before'],
                                'class' => $modules[0]
                            );
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
}
