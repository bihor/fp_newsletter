<?php

namespace Fixpunkt\FpNewsletter\Events;

use TYPO3\CMS\Core\EventDispatcher\EventDispatcher;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ValidateEvent {
	protected $valid = true;
	/** @var string  */
	protected $message = "";

	/**
	 * Dispatches the event.
	 */
	public function __construct() {
		GeneralUtility::makeInstance(EventDispatcher::class) -> dispatch($this);
	}

	/**
	 * @return bool
	 */
	public function isValid() : bool {
		return $this->valid;
	}

	/**
	 * @param bool $valid
	 */
	public function setValid(bool $valid) : void {
		$this->valid = $valid;
	}

	/**
	 * @return string
	 */
	public function getMessage() : string {
		return $this->message;
	}

	/**
	 * @param string $message
	 */
	public function setMessage(string $message) : void {
		$this->message = $message;
	}
}