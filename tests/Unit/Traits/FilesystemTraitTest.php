<?php

declare(strict_types=1);

namespace DrevOps\GitArtifact\Tests\Unit\Traits;

use DrevOps\GitArtifact\Traits\FilesystemTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(FilesystemTrait::class)]
class FilesystemTraitTest extends TestCase {

  /**
   * Test fsGetRootDir() returns PWD when set.
   */
  public function testFsGetRootDirWithPwd(): void {
    $testClass = new FilesystemTraitTestClass();

    // Set PWD environment variable.
    $_SERVER['PWD'] = '/test/path';

    $result = $testClass->callFsGetRootDir();

    $this->assertEquals('/test/path', $result);

    // Clean up.
    unset($_SERVER['PWD']);
  }

  /**
   * Test fsGetRootDir() returns getcwd() when PWD not set.
   */
  public function testFsGetRootDirWithoutPwd(): void {
    $testClass = new FilesystemTraitTestClass();

    // Unset PWD to force getcwd() usage.
    $originalPwd = $_SERVER['PWD'] ?? NULL;
    unset($_SERVER['PWD']);

    $result = $testClass->callFsGetRootDir();

    $this->assertEquals(getcwd(), $result);

    // Restore original PWD.
    if ($originalPwd !== NULL) {
      $_SERVER['PWD'] = $originalPwd;
    }
  }

  /**
   * Test fsGetRootDir() caches the result.
   */
  public function testFsGetRootDirCaching(): void {
    $testClass = new FilesystemTraitTestClass();

    // Set PWD.
    $_SERVER['PWD'] = '/test/path1';

    $result1 = $testClass->callFsGetRootDir();

    // Change PWD.
    $_SERVER['PWD'] = '/test/path2';

    // Should still return cached value.
    $result2 = $testClass->callFsGetRootDir();

    $this->assertEquals('/test/path1', $result1);
    $this->assertEquals('/test/path1', $result2);

    // Clean up.
    unset($_SERVER['PWD']);
  }

  /**
   * Test fsAssertPathsExist() with existing path.
   */
  public function testFsAssertPathsExistWithExistingPath(): void {
    $testClass = new FilesystemTraitTestClass();

    // Test with existing file.
    $tempFile = tempnam(sys_get_temp_dir(), 'test');
    $result = $testClass->callFsAssertPathsExist($tempFile, TRUE);

    $this->assertTrue($result);

    // Clean up.
    unlink($tempFile);
  }

  /**
   * Test fsAssertPathsExist() with non-existing path in strict mode.
   */
  public function testFsAssertPathsExistWithNonExistingPathStrict(): void {
    $testClass = new FilesystemTraitTestClass();

    $this->expectException(\Exception::class);
    $this->expectExceptionMessage('One of the files or directories does not exist');

    $testClass->callFsAssertPathsExist('/non/existing/path', TRUE);
  }

  /**
   * Test fsAssertPathsExist() with non-existing path in non-strict mode.
   */
  public function testFsAssertPathsExistWithNonExistingPathNonStrict(): void {
    $testClass = new FilesystemTraitTestClass();

    $result = $testClass->callFsAssertPathsExist('/non/existing/path', FALSE);

    $this->assertFalse($result);
  }

  /**
   * Test fsAssertPathsExist() with array of paths.
   */
  public function testFsAssertPathsExistWithArrayOfPaths(): void {
    $testClass = new FilesystemTraitTestClass();

    // Create temporary files.
    $tempFile1 = tempnam(sys_get_temp_dir(), 'test1');
    $tempFile2 = tempnam(sys_get_temp_dir(), 'test2');

    $result = $testClass->callFsAssertPathsExist([$tempFile1, $tempFile2], TRUE);

    $this->assertTrue($result);

    // Clean up.
    unlink($tempFile1);
    unlink($tempFile2);
  }

  /**
   * Test fsGetAbsolutePath() with absolute path.
   */
  public function testFsGetAbsolutePathWithAbsolutePath(): void {
    $testClass = new FilesystemTraitTestClass();

    $result = $testClass->callFsGetAbsolutePath('/absolute/path');

    $this->assertEquals('/absolute/path', $result);
  }

  /**
   * Test fsGetAbsolutePath() with relative path.
   */
  public function testFsGetAbsolutePathWithRelativePath(): void {
    $testClass = new FilesystemTraitTestClass();
    $testClass->setFsRootDir('/root/dir');

    $result = $testClass->callFsGetAbsolutePath('relative/path');

    $this->assertEquals('/root/dir/relative/path', $result);
  }

  /**
   * Test fsGetAbsolutePath() with custom root.
   */
  public function testFsGetAbsolutePathWithCustomRoot(): void {
    $testClass = new FilesystemTraitTestClass();

    $result = $testClass->callFsGetAbsolutePath('relative/path', '/custom/root');

    $this->assertEquals('/custom/root/relative/path', $result);
  }

}
