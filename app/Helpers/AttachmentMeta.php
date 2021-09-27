<?php

namespace Mentosmenno2\ImageCropPositioner\Helpers;

use Mentosmenno2\ImageCropPositioner\Objects\Face;
use Mentosmenno2\ImageCropPositioner\Objects\Hotspot;

class AttachmentMeta {

	public const META_KEY_FACES    = 'image_crop_positioner_faces';
	public const META_KEY_HOTSPOTS = 'image_crop_positioner_hotspots';

	/**
	 * @param integer $attachment_id
	 * @return Face[]
	 */
	public function get_faces( int $attachment_id ): array {
		$faces_data = get_post_meta( $attachment_id, self::META_KEY_FACES, true );
		if ( ! is_array( $faces_data ) ) {
			$faces_data = array();
		}

		return array_map(
			function( array $face_data ): Face {
				return new Face( $face_data );
			}, $faces_data
		);
	}

	/**
	 * @param integer $attachment_id
	 * @param Face[] $faces
	 */
	public function set_faces( int $attachment_id, array $faces ): void {
		$faces_data = array_map(
			function( Face $face ): array {
				return $face->get_data_array();
			}, $faces
		);
		update_post_meta( $attachment_id, self::META_KEY_FACES, $faces_data );
	}

	/**
	 * @param integer $attachment_id
	 * @return Hotspot[]
	 */
	public function get_hotspots( int $attachment_id ): array {
		$hotspots_data = get_post_meta( $attachment_id, self::META_KEY_HOTSPOTS, true );
		if ( ! is_array( $hotspots_data ) ) {
			$hotspots_data = array();
		}

		return array_map(
			function( array $hotspot_data ): Hotspot {
				return new Hotspot( $hotspot_data );
			}, $hotspots_data
		);
	}

	/**
	 * @param integer $attachment_id
	 * @param Hotspot[] $hotspots
	 */
	public function set_hotspots( int $attachment_id, array $hotspots ): void {
		$hotspots_data = array_map(
			function( Hotspot $hotspot ): array {
				return $hotspot->get_data_array();
			}, $hotspots
		);
		update_post_meta( $attachment_id, self::META_KEY_HOTSPOTS, $hotspots_data );
	}
}