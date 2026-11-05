<?php

namespace App\Services;

use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\MarkdownConverter;

/**
 * Single Markdown renderer used by both the admin preview and the
 * public article pages, so what the admin previews is what visitors see.
 *
 * Safety: raw HTML is escaped (never printed raw) and unsafe links
 * (javascript:, data:, vbscript:, …) have their href stripped by
 * CommonMark, so an author cannot inject script through Markdown.
 */
class ArticleMarkdown
{
    protected static ?MarkdownConverter $converter = null;

    public static function render(?string $markdown): string
    {
        if ($markdown === null || trim($markdown) === '') {
            return '';
        }

        if (static::$converter === null) {
            $environment = new Environment([
                'html_input' => 'escape',
                'allow_unsafe_links' => false,
            ]);
            $environment->addExtension(new CommonMarkCoreExtension());
            $environment->addExtension(new GithubFlavoredMarkdownExtension());
            static::$converter = new MarkdownConverter($environment);
        }

        return (string) static::$converter->convert($markdown);
    }
}
