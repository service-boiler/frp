<?php

namespace ServiceBoiler\Prf\Site\Contracts;


use Illuminate\Support\Collection;

interface Importable
{

	/**
	 * @return Importable
	 */
	function import(): self;

	/**
	 * @return Collection
	 */
	function errors(): Collection;

	/**
	 * @return Collection
	 */
	function data(): Collection;

	/**
	 * @return Collection
	 */
	function values(): Collection;

	/**
	 * @return Importable
	 */
	function reset(): self;


}