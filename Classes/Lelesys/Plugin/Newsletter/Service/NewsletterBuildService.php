<?php
namespace Lelesys\Plugin\Newsletter\Service;

/*
 * This script belongs to the package "Lelesys.Plugin.Newsletter".         *
 *                                                                         *
 * It is free software; you can redistribute it and/or modify it under     *
 * the terms of the GNU Lesser General Public License, either version 3    *
 * of the License, or (at your option) any later version.                  *
 *                                                                         */

use TYPO3\Flow\Annotations as Flow;
use Lelesys\Plugin\Newsletter\Domain\Model\Newsletter;
use TYPO3\TYPO3CR\Domain\Model\NodeTemplate;
use TYPO3\TYPO3CR\Domain\Model\PersistentNodeInterface;
use TYPO3\Fluid\View\StandaloneView;
use TYPO3\TYPO3CR\Domain\Model\Node;
use TYPO3\Flow\Http\Request as Request;

/**
 * Newsletter Build Service
 *
 * @Flow\Scope("singleton")
 */
class NewsletterBuildService {


	/**
	 * Email Service
	 *
	 * @Flow\Inject
	 * @var \Lelesys\Plugin\Newsletter\Service\EmailNotificationService
	 */
	protected $emailNotificationService;

	/**
	 * Newsletter Service
	 *
	 * @Flow\Inject
	 * @var \Lelesys\Plugin\Newsletter\Domain\Service\NewsletterService
	 */
	protected $newsletterService;

	/**
	 * NodeData Repository
	 *
	 * @Flow\Inject
	 * @var \TYPO3\TYPO3CR\Domain\Repository\NodeDataRepository
	 */
	protected $nodeDataRepository;

	/**
	 * Person Repository
	 *
	 * @Flow\Inject
	 * @var \Lelesys\Plugin\Newsletter\Domain\Repository\Recipient\PersonRepository
	 */
	protected $personRepository;

	/**
	 * ResourceManager
	 *
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Resource\ResourceManager
	 */
	protected $resourceManager;

	/**
	 * Settings
	 *
	 * @var array
	 */
	protected $settings;

	/**
	 * Contextfactory
	 *
	 * @Flow\Inject
	 * @var TYPO3\TYPO3CR\Domain\Service\ContextFactoryInterface
	 */
	protected $contextFactory;

	/**
	 * Inject ConfigurationManager
	 *
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Configuration\ConfigurationManager
	 */
	protected $configurationManager;

	/**
	 * Inject RouterInterface
	 *
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Mvc\Routing\RouterInterface
	 */
	protected $router;

	/**
	 * Inject Dispatcher
	 *
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Mvc\Dispatcher
	 */
	protected $dispatcher;

	/**
	 * The security conntext
	 *
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\Context
	 */
	protected $securityContext;

	/**
	 * Inject ObjectManagerInterface
	 *
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Object\ObjectManagerInterface
	 */
	protected $objectManager;

	/**
	 * EmailLog Service
	 *
	 * @Flow\Inject
	 * @var \Lelesys\Plugin\Newsletter\Domain\Service\EmailLogService
	 */
	protected $emailLogService;

	/**
	 * Injects settings
	 *
	 * @param array $settings Inject settings
	 * @return void
	 */
	public function injectSettings(array $settings) {
		$this->settings = $settings;
	}

	/**
	 * Bulid a email message
	 *
	 * @param \Lelesys\Plugin\Newsletter\Domain\Model\Newsletter $newsletter
	 * @param string $format Format html or txt
	 * @return string The message
	 */
	public function buildMailContents(\Lelesys\Plugin\Newsletter\Domain\Model\Newsletter $newsletter, $format = 'txt') {
		$baseUrl = $this->settings['email']['baseUrl'];
		$nodeData = $this->newsletterService->getContentNode($newsletter->getContentNode());
		$contextFactory = $this->createContext();
		$node = new Node($nodeData, $contextFactory);
		$nodeIdentifier = $node->getIdentifier();
		$routesConfiguration = $this->configurationManager->getConfiguration(\TYPO3\Flow\Configuration\ConfigurationManager::CONFIGURATION_TYPE_ROUTES);
		$this->router->setRoutesConfiguration($routesConfiguration);
		$uri = new \TYPO3\Flow\Http\Uri($baseUrl);
		$httpRequest = Request::create($uri);
		$actionRequest = $httpRequest->createActionRequest();
		$actionRequest->setArgument('node', $nodeIdentifier);
		$newActionRequest = $this->router->route($httpRequest);
		$actionRequest->setControllerPackageKey($newActionRequest->getControllerPackageKey());
		$actionRequest->setControllerName($newActionRequest->getControllerName());
		$actionRequest->setControllerActionName($newActionRequest->getControllerActionName());
		$actionRequest->setFormat($format);
		$this->securityContext->setRequest($actionRequest);
		$response = $this->objectManager->get('\TYPO3\Flow\Http\Response');
		$this->dispatcher->dispatch($actionRequest, $response);
		$response->makeStandardsCompliant($httpRequest);
		$output = $response->getContent();
		if ($format === 'txt') {
			$output = strip_tags($output,'<a>');
		}

		return $output;
	}

