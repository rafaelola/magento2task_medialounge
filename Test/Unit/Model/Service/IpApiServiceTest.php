<?php
    
    namespace Test\Unit\Model\Service;

    use ML\DeveloperTest\Model\Service\IpApiService;
    use PHPUnit\Framework\TestCase;
    use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;
    use Magento\Framework\HTTP\Client\Curl;
    use ML\DeveloperTest\Helper\RestrictedCountriersConfig;
    use \Psr\Log\LoggerInterface;
    use Magento\Directory\Model\CountryFactory;

    class IpApiServiceTest extends TestCase
    {
        protected $remoteAddressMock;
    
        protected $curlMock;
    
        protected $configHelperMock;
    
        protected $loggerMock;
    
        protected $countryFactoryMock;
        protected $ipApiServiceMock;
        protected function setUp(): void
        {
            parent::setUp();
         
            $this->remoteAddressMock =  $this->getMockBuilder(RemoteAddress::class)
                ->disableOriginalConstructor()
                ->getMock();
            $this->curlMock = $this->createMock(Curl::class);
            $this->configHelperMock = $this->getMockBuilder(RestrictedCountriersConfig::class)
                ->disableOriginalConstructor()
                ->getMock();
            $this->loggerMock = $this->getMockBuilder(LoggerInterface::class)
                ->disableOriginalConstructor()
                ->getMock();
            $this->countryFactoryMock = $this->getMockBuilder(CountryFactory::class)
                ->disableOriginalConstructor()
                ->getMock();
            $this->ipApiServiceMock = new IpApiService(
                $this->remoteAddressMock,
                $this->curlMock,
                $this->configHelperMock,
                $this->loggerMock,
                $this->countryFactoryMock
            );
            
        }
        
        public function testGetIsFeatureEnabled(): void
        {
            $this->configHelperMock->expects($this->once())
                ->method('getFeatureEnabled')
                ->willReturn(true);
            $this->assertTrue($this->ipApiServiceMock->isFeatureEnabled());
        }
        
        public function testGetRestrictedErrorMessage()
        {
            $this->configHelperMock->expects($this->once())
                ->method('getRestrictedMessage')
                ->willReturn('I’m sorry, this product cannot be ordered from');
            $this->assertEquals('I’m sorry, this product cannot be ordered from',$this->ipApiServiceMock->getRestrictedErrorMessage());
        }
    }
