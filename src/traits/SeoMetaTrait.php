<?php

namespace Gwd\SeoMeta\Traits;

use Gwd\SeoMeta\Models\SeoMetaItem;
use Illuminate\Support\Facades\Storage;

trait SeoMetaTrait
{
    /**
     * Get the seo_metaable relationship.
     *
     * @return morphOne
     */
    public function seo_meta()
    {
        return $this->morphOne(SeoMetaItem::class, 'seo_metaable');
    }

    /**
     * Return the seo_metaable data as array
     *
     * @return array
     */
    public function getSeoMeta()
    {
        $attrs = false;

        if ($this->seo_meta) {
            $attrs = $this->seo_meta->toArray();
        } else {
            $title = $this->getSeoTitleDefault();

            if ($title) {
                $formatter = $this->getSeoTitleFormatter() ?? config('seo.title_formatter');
                $attrs = [
                    'title' => $title,
                    'description' => $this->getSeoDescriptionDefault(),
                    'keywords' => $this->getSeoKeywordsDefault(),
                    'image' => $this->getSeoImageDefault(),
                    'follow_type' => $this->getSeoFollowDefault(),
                    'params' => (object)[
                        'title_format' => $formatter
                    ]
                ];
            }
        }

        if($attrs && isset($attrs['image']) && $attrs['image'] && strpos($attrs['image'], '//') === false){
            $attrs['image_path'] = Storage::url($attrs['image']);
        }

        return $attrs;
    }

    /**
     * Get SEO title formatter
     *
     * @return
     */
    public function getSeoTitleFormatter()
    {
        return config('seo.title_formatter');
    }

    /**
     * Get default SEO title
     *
     * @return string
     */
    public function getSeoTitleDefault()
    {
        return null;
    }

    /**
     * Get default SEO description
     *
     * @return string
     */
    public function getSeoDescriptionDefault()
    {
        return null;
    }

    /**
     * Get default SEO title
     *
     * @return string
     */
    public function getSeoKeywordsDefault()
    {
        return null;
    }

    /**
     * Get default SEO title
     *
     * @return string
     */
    public function getSeoImageDefault()
    {
        return config('seo.default_seo_image');
    }

    /**
     * Get default SEO title
     *
     * @return string
     */
    public function getSeoFollowDefault()
    {
        return config('seo.default_follow_type');
    }
}
