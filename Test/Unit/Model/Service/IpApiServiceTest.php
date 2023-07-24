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
    
        /**
         * @throws \JsonException
         */
        public function testGetCustomerCountryCode()
        {
            $raw_response = '{"country_code":"US"}';
            $this->remoteAddressMock->expects($this->once())
                ->method('getRemoteAddress')
                ->willReturn('168.10.10.292');
            $this->configHelperMock->expects($this->once())
                ->method('getIp2CountryApiUrl')
                ->willReturn('http://api.ipstack.com/');
            $this->configHelperMock->expects($this->once())
                ->method('getIp2CountryApiKey')
                ->willReturn('123456789');
            $this->curlMock->expects($this->once())
                ->method('get')
                ->willReturn($raw_response);
            $this->curlMock->expects($this->once())
                ->method('getBody')
                ->willReturn($raw_response);
            $result = json_decode($raw_response, true, 512, JSON_THROW_ON_ERROR);
            $this->assertEquals($result['country_code'], $this->ipApiServiceMock->getCustomerCountryCode());
        }
        
        public function testGetCustomerCountryCodeWithApiError()
        {
            $raw_response = '{"error":{"code":105,"type":"https://apilayer.net/api/errors/105","info":"Access Restricted - Your current Subscription Plan does not support HTTPS Encryption."}}';
            $this->remoteAddressMock->expects($this->once())
                ->method('getRemoteAddress')
                ->willReturn('::1');
            $this->configHelperMock->expects($this->once())
                ->method('getIp2CountryApiUrl')
                ->willReturn('https://api.ipstack.com/');
            $this->configHelperMock->expects($this->once())
                ->method('getIp2CountryApiKey')
                ->willReturn('123456789');
            $this->curlMock->expects($this->once())
                ->method('get')
                ->willReturn($raw_response);
            $this->curlMock->expects($this->once())
                ->method('getBody')
                ->willReturn($raw_response);
            $result = json_decode($raw_response, true, 512, JSON_THROW_ON_ERROR);
            $this->loggerMock->expects($this->once())
                ->method('critical')
                ->with(sprintf('Info:%s Code:%s, Type:%s',$result['error']['info'],$result['error']['code'],$result['error']['type']));
            $this->assertEquals('API error', $this->ipApiServiceMock->getCustomerCountryCode());
        }
    }
