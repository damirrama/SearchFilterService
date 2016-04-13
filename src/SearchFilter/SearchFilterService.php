<?php

namespace AppBundle\Service\SearchFilter;

use AppBundle\Service\SearchFilter\Helper\Context;
use AppBundle\Service\SearchFilter\Helper\DataSupplierBridge;
use AppBundle\Service\SearchFilter\Helper\RepositoryMapping;

/**
 * combines the helper classes to a easy usable service
 *
 * Class SearchFilterService
 * @package AppBundle\Service\SearchFilter
 */
class SearchFilterService {

	/**
	 * @var Context
	 */
	private $context;

	/**
	 * @var DataSupplierBridge
	 */
	private $supplier;

	/**
	 * @var RepositoryMapping
	 */
	private $mapping;

	/**
	 * @var bool
	 */
	private $isContextInitialized = false;

	/**
	 * SearchFilterService constructor.
	 *
	 * @param Context            $context
	 * @param DataSupplierBridge $supplier
	 * @param RepositoryMapping  $mapping
	 */
	public function __construct(Context $context, DataSupplierBridge $supplier, RepositoryMapping $mapping) {
		$this->context = $context;
		$this->supplier = $supplier;
		$this->mapping = $mapping;
	}

	/**
	 * initialize context helper object with needed data
	 */
	public function initializeContext() {

		// getting the $_SESSION and the $_POST search filter key

		$controllerObject = $this->context->getControllerInstance();

		try {

			$searchFilterSessionKey = $controllerObject->getSearchFilterSessionKey();
			$searchFilterPostMethodKey = $controllerObject->getSearchFilterPostMethodKey();

		} catch (\Exception $e) {
			$searchFilterControllerInterface = 'AppBundle\Service\SearchFilter\SearchFilterControllerInterface';
			throw new \LogicException(sprintf('You must first implement the "%s" in "%s" to use the "search_filter.service"', $searchFilterControllerInterface, get_class($controllerObject)));
		}

		$this->context->setSearchFilterSessionKey($searchFilterSessionKey);
		$this->context->setSearchFilterPostMethodKey($searchFilterPostMethodKey);
		$this->context->setSearchFilterValueFromRequest();

		$this->isContextInitialized = true;
	}

	/**
	 * @return \Doctrine\ORM\Mapping\Entity[]
	 */
	public function getResult() {

		if (!$this->isContextInitialized) {
			throw new \LogicException('Did you forget to call SearchFilterService::initializeContext() ?');
		}

		return $this->supplier->getResult($this->mapping);
	}

	/**
	 * @return Context
	 */
	public function getContext() {
		return $this->context;
	}

	/**
	 * @return DataSupplierBridge
	 */
	public function getSupplier() {
		return $this->supplier;
	}

	/**
	 * @return RepositoryMapping
	 */
	public function getMapping() {
		return $this->mapping;
	}

}