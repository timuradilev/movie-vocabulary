<?php

namespace App\Extractor\Filters;

use App\Extractor\FilterInterface;
use App\Extractor\SubtitleLine;

final class ItalicTagFilter implements FilterInterface {

	public function filter(SubtitleLine $subtitleLine): SubtitleLine {
		$subtitleLine->text = preg_replace('/(<i>)|(<\/i>)/', '', $subtitleLine->text);
		return $subtitleLine;
	}

}