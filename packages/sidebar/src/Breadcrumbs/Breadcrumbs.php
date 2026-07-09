<?php

declare(strict_types=1);

namespace Shopper\Sidebar\Breadcrumbs;

use Shopper\Sidebar\Contracts\Builder\Group;
use Shopper\Sidebar\Contracts\Builder\Item;
use Shopper\Sidebar\Contracts\Builder\Menu;
use Shopper\Sidebar\Presentation\ActivePath;
use Shopper\Sidebar\Presentation\ActiveStateChecker;

class Breadcrumbs
{
    /** @var list<Breadcrumb> */
    protected array $pushed = [];

    public function __construct(
        protected ActiveStateChecker $checker = new ActiveStateChecker,
    ) {}

    public function push(Breadcrumb $crumb): void
    {
        $this->pushed[] = $crumb;
    }

    public function prepend(Breadcrumb $crumb): void
    {
        array_unshift($this->pushed, $crumb);
    }

    /**
     * @return list<Breadcrumb>
     */
    public function all(): array
    {
        return $this->pushed;
    }

    public function reset(): void
    {
        $this->pushed = [];
    }

    /**
     * @return list<Breadcrumb>
     */
    public function build(Menu $menu): array
    {
        $path = $this->checker->findActivePath($menu);

        if ($path === null) {
            return $this->pushed;
        }

        $pushedUrls = array_values(array_filter(array_map(
            fn (Breadcrumb $crumb): ?string => $crumb->url,
            $this->pushed,
        )));

        $autoCrumbs = array_values(array_filter(
            $this->crumbsFromPath($path),
            fn (Breadcrumb $crumb): bool => $crumb->url === null
                || ! in_array($crumb->url, $pushedUrls, true),
        ));

        return [...$autoCrumbs, ...$this->pushed];
    }

    /**
     * @return list<Breadcrumb>
     */
    protected function crumbsFromPath(ActivePath $path): array
    {
        $crumbs = [];

        foreach ($path->items as $index => $item) {
            $crumbs[] = new Breadcrumb(
                text: $item->getName(),
                url: $item->getUrl(),
                icon: $item->getIcon(),
                links: $index === 0 ? $this->siblingLinks($path->group, $item) : null,
                spa: $item->withSpa(),
            );
        }

        return $crumbs;
    }

    /**
     * @return list<BreadcrumbLink>
     */
    protected function siblingLinks(Group $group, Item $active): array
    {
        $links = [];

        foreach ($group->getItems() as $sibling) {
            if ($sibling === $active) {
                continue;
            }

            if (! $sibling->isAuthorized()) {
                continue;
            }

            $links[] = new BreadcrumbLink(
                text: $sibling->getName(),
                url: $sibling->getUrl(),
                icon: $sibling->getIcon(),
                spa: $sibling->withSpa(),
            );
        }

        return $links;
    }
}
