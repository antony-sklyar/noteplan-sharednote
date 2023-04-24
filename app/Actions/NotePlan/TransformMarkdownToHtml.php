<?php

namespace App\Actions\NotePlan;

use Illuminate\Mail\Markdown;
use Illuminate\Support\Str;

class TransformMarkdownToHtml
{
    public function __construct(private ExtractBlocks $extractor)
    {
    }

    public function execute(string $markdown): string
    {
        $blocks = $this->extractor->execute($markdown);
        $html = '';
        foreach ($blocks as $block) {
            $blockContent = $block['content'];
            switch ($block['type']) {
                case 'separator':
                    $html .= $this->htmlSeparator();
                    break;

                case 'table':
                    $html .= $this->htmlTable($blockContent);
                    break;

                case 'codeblock':
                    $html .= $this->htmlCodeBlock($blockContent);
                    break;

                case 'ol':
                    $html .= $this->htmlOrderedList($blockContent);
                    break;

                case 'h':
                    $html .= $this->htmlHeading($blockContent);
                    break;

                case 'task':
                    $html .= $this->htmlTask($blockContent);
                    break;

                case 'checklist':
                    $html .= $this->htmlChecklist($blockContent);
                    break;

                case 'quote':
                    $html .= $this->htmlQuote($blockContent);
                    break;

                case 'ul':
                    $html .= $this->htmlUnorderedList($blockContent);
                    break;

                case 'p':
                    $html .= $this->htmlParagraph($blockContent);
                    break;

                default:
                    $html .= $blockContent;
            }
        }
        return $html;
    }

    private function htmlSeparator(): string
    {
        return '<hr class="my-3" />';
    }

    private function htmlTable(string $markdownTable): string
    {
        return Markdown::parse($markdownTable);
    }

    private function htmlCodeBlock(string $content): string
    {
        return preg_replace(
            '/```[a-z]*\n((`{0,2}[^`]+)+)```/',
            ' <pre class="my-3 py-1 px-2 rounded-sm text-xs font-bold leading-4 bg-sky-900 text-sky-100">$2</pre> ',
            $content
        );
    }

    private function htmlOrderedList(string $content): string
    {
        $html = Markdown::parse($content);
        // TODO: parse each list item to replace custom markdown like highlights, inline comments and etc.
        return $html;
    }

    private function htmlHeading(string $content): string
    {
        $fontSizes = ['text-4xl', 'text-3xl', 'text-2xl', 'text-xl', 'text-lg', 'text-base', 'text-sm'];
        $html = $content;
        for ($level = 0; $level < count($fontSizes); $level++) {
            $fontSize = $fontSizes[$level];
            $html = preg_replace(
                '/^#{' . $level . '} (.*)$/',
                "<h$level class='my-4 font-extrabold $fontSize'>$1</h$level>",
                $html
            );
        }
        return $html;
    }

    private function detectLevel(string $markdownItem): string
    {
        $level = 0;
        while (Str::startsWith($markdownItem, "\t")) {
            $level++;
            $markdownItem = Str::substr($markdownItem, 1);
        }
        return $level;
    }

    private function detectTaskState(string $markdown): string
    {
        if (Str::contains($markdown, ' [x] ')) {
            return 'completed';
        }
        if (Str::contains($markdown, ' [-] ')) {
            return 'canceled';
        }
        if (Str::contains($markdown, ' [>] ')) {
            return 'scheduled';
        }
        return 'incomplete';
    }

    private function htmlTask(string $markdown): string
    {
        $level = $this->detectLevel($markdown);
        $state = $this->detectTaskState($markdown);
        $item = preg_replace('/^(\s*\*( \[.?\])?\s+)/', '', $markdown);
        $html = $this->inlineMarkdownToHtml($item);
        return "<li class='task level$level $state'>$html</li>";
    }

    private function htmlChecklist(string $markdown): string
    {
        $level = $this->detectLevel($markdown);
        $state = $this->detectTaskState($markdown);
        $item = preg_replace('/^(\s*\+( \[.?\])?\s+)/', '', $markdown);
        $html = $this->inlineMarkdownToHtml($item);
        return "<li class='task checklist level$level $state'>$html</li>";
    }

    private function htmlUnorderedList(string $markdown): string
    {
        $level = $this->detectLevel($markdown);
        $item = preg_replace('/^\s*\-\s+/', '', $markdown);
        $html = $this->inlineMarkdownToHtml($item);
        return "<li class='ul level$level'>$html</li>";
    }

    private function htmlQuote(string $markdown): string
    {
        $level = $this->detectLevel($markdown);
        $quote = preg_replace('/\s*>\s+/', '', $markdown);
        $html = $this->inlineMarkdownToHtml($quote);
        return "<blockquote class='quote$level px-4 py-1 my-2 border-l-2 border-l-slate-500'>$html</blockquote>";
    }

    private function htmlParagraph(string $markdown): string
    {
        return '<p class="my-2">' . $this->inlineMarkdownToHtml($markdown) . '</p>';
    }

    private function inlineMarkdownToHtml(string $markdown): string
    {
        // remove synced line marker
        $markdown = preg_replace('/(^|\s)(\^[a-zA-Z0-9]+)(\s|$)/', '$1$3', $markdown);
        // bold
        $markdown = preg_replace('/(^|\s)\*\*(\w(?:[^\*]+\*?)+)\*\*([\.,\?!]|\s|$)/', '$1<b>$2</b>$3', $markdown);
        // italic
        $markdown = preg_replace('/(^|\s)\*(\w[^\*]+)\*([\.,\?!]|\s|$)/', '$1<i>$2</i>$3', $markdown);
        // highlight
        $markdown = preg_replace('/(^|\s)::(\w(?:[^:]+:?)+)::([\.,\?!]|\s|$)/', '$1<mark>$2</mark>$3', $markdown);
        // strikethrough
        $markdown = preg_replace('/(^|\s)~~(\w(?:[^~]+~?)+)~~([\.,\?!]|\s|$)/', '$1<strike>$2</strike>$3', $markdown);
        // underline
        $markdown = preg_replace('/(^|\s)~(\w[^~]+)~([\.,\?!]|\s|$)/', '$1<u>$2</u>$3', $markdown);
        // inline code
        $markdown = preg_replace(
            '/(^|\s)`(\w[^`]+)`([\.,\?!]|\s|$)/',
            '$1<code class="text-blue-500 dark:text-blue-300">$2</code>$3',
            $markdown
        );
        // links
        $markdown = preg_replace(
            '/\[([^\]]+)\]\(([^\)]+)\)/',
            '<a href="$2" target="_blank" class="hover:underline text-blue-500 dark:text-blue-300">$1</a>',
            $markdown
        );
        // inline comment
        $markdown = preg_replace(
            '/(^|\s)%%(([^%]+%?)+)%%([\.,\?!]|\s|$)/',
            '$1<span class="comment comment-inline"><span class="comment-content">$2</span></span>$3',
            $markdown
        );
        // end line comment
        $markdown = preg_replace(
            '/\s\/\/\s(.*)$/',
            ' <span class="comment">// <span class="comment-content">$1</span></span> ',
            $markdown
        );
        // images
        // attachments

        return $markdown;
    }
}
