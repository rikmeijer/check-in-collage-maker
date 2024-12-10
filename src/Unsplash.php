<?php

namespace rikmeijer\CheckInCollageMaker;

use Unsplash\Photo,
    Unsplash\Topic;

readonly class Unsplash {

    public function __construct(
            private bool $skip_cache,
            private array $topics,
            private string $cache_directory,
            private int $max_images
    ) {
        is_dir($cache_directory) || mkdir($cache_directory);
    }

    public function available_topics() {
        $available_topics = @Topic::all(per_page: 50, order_by: 'featured')->toArray();
        usort($available_topics, fn(array $topic_a, array $topic_b) => strcasecmp($topic_a['title'], $topic_b['title']));
        foreach ($available_topics as $topic) {
            yield $topic['id'] => [
                'title' => $topic['title'],
                'selected' => in_array($topic['id'], $this->topics) || in_array($topic['slug'], $this->topics)
            ];
        }
    }

    public function __invoke() {
        $yielded = 0;
        $cached_files = glob($this->cache_directory . '/*.php');
        if ($this->skip_cache === false && count($cached_files) > 0) {
            foreach (array_rand($cached_files, min($this->max_images, count($cached_files))) as $cache_file_index) {
                yield include $cached_files[$cache_file_index];
                $yielded++;
            }
            if ($yielded >= $this->max_images) {
                return;
            }
        }

        $response = Photo::get("photos/random", ['query' => ['topics' => implode(',', $this->topics), 'orientation' => 'landscape', 'count' => $this->max_images - $yielded]]);
        $photos = json_decode($response->getBody(), true);
        foreach ($photos as $photo) {
            file_put_contents($this->cache_directory . '/' . $photo['id'] . '.php', '<?php return ' . var_export($photo, true) . ';');
            yield $photo;
        }
    }
}
