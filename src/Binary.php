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

if(!defined("ENDIANNESS")){
	define("ENDIANNESS", (pack("d", 1) === "\77\360\0\0\0\0\0\0" ? Binary::BIG_ENDIAN : Binary::LITTLE_ENDIAN));
}

class Binary{
	const BIG_ENDIAN = 0x00;
	const LITTLE_ENDIAN = 0x01;

	private static function checkLength($str, $expect){
		assert(($len = strlen($str)) === $expect, "Expected $expect bytes, got $len");
	}

	/**
	 * Decodes a boolean value from 1 byte.
	 *
	 * @param string $b
	 * @return bool
	 */
	public static function readBool(string $b) : bool{
		self::checkLength($b, 1);
		return $b !== "\x00";
	}

	/**
	 * Encodes a boolean value as 1 byte.
	 *
	 * @param bool $b
	 * @return string
	 */
	public static function writeBool(bool $b){
		return $b ? "\x01" : "\x00";
	}

	/**
	 * Decodes an unsigned byte
	 *
	 * @param string $c
	 * @return int
	 */
	public static function readByte(string $c) : int{
		self::checkLength($c, 1);
		return ord($c{0});
	}

	/**
	 * Decodes a signed byte
	 *
	 * @param string $c
	 * @return int
	 */
	public function readSignedByte(string $c) : int{
		return (PHP_INT_SIZE === 8 ? (ord($c{0}) << 56 >> 56) : (ord($c{0}) << 24 >> 24));
	}

	/**
	 * Encodes an unsigned/signed byte
	 *
	 * @param int $c
	 * @return string
	 */
	public static function writeByte(int $c) : string{
		return chr($c);
	}

	/**
	 * Decodes a 16-bit unsigned big-endian number
	 *
	 * @param string $str
	 * @return int
	 */
	public static function readShort(string $str) : int{
		self::checkLength($str, 2);
		return unpack("n", $str)[1];
	}

	/**
	 * Decodes a 16-bit unsigned little-endian number
	 *
	 * @param string $str
	 * @return int
	 */
	public static function readLShort(string $str) : int{
		self::checkLength($str, 2);
		return unpack("v", $str)[1];
	}

	/**
	 * Decodes a 16-bit signed big-endian number
	 *
	 * @param string $str
	 * @return int
	 */
	public static function readSignedShort(string $str) : int{
		self::checkLength($str, 2);
		return (PHP_INT_SIZE === 8 ? (unpack("n", $str)[1] << 48 >> 48) : (unpack("n", $str)[1] << 16 >> 16));
	}

	/**
	 * Decodes a 16-bit signed little-endian number
	 *
	 * @param string $str
	 * @return int
	 */
	public static function readSignedLShort(string $str) : int{
		self::checkLength($str, 2);
		return (PHP_INT_SIZE === 8 ? (unpack("v", $str)[1] << 48 >> 48) : (unpack("v", $str)[1] << 16 >> 16));
	}

	/**
	 * Encodes a 16-bit signed/unsigned big-endian number
	 *
	 * @param int $value
	 * @return string
	 */
	public static function writeShort(int $value) : string{
		return pack("n", $value);
	}

	/**
	 * Encodes a 16-bit signed/unsigned little-endian number
	 *
	 * @param int $value
	 * @return string
	 */
	public static function writeLShort(int $value) : string{
		return pack("v", $value);
	}

	/**
	 * Decodes a 24-bit big-endian number
	 *
	 * @param string $str
	 * @return int
	 */
	public static function readTriad(string $str) : int{
		self::checkLength($str, 3);
		return unpack("N", "\x00" . $str)[1];
	}

	/**
	 * Decodes a 24-bit little-endian number
	 *
	 * @param string $str
	 * @return int
	 */
	public static function readLTriad(string $str) : int{
		self::checkLength($str, 3);
		return unpack("V", $str . "\x00")[1];
	}

	/**
	 * Encodes a 24-bit big-endian number
	 *
	 * @param int $value
	 * @return string
	 */
	public static function writeTriad(int $value) : string{
		return substr(pack("N", $value), 1);
	}

	/**
	 * Writes a 24-bit little-endian number
	 *
	 * @param int $value
	 * @return string
	 */
	public static function writeLTriad(int $value) : string{
		return substr(pack("V", $value), 0, -1);
	}

