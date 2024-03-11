<?php

namespace App\Extractor;

interface FilterInterface {

	public function filter(SubtitleLine $subtitleLine): SubtitleLine;

}
