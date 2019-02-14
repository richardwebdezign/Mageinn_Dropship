<?php
namespace Mageinn\Dropship\Test\Unit\Model\Batch\Export;

/**
 * Class FileTest
 * @package Mageinn\Dropship\Test\Unit\Model\Batch\Export
 */
class FileTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var string
     */
    protected $_testClassName = \Mageinn\Dropship\Model\Batch\Export\File::class;

    /**
     * @var \Mageinn\Dropship\Model\Batch\Export\File
     */
    protected $_testClass;

    /**
     * Method ran before each test
     */
    public function setUp()
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->_testClass = $objectManager->getObject('Mageinn\Dropship\Model\Batch\Export\File');
    }

    /**
     * Test for _isMail method happy flow
     */
    public function testIsMail()
    {
        // Access the method if protected
        $fileHeadings = new \ReflectionMethod($this->_testClassName, '_isMail');
        $fileHeadings->setAccessible(true);
        $destination = 'mailto:User@vendor.com?from=Noreply-Store@store.com&subject=Orders export from' .
            ' store.com&body=Please find orders attached&filename=orders-vendor-[YYYY][MM][DD].txt';

        $expectedResult = [
            'mailto:User@vendor.com?from=Noreply-Store@store.com&subject=Orders export from' .
            ' store.com&body=Please find orders attached&filename=orders-vendor-[YYYY][MM][DD].txt',
            'User@vendor.com',
            '?from=Noreply-Store@store.com&subject=Orders export from' .
            ' store.com&body=Please find orders attached&filename=orders-vendor-[YYYY][MM][DD].txt',
        ];
        $result = $fileHeadings->invoke($this->_testClass, $destination);

        $this->assertEquals($expectedResult, $result);
    }

    /**
     * Test for _isMail method for server path
     */
    public function testIsMailServerPath()
    {
        // Access the method if protected
        $fileHeadings = new \ReflectionMethod($this->_testClassName, '_isMail');
        $fileHeadings->setAccessible(true);
        $destination = 'var/some/dir/text.txt';

        $result = $fileHeadings->invoke($this->_testClass, $destination);

        $this->assertFalse($result);
    }

    /**
     * Test for _isMail method any other string
     */
    public function testIsMailOtherString()
    {
        // Access the method if protected
        $fileHeadings = new \ReflectionMethod($this->_testClassName, '_isMail');
        $fileHeadings->setAccessible(true);
        $destination = 'other string';

        $result = $fileHeadings->invoke($this->_testClass, $destination);

        $this->assertFalse($result);
    }

    /**
     * Test for _createFileOnServer method with exception
     *
     * Only wrote this test for the method as the rest of the method either uses only PHP default function calls
     * or Magento core function calls
     */
    public function testCreateFileOnServerWithException()
    {
        // Access the method if protected
        $createFile = new \ReflectionMethod($this->_testClassName, '_createFileOnServer');
        $createFile->setAccessible(true);
        $contents = 'content';
        $destination = 'just/some/folder/file.txt';

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid file destination');
        $createFile->invokeArgs($this->_testClass, [$contents, $destination]);
    }

    /**
     * Test for _getComponentsArray method
     */
    public function testGetComponentsArray()
    {
        $components = new \ReflectionMethod($this->_testClassName, '_getComponentsArray');
        $components->setAccessible(true);

        $componentsString = '?cc=email@example.com&filename=file.txt';

        $expectedResult = [
            'cc' => 'email@example.com',
            'filename' => 'file.txt',
        ];
        $result = $components->invoke($this->_testClass, $componentsString);

        $this->assertEquals($expectedResult, $result);
    }

    /**
     * @param string $destination
     * @param array $placeholders
     * @throws \ReflectionException
     * @throws \Exception
     * @dataProvider destinationStringProvider
     */
    public function testGetDestinationPath($destination, $placeholders)
    {
        $method = new \ReflectionMethod($this->_testClassName, 'getDestinationPath');
        $method->setAccessible(true);

        $result = $method->invoke($this->_testClass, $destination);

        $noPlaceholders = true;
        foreach ($placeholders as $placeholder) {
            if ($placeholder === '[OTHER]') {
                continue;
            }

            if (strpos($result, $placeholder) === 0) {
                $noPlaceholders = false;
            }
        }

        if (empty($placeholders)) {
            $this->assertTrue(empty($result));
        } else {
            if ($noPlaceholders) {
                $this->assertNotEquals($result, $destination);
            } else {
                throw new \Exception("Destination ({$destination}) has incorrect path ({$result}).");
            }
        }
    }

    /**
     * Batch export destination string
     * Placeholders parameter is used in order to check the formatted string.
     *
     * @return array
     */
    public function destinationStringProvider()
    {
        return [
            [
                'destination' => '',
                'placeholders' => []
            ],
            [
                'destination' => 'var/export/vendor/order-[MM]',
                'placeholders' => ['[MM]']
            ],
            [
                'destination' => 'var/export/vendor/order-[MM][DD][YYYY][YY][hh][mm][ss]',
                'placeholders' => ['[MM]','[DD]','[YYYY]', '[YY]', '[hh]', '[mm]', '[ss]']
            ],
            [
                'destination' => 'var/export/vendor/order-[MM][OTHER]',
                'placeholders' => ['[MM]', '[OTHER]']
            ]
        ];
    }
}
