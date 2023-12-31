<?php
    
    namespace ML\DeveloperTest\Observer\Checkout;
    
    use Magento\Framework\Event\Observer;
    use Magento\Framework\Exception\LocalizedException;
    use Magento\Framework\Exception\NoSuchEntityException;
    use Magento\Framework\Message\ManagerInterface;
    use ML\DeveloperTest\Model\Service\IpApiService;
    use Magento\Checkout\Model\Session;
    use Magento\Framework\View\Page\Config;



    class ProductRestrictedToCountry implements \Magento\Framework\Event\ObserverInterface
    {
        /**
         * @var ManagerInterface
         */
        protected $messageManager;
        
        /**
         * @var IpApiService
         */
    
        /**
         * @var IpApiService
         */
        protected $ipApiService;
    
        /**
         * @var
         */
        protected $checkoutSession;
    
        /**
         * @var Config
         */
        protected $pageConfig;
        
        public function __construct(
            ManagerInterface $messageManager,
            IpApiService $ipApiService,
            Session $checkoutSession,
            Config $pageConfig
        ) {
            $this->messageManager = $messageManager;
            $this->ipApiService = $ipApiService;
            $this->checkoutSession = $checkoutSession;
            $this->pageConfig = $pageConfig;
        }
    
        /**
         * @throws \JsonException
         */
        public function execute(Observer $observer)
        {
          if($this->ipApiService->isFeatureEnabled() === false){
              return;
          }
            /** @var \Magento\Catalog\Model\Product $product */
           $product = $observer->getEvent()->getProduct();
           $restrictedCountries = explode(',',$product->getRestrictedCountry());
            try {
                $quote = $this->checkoutSession->getQuote();
                if ($restrictedCountries !== null) {
                    $customerCountryCode = $this->ipApiService->getCustomerCountryCode();
                    if ($customerCountryCode !== 'no country code found' && !in_array($customerCountryCode,$restrictedCountries, true)) {
                        foreach ($quote->getAllItems() as $item) {
                            if ($item->getProduct()->getId() === $product->getId()) {
                                $quote->setHasError(true);
                                $quote->deleteItem($item);
                                break;
                            }
                        }
                        $countryName = $this->ipApiService->getCountryNameByCode($customerCountryCode) ?? $customerCountryCode;
                        $message = sprintf('%s %s',
                            $this->ipApiService->getRestrictedErrorMessage(), $countryName);
                        $this->messageManager->addErrorMessage($message);
                        
                        // Fix for success message showing up even though the product has been removed from cart
                        $this->pageConfig->addBodyClass( 'remove-success-message');
                    }
        
        
                }
            } catch (NoSuchEntityException |LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            }
            
        }
    }
