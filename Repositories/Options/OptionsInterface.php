<?php namespace Repositories\Options;

interface OptionsInterface
{
	static function create();
	static function read();
	static function update();
	static function delete();
}