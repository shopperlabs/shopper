export interface Media {
  id: string | number
  /** The original file, at the size it was uploaded. */
  url: string
  /**
   * The resized variants, keyed by the conversion names configured for the
   * shop (`large` and `medium` out of the box). Display these rather than
   * `url` on listings and thumbnails. A conversion added after an image was
   * uploaded stays absent until the media is regenerated, so read defensively.
   */
  conversions?: Record<string, string>
  name?: string | null
  extension?: string | null
  created_at?: string
  updated_at?: string
}
