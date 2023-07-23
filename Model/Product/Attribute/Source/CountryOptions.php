<?php
    
    namespace ML\DeveloperTest\Model\Product\Attribute\Source;

    use Magento\Framework\DB\Ddl\Table;
    use Magento\Directory\Helper\Data as DirectoryHelper;

    class CountryOptions extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
    {
        /**
         * @var DirectoryHelper
         */
        protected $directoryHelper;
        public function __construct(
            DirectoryHelper $directoryHelper
        )
        {
            $this->directoryHelper = $directoryHelper;
        }
    
        
        public function getAllOptions(): ?array
        {
            $countryCollection = $this->directoryHelper->getCountryCollection();
            $this->_options = [];
            foreach ($countryCollection as $country) {
                $this->_options[] = [
                    'label' => $country->getName(),
                    'value' => $country->getId()
                ];
            }
            return $this->_options;
        }
        
        
    
        /**
         * Get a text for option value
         *
         * @param string|integer $value
         * @return string|bool
         */
        public function getOptionText($value)
        {
            foreach ($this->getAllOptions() as $option) {
                if ($option['value'] == $value) {
                    return $option['label'];
                }
            }
            return false;
        }
        /**
         * Retrieve flat column definition
         *
         * @return array
         */
        public function getFlatColumns()
        {
            $attributeCode = $this->getAttribute()->getAttributeCode();
            return [
                $attributeCode => [
                    'unsigned' => false,
                    'default' => null,
                    'extra' => null,
                    'type' => Table::TYPE_TEXT,
                    'nullable' => true,
                    'comment' => 'Custom Attribute Options  ' . $attributeCode . ' column',
                ],
            ];
        }
    }