	/**
	 * Decodes a 32-bit big-endian number
	 *
	 * @param string $str
	 * @return int
	 */
	public static function readInt(string $str) : int{
		self::checkLength($str, 4);
		return (PHP_INT_SIZE === 8 ? (unpack("N", $str)[1] << 32 >> 32) : (unpack("N", $str)[1]));
	}

	/**
	 * Decodes a 32-bit little-endian number
	 *
	 * @param string $str
	 * @return int
	 */
	public static function readLInt($str){
		self::checkLength($str, 4);
		return (PHP_INT_SIZE === 8 ? (unpack("V", $str)[1] << 32 >> 32) : (unpack("V", $str)[1]));
	}

	/**
	 * Encodes a 32-bit big-endian number
	 *
	 * @param int $value
	 * @return string
	 */
	public static function writeInt(int $value) : string{
		return pack("N", $value);
	}

	/**
	 * Encodes a 32-bit little-endian number
	 *
	 * @param int $value
	 * @return string
	 */
	public static function writeLInt(int $value) : string{
		return pack("V", $value);
	}

	/**
	 * Decodes a 64-bit big-endian number
	 *
	 * @param string $x
	 * @return int|string int, or string representation of long on 32-bit systems
	 */
	public static function readLong(string $x){
		self::checkLength($x, 8);
		if(PHP_INT_SIZE === 8){
			$int = unpack("N*", $x);
			return ($int[1] << 32) | $int[2];
		}else{
			$value = "0";
			for($i = 0; $i < 8; $i += 2){
				$value = bcmul($value, "65536", 0);
				$value = bcadd($value, self::readShort(substr($x, $i, 2)), 0);
			}

			if(bccomp($value, "9223372036854775807") == 1){
				$value = bcadd($value, "-18446744073709551616");
			}

			return $value;
		}
	}

	/**
	 * Decodes a 64-bit little-endian number
	 *
	 * @param string $str
	 * @return int|string int, or string representation of long on 32-bit systems
	 */
	public static function readLLong(string $str){
		return self::readLong(strrev($str));
	}

	/**
	 * Encodes a 64-bit big-endian number
	 *
	 * @param int|float|string $value This may be an float or a string on 32-bit systems.
	 * @return string
	 */
	public static function writeLong($value) : string{
		if(PHP_INT_SIZE === 8){
			return pack("NN", $value >> 32, $value & 0xFFFFFFFF);
		}else{
			$x = "";

			if(bccomp($value, "0") == -1){
				$value = bcadd($value, "18446744073709551616");
			}

			$x .= self::writeShort(bcmod(bcdiv($value, "281474976710656"), "65536"));
			$x .= self::writeShort(bcmod(bcdiv($value, "4294967296"), "65536"));
			$x .= self::writeShort(bcmod(bcdiv($value, "65536"), "65536"));
			$x .= self::writeShort(bcmod($value, "65536"));

			return $x;
		}
	}

	/**
	 * Encodes a 64-bit little-endian number
	 *
	 * @param int|float|string $value This may be an float or a string on 32-bit systems.
	 * @return string
	 */
	public static function writeLLong($value){
		return strrev(self::writeLong($value));
	}



	/**
	 * Decodes a 32-bit big-endian floating-point number, optionally rounding to a specified number of decimal places
	 *
	 * @param string $str
	 * @param int $accuracy
	 *
	 * @return float
	 */
	public static function readFloat(string $str, int $accuracy = -1) : float{
		self::checkLength($str, 4);
		$value = ENDIANNESS === self::BIG_ENDIAN ? unpack("f", $str)[1] : unpack("f", strrev($str))[1];
		return ($accuracy > -1 ? round($value, $accuracy) : $value);
	}

	/**
	 * Decodes a 32-bit little-endian floating-point number, optionally rounding to a specified number of decimal places
	 *
	 * @param string $str
	 * @param int $accuracy
	 *
	 * @return float
	 */
	public static function readLFloat(string $str, int $accuracy = -1) : float{
		self::checkLength($str, 4);
		$value = ENDIANNESS === self::BIG_ENDIAN ? unpack("f", strrev($str))[1] : unpack("f", $str)[1];
		return ($accuracy > -1 ? round($value, $accuracy) : $value);
	}

