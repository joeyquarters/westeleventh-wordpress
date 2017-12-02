<?php

namespace WestEleventh;

use Timber\Post;

class Episode extends Post {

  /**
   * Podtrac is a free analytics platform for podcast downloads. To use it, we prepend each episode's URL
   * with the correct Podtrac URL and it redirects.
   * @TODO This should use a different URL based on the filetype (.mp3, .m4a). We're not currently using .mp3,
   *       but ideally this would be smarter.
   * @return String  The Podtrac prepended URL
   */
  public function get_podtrac_url() {
    $audio = $this->get_field( 'audio' );

    if ($audio && $audio['url']) {
      return preg_replace( "(^https?://)", "http://dts.podtrac.com/redirect.m4a/", $audio['url'] );
    }
  }

  /**
   * Retrieve just the mime type from the ACF data
   * @return string The mime type of the attached audio file
   */
  public function get_mime_type() {
    $audio = $this->get_field( 'audio' );

    if ($audio && isset($audio['mime_type'])) {
      return $audio['mime_type'];
    }
  }

  /**
   * For some reason, WordPress doesn't *always* save the filesize in the attachment
   * metadata, so we fall back to calculating it on the fly.
   * @return int|null The filesize of the attached audio file. Null if it does not exist
   */
  public function get_filesize() {
    $audio = $this->get_field( 'audio' );

    if ($audio && isset($audio['id'])) {
      $attached_file = get_attached_file( $audio['id'] );
      $meta = wp_get_attachment_metadata( $audio['id'] );

      if ( isset( $meta['filesize'] ) ) {
        return $meta['filesize'];
      } elseif ( file_exists( $attached_file ) ) {
        return filesize( $attached_file );
      }
    }
  }

  /**
   * WordPress calculates the audio duration and saves it in the attachment metadata
   * @return string The human-readable format of the audio duration
   */
  public function get_audio_duration() {
    $audio = $this->get_field('audio');

    if ($audio && isset($audio['id'])) {
      $metadata = wp_get_attachment_metadata($audio['id']);

      if ($metadata && isset($metadata['length_formatted'])) {
        return $metadata['length_formatted'];
      }
    }
  }

}
