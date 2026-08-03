import type { Category, CategoryTreeNode } from '@shopperlabs/shopper-types'

import type { FetchInit } from '../http'
import { CollectionResource } from './collection'

export class CategoryResource extends CollectionResource<Category> {
  /** The full nested tree of visible categories, ordered by position. */
  public async tree(init?: FetchInit): Promise<CategoryTreeNode[]> {
    const document = await this.client.request(`${this.path}/tree`, undefined, init)

    return (document.data ?? []) as unknown as CategoryTreeNode[]
  }
}
