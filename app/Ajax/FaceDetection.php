<?php

namespace Mentosmenno2\ImageCropPositioner\Ajax;

use Exception;
use Mentosmenno2\ImageCropPositioner\Assets;
use Mentosmenno2\ImageCropPositioner\Objects\Face;
use Mentosmenno2\ImageCropPositioner\FaceDetection\FaceDetector;
use WP_Error;

class FaceDetection {

	protected const ACCURACY_THRESHHOLD = 50;

	public function register_hooks(): void {
		add_action( 'wp_ajax_image_crop_positioner_face_detection', array( $this, 'handle_request' ) );
	}

	/**
	 * Handle the request
	 */
	public function handle_request(): void {
		if ( ! check_ajax_referer( Assets::NONCE_ACTION, false, false ) ) {
			$error = new WP_Error(
				403, __( 'Invalid nonce.', 'image-crop-positioner' ), array(
					'status' => 403,
				)
			);
			wp_send_json_error( $error, 403 );
			exit;
		}

		$attachment_id = (int) filter_input( INPUT_POST, 'attachment_id', FILTER_VALIDATE_INT );
		$post_type     = get_post_type( $attachment_id );
		if ( ! $attachment_id || $post_type !== 'attachment' ) {
			$error = new WP_Error(
				400, __( 'Invalid attachment ID.', 'image-crop-positioner' ), array(
					'status' => 400,
				)
			);
			wp_send_json_error( $error, 400 );
			exit;
		}

		$this->detect_faces( $attachment_id );
	}

	/**
	 * Detect faces from attachment, save it in the meta, and send them to the json response.
	 */
	protected function detect_faces( int $attachment_id ): void {
		$file = wp_get_original_image_path( $attachment_id );
		if ( ! is_string( $file ) ) {
			$error = new WP_Error(
				400, __( 'Attachment has no file path.', 'image-crop-positioner' ), array(
					'status' => 400,
				)
			);
			wp_send_json_error( $error, 400 );
			exit;
		}

		try {
			$extraction = FaceDetector::get_instance()->extract( $file );
		} catch ( Exception $e ) {
			$error = new WP_Error(
				400, $e->getMessage(), array(
					'status' => 400,
				)
			);
			wp_send_json_error( $error, 400 );
			exit;
		}

		$data = array(
			'faces' => array(),
		);
		if ( $extraction->face instanceof Face && $extraction->face->get_accuracy() >= self::ACCURACY_THRESHHOLD ) {
			$data['faces'][] = $extraction->face->get_data_array();
		}
		wp_send_json_success( $data, 200 );
		exit;
	}
}
