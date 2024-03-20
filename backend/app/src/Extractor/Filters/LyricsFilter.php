<?php

namespace App\Extractor\Filters;

use App\Extractor\FilterInterface;

final class LyricsFilter implements FilterInterface {

	public function filter(string $subtitleLine): string {
		return preg_replace('/(♪[^♪]+♪)/u', '', $subtitleLine);
	}

}