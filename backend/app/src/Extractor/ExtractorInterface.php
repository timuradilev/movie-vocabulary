<?php

namespace App\Extractor;

interface ExtractorInterface {

	/**
	 * @return list<Word>
	 */
	public function extract(SubtitleLine $subtitleLine): array;

}