	/**
	 * Build mails and send newsletter to subscriber
	 *
	 * @param array $emailLogs An array of EmailLog
	 * @return integer
	 */
	public function buildAndSendNewsletter(array $emailLogs) {
		$emailCount = 0;
		/** @var $emailLog \Lelesys\Plugin\Newsletter\Domain\Model\EmailLog */
		foreach ($emailLogs as $emailLog) {
			$newsletter = $emailLog->getNewsletter();
			$fromEmail = $newsletter->getFromEmail();
			$fromName = $newsletter->getFromName();
			$subject = $newsletter->getSubject();
			$priority = $newsletter->getPriority();
			$characterSet = $newsletter->getCharacterSet();
			$replyEmail = $newsletter->getReplyToEmail();
			$replyName = $newsletter->getReplyToName();
			$newsletterAttachments = $newsletter->getAttachments();
			$attachments = array();
			foreach($newsletterAttachments as $newsletterAttachment) {
				$attachments[$this->resourceManager->getPersistentResourcesStorageBaseUri() . $newsletterAttachment->getResource()->getResourcePointer()->getHash()] = $newsletterAttachment->getTitle();
			}

			$values = array('newsletter' => $newsletter);
			if ($emailLog->getRecipientType() === \Lelesys\Plugin\Newsletter\Domain\Model\EmailLog::RECIPIENT_TYPE_PERSON) {
				$recipientId = $emailLog->getRecipient();
				$recipient = $this->personRepository->findByIdentifier($recipientId);
				$code = sha1($recipient->getPrimaryElectronicAddress()->getIdentifier() . $recipient->getUuid());
				$values['recipientId'] = $recipient->getUuid();
				$values['code'] = $code;
				if ($recipient->getAcceptsHtml() === TRUE) {
					$contentType = 'text/html';
					if ($newsletter->getHtmlBody() === NULL) {
						$message = $this->buildMailContents($newsletter, 'html');
						$newsletter->setHtmlBody($message);
						$this->newsletterService->update($newsletter);
					}

					$values['mailContent'] = $newsletter->getHtmlBody();
					$recipientAddress = array($recipient->getPrimaryElectronicAddress()->getIdentifier() => $recipient->getName()->getFirstName());
					$messageBody = $this->emailNotificationService->buildEmailMessage($values, 'html');
				} else {
					$contentType = 'text/plain';
					if ($newsletter->getPlainTextBody() === NULL) {
						$message = $this->buildMailContents($newsletter, 'txt');
						$newsletter->setPlainTextBody($message);
						$this->newsletterService->update($newsletter);
					}

					$values['mailContent'] = $newsletter->getPlainTextBody();
					$messageBody = $this->emailNotificationService->buildEmailMessage($values, 'txt');
					$recipientAddress = array($recipient->getPrimaryElectronicAddress()->getIdentifier() => $recipient->getName()->getFirstName());
				}
			} else {
				$recipient = $emailLog->getRecipient();
				$contentType = 'text/plain';

				if ($newsletter->getPlainTextBody() === NULL) {
					$message = $this->buildMailContents($newsletter, 'txt');
					$newsletter->setPlainTextBody($message);
					$this->newsletterService->update($newsletter);
				}

				$values['recipient'] = $recipient;
				$values['mailContent'] = $newsletter->getPlainTextBody();
				$messageBody = $this->emailNotificationService->buildEmailMessage($values, 'txt');
				$recipientAddress = array(trim($recipient));
			}

			$this->emailNotificationService->sendNewsletterMail($fromEmail, $fromName, $replyEmail, $replyName, $subject, $priority, $characterSet, $attachments, $contentType, $recipientAddress, $messageBody);

			$emailLog->setIsSent(1);
			$emailLog->setTimeSent(new \DateTime());
			$this->emailLogService->update($emailLog);
			$emailCount++;
		}

		return $emailCount;
	}

	/**
	 * Create Context
	 *
	 * @return \TYPO3\TYPO3CR\Domain\Service\ContextInterface
	 */
	public function createContext() {
		return $this->contextFactory->create(array(
					'workspaceName' => 'live',
					'invisibleContentShown' => TRUE,
					'inaccessibleContentShown' => TRUE
		));
	}

}
?>