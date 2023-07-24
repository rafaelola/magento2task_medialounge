<?php
    
    namespace ML\DeveloperTest\Helper;

    use Magento\Framework\App\Config\ScopeConfigInterface;
    use Magento\Framework\App\Helper\AbstractHelper;
    use Magento\Store\Model\StoreManagerInterface;
    class RestrictedCountriersConfig extends AbstractHelper
    {
        /**
         * StoreManagerInterface
         */
        protected $storeManager;
    
        public function __construct(
            \Magento\Framework\App\Helper\Context $context,
            StoreManagerInterface $storeManager
        ) {
            $this->storeManager = $storeManager;
            parent::__construct($context);
        }
        
        public function getFeatureEnabled(): bool
        {
            return $this->getConfiguredFlag('ml_developertest/defaults/feature_enabled');
        }
        
        public function getRestrictedMessage(): string
        {
            return $this->getConfiguredValue('ml_developertest/display/restricted_message');
        }
        
        public function getIp2CountryApiUrl(): string
        {
            return $this->getConfiguredValue('ml_developertest/ip_to_country_geoip/api_url');
        }
        
        public function getIp2CountryApiKey(): string
        {
            return $this->getConfiguredValue('ml_developertest/ip_to_country_geoip/api_key');
        }
    
        /**
         * Returns the store level config value
         */
        private function getConfiguredValue($config_path, $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT):string
        {
            return $this->scopeConfig->getValue($config_path, $scope);
        }
    
        public function getConfiguredFlag($config_path, $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT): bool
        {
            return $this->scopeConfig->isSetFlag($config_path, $scope);
        }
        
    
    }
