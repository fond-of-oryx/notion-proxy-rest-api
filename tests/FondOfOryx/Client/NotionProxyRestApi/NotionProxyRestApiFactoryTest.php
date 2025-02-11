<?php

namespace FondOfOryx\Client\NotionProxyRestApi;

use Codeception\Test\Unit;
use FondOfOryx\Client\NotionProxyRestApi\Request\RequestInterface;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Log\LoggerInterface;
use Spryker\Shared\Log\Config\LoggerConfigInterface;

class NotionProxyRestApiFactoryTest extends Unit
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|\FondOfOryx\Client\NotionProxyRestApi\NotionProxyRestApiConfig
     */
    protected MockObject|NotionProxyRestApiConfig $configMock;

    /**
     * @var \Psr\Log\LoggerInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected LoggerInterface|MockObject $loggerMock;

    /**
     * @var \FondOfOryx\Client\NotionProxyRestApi\NotionProxyRestApiFactory
     */
    protected NotionProxyRestApiFactory $factory;

    /**
     * @return void
     */
    protected function _before(): void
    {
        parent::_before();

        $this->configMock = $this->getMockBuilder(NotionProxyRestApiConfig::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->loggerMock = $this->getMockBuilder(LoggerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->factory = new class ($this->loggerMock) extends NotionProxyRestApiFactory {
            /**
             * @var \Psr\Log\LoggerInterface
             */
            protected LoggerInterface $logger;

            /**
             * @param \Psr\Log\LoggerInterface $logger
             */
            public function __construct(LoggerInterface $logger)
            {
                $this->logger = $logger;
            }

            /**
             * @param \Spryker\Shared\Log\Config\LoggerConfigInterface|null $loggerConfig
             *
             * @return \Psr\Log\LoggerInterface
             */
            protected function getLogger(?LoggerConfigInterface $loggerConfig = null): LoggerInterface
            {
                return $this->logger;
            }
        };
        $this->factory->setConfig($this->configMock);
    }

    /**
     * @return void
     */
    public function testCreateClient(): void
    {
        $this->configMock->expects(static::atLeastOnce())
            ->method('getClientConfig')
            ->willReturn([]);

        static::assertInstanceOf(
            RequestInterface::class,
            $this->factory->createRequestClient(),
        );
    }
}
