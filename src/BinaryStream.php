<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
*/

namespace pocketmine\binaryutils;

#include <rules/DataPacket.h>


class BinaryStream extends \stdClass{

	/** @var int */
	public $offset;
	/** @var string */
	public $buffer;

	/**
	 * BinaryStream constructor.
	 *
	 * @param string $buffer
	 * @param int    $offset
	 */
	public function __construct(string $buffer = "", int $offset = 0){
		$this->buffer = $buffer;
		$this->offset = $offset;
	}

	public function reset(){
		$this->buffer = "";
		$this->offset = 0;
	}

	/**
	 * @param string $buffer
	 * @param int    $offset
	 */
	public function setBuffer(string $buffer = "", int $offset = 0){
		$this->buffer = $buffer;
		$this->offset = $offset;
	}

	/**
	 * Returns the stream pointer.
	 *
	 * @return int
	 */
	public function getOffset() : int{
		return $this->offset;
	}

	/**
	 * Returns the full raw binary buffer
	 *
	 * @return string
	 */
	public function getBuffer() : string{
		return $this->buffer;
	}

	/**
	 * Returns a part of the buffer starting from the current pointer up to $len chars, or to the end of the buffer if TRUE is supplied.
	 *
	 * @param int|bool $len If true, returns everything from the pointer to the end of the buffer, or a string of length $len bytes if an intege
	 *
	 * @return string
	 */
	public function get($len) : string{
		if($len < 0){
			$this->offset = strlen($this->buffer) - 1;
			return "";
		}elseif($len === true){
			$str = substr($this->buffer, $this->offset);
			$this->offset = strlen($this->buffer);
			return $str;
		}

		return $len === 1 ? $this->buffer{$this->offset++} : substr($this->buffer, ($this->offset += $len) - $len, $len);
	}

	/**
	 * Appends the supplied string to the end of the buffer
	 *
	 * @param string $str
	 */
	public function put(string $str){
		$this->buffer .= $str;
	}

	/**
	 * Reads a bool value from the next byte in the buffer.
	 *
	 * @return bool
	 */
	public function getBool() : bool{
		return Binary::readBool($this->get(1));
	}

	/**
	 * Writes a bool to the end of the buffer.
	 *
	 * @param bool $v
	 */
	public function putBool(bool $v){
		$this->buffer .= Binary::writeBool($v);
	}

	/**
	 * Reads an int8 value from the next byte of the buffer
	 * @return int
	 */
	public function getByte() : int{
		return ord($this->buffer{$this->offset++});
	}

	/**
	 * Writes an int8 value to the end of the buffer in 1 byte.
	 * @param int $v
	 */
	public function putByte(int $v){
		$this->buffer .= chr($v);
	}


	/**
	 * Reads a big-endian int16 value from the next 2 bytes of the buffer.
	 * @return int
	 */
	public function getShort() : int{
		return Binary::readShort($this->get(2));
	}

	/**
	 * Reads a little-endian int16 value from the next 2 bytes of the buffer.
	 * @return int
	 */
	public function getLShort() : int{
		return Binary::readLShort($this->get(2));
	}

	/**
	 * Reads a signed big-endian int16 value from the next 2 bytes of the buffer.
	 * @return int
	 */
	public function getSignedShort() : int{
		return Binary::readSignedShort($this->get(2));
	}

	/**
	 * Reads a signed little-endian int16 value from the next 2 bytes of the buffer.
	 * @return int
	 */
	public function getSignedLShort() : int{
		return Binary::readSignedLShort($this->get(2));
	}

	/**
	 * Writes a big-endian int16 value to the end of the buffer.
	 * @param int $v
	 */
	public function putShort(int $v){
		$this->buffer .= Binary::writeShort($v);
	}

	/**
	 * Writes a little-endian int16 value to the end of the buffer.
	 * @param int $v
	 */
	public function putLShort(int $v){
		$this->buffer .= Binary::writeLShort($v);
	}


	/**
	 * Reads a big-endian int24 value from the next 3 bytes of the buffer.
	 * @return int
	 */
	public function getTriad() : int{
		return Binary::readTriad($this->get(3));
	}

	/**
	 * Reads a little-endian int24 value from the next 3 bytes of the buffer.
	 * @return int
	 */
	public function getLTriad() : int{
		return Binary::readLTriad($this->get(3));
	}

	/**
	 * Writes a big-endian int24 value to the end of the buffer in 3 bytes.
	 * @param int $v
	 */
	public function putTriad(int $v){
		$this->buffer .= Binary::writeTriad($v);
	}

