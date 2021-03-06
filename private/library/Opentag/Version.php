<?php

namespace Opentag;

use Opentag\Exception\DependentComponentNotFoundException;
use Opentag\Exception\IncompatibleComponentVersionException;

/**
 * Version class allows to checking the dependencies required
 * and the current version of doctrine extensions
 *
 * @since   beta 1.0
 * @version $Revision$
 * @author James A Helly <mrhelly@gmail.com>,  Gediminas Morkevicius <gediminas.morkevicius@gmail.com>
 * @subpackage Doctrine Adapter
 * @package Opentag
 * @category Opentag
 * @link http://opentag.spyders-lair.com
 * @license http://www.opensource.org/licenses/lgpl-license.php LGPL
 */
final class Version
{
    /**
     * Current version of extensions
     */
    const VERSION = '1.0.1';

    /**
     * Checks the dependent ORM library components
     * for compatibility
     *
     * @throws DependentComponentNotFoundException
     * @throws IncompatibleComponentVersionException
     */
    public static function checkORMDependencies()
    {
        // doctrine common library
        if (!class_exists('Doctrine\\Common\\Version')) {
            throw new DependentComponentNotFoundException("Doctrine\\Common library is either not registered by autoloader or not installed");
        }
        //var_dump(\Doctrine\Common\Version::compare('2.0.2') === 0);
        if (\Doctrine\Common\Version::compare('2.0.x') < 0 && \Doctrine\Common\Version::compare('2.1') <= 0) {
            throw new IncompatibleComponentVersionException("Doctrine\\Common library is older than expected for these extensions");
        }

        // doctrine dbal library
        if (!class_exists('Doctrine\\DBAL\\Version')) {
            throw new DependentComponentNotFoundException("Doctrine\\DBAL library is either not registered by autoloader or not installed");
        }
        if (\Doctrine\DBAL\Version::compare(self::VERSION) < 0 && \Doctrine\DBAL\Version::compare('2.1') <= 0) {
            throw new IncompatibleComponentVersionException("Doctrine\\DBAL library is older than expected for these extensions");
        }

        // doctrine ORM library
        if (!class_exists('Doctrine\\ORM\\Version')) {
            throw new DependentComponentNotFoundException("Doctrine\\ORM library is either not registered by autoloader or not installed");
        }
        if (\Doctrine\ORM\Version::compare(self::VERSION) < 0 && \Doctrine\ORM\Version::compare('2.1') <= 0) {
            throw new IncompatibleComponentVersionException("Doctrine\\ORM library is older than expected for these extensions");
        }
    }

    /**
     * Checks the dependent ODM MongoDB library components
     * for compatibility
     *
     * @throws DependentComponentNotFoundException
     * @throws IncompatibleComponentVersionException
    public static function checkODMMongoDBDependencies()
    {
        // doctrine common library
        if (!class_exists('Doctrine\\Common\\Version')) {
            throw new DependentComponentNotFoundException("Doctrine\\Common library is either not registered by autoloader or not installed");
        }

        if (\Doctrine\Common\Version::compare('2.0.x') < 0 && \Doctrine\Common\Version::compare('2.1') <= 0) {
            throw new IncompatibleComponentVersionException("Doctrine\\Common library is older than expected for these extensions");
        }

        // doctrine mongodb library
        if (!class_exists('Doctrine\\MongoDB\\Database')) {
            throw new DependentComponentNotFoundException("Doctrine\\MongoDB library is either not registered by autoloader or not installed");
        }

        // doctrine ODM MongoDB library
        if (!class_exists('Doctrine\\ODM\\MongoDB\\Version')) {
            throw new DependentComponentNotFoundException("Doctrine\\ODM\\MongoDB library is either not registered by autoloader or not installed");
        }
        if (\Doctrine\ODM\MongoDB\Version::compare('1.0.0BETA2-DEV') > 0) {
            throw new IncompatibleComponentVersionException("Doctrine\\ODM\\MongoDB library is older than expected for these extensions");
        }
    }
    //*/
}