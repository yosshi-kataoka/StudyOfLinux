SELECT
  domain_name,
  MAX(page_views) AS max_page_views
FROM page_views
WHERE domain_name IN ('de', 'en')
GROUP BY domain_name
ORDER BY max_page_views DESC;
