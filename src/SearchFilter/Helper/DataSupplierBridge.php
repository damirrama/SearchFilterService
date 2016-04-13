<?php

namespace AppBundle\Service\SearchFilter\Helper;

use Doctrine\ORM\Mapping\Entity;

/**
 * calling the right Repository::method() based
 * on the search value and returning the result
 *
 * Class DataSupplierBridge
 * @package AppBundle\Service\SearchFilter\Helper
 */
class DataSupplierBridge {

	/**
	 * @param RepositoryMapping $mapping
	 *
	 * @return Entity[]
	 */
	public function getResult(RepositoryMapping $mapping) {

		return call_user_func_array([
			                            $mapping->getRepository(),
			                            $mapping->getMethod()
		                            ], $mapping->getParameters());
	}
}