<?php

namespace Tests\Unit;

use App\Models\Article;
use App\Services\ArticleMarkdown;
use PHPUnit\Framework\TestCase;

/**
 * Database-free tests for the article feature. Feature tests that touch
 * the database are intentionally NOT run against the production database
 * (phpunit.xml does not configure a separate test database); run those
 * only in a local / CI environment with a test database configured.
 */
class ArticleTest extends TestCase
{
    public function test_markdown_escapes_raw_html(): void
    {
        $html = ArticleMarkdown::render("<script>alert(1)</script>\n\nHello");
        $this->assertStringNotContainsString('<script>', $html);
        $this->assertStringContainsString('&lt;script&gt;', $html);
        $this->assertStringContainsString('Hello', $html);
    }

    public function test_markdown_strips_unsafe_links_but_keeps_safe_links(): void
    {
        $html = ArticleMarkdown::render("[evil](javascript:alert(1))\n\n[ok](https://example.com)");
        $this->assertStringNotContainsString('javascript:', $html);
        $this->assertStringContainsString('href="https://example.com"', $html);
    }

    public function test_markdown_renders_headings_and_paragraphs(): void
    {
        $html = ArticleMarkdown::render("# Judul\n\nIsi artikel.");
        $this->assertStringContainsString('<h1>Judul</h1>', $html);
        $this->assertStringContainsString('<p>Isi artikel.</p>', $html);
    }

    public function test_article_uses_slug_as_route_key(): void
    {
        $article = new Article();
        $this->assertSame('slug', $article->getRouteKeyName());
    }

    public function test_article_casts_tags_and_featured_flag(): void
    {
        $article = new Article(['tags' => ['a', 'b'], 'is_featured' => 1]);
        $this->assertSame(['a', 'b'], $article->tags);
        $this->assertTrue($article->is_featured);
    }
}
