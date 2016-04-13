<?php

namespace AppBundle\Twig;

use AppBundle\Service\SearchFilter\Helper\Context;

/**
 * Class SearchFilterExtension
 * @package AppBundle\Twig\Extension
 */
class SearchFilterExtension extends \Twig_Extension {

	/**
	 * @var Context|null
	 */
	private $context = null;

	/**
	 * SearchFilterExtension constructor.
	 *
	 * @param Context $context
	 */
	public function __construct(Context $context) {
		$this->context = $context;
	}


	/**
	 * @return array
	 */
	public function getFunctions() {
		return [
			new \Twig_SimpleFunction('searchify', [
				$this,
				'searchify'
			])
		];
	}

	/**
	 * @param string $key
	 *
	 * @return string
	 */
	public function searchify($key = '') {
		return $this->context->getSearchFilterValue($key);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getName() {
		return 'search_filter.extension';
	}
}