	/**
	 * Writes a little-endian int24 value to the end of the buffer in 3 bytes.
	 * @param int $v
	 */
	public function putLTriad(int $v){
		$this->buffer .= Binary::writeLTriad($v);
	}



	/**
	 * Reads a big-endian int32 value from the next 4 bytes of the buffer.
	 * @return int
	 */
	public function getInt() : int{
		return Binary::readInt($this->get(4));
	}

	/**
	 * Reads a little-endian int32 value from the next 4 bytes of the buffer.
	 * @return int
	 */
	public function getLInt() : int{
		return Binary::readLInt($this->get(4));
	}

	/**
	 * Writes a big-endian int32 value to the end of the buffer in 4 bytes.
	 * @param int $v
	 */
	public function putInt(int $v){
		$this->buffer .= Binary::writeInt($v);
	}

	/**
	 * Writes a little-endian int32 value to the end of the buffer in 4 bytes.
	 * @param int $v
	 */
	public function putLInt(int $v){
		$this->buffer .= Binary::writeLInt($v);
	}


	/**
	 * Reads a big-endian int64 value from the next 8 bytes of the buffer.
	 *
	 * @return int|string int, or string representation of long on 32-bit systems
	 */
	public function getLong(){
		return Binary::readLong($this->get(8));
	}

	/**
	 * Reads a little-endian int64 value from the next 8 bytes of the buffer.
	 *
	 * @return int|string int, or string representation of long on 32-bit systems
	 */
	public function getLLong(){
		return Binary::readLLong($this->get(8));
	}

	/**
	 * Writes a big-endian int64 value to the end of the buffer in 8 bytes.
	 *
	 * @param int|float|string $v This may be an float or a string on 32-bit systems.
	 */
	public function putLong($v){
		$this->buffer .= Binary::writeLong($v);
	}

	/**
	 * Writes a little-endian int64 value to the buffer in 8 bytes.
	 *
	 * @param int|float|string $v This may be an float or a string on 32-bit systems.
	 */
	public function putLLong($v){
		$this->buffer .= Binary::writeLLong($v);
	}


	/**
	 * Reads a big-endian float value from the next 4 bytes of the buffer.
	 *
	 * @param int $accuracy The number of decimal places to round the resulting value to.
	 * @return float
	 */
	public function getFloat(int $accuracy = -1) : float{
		return Binary::readFloat($this->get(4), $accuracy);
	}

	/**
	 * Reads a little-endian float value from the next 4 bytes of the buffer.
	 *
	 * @param int $accuracy The number of decimal places to round the resulting value to.
	 * @return float
	 */
	public function getLFloat(int $accuracy = -1) : float{
		return Binary::readLFloat($this->get(4), $accuracy);
	}

	/**
	 * Writes a big-endian float value to the end of the buffer in 4 bytes.
	 *
	 * @param float $v
	 */
	public function putFloat(float $v){
		$this->buffer .= Binary::writeFloat($v);
	}

	/**
	 * Writes a little-endian float value to the end of the buffer in 4 bytes.
	 *
	 * @param float $v
	 */
	public function putLFloat(float $v){
		$this->buffer .= Binary::writeLFloat($v);
	}

	/**
	 * Reads an encoded UUID from the next 16 bytes of the buffer.
	 * @return UUID
	 */
	public function getUUID(){
		return UUID::fromBinary($this->get(16));
	}

	/**
	 * Writes a UUID to the end of the buffer in 16 bytes.
	 * @param UUID $uuid
	 */
	public function putUUID(UUID $uuid){
		$this->put($uuid->toBinary());
	}


	/**
	 * Reads an unsigned variable-length integer from the buffer.
	 * @return int|string int, or string representation of long on 32-bit systems
	 */
	public function getUnsignedVarInt(){
		return Binary::readUnsignedVarInt($this);
	}

	/**
	 * Reads a signed (zigzagged) variable-length integer from the buffer.
	 * @return int|string int, or string representation of long on 32-bit systems
	 */
	public function getVarInt(){
		return Binary::readVarInt($this);
	}

	/**
	 * Writes an unsigned variable-length integer to the buffer.
	 * @param int|float|string $v This may be an float or a string on 32-bit systems.
	 */
	public function putUnsignedVarInt($v){
		$this->put(Binary::writeUnsignedVarInt($v));
	}

	/**
	 * Writes an signed (zigzagged) variable-length integer to the buffer.
	 * @param int|float|string $v This may be an float or a string on 32-bit systems.
	 */
	public function putVarInt($v){
		$this->put(Binary::writeVarInt($v));
	}

	/**
	 * Returns whether there is any more data left in the stream to read.
	 * @return bool
	 */
	public function feof() : bool{
		return !isset($this->buffer{$this->offset});
	}
}
