<?php

namespace App\Extractor;

final class Extractor {

	/**
	 * @param list<FilterInterface> $filters
	 * @param list<ExtractorInterface> $extractors
	 */
	public function __construct(
		private readonly array $filters,
		private readonly array $extractors,
	) {
	}

	/**
	 * @param list<string> $subtitleLines
	 * @return list<Word>
	 */
	public function getWords(array $subtitleLines): array {
		$filteredLines = [];
		foreach ($subtitleLines as $subtitleLine) {
			foreach ($this->filters as $filter) {
				$subtitleLine = $filter->filter($subtitleLine);
				if ($subtitleLine === null) {
					break;
				}
			}

			if ($subtitleLine !== null) {
				$filteredLines[] = $subtitleLine;
			}
		}

		$subtitleLines = array_map(static fn (string $line): SubtitleLine => new SubtitleLine($line), $filteredLines);

		$words = [];
		foreach ($subtitleLines as $subtitleLine) {
			foreach ($this->extractors as $extractor) {
				$words[] = $extractor->extract($subtitleLine);
				$subtitleLine->rewind();
				if (!$subtitleLine->valid()) {
					break;
				}
			}
		}

		$words = array_merge(...$words);
		$deduplicatedWords = [];
		foreach ($words as $word) {
			$deduplicatedWords[$word->value] = $word;
		}

		return array_values($deduplicatedWords);
	}

}
