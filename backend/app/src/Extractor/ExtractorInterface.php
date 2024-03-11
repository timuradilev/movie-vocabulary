<?php

namespace App\Extractor;

interface ExtractorInterface {

	/**
	 * @return list<SubtitleLine>
	 */
	public function extract(SubtitleLine $subtitleLine): array;

}
