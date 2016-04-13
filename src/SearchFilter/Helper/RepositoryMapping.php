<?php

namespace AppBundle\Service\SearchFilter\Helper;

use Doctrine\ORM\EntityRepository;

/**
 * holds information of one repository
 *
 * Class RepositoryMapping
 * @package AppBundle\Service\SearchFilter\Helper
 */
class RepositoryMapping {

	/**
	 * @var EntityRepository|null
	 */
	private $repository = null;

	/**
	 * @var string
	 */
	private $methodName = '';

	/**
	 * @var array
	 */
	private $parameter = [];

	/**
	 * @param EntityRepository $repository
	 */
	public function setRepository(EntityRepository $repository) {
		$this->repository = $repository;
	}

	/**
	 * @return EntityRepository|null
	 */
	public function getRepository() {
		return $this->repository;
	}

	/**
	 * @param string $methodName
	 */
	public function setMethod($methodName) {
		$this->methodName = $methodName;
	}

	/**
	 * @return string
	 */
	public function getMethod() {
		return $this->methodName;
	}

	/**
	 * @param array $parameter
	 */
	public function setParameters(array $parameter) {
		$this->parameter = $parameter;
	}

	/**
	 * @return array
	 */
	public function getParameters() {
		return $this->parameter;
	}
}