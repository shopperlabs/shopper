/**
 * A social network link, in the order the merchant arranged them in the admin.
 */
export interface SocialLink {
  /** The platform key (facebook, instagram, x, tiktok, ...). */
  platform: string
  /** The profile URL. */
  url: string
  /** The display name of the platform (X (Twitter), LinkedIn, ...). */
  label: string
}

/** A locale the shop can answer in, for a language switcher. */
export interface LocaleOption {
  code: string
  label: string
}

/** The store country, resolved to its ISO code and translated name. */
export interface SettingsCountry {
  /** The ISO 3166-1 alpha-2 code. */
  code: string
  /** The country name in the negotiated locale (Accept-Language). */
  name: string
}

/** An image uploaded from the settings form. */
export interface SettingsMedia {
  url: string
}

/**
 * Public store settings, returned as a singleton by GET /store/settings.
 *
 * Every value can be null when the merchant has not filled it in. `about`
 * holds richtext HTML authored in the admin, so render it as markup.
 */
export interface StoreSettings {
  /** Always "store": the settings are a singleton, not a row. */
  id: string
  /** The public store name. */
  name: string | null
  /** The registered company name, for legal notices and invoices. */
  legal_name: string | null
  /** The store presentation, as richtext HTML. */
  about: string | null
  /** The contact email address. */
  email: string | null
  /** The contact phone number. */
  phone_number: string | null
  street_address: string | null
  city: string | null
  postal_code: string | null
  state: string | null
  country: SettingsCountry | null
  logo: SettingsMedia | null
  cover: SettingsMedia | null
  social_links: SocialLink[]
  /** The default currency code (EUR, USD, ...). */
  default_currency: string | null
  /** The currency codes the shop accepts, for a currency switcher. */
  currencies: string[]
  /** The locale the current response was rendered in. */
  default_locale: string
  supported_locales: LocaleOption[]
}
