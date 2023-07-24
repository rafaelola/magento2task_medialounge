<?php
    
    namespace ML\DeveloperTest\Model\Service;

    use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;
    use Magento\Framework\HTTP\Client\Curl;
    use ML\DeveloperTest\Helper\RestrictedCountriersConfig;
    use \Psr\Log\LoggerInterface;
    use Magento\Directory\Model\CountryFactory;
    class IpApiService
    {
        protected $remoteAddress;
    
        protected $curl;
    
        protected $configHelper;
        
        protected $logger;
        
        protected $countryFactory;
        public function __construct(
            RemoteAddress $remoteAddress,
            Curl $curl,
            RestrictedCountriersConfig $configHelper,
            LoggerInterface $logger,
            CountryFactory $countryFactory
        )
        {
            $this->remoteAddress = $remoteAddress;
            $this->curl = $curl;
            $this->configHelper = $configHelper;
            $this->logger = $logger;
            $this->countryFactory = $countryFactory;
        }
    
        /**
         * @throws \JsonException
         */
        public function getCustomerCountryCode(): string
        {
           // $ip = $this->remoteAddress->getRemoteAddress();
            $ip = '161.185.160.93';
            $access_key = $this->configHelper->getIp2CountryApiKey();
            $url = sprintf('%s%s?access_key=%s',$this->configHelper->getIp2CountryApiUrl(),$ip,$access_key);
            $this->curl->get($url);
            $response = json_decode($this->curl->getBody(), true, 512, JSON_THROW_ON_ERROR);
            if(isset($response['error']) && $response['error'] === true){
                $this->logger->critical(sprintf('Info:%s Code:%s, Type:%s',$response['error']['info'],$response['error']['code'],$response['error']['type']));
                return 'API error';
            }
            return $response['country_code'] ?? 'no country code found';
           
        }
        
        public function getRestrictedErrorMessage(): string
        {
            return $this->configHelper->getRestrictedMessage();
        }
        
        public function isFeatureEnabled(): bool
        {
            $isEnabled = (int)$this->configHelper->getFeatureEnabled();
            return $isEnabled === 1;
        }
        
        public function getCountryNameByCode(string $code): string
        {
            return $this->countryFactory->create()->loadByCode($code)->getName();
        }
    }
