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

namespace Hogosha\Monitor\Utils;

/**
 * Thanks to sanpii for this class.
 *
 * @link https://github.com/Behatch/contexts/blob/master/src/Xml/Dom.php
 *
 * Class Dom.
 */
class Dom
{
    private $dom;

    /**
     * Constructor.
     *
     * @param string $content
     *
     * @throws \DomException
     */
    public function __construct($content)
    {
        $internalErrors = libxml_use_internal_errors(true);
        $disableEntities = libxml_disable_entity_loader(true);
        libxml_clear_errors();

        $this->dom = new \DomDocument();
        $this->dom->validateOnParse = true;

        if (!$this->dom->loadXML($content, LIBXML_PARSEHUGE)) {
            libxml_disable_entity_loader($disableEntities);

            $this->throwError();
        }
    }

    /**
     * __toString.
     *
     * @return string
     */
    public function __toString()
    {
        $this->dom->formatOutput = true;

        return $this->dom->saveXML();
    }

    /**
     * validate.
     *
     * @throws \DomException
     */
    public function validate()
    {
        $this->dom->validate();
        $this->throwError();
    }

    /**
     * validateXsd.
     *
     * @param string $xsd
     *
     * @throws \DomException
     */
    public function validateXsd($xsd)
    {
        $this->dom->schemaValidateSource($xsd);
        $this->throwError();
    }

    /**
     * validateNg.
     *
     * @param string $ng
     *
     * @throws \RuntimeException
     */
    public function validateNg($ng)
    {
        try {
            $this->dom->relaxNGValidateSource($ng);
            $this->throwError();
        } catch (\DOMException $e) {
            throw new \RuntimeException($e->getMessage());
        }
    }

    /**
     * xpath.
     *
     * @param string $element
     *
     * @return DOMNodeList
     */
    public function xpath($element)
    {
        $xpath = new \DOMXpath($this->dom);
        $this->registerNamespace($xpath);

        $element = $this->fixNamespace($element);
        $elements = $xpath->query($element);

        return ($elements === false) ? new \DOMNodeList() : $elements;
    }

    /**
     * getNamespaces.
     *
     * @return string
     */
    public function getNamespaces()
    {
        $xml = simplexml_import_dom($this->dom);

        return $xml->getNamespaces(true);
    }

    /**
     * registerNamespace.
     *
     * @param \DOMXpath $xpath
     */
    private function registerNamespace(\DOMXpath $xpath)
    {
        $namespaces = $this->getNamespaces();

        foreach ($namespaces as $prefix => $namespace) {
            if (empty($prefix) && $this->hasDefaultNamespace()) {
                $prefix = 'rootns';
            }
            $xpath->registerNamespace($prefix, $namespace);
        }
    }

    /**
     * "fix" queries to the default namespace if any namespaces are defined.
     *
     * @param string $element
     */
    private function fixNamespace($element)
    {
        $namespaces = $this->getNamespaces();

        if (!empty($namespaces) && $this->hasDefaultNamespace()) {
            for ($i = 0; $i < 2; ++$i) {
                $element = preg_replace('/\/(\w+)(\[[^]]+\])?\//', '/rootns:$1$2/', $element);
            }
            $element = preg_replace('/\/(\w+)(\[[^]]+\])?$/', '/rootns:$1$2', $element);
        }

        return $element;
    }

    private function hasDefaultNamespace()
    {
        $defaultNamespaceUri = $this->dom->lookupNamespaceURI(null);
        $defaultNamespacePrefix = $defaultNamespaceUri ? $this->dom->lookupPrefix($defaultNamespaceUri) : null;

        return empty($defaultNamespacePrefix) && !empty($defaultNamespaceUri);
    }

    private function throwError()
    {
        $error = libxml_get_last_error();
        if (!empty($error)) {
            // https://bugs.php.net/bug.php?id=46465
            if ($error->message != 'Validation failed: no DTD found !') {
                throw new \DomException($error->message.' at line '.$error->line);
            }
        }
    }
}
