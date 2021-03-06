<?php
namespace Lelesys\Plugin\Newsletter\Domain\Service;

/*
 * This script belongs to the package "Lelesys.Plugin.Newsletter".         *
 *                                                                         *
 * It is free software; you can redistribute it and/or modify it under     *
 * the terms of the GNU Lesser General Public License, either version 3    *
 * of the License, or (at your option) any later version.                  *
 *                                                                         */

use TYPO3\Flow\Annotations as Flow;
use Lelesys\Plugin\Newsletter\Domain\Model\Category;

/**
 * Newsletter Category Service
 *
 * @Flow\Scope("singleton")
 */
class CategoryService {

	/**
	 * Newsletter Service
	 *
	 * @Flow\Inject
	 * @var \Lelesys\Plugin\Newsletter\Domain\Service\NewsletterService
	 */
	protected $newsletterService;

	/**
	 * Category Repository
	 *
	 * @Flow\Inject
	 * @var \Lelesys\Plugin\Newsletter\Domain\Repository\CategoryRepository
	 */
	protected $categoryRepository;

	/**
	 * Person Service
	 *
	 * @Flow\Inject
	 * @var \Lelesys\Plugin\Newsletter\Domain\Service\PersonService
	 */
	protected $personService;

	/**
	 * Inject PersistenceManagerInterface
	 *
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
	 */
	protected $persistenceManager;

	/**
	 * List of all categories
	 *
	 * @return \Lelesys\Plugin\Newsletter\Domain\Model\Category
	 */
	public function listAll() {
		return $this->categoryRepository->findAll();
	}

	/**
	 * Adds new category
	 *
	 * @param \Lelesys\Plugin\Newsletter\Domain\Model\Category $newCategory Newsletter category
	 * @return void
	 */
	public function create(\Lelesys\Plugin\Newsletter\Domain\Model\Category $newCategory) {
		$this->categoryRepository->add($newCategory);
	}

	/**
	 * Updates category
	 *
	 * @param \Lelesys\Plugin\Newsletter\Domain\Model\Category $category Newsletter category
	 * @return void
	 */
	public function update(\Lelesys\Plugin\Newsletter\Domain\Model\Category $category) {
		$this->categoryRepository->update($category);
	}

	/**
	 * Deletes category
	 *
	 * @param \Lelesys\Plugin\Newsletter\Domain\Model\Category $category Newsletter category
	 * @return void
	 */
	public function delete(\Lelesys\Plugin\Newsletter\Domain\Model\Category $category) {
		$this->newsletterService->deleteRelatedCategories($category);
		$this->personService->deleteRelatedCategories($category);
		$this->categoryRepository->remove($category);
		$this->persistenceManager->persistAll();
	}

}
?>