	/**
	 * Encodes a 32-bit big-endian floating-point number
	 *
	 * @param float $value
	 * @return string
	 */
	public static function writeFloat(float $value) : string{
		return ENDIANNESS === self::BIG_ENDIAN ? pack("f", $value) : strrev(pack("f", $value));
	}

	/**
	 * Encodes a 32-bit little-endian floating-point number
	 *
	 * @param float $value
	 * @return string
	 */
	public static function writeLFloat(float $value) : string{
		return ENDIANNESS === self::BIG_ENDIAN ? strrev(pack("f", $value)) : pack("f", $value);
	}

	public static function printFloat($value){
		return preg_replace("/(\\.\\d+?)0+$/", "$1", sprintf("%F", $value));
	}

	/**
	 * Decodes a 64-bit big-endian floating-point number
	 *
	 * @param string $str
	 * @return mixed
	 */
	public static function readDouble(string $str) : string{
		self::checkLength($str, 8);
		return ENDIANNESS === self::BIG_ENDIAN ? unpack("d", $str)[1] : unpack("d", strrev($str))[1];
	}

	/**
	 * Decodes a 64-bit little-endian floating-point number
	 *
	 * @param string $str
	 * @return mixed
	 */
	public static function readLDouble(string $str){
		self::checkLength($str, 8);
		return ENDIANNESS === self::BIG_ENDIAN ? unpack("d", strrev($str))[1] : unpack("d", $str)[1];
	}

	/**
	 * Encodes a 64-bit big-endian floating-point number
	 *
	 * @param mixed $value
	 * @return string
	 */
	public static function writeDouble($value) : string{
		return ENDIANNESS === self::BIG_ENDIAN ? pack("d", $value) : strrev(pack("d", $value));
	}

	/**
	 * Encodes a 64-bit little-endian floating-point number
	 *
	 * @param mixed $value
	 * @return string
	 */
	public static function writeLDouble($value) : string{
		return ENDIANNESS === self::BIG_ENDIAN ? strrev(pack("d", $value)) : pack("d", $value);
	}


	//TODO: separate VarInt and VarLong, proper VarLong support for 32-bit systems

	/**
	 * Decodes a signed variable-length integer from the supplied BinaryStream, up to 10 bytes in length.
	 *
	 * TODO: find a better method to do this or move it to BinaryStream
	 *
	 * @param BinaryStream $stream
	 * @return int
	 */
	public static function readVarInt(BinaryStream $stream) : int{
		$shift = PHP_INT_SIZE === 8 ? 63 : 31;
		$raw = self::readUnsignedVarInt($stream);
		$temp = ((($raw << $shift) >> $shift) ^ $raw) >> 1;
		return $temp ^ ($raw & (1 << $shift));
	}

	/**
	 * Decodes an unsigned variable-length integer from the supplied BinaryStream, up to 10 bytes in length.
	 *
	 * TODO: find a better method to do this or move it to BinaryStream
	 *
	 * @param BinaryStream $stream
	 * @return int
	 */
	public static function readUnsignedVarInt(BinaryStream $stream) : int{
		$value = 0;
		$i = 0;
		do{
			if($i > 63){
				throw new \InvalidArgumentException("VarInt did not terminate after 10 bytes!");
			}
			$value |= ((($b = $stream->getByte()) & 0x7f) << $i);
			$i += 7;
		}while($b & 0x80);

		return $value;
	}

	/**
	 * Encodes a supplied value to a variable-length integer.
	 *
	 * @param mixed $v
	 * @return string
	 */
	public static function writeVarInt($v){
		return self::writeUnsignedVarInt(($v << 1) ^ ($v >> (PHP_INT_SIZE === 8 ? 63 : 31)));
	}

	/**
	 * Encodes a supplied value to a variable-length integer
	 *
	 * @param mixed $value
	 * @return string
	 */
	public static function writeUnsignedVarInt($value){
		$buf = "";
		for($i = 0; $i < 10; ++$i){
			if(($value >> 7) !== 0){
				$buf .= chr($value | 0x80);
			}else{
				$buf .= chr($value & 0x7f);
				return $buf;
			}

			$value = (($value >> 7) & (PHP_INT_MAX >> 6)); //PHP really needs a logical right-shift operator
		}

		throw new \InvalidArgumentException("Value too large to be encoded as a VarInt");
	}
}
