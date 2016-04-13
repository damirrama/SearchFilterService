<?php

namespace AppBundle\Service\SearchFilter\Helper;

use AppBundle\Service\SearchFilter\SearchFilterControllerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * represents the search context on each request
 *
 * Class Context
 * @package AppBundle\Service\SearchFilter\Helper
 */
class Context {

	/**
	 * @var Request|null
	 */
	private $request = null;

	/**
	 * @var string
	 */
	private $searchFilterSessionKey = '';

	/**
	 * @var string
	 */
	private $searchFilterPostMethodKey = '';

	/**
	 * @var string
	 */
	private $controller = '';

	/**
	 * Context constructor.
	 *
	 * @param RequestStack $requestStack
	 */
	public function __construct(RequestStack $requestStack) {
		$this->request = $requestStack->getCurrentRequest();
		list($this->controller,) = explode('::', $this->request->attributes->get('_controller'));
	}

	/**
	 * @return Request|null
	 */
	public function getRequest() {
		return $this->request;
	}

	/**
	 * @param string $searchFilterSessionKey
	 */
	public function setSearchFilterSessionKey($searchFilterSessionKey) {
		$this->searchFilterSessionKey = $searchFilterSessionKey;
	}

	/**
	 * @return string
	 *
	 * @throws \LogicException
	 */
	public function getSearchFilterSessionKey() {

		if ('' === $this->searchFilterSessionKey) {
			throw new \LogicException('Search filter session key is empty. Did you forget to call Context::setSearchFilterSessionKey() ?');
		}

		return $this->searchFilterSessionKey;
	}

	/**
	 * @param string $searchFilterPostMethodKey
	 */
	public function setSearchFilterPostMethodKey($searchFilterPostMethodKey) {
		$this->searchFilterPostMethodKey = $searchFilterPostMethodKey;
	}

	/**
	 * @return string
	 *
	 * @throws \LogicException
	 */
	public function getSearchFilterPostMethodKey() {

		if ('' === $this->searchFilterPostMethodKey) {
			throw new \LogicException('Search filter post method key is empty. Did you forget to call Context::setSearchFilterPostMethodKey() ?');
		}

		return $this->searchFilterPostMethodKey;
	}

	/**
	 * @param string $searchFilterValue
	 */
	public function setSearchFilterValue($searchFilterValue) {
		$this->request->getSession()->set($this->getSearchFilterSessionKey(), $searchFilterValue);
	}

	/**
	 * saving the $_POST search value to the $_SESSION if any available
	 */
	public function setSearchFilterValueFromRequest() {

		if ($this->request->request->has($this->getSearchFilterPostMethodKey())) {

			$searchFilterValue = $this->request->get($this->getSearchFilterPostMethodKey());
			$this->request->getSession()->set($this->getSearchFilterSessionKey(), $searchFilterValue);
		}
	}

	/**
	 * if no $key passed, the current context key will be used
	 *
	 * @param string $key
	 *
	 * @return string
	 */
	public function getSearchFilterValue($key = '') {
		return $this->request->getSession()->get($key ?: $this->getSearchFilterSessionKey(), false);
	}

	/**
	 * @return bool
	 */
	public function hasSearchFilterValue() {
		return $this->hasSearchFilterValueInSession() || $this->hasSearchFilterValueInPost();
	}

	/**
	 * @return bool
	 */
	public function hasSearchFilterValueInSession() {
		$keyExistsInSession = $this->request->getSession()->has($this->getSearchFilterSessionKey());

		// usable value must be a string and have at least one char
		$sessionVal = $this->request->getSession()->get($this->getSearchFilterSessionKey());
		$existingKeyHasUsableValue = is_string($sessionVal) && mb_strlen(trim($sessionVal)) > 0;

		return $keyExistsInSession && $existingKeyHasUsableValue;
	}

	/**
	 * @return bool
	 */
	public function hasSearchFilterValueInPost() {
		return $this->request->request->has($this->getSearchFilterPostMethodKey());
	}

	/**
	 * @return SearchFilterControllerInterface
	 *
	 * @throws \InvalidArgumentException
	 */
	public function getControllerInstance() {
		if (!class_exists($this->controller)) {
			throw new \InvalidArgumentException(sprintf('Controller "%s" does not exists!', $this->controller));
		}

		return new $this->controller();
	}
}