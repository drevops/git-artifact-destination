<?php

namespace DrevOps\Robo\Tests\Traits;

/**
 * Trait ReflectionTrait.
 *
 * Provides methods to work with class reflection.
 */
trait ReflectionTrait
{

  /**
   * Call protected methods on the class.
   *
   * @param object|string $object
   *   Object or class name to use for a method call.
   * @param string $name
   *   Method name. Method can be static.
   * @param array $args
   *   Array of arguments to pass to the method. To pass arguments by reference,
   *   pass them by reference as an element of this array.
   *
   * @return mixed
   *   Method result.
   */
    protected static function callProtectedMethod(object|string $object, string $name, array $args = [])
    {
        $objectOrClass = is_object($object) ? $object::class : $object;

        if (!class_exists($objectOrClass)) {
            throw new \InvalidArgumentException(sprintf('Class %s does not exist', $objectOrClass));
        }

        $class = new \ReflectionClass($objectOrClass);

        if (!$class->hasMethod($name)) {
            throw new \InvalidArgumentException(sprintf('Method %s does not exist', $name));
        }

        $method = $class->getMethod($name);

        $originalAccessibility = $method->isPublic();

      // Set method accessibility to true, so it can be invoked.
        $method->setAccessible(true);

      // If the method is static, we won't pass an object instance to invokeArgs()
      // Otherwise, we ensure to pass the object instance.
        $invokeObject = $method->isStatic() ? null : (is_object($object) ? $object : null);

      // Ensure we have an object for non-static methods.
        if (!$method->isStatic() && $invokeObject === null) {
            throw new \InvalidArgumentException("An object instance is required for non-static methods");
        }

        $result = $method->invokeArgs($invokeObject, $args);

      // Reset the method's accessibility to its original state.
        $method->setAccessible($originalAccessibility);

        return $result;
    }

  /**
   * Set protected property value.
   *
   * @param object $object
   *   Object to set the value on.
   * @param string $property
   *   Property name to set the value. Property should exists in the object.
   * @param mixed $value
   *   Value to set to the property.
   */
    protected static function setProtectedValue($object, $property, mixed $value): void
    {
        $class = new \ReflectionClass($object::class);
        $property = $class->getProperty($property);
        $property->setAccessible(true);

        $property->setValue($object, $value);
    }

  /**
   * Get protected value from the object.
   *
   * @param object $object
   *   Object to set the value on.
   * @param string $property
   *   Property name to get the value. Property should exists in the object.
   *
   * @return mixed
   *   Protected property value.
   */
    protected static function getProtectedValue($object, $property)
    {
        $class = new \ReflectionClass($object::class);
        $property = $class->getProperty($property);
        $property->setAccessible(true);

        return $property->getValue($class);
    }
}