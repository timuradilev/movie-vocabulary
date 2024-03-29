<?php

namespace App\Loader;

use App\Extractor\SubtitleLine;
use Symfony\Component\Filesystem\Filesystem;

final class SrtLoader {

	public function __construct(private readonly string $filename) {
		$filesystem = new Filesystem();
		if (!$filesystem->exists($this->filename)) {
			throw new \InvalidArgumentException($this->filename);
		}
	}

	/**
	 * @return list<string>
	 */
	public function getSubtitleLines(): array {
		$lines = [];

		$file = fopen($this->filename, 'rb');

		$buffer = '';
		$readLine = static function ($file) use (&$buffer): ?string {
			do {
				$matches = [];
				if (preg_match('/(\n)/', $buffer, $matches, PREG_OFFSET_CAPTURE) > 0) {
					$origin = $buffer;
					$buffer = substr($buffer, $matches[1][1] + strlen($matches[1][0]));
					return trim(substr($origin, 0, $matches[1][1]));
				}
				if (feof($file)) {
					if ($buffer !== '') {
						$lastLine = $buffer;
						$buffer = '';
						return $lastLine;
					}
					return null;
				}

				$buffer .= fread($file, 100);
			} while (true);
		};

		$subtitleLine = '';
		do {
			$line = $readLine($file);
			if ($line === null) {
				break;
			}

			if ($line === '') {
				if ($subtitleLine !== '') {
					$lines[] = trim($subtitleLine);
					$subtitleLine = '';
				}
			} elseif (preg_match('/^\d+$/', $line) !== 1 && preg_match('/^\d+:\d+:\d+,\d+ --> \d+:\d+:\d+,\d+/', $line) !== 1) {
				$subtitleLine .= " {$line}";
			}
		} while (true);

		if ($subtitleLine !== '') {
			$lines[] = $subtitleLine;
		}

		fclose($file);
		
		return $lines;
	}

}
