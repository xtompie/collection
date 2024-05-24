<?php


use PHPUnit\Framework\TestCase;
use Xtompie\Collection\Collection;

class CollectionTest extends TestCase
{

    public function testAnyReturnsTrueWhenCollectionIsNotEmpty(): void
    {
        // Arrange
        $collection = Collection::of([1, 2, 3]);

        // Act
        $result = $collection->any();

        // Assert
        $this->assertTrue($result);
    }

    public function testAnyReturnsFalseWhenCollectionIsEmpty(): void
    {
        // Arrange
        $collection = Collection::ofEmpty();

        // Act
        $result = $collection->any();

        // Assert
        $this->assertFalse($result);
    }

    public function testNoneReturnsTrueWhenCollectionIsEmpty(): void
    {
        // Arrange
        $collection = Collection::ofEmpty();

        // Act
        $result = $collection->none();

        // Assert
        $this->assertTrue($result);
    }

    public function testNoneReturnsFalseWhenCollectionIsNotEmpty(): void
    {
        // Arrange
        $collection = Collection::of([1, 2, 3]);

        // Act
        $result = $collection->none();

        // Assert
        $this->assertFalse($result);
    }

    public function testContainsReturnsTrueWhenCollectionContainsItem(): void
    {
        // Arrange
        $collection = Collection::of([1, 2, 3]);

        // Act
        $result = $collection->contains(2);

        // Assert
        $this->assertTrue($result);
    }

    public function testContainsReturnsFalseWhenCollectionDoesNotContainItem(): void
    {
        // Arrange
        $collection = Collection::of([1, 2, 3]);

        // Act
        $result = $collection->contains(4);

        // Assert
        $this->assertFalse($result);
    }

    public function testCountReturnsNumberOfItemsInCollection(): void
    {
        // Arrange
        $collection = Collection::of([1, 2, 3]);

        // Act
        $result = $collection->count();

        // Assert
        $this->assertEquals(3, $result);
    }

    public function testFilterReturnsNewCollectionWithItemsFilteredByCallback(): void
    {
        // Arrange
        $collection = Collection::of([1, 2, 3]);

        // Act
        $result = $collection->filter(fn ($i) => $i > 1);

        // Assert
        $this->assertEquals([2, 3], $result->values()->toArray());
    }

    public function testOnlyReturnsNewCollectionWithItemsWithKeysFromGivenArray(): void
    {
        // Arrange
        $collection = Collection::of(['a' => 1, 'b' => 2, 'c' => 3]);

        // Act
        $result = $collection->only(['a', 'c']);

        // Assert
        $this->assertEquals(['a' => 1, 'c' => 3], $result->toArray());
    }

    public function testValuesReturnsNewCollectionWithValuesFromOriginalCollection(): void
    {
        // Arrange
        $collection = Collection::of(['a' => 1, 'b' => 2, 'c' => 3]);

        // Act
        $result = $collection->values();

        // Assert
        $this->assertEquals([1, 2, 3], $result->toArray());
    }

    public function testKeysReturnsNewCollectionWithKeysFromOriginalCollection(): void
    {
        // Arrange
        $collection = Collection::of(['a' => 1, 'b' => 2, 'c' => 3]);

        // Act
        $result = $collection->keys();

        // Assert
        $this->assertEquals(['a', 'b', 'c'], $result->toArray());
    }

    public function testFirstReturnsFirstItemInCollection(): void
    {
        // Arrange
        $collection = Collection::of([1, 2, 3]);

        // Act
        $result = $collection->first();

        // Assert
        $this->assertEquals(1, $result);
    }

    public function testFirstReturnsNullWhenCollectionIsEmpty(): void
    {
        // Arrange
        $collection = Collection::ofEmpty();

        // Act
        $result = $collection->first();

        // Assert
        $this->assertNull($result);
    }

    public function testColReturnsNewCollectionWithValuesFromSpecifiedColumn(): void
    {
        // Arrange
        $collection = Collection::of([
            ['id' => 1, 'name' => 'John'],
            ['id' => 2, 'name' => 'Jane'],
            ['id' => 3, 'name' => 'Alice'],
        ]);

        // Act
        $result = $collection->col('name');

        // Assert
        $this->assertEquals(['John', 'Jane', 'Alice'], $result->toArray());
    }

}