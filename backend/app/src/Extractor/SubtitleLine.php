<?php

namespace App\Extractor;

use InvalidArgumentException;
use UnexpectedValueException;

final class SubtitleLine implements \Iterator {

	/**
	 * @var list<array>
	 */
	private array $words;
	/**
	 * @var array<int, true>
	 */
	private array $removed = [];
	private int $position = 0;

	public function __construct(string $text) {
		$this->words = preg_split('/\s+/', $text);
		if (\count($this->words) === 0) {
			throw new UnexpectedValueException('empty line given');
		}
	}

	public function removeWord(int $index): void {
		$this->validateIndex($index);
		$this->removed[$index] = true;
	}

	public function getTextToTheLeft(int $index): string {
		$this->validateIndex($index);

		return implode(' ', array_slice($this->words, 0, $index));
	}

	public function getTextToTheRight(int $index): string {
		$this->validateIndex($index);

		return implode(' ', array_slice($this->words, $index + 1));
	}

	public function current(): string {
		return $this->words[$this->position];
	}

	public function next(): void {
		while (\array_key_exists(++$this->position, $this->removed)) {
		}
	}

	public function key(): int {
		return $this->position;
	}

	public function valid(): bool {
		return $this->position < \count($this->words);
	}

	public function rewind(): void {
		$this->position = 0;
		while (\array_key_exists($this->position, $this->removed)) {
			++$this->position;
		}
	}

	private function validateIndex(int $index): void {
		if ($index < 0 || $index >= \count($this->words)) {
			throw new InvalidArgumentException('invalid index given');
		}
	}
}
