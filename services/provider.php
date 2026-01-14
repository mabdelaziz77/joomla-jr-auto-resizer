<?php
/**
 * JR Auto Resizer Service Provider
 *
 * @package     Joomreem.Plugin
 * @subpackage  Content.JrAutoResizer
 * @link        https://www.joomreem.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @copyright   Copyright (C) 2025 JoomReem. All rights reserved.
 */

defined('_JEXEC') or die;

use Joomla\CMS\Extension\PluginInterface;
use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Event\DispatcherInterface;
use Joomreem\Plugin\Content\JrAutoResizer\Extension\JrAutoResizer;

return new class implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container->set(
            PluginInterface::class,
            function (Container $container) {
                $dispatcher = $container->get(DispatcherInterface::class);
                $plugin     = new JrAutoResizer(
                    $dispatcher,
                    (array) PluginHelper::getPlugin('content', 'jrautoresizer')
                );
                $plugin->setApplication(Factory::getApplication());

                return $plugin;
            }
        );
    }
};
