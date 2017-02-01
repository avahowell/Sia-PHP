<?php
/**
 * @copyright Copyright (c) 2017, Nebulous
 *
 * @author Johnathan Howell <me@johnathanhowell.com>
 *
 * @license MIT
 * */

namespace Sia;

include('./Requests/library/Requests.php');
\Requests::register_autoloader();

class Client {
	private $apiaddr;

	private function apiGet($route) {
		$url = $this->apiaddr . $route;
		$res = \Requests::get($url, array('User-Agent' => 'Sia-Agent'));

		if ( $res->status_code < 200 || $res->status_code > 299 || !$res->success ) {
			throw new \Exception(json_decode($res->body)->message);
		}

		return json_decode($res->body);
	}

	private function apiPost($route, $params) {
		$url = $this->apiaddr . $route;
		$res = \Requests::post($url, array('User-Agent' => 'Sia-Agent'), json_encode($params));

		if ( $res->status_code < 200 || $res->status_code > 299 || !$res->success ) {
			throw new \Exception(json_decode($res->body)->message);
		}

		return json_decode($res->body);
	}

	public function __construct($apiaddr) {
		if (!is_string($apiaddr)) {
			throw new \InvalidArgumentException('api addr must be a string');
		}
		$this->apiaddr = $apiaddr;
	}	

	// Daemon API
	// version returns a string representation of the current Sia daemon version.
	public function version() {
		return $this->apiGet('/daemon/version')->version;
	}

	// Wallet API
	// wallet returns the wallet object
	public function wallet() {
		return $this->apiGet('/wallet');
	}

	// Renter API
	// renterSettings returns the renter settings
	public function renterSettings() {
		return $this->apiGet('/renter');
	}

	// renterFiles returns the files in the renter
	public function renterFiles() {
		return $this->apiGet('/renter/files')->files;
	}
}

