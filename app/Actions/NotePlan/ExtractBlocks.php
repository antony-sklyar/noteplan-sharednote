<?php

namespace App\Actions\NotePlan;

use Illuminate\Support\Str;

class ExtractBlocks
{
    public function execute(string $markdown): array
    {
        $lines = explode("\n", $markdown);
        $blocks = [];
        for ($i = 0; $i < count($lines); $i++) {
            $line = $lines[$i];
            if (Str::startsWith($line, '---')) {
                $blocks[] = ['type' => 'separator', 'content' => $line];
            }
            elseif (Str::startsWith($line, '|')) {
                $table = $line;
                while (++$i < count($lines) && Str::startsWith($lines[$i], '|')) {
                    $table .= "\n" . $lines[$i];
                }
                $blocks[] = ['type' => 'table', 'content' => $table];
            }
            elseif (Str::startsWith($line, '```')) {
                $codeBlock = '';
                while (++$i < count($lines) && !Str::startsWith($lines[$i], '```')) {
                    $codeBlock .= "\n" . $lines[$i];
                }
                $codeBlock = substr($codeBlock, 1);
                $blocks[] = ['type' => 'codeblock', 'content' => $codeBlock];
            }
            else {
                $trimmed = trim($line);

                if (preg_match('/^\*( \[( |>|x|\-)\])? /', $trimmed)) {
                    $type = 'task';
                }
                elseif (preg_match('/^\+( \[( |>|x|\-)\])? /', $trimmed)) {
                    $type = 'checklist';
                }
                elseif (Str::startsWith($trimmed, '> ')) {
                    $type = 'quote';
                }
                elseif (Str::startsWith($trimmed, '- ')) {
                    $type = 'ul';
                }
                elseif (preg_match('/^\d+(\.|\)) /', $trimmed)) {
                    $type = 'ol';
                }
                elseif (preg_match('/^#+ /', $trimmed)) {
                    $type = 'h';
                }
                else {
                    $type = 'p';
                }

                if ($trimmed) {
                    $blocks[] = ['type' => $type, 'content' => $line];
                }
            }
        }

        return $blocks;
    }
}
