<?php
namespace Dragnic\LeagueBundle\Entity;

class PropertyIterator implements \Iterator
{

    /** @var  PropertyIteratorAggregate */
    protected $collection;
    protected $currentIndex;
    protected $keys;
    protected $currentKey;

    public function __construct(PropertyIteratorAggregate $collection)
    {
        $this->collection = $collection;
        $this->keys = $collection->keys();
        $this->currentIndex = 0;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    public function current()
    {
        return $this->collection->offsetGet($this->key());
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next()
    {
        $this->currentIndex++;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     */
    public function key()
    {
        if (array_key_exists($this->currentIndex, $this->keys)) {
            $key = $this->keys[$this->currentIndex];
            if (0 === strpos($key, '__')) {
                $this->next();
                $key = $this->key();
            }
        } else {
            $key = null;
        }

        return $key;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid()
    {
        return $this->collection->offsetExists($this->key());
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        $this->currentIndex = 0;
    }
}