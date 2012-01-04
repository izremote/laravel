<?php namespace Laravel\Database\Schema;

abstract class Column {

	/**
	 * The name of the table column.
	 *
	 * @var string
	 */
	public $name;

	/**
	 * Indicates if the column should be nullable.
	 *
	 * @var bool
	 */
	public $nullable = false;

	/**
	 * Create a new schema column instance.
	 *
	 * @param  string  $name
	 * @return void
	 */
	public function __construct($name)
	{
		$this->name = $name;
	}

	/**
	 * Indicate that the column should be nullable.
	 *
	 * @return Column
	 */
	final public function nullable()
	{
		$this->nullable = true;
		return $this;
	}

	/**
	 * Get the type of the column instance.
	 *
	 * @return string
	 */
	final public function type()
	{
		if ($this instanceof Columns\String)
		{
			return 'string';
		}
		elseif ($this instanceof Columns\Integer)
		{
			return 'integer';
		}

		throw new \Exception("Unable to determine the column type.");
	}

}