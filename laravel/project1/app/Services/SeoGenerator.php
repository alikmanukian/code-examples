<?php

namespace App\Services;

use Butschster\Head\Facades\Meta;
use Butschster\Head\Packages\Entities\OpenGraphPackage;
use Butschster\Head\Packages\Entities\TwitterCardPackage;

class SeoGenerator
{
    protected OpenGraphPackage $og;
    protected TwitterCardPackage $tc;

    public function __construct()
    {
        $this->og = new OpenGraphPackage('facebook');
        $this->tc = new TwitterCardPackage('twitter');
    }

    public function setTitle($title): self
    {
        Meta::setTitle($title);
        $this->og->setTitle($title);
        $this->tc->setTitle($title);

        return $this;
    }

    public function setDescription($description): self
    {
        Meta::setDescription($description);
        $this->og->setDescription($description);
        $this->tc->setDescription($description);

        return $this;
    }

    public function setType($type): self
    {
        $this->og->setType($type);
        $this->tc->setType($type);

        return $this;
    }

    public function setCanonical($url): self
    {
        Meta::setCanonical($url);
        $this->og->setUrl($url);

        return $this;
    }

    public function setImage($image): self
    {
        $this->og->addImage($image);
        $this->tc->setImage($image);

        return $this;
    }

    public function setSiteName($name): self
    {
        $this->og->setSiteName($name);

        return $this;
    }

    public function addKeywords($keywords): self
    {
        Meta::setKeywords([...Meta::getKeywords()?->toArray(), ...$keywords]);
        return $this;
    }


    public function register(): void
    {
        Meta::registerPackage($this->og);
        Meta::registerPackage($this->tc);
    }
}
