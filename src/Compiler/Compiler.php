<?php

/*
 * This file is part of the hogosha-monitor package
 *
 * Copyright (c) 2016 Guillaume Cavana
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Guillaume Cavana <guillaume.cavana@gmail.com>
 */

namespace Hogosha\Monitor\Compiler;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Process\Process;

/**
 * @author Guillaume Cavana <guillaume.cavana@gmail.com>
 */
class Compiler
{
    protected $version;

    /**
     * Compile.
     */
    public function compile()
    {
        $pharFilePath = dirname(__FILE__).'/../../build/monitor.phar';
        if (file_exists($pharFilePath)) {
            unlink($pharFilePath);
        }

        $this->loadVersion();

        $phar = new \Phar($pharFilePath, 0, 'monitor.phar');
        $phar->setSignatureAlgorithm(\Phar::SHA1);

        $phar->startBuffering();
        $root = __DIR__.'/../..';

        $finder = new Finder();
        $finder->files()
            ->ignoreVCS(true)
            ->name('*.php')
            ->name('LICENSE')
            ->notName('Compiler.php')
            ->exclude('Tests')
            ->exclude('tests')
            ->exclude('docs')
            ->in($root.'/src')
            ->in($root.'/vendor/guzzlehttp')
            ->in($root.'/vendor/rtheunissen')
            ->in($root.'/vendor/eljam')
            ->in($root.'/vendor/hogosha')
            ->in($root.'/vendor/webmozart')
            ->in($root.'/vendor/psr')
            ->in($root.'/vendor/guzzle')
            ->in($root.'/vendor/symfony')
        ;

        foreach ($finder as $file) {
            $this->addFile($phar, $file);
        }

        $this->addFile($phar, new \SplFileInfo($root.'/vendor/autoload.php'));
        $this->addFile($phar, new \SplFileInfo($root.'/vendor/composer/autoload_namespaces.php'));
        $this->addFile($phar, new \SplFileInfo($root.'/vendor/composer/autoload_psr4.php'));
        $this->addFile($phar, new \SplFileInfo($root.'/vendor/composer/autoload_classmap.php'));
        $this->addFile($phar, new \SplFileInfo($root.'/vendor/composer/autoload_files.php'));
        $this->addFile($phar, new \SplFileInfo($root.'/vendor/composer/autoload_real.php'));

        if (file_exists($root.'/vendor/composer/include_paths.php')) {
            $this->addFile($phar, new \SplFileInfo($root.'/vendor/composer/include_paths.php'));
        }
        $this->addFile($phar, new \SplFileInfo($root.'/vendor/composer/ClassLoader.php'));

        $binContent = file_get_contents($root.'/bin/monitor');
        $binContent = preg_replace('{^#!/usr/bin/env php\s*}', '', $binContent);
        $phar->addFromString('bin/monitor', $binContent);

        // Stubs
        $phar->setStub($this->getStub());
        $phar->stopBuffering();
        unset($phar);
    }

    protected function addFile(\Phar $phar, \SplFileInfo $file, $strip = true)
    {
        $path = str_replace(dirname(dirname(__DIR__)).DIRECTORY_SEPARATOR, '', $file->getRealPath());
        $content = file_get_contents($file);
        if ($strip) {
            $content = self::stripWhitespace($content);
        } elseif ('LICENSE' === basename($file)) {
            $content = "\n".$content."\n";
        }

        if ($path === 'src/Monitor.php') {
            $content = str_replace('@package_version@', $this->version, $content);
        }

        $phar->addFromString($path, $content);
    }

    /**
     * @param string $source A PHP string
     *
     * @return string The PHP string with the whitespace removed
     */
    public static function stripWhitespace($source)
    {
        if (!function_exists('token_get_all')) {
            return $source;
        }
        $output = '';
        foreach (token_get_all($source) as $token) {
            if (is_string($token)) {
                $output .= $token;
            } elseif (in_array($token[0], array(T_COMMENT, T_DOC_COMMENT))) {
                $output .= str_repeat("\n", substr_count($token[1], "\n"));
            } elseif (T_WHITESPACE === $token[0]) {
                // reduce wide spaces
                $whitespace = preg_replace('{[ \t]+}', ' ', $token[1]);
                // normalize newlines to \n
                $whitespace = preg_replace('{(?:\r\n|\r|\n)}', "\n", $whitespace);
                // trim leading spaces
                $whitespace = preg_replace('{\n +}', "\n", $whitespace);
                $output .= $whitespace;
            } else {
                $output .= $token[1];
            }
        }

        return $output;
    }

    protected function getStub()
    {
        return <<<'EOF'
#!/usr/bin/env php
<?php
/*
 * This file is part of the Visithor package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 */
Phar::mapPhar('monitor.phar');

require 'phar://monitor.phar/bin/monitor';
__HALT_COMPILER();
EOF;
    }

    /**
     * Load versions.
     */
    private function loadVersion()
    {
        $process = new Process('git log --pretty="%H" -n1 HEAD', __DIR__);
        if ($process->run() !== 0) {
            throw new \RuntimeException('Can\'t run git log. You must ensure to run compile from visithor git repository clone and that git binary is available.');
        }
        $this->version = trim($process->getOutput());

        $process = new Process('git log -n1 --pretty=%ci HEAD', __DIR__);
        if ($process->run() !== 0) {
            throw new \RuntimeException('Can\'t run git log. You must ensure to run compile from visithor git repository clone and that git binary is available.');
        }
        $date = new \DateTime(trim($process->getOutput()));
        $date->setTimezone(new \DateTimeZone('UTC'));
        $this->versionDate = $date->format('Y-m-d H:i:s');

        $process = new Process('git describe --tags HEAD');
        if ($process->run() === 0) {
            $this->version = trim($process->getOutput());
        }
    }
}
