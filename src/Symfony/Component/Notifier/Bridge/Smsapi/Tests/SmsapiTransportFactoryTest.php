<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Notifier\Bridge\Smsapi\Tests;

use Symfony\Component\Notifier\Bridge\Smsapi\SmsapiTransportFactory;
use Symfony\Component\Notifier\Test\TransportFactoryTestCase;

final class SmsapiTransportFactoryTest extends TransportFactoryTestCase
{
    public function createFactory(): SmsapiTransportFactory
    {
        return new SmsapiTransportFactory();
    }

    public function createProvider(): iterable
    {
        yield [
            'smsapi://host.test?from=testFrom&fast=0',
            'smsapi://token@host.test?from=testFrom',
            'smsapi://token@host.test?from=testFrom&fast=0&test=0',
        ];

        yield [
            'smsapi://host.test?from=testFrom&fast=0',
            'smsapi://token@host.test?from=testFrom&fast=0',
            'smsapi://token@host.test?from=testFrom&fast=1&test=0',
            'smsapi://token@host.test?from=testFrom&test=0',
        ];

        yield [
            'smsapi://host.test?from=testFrom&fast=1',
            'smsapi://token@host.test?from=testFrom&fast=1',
            'smsapi://token@host.test?from=testFrom&fast=1&test=1',
            'smsapi://token@host.test?from=testFrom&test=1',
        ];

        yield [
            'smsapi://host.test?from=testFrom&fast=1',
            'smsapi://token@host.test?from=testFrom&fast=true',
            'smsapi://token@host.test?from=testFrom&fast=true&test=true',
            'smsapi://token@host.test?from=testFrom&test=true',
        ];
    }

    public function supportsProvider(): iterable
    {
        yield [true, 'smsapi://host?from=testFrom'];
        yield [true, 'smsapi://host?from=testFrom&fast=1'];
        yield [true, 'smsapi://host?from=testFrom&fast=1&test=1'];
        yield [false, 'somethingElse://host?from=testFrom'];
    }

    public function incompleteDsnProvider(): iterable
    {
        yield 'missing token' => ['smsapi://host.test?from=testFrom'];
    }

    public function missingRequiredOptionProvider(): iterable
    {
        yield 'missing option: from' => ['smsapi://token@host'];
    }

    public function unsupportedSchemeProvider(): iterable
    {
        yield ['somethingElse://token@host?from=testFrom'];
        yield ['somethingElse://token@host']; // missing "from" option
    }
}
