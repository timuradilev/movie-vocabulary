<?php

namespace App\Extractor\Filters;

use App\Extractor\FilterInterface;
use App\Extractor\SubtitleLine;

final class ItalicTagFilter implements FilterInterface {

	public function filter(string $subtitleLine): string {
		return preg_replace('/(<i>)|(<\/i>)/', '', $subtitleLine);
	}

}