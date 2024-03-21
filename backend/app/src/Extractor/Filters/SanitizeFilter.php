<?php

namespace App\Extractor\Filters;

use App\Extractor\FilterInterface;

final class SanitizeFilter implements FilterInterface {

	public function filter(string $subtitleLine): ?string {
		$subtitleLine = preg_replace(
			[
				'/\n/',
				'/\.\.\./',
				'/’/',
			],
			[
				' ',
				'',
				'\'',
			],
			$subtitleLine,
		);

		$subtitleLine = preg_replace(
			[
				'/(in\')(\W|$)/iu',
				'/(^|\s)[^a-z]+([a-z])/i',
				'/([a-z])[^a-z]+(\s|$)/i',
			],
			[
				'ing$2',
				'$1$2',
				'$1$2',
			],
			$subtitleLine,
		);
		// todo: cousin's => cousin

		return strtolower($subtitleLine);
	}

}