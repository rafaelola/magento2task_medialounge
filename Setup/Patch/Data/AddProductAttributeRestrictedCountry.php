<?php
    
    namespace ML\DeveloperTest\Setup\Patch\Data;

    use Magento\Eav\Setup\EavSetup;
    use Magento\Eav\Setup\EavSetupFactory;
    use Magento\Framework\Setup\ModuleDataSetupInterface;
    use Magento\Framework\Setup\Patch\DataPatchInterface;
    use Magento\Framework\Setup\Patch\PatchRevertableInterface;
    class AddProductAttributeRestrictedCountry implements DataPatchInterface, PatchRevertableInterface
    {
    
        /**
         * @var ModuleDataSetupInterface
         */
        private $moduleDataSetup;
        /**
         * @var EavSetupFactory
         */
        private $eavSetupFactory;
    
        /**
         * Constructor
         *
         * @param ModuleDataSetupInterface $moduleDataSetup
         * @param EavSetupFactory $eavSetupFactory
         */
        public function __construct(
            ModuleDataSetupInterface $moduleDataSetup,
            EavSetupFactory $eavSetupFactory
        ) {
            $this->moduleDataSetup = $moduleDataSetup;
            $this->eavSetupFactory = $eavSetupFactory;
        }
    
        /**
         * {@inheritdoc}
         */
        public function apply()
        {
            $this->moduleDataSetup->getConnection()->startSetup();
            /** @var EavSetup $eavSetup */
            $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'restricted_country',
                [
                    'type' => 'varchar',
                    'label' => 'Restrict Countries',
                    'input' => 'multiselect',
                    'source' => \ML\DeveloperTest\Model\Product\Attribute\Source\CountryOptions::class,
                    'required' => false,
                    'backend' => '',
                    'sort_order' => '37',
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                    'default' => null,
                    'visible' => true,
                    'user_defined' => true,
                    'searchable' => false,
                    'filterable' => false,
                    'comparable' => false,
                    'visible_on_front' => true,
                    'unique' => false,
                    'apply_to' => '',
                    'group' => 'Product Details',
                    'used_in_product_listing' => true,
                    'is_used_in_grid' => true,
                    'is_visible_in_grid' => false,
                    'is_filterable_in_grid' => false,
                ]
            );
        
            $this->moduleDataSetup->getConnection()->endSetup();
        }
    
        public function revert()
        {
            $this->moduleDataSetup->getConnection()->startSetup();
            /** @var EavSetup $eavSetup */
            $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
            $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'restricted_country');
        
            $this->moduleDataSetup->getConnection()->endSetup();
        }
    
        /**
         * {@inheritdoc}
         */
        public function getAliases()
        {
            return [];
        }
    
        /**
         * {@inheritdoc}
         */
        public static function getDependencies()
        {
            return [
        
            ];
        }
    }
