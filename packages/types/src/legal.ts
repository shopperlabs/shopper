/**
 * A legal page (terms, privacy, refund, shipping) managed from the admin.
 */
export interface Legal {
  id: string
  /** The page title. */
  title: string
  /** The public identifier used to retrieve the page. */
  slug: string
  /** The page body, as richtext HTML. */
  content: string | null
  /** When the page was last edited, shown as the last update date. */
  updated_at: string
}
