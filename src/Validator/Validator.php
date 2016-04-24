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

namespace Hogosha\Monitor\Validator;

use Hogosha\Monitor\Exception\ValidatorException;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class Validator.
 */
class Validator
{
    /**
     * $type.
     *
     * @var string
     */
    protected $type;

    /**
     * $match.
     *
     * @var string
     */
    protected $match;

    /**
     * $error.
     *
     * @var string
     */
    protected $error;

    /**
     * Constructor.
     *
     * @param array|null $options
     */
    public function __construct(array $options = array())
    {
        $resolver = new OptionsResolver();
        $resolver->setRequired(
            [
                'type',
                'match',
            ]
        );

        $resolver->setDefault('type', function (Options $options, $value) {
            if (null === $value) {
                return;
            }
        });

        $resolver->setDefault('match', function (Options $options, $value) {
            if (null === $value) {
                return;
            }
        });

        $resolver->setAllowedValues('type', function ($value) {
            if (in_array($value, ['json', 'xml', 'html'])) {
                return true;
            }

            if (null == $value) {
                return  true;
            }
        });

        $resolver->setAllowedTypes('type', ['string', 'null']);

        $options = $resolver->resolve($options);

        $this->type = $options['type'];
        $this->match = $options['match'];
    }

    /**
     * check.
     *
     * @param string $value
     *
     * @return bool
     */
    public function check($value)
    {
        $validator = $this->buildValidator();

        // If no validator then the result is true
        if (null == $validator) {
            return;
        }

        try {
            $validator->check($value, $this->match);

            return true;
        } catch (ValidatorException $e) {
            $this->error = $e->getMessage();

            return false;
        }
    }

    /**
     * getError.
     *
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * buildValidator.
     *
     * @return ValidatorInterface
     */
    private function buildValidator()
    {
        switch ($this->type) {
            case 'json':
                return new JsonValidator();
                break;
            case 'html':
                return new HtmlValidator();
                break;
            case 'xml':
                return new XmlValidator();
                break;
            default:
                return;
                break;
        }
    }
}
