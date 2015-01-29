<?php
/**
 * @author      Wizacha DevTeam <dev@wizacha.com>
 * @copyright   Copyright (c) Wizacha
 * @license     Proprietary
 */

namespace Wizacha\Discuss\tests\unit;

use mageekguy\atoum;

class AccessConfiguration extends \Wizacha\Discuss\Client
{
    public function getConfiguration()
    {
        return $this->getEntityManager()->getConfiguration();
    }
}

class Client extends atoum\test
{

    public function test_ctor_FailedWithBadParams()
    {
        $this->exception(
            function() {
                new \Wizacha\Discuss\Client([], true);
            }
        )->isInstanceOf('\Doctrine\DBAL\DBALException');
    }

    public function test_getMessageRepository_succeed()
    {
        $client = new \Wizacha\Discuss\Tests\Client();
        $this
            ->object($client->getMessageRepository())
            ->isInstanceOf('\Wizacha\Discuss\Repository\MessageRepository')
        ;
    }

    public function test_getDiscussionRepository_succeed()
    {
        $client = new \Wizacha\Discuss\Tests\Client();
        $this
            ->object($client->getDiscussionRepository())
            ->isInstanceOf('\Wizacha\Discuss\Repository\DiscussionRepository')
        ;
    }

    public function test_getEventDispatcher_defaultSucceed()
    {
        $client = new \Wizacha\Discuss\Tests\Client();
        $this
            ->object($client->getEventDispatcher())
            ->isInstanceOf('\Symfony\Component\EventDispatcher\EventDispatcher')
        ;
    }

    public function test_getEventDispatcher_injectionSucceed()
    {
        $config                     = \Wizacha\Discuss\Tests\Client::getDefaultConfig();
        $config['event_dispatcher'] = new \mock\Symfony\Component\EventDispatcher\EventDispatcherInterface;
        $client                     = new \Wizacha\Discuss\Tests\Client($config);

        $this
            ->object($client->getEventDispatcher())
            ->isIdenticalTo($config['event_dispatcher']);
    }

    public function test_getEventDispatcher_injectionIgnored()
    {
        $config                     = \Wizacha\Discuss\Tests\Client::getDefaultConfig();
        $config['event_dispatcher'] = 'This is NOT a dispatcher';
        $client                     = new \Wizacha\Discuss\Tests\Client($config);
        $this
            ->object($client->getEventDispatcher())
            ->isInstanceOf('\Symfony\Component\EventDispatcher\EventDispatcher');
    }

    public function test_useFileCacheForProd()
    {
        $config = \Wizacha\Discuss\Tests\Client::getDefaultConfig();
        $client = new AccessConfiguration($config);
        $config = $client->getConfiguration();
        $directory = sys_get_temp_dir();
        $this
            ->object($config->getResultCacheImpl())->isInstanceOf('Doctrine\Common\Cache\ArrayCache')
            ->object($config->getMetadataCacheImpl())->isInstanceOf('Doctrine\Common\Cache\FileSystemCache')
                ->string($config->getMetadataCacheImpl()->getDirectory())->isEqualTo($directory)
            ->object($config->getQueryCacheImpl())->isInstanceOf('Doctrine\Common\Cache\FileSystemCache')
                ->string($config->getQueryCacheImpl()->getDirectory())->isEqualTo($directory)
        ;
    }

    public function test_useFileCacheUseCorrectDirectory()
    {
        $config                     = \Wizacha\Discuss\Tests\Client::getDefaultConfig();
        $config['directory_cache'] = $directory = sys_get_temp_dir().'/test';
        $client                     = new AccessConfiguration($config);
        $config                     = $client->getConfiguration();
        $this
            ->object($config->getResultCacheImpl())->isInstanceOf('Doctrine\Common\Cache\ArrayCache')
            ->object($config->getMetadataCacheImpl())->isInstanceOf('Doctrine\Common\Cache\FileSystemCache')
                ->string($config->getMetadataCacheImpl()->getDirectory())->isEqualTo($directory)
            ->object($config->getQueryCacheImpl())->isInstanceOf('Doctrine\Common\Cache\FileSystemCache')
                ->string($config->getQueryCacheImpl()->getDirectory())->isEqualTo($directory)
        ;
    }
}


