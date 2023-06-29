<?php
namespace Sportal\FootballApi\Cache;

use PHPUnit\Framework\TestCase;
use Predis\Client;
use Psr\Log\LoggerInterface;
use Predis\Transaction\MultiExec;

class CacheManagerTest extends TestCase
{

    public function testGetInstance()
    {
        $redisStub = $this->getMockBuilder(Client::class)
            ->setMethods([
            'hget'
        ])
            ->getMock();
        
        $redisStub->expects($this->once())
            ->method('hget')
            ->with($this->equalTo('model'), $this->equalTo(1));
        
        $loggerStub = $this->createMock(LoggerInterface::class);
        $cacheManager = new CacheManager($redisStub, $loggerStub);
        
        $redisStub->method('hget')->willReturn(null);
        
        $this->assertEquals(null, $cacheManager->getInstance('model', [
            'id' => 1
        ]));
    }

    public function testAddToList()
    {
        $persistanceName = "event";
        $parameters = [];
        $existing = [
            1,
            2
        ];
        $add = [
            [
                'id' => '4'
            ],
            [
                'id' => '5'
            ]
        ];
        
        $merged = array_merge($existing,
            array_map(function ($value) {
                return $value['id'];
            }, $add));
        
        $listId = $persistanceName . "-" . md5(serialize($parameters));
        
        $redisStub = $this->getMockBuilder(Client::class)
            ->setMethods([
            'transaction'
        ])
            ->getMock();
        
        $transactionStub = $this->getMockBuilder(MultiExec::class)
            ->disableOriginalConstructor()
            ->disableArgumentCloning()
            ->setMethods([
            'set',
            'get'
        ])
            ->getMock();
        
        $redisStub->expects($this->once())
            ->method('transaction');
        
        $redisStub->method('transaction')->willReturnCallback(
            function (array $options, callable $callback) use ($transactionStub) {
                $callback($transactionStub);
            });
        
        $transactionStub->method('get')->willReturn(serialize($existing));
        
        $transactionStub->expects($this->once())
            ->method('get')
            ->with($listId);
        $transactionStub->expects($this->once())
            ->method('set')
            ->with($listId, serialize($merged));
        
        $loggerStub = $this->createMock(LoggerInterface::class);
        $cacheManager = new CacheManager($redisStub, $loggerStub);
        $cacheManager->addToList($persistanceName, $parameters, $add);
    }
}
