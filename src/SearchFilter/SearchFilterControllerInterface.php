<?php

namespace AppBundle\Service\SearchFilter;

/**
 * must be implemented by any controller which uses the SearchFilterService
 *
 * Interface SearchFilterControllerInterface
 * @package AppBundle\Controller\Interfaces
 */
interface SearchFilterControllerInterface {

	/**
	 * $_SESSION key to store the search value
	 *
	 * @return string
	 */
	public function getSearchFilterSessionKey();

	/**
	 * $_POST key to get the search value
	 *
	 * @return mixed
	 */
	public function getSearchFilterPostMethodKey();
}