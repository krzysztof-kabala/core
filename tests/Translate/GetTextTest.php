<?php
/**
 * This file is part of Vegas package
 *
 * @author Arkadiusz Ostrycharz <aostrycharz@amsterdam-standard.pl>
 * @copyright Amsterdam Standard Sp. Z o.o.
 * @homepage http://vegas-cmf.github.io
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Vegas\Tests\Tag;

use Vegas\Paginator\Adapter\Mongo;
use Vegas\Test\TestCase;
use Vegas\Translate\Adapter\GetText;

class GetTextTest extends TestCase
{
    /**
     * @expectedException \Phalcon\Translate\Exception
     */
    public function testNotArrayArgument()
    {
        $gettext = new Gettext('not_an_array');
    }

    /**
     * @expectedException \Phalcon\Translate\Exception
     */
    public function testLocaleArgument()
    {
        $gettext = new Gettext([]);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testDomainArgument()
    {
        $gettext = new Gettext(['locale' => 'en_US']);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testDomainDirectoryArgument()
    {
        $gettext = new Gettext([
            'locale' => 'en_US',
            'directory' => '/tmp'
        ]);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testDomainFileArgument()
    {
        $gettext = new Gettext([
            'locale' => 'en_US',
            'file' => 'tmp.data'
        ]);
    }

    /**
     * @expectedException \Phalcon\Translate\Exception
     */
    public function testDomainNotArrayArgument()
    {
        $gettext = new Gettext([
            'locale' => 'en_US',
            'file' => 'tmp.data',
            'directory' => '/tmp',
            'domains' => 'not_an_array'

        ]);
    }

    public function testDomainArrayArgument()
    {
        $gettext = new Gettext([
            'locale' => 'en_US',
            'file' => 'tmp.data',
            'directory' => '/tmp',
            'domains' => [
                'domain1', 'domain2'
            ]
        ]);
    }

    public function testDomainFileAsArrayArgument()
    {
        $gettext = new Gettext([
            'locale' => 'en_US',
            'file' => ['tmp.data'],
            'directory' => '/tmp'
        ]);
    }

    public function testString()
    {
        $getText = new Gettext([
            'locale' => 'en_EN',
            'file' => 'messages',
            'directory' => APP_ROOT.'/lang'
        ]);

        $this->assertEquals('Some random string.', $getText->_('Some random string.'));
    }
}