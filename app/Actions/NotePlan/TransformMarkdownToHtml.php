<?php

namespace App\Actions\NotePlan;

use Illuminate\Mail\Markdown;

class TransformMarkdownToHtml
{
    public function execute(string $markdown): string
    {
        $markdown = $this->removeSyncedLineIndicators($markdown);
        $markdown = $this->formatScheduleDates($markdown);

        foreach ([0,1,2,3,4,5,6] as $level) {
            $prefix = '';
            for ($i = 0; $i < $level; $i++) $prefix .= '\t';

            $markdown = $this->formatHeadings($markdown, $level);
            $markdown = $this->formatQuotes($markdown, $prefix, $level);
            $markdown = $this->transformMarkdownCompliantTasks($markdown, $prefix, $level);
            $markdown = $this->transformNoteplanAsteriskTasks($markdown, $prefix, $level);
            $markdown = $this->transformNoteplanChecklists($markdown, $prefix, $level);
        }

        $markdown = $this->formatBold($markdown);
        $markdown = $this->formatItalic($markdown);
        $markdown = $this->formatUrlLinks($markdown);
        $markdown = $this->formatInlineCode($markdown);
        $markdown = $this->formatCodeBlocks($markdown);

        $markdown = $this->formatHighlight($markdown);
        $markdown = $this->formatStrikethrough($markdown);
        $markdown = $this->formatUnderline($markdown);
        $markdown = $this->formatInlineComments($markdown);
        $markdown = $this->formatSeparators($markdown);

        return Markdown::parse($markdown);
    }

    private function formatHeadings(string $content, int $level): string
    {
        $fontSize = ['text-4xl', 'text-3xl', 'text-2xl', 'text-xl', 'text-lg', 'text-base', 'text-sm'][$level];
        return preg_replace(
            '/(^|\n)#{' . $level . '} ([^\n]*)\n/',
            "\n<h$level class='my-4 font-extrabold $fontSize'>$2</h$level>\n\n",
            $content
        );
    }

    private function formatBold(string $content): string
    {
        return preg_replace('/\*\*(([^\*]\*?)[^*]+)\*\*/', '<b>$1</b>', $content);
    }

    private function formatItalic(string $content): string
    {
        return preg_replace('/(^|\s)\*([^\s][^\*]*)\*(\s|$)/', ' <i>$2</i> ', $content);
    }

    private function formatInlineCode(string $content): string
    {
        return preg_replace(
            '/(^|\s)`([^\s`][^`]*[^\s`])`(\s|$)/',
            ' <code class="text-blue-500 dark:text-blue-300">$2</code> ',
            $content
        );
    }

    private function formatCodeBlocks(string $content): string
    {
        return preg_replace(
            '/(^|\n)```[a-z]*\n((`{0,2}[^`]+)+)```\n/',
            ' <pre class="my-3 py-1 px-2 rounded-sm text-xs font-bold leading-4 bg-sky-900 text-sky-100">$2</pre> ',
            $content
        );
    }

    private function formatUrlLinks(string $content): string
    {
        return preg_replace(
            '/\[([^\]]+)\]\(([^\)]+)\)/',
            '<a href="$2" target="_blank" class="hover:underline text-blue-500 dark:text-blue-300">$1</a>',
            $content
        );
    }

    private function formatHighlight(string $content): string
    {
        return preg_replace('/::(([^:]:?)[^:]+)::/', '<mark>$1</mark>', $content);
    }

    private function formatStrikethrough(string $content): string
    {
        return preg_replace('/~~(([^~]~?)[^~]+)~~/', '<strike>$1</strike>', $content);
    }

    private function formatUnderline(string $content): string
    {
        return preg_replace('/~(([^~]~?)[^~]+)~/', '<u>$1</u>', $content);
    }

    private function formatInlineComments(string $content): string
    {
        return preg_replace(
            '/%%(([^%]%?)[^%]+)%%/',
            '<span class="inline-comment"><span class="inline-comment-content">$1</span></span>',
            $content
        );
    }

    private function formatSeparators(string $content): string
    {
        return preg_replace(
            '/\n\-{3,}(\n|$)/',
            '<hr class="my-3" />',
            $content
        );
    }

    private function removeSyncedLineIndicators(string $content): string
    {
        return preg_replace('/(\s)(\^[a-zA-Z0-9]+)(\s)/', '$1$3', $content);
    }

    private function formatScheduleDates(string $content): string
    {
        return preg_replace(
            '/\s+>(today|\d{4}\-\d{2}\-\d{2})\n/',
            " <span class='scheduled'>🗓 $1</span>\n" ,
            $content
        );
    }

    private function formatQuotes(string $content, string $prefix, int $level): string
    {
        return preg_replace(
            '/\n' . $prefix . '> ([^\n]+)(\n|$)/',
            "\n<blockquote class='quote$level px-4 py-1 my-2 border-l-2 border-l-slate-500'>$1</blockquote>\n",
            $content
        );
    }

    private function transformMarkdownCompliantTasks(string $content, string $prefix, int $level): string
    {
        $content = preg_replace(
            '/\n' . $prefix . '(\-|\*) \[ \]\s+([^\n]+)/',
            "\n<li class='task level$level'>$2</li>",
            $content
        );
        $content = preg_replace(
            '/\n' . $prefix . '(\-|\*) \[x\]\s+([^\n]+)/',
            "\n<li class='task level$level completed'>$2</li>",
            $content
        );
        $content = preg_replace(
            '/\n' . $prefix . '(\-|\*) \[\-\]\s+([^\n]+)/',
            "\n<li class='task level$level canceled'>$2</li>",
            $content
        );
        $content = preg_replace(
            '/\n' . $prefix . '(\-|\*) \[>\]\s+([^\n]+)/',
            "\n<li class='task level$level scheduled'>$2</li>",
            $content
        );
        return $content;
    }

    private function transformNoteplanAsteriskTasks(string $content, string $prefix, int $level): string
    {
        return preg_replace('/\n' . $prefix . '\* ([^\n]+)/', "\n<li class='task level$level'>$1</li>", $content);
    }

    private function transformNoteplanChecklists(string $content, string $prefix, int $level): string
    {
        $content = preg_replace('/\n' . $prefix . '\+ \[ \]\s+([^\n]+)/', "\n<li class='task level$level checklist'>$1</li>", $content);
        $content = preg_replace('/\n' . $prefix . '\+ \[x\]\s+([^\n]+)/', "\n<li class='task level$level checklist completed'>$1</li>", $content);
        $content = preg_replace('/\n' . $prefix . '\+ \[\-\]\s+([^\n]+)/', "\n<li class='task level$level checklist canceled'>$1</li>", $content);
        $content = preg_replace('/\n' . $prefix . '\+ \[>\]\s+([^\n]+)/', "\n<li class='task level$level checklist scheduled'>$1</li>", $content);
        $content = preg_replace('/\n' . $prefix . '\+ ([^\n]+)/', "\n<li class='task level$level checklist'>$1</li>", $content);
        return $content;
    }
}
