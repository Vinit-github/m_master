<?php
declare(strict_types=1);

namespace Bentley\PromotionalMessages\Block\Product;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Catalog\Model\Product;

class SpecialPromo extends Template
{
    /*
     *   Module is_enabled
     */
    const IS_ENABLED = "promo_message/general/enabled";

    /*
     *   Module Default message
     */
    const DEFAULT_MESSAGE = "promo_message/general/default_message";

    /*
     *   Module is_enabled
     */
    const ATTRIBUTE_CODE = "promo_message/general/special_promo";
    /**
     * Core store config
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * Core store config
     *
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param array $data
     * @codeCoverageIgnore
     */
    public function __construct(
        Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        $this->_scopeConfig = $scopeConfig;
        parent::__construct($context, $data);
    }

    /**
     * @return Product|null
     */
    public function getProduct()
    {
        return $this->_coreRegistry->registry("current_product");
    }

    /**
     * @return string|null
     */
    public function getSpecialPromo()
    {   
        if(!$this->isEnabled()){
            return false;
        }

        $product = $this->getProduct();
        if ($product){           
            $promoCode = $this->getPromoCode();            
            if($promoCode){
                $promoText = $product->getData($promoCode);
                if(!$promoText){
                    $promoText = $this->getDefaultMessage();
                }       
            } else {
                $promoText = $this->getDefaultMessage();
            }
            
        }        
        return $promoText;
    }

    /**
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return (bool) $this->_scopeConfig->getValue(
            self::IS_ENABLED,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     *
     * @return string|null
     */
    private function getDefaultMessage()
    {
        $defaultMessage = $this->_scopeConfig->getValue(
            self::DEFAULT_MESSAGE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        if ($defaultMessage) {
            return $defaultMessage;
        }
        return null;
    }

    /**
     *
     * @return string|null
     */
    private function getPromoCode()
    {
        $attributeCode = $this->_scopeConfig->getValue(
            self::ATTRIBUTE_CODE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        if ($attributeCode) {
            return $attributeCode;
        }
        return null;
    }
}
