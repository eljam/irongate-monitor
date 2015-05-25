<?php

namespace Irongate\Monitor\Site;

use Irongate\Monitor\Configuration\ConfigurationLoader;

class SiteProvider
{
    protected $sites;

    public function __construct(ConfigurationLoader $configurationLoader)
    {
        $config = $configurationLoader->loadConfiguration();
        foreach ($config['sites'] as $name => $attribute) {
            $this->sites[] = new SiteInfo(
                $name,
                $attribute['url'],
                $attribute['timeout']
            );
        }
    }

    public function getSites()
    {
        return $this->sites;
    }
}
