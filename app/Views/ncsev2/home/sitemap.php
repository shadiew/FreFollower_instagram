<?php
/**
 * @var \Wow\Template\View $this
 * @var array $model
 */
$this->response->header('Content-Type: application/xml');
echo '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>http://takipmen.com</loc>
        <changefreq>weekly</changefreq>
        <priority>0.5</priority>
    </url>
    <url>
        <loc>http://takipmen.com/Paketler</loc>
        <changefreq>weekly</changefreq>
        <priority>0.5</priority>
    </url>
    <url>
        <loc>http://takipmen.com/Blog</loc>
        <changefreq>weekly</changefreq>
        <priority>0.5</priority>
    </url>';
foreach ($model AS $blog) {
    echo '<url>
                <loc>http://takipmen.com/Blog/' . $blog["seoLink"] . '</loc>
                <changefreq>weekly</changefreq>
                <priority>0.5</priority>
              </url>';
}
echo '</urlset>';