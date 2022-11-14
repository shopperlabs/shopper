<?php

namespace Shopper\Framework\Events\Handlers;

use Maatwebsite\Sidebar\Group;
use Maatwebsite\Sidebar\Item;
use Maatwebsite\Sidebar\Menu;
use Shopper\Framework\Models\Shop\Order\Order;
use Shopper\Framework\Models\Shop\Order\OrderStatus;
use Shopper\Framework\Sidebar\AbstractAdminSidebar;

class RegisterOrderSidebar extends AbstractAdminSidebar
{
    public function extendWith(Menu $menu): Menu
    {
        $count = Order::query()->where('status', OrderStatus::PENDING)->count();

        $menu->group(__('shopper::layout.sidebar.shop'), function (Group $group) use ($count) {
            $group->weight(20);
            $group->authorize();

            $group->item(__('shopper::layout.sidebar.orders'), function (Item $item) use ($count) {
                $item->weight(1);
                $item->authorize($this->user->hasPermissionTo('browse_orders'));
                if ($count > 0) {
                    $item->badge($count);
                }
                $item->route('shopper.orders.index');
                $item->icon('
                    <svg class="shrink-0 -ml-1 mr-3 h-5 w-5" fill="none" xmlns="http://www.w3.org/2000/svg" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M5.52 2.64 3.96 4.72c-.309.412-.463.618-.46.79a.5.5 0 0 0 .192.384C3.828 6 4.085 6 4.6 6h14.8c.515 0 .773 0 .908-.106a.5.5 0 0 0 .192-.384c.003-.172-.151-.378-.46-.79l-1.56-2.08m-12.96 0c.176-.235.264-.352.376-.437a1 1 0 0 1 .33-.165C6.36 2 6.505 2 6.8 2h10.4c.293 0 .44 0 .575.038a1 1 0 0 1 .33.165c.111.085.199.202.375.437m-12.96 0L3.64 5.147c-.237.316-.356.475-.44.649a2 2 0 0 0-.163.487C3 6.473 3 6.671 3 7.067V18.8c0 1.12 0 1.68.218 2.108a2 2 0 0 0 .874.874C4.52 22 5.08 22 6.2 22h11.6c1.12 0 1.68 0 2.108-.218a2 2 0 0 0 .874-.874C21 20.48 21 19.92 21 18.8V7.067c0-.396 0-.594-.037-.784a1.998 1.998 0 0 0-.163-.487c-.084-.174-.203-.333-.44-.65L18.48 2.64M16 10a4 4 0 1 1-8 0"/>
                    </svg>
                ');
            });

            $group->item(__('shopper::layout.sidebar.discounts'), function (Item $item) {
                $item->weight(9);
                $item->authorize($this->user->hasPermissionTo('browse_discounts'));
                $item->route('shopper.discounts.index');
                $item->icon('
                    <svg class="shrink-0 -ml-1 mr-3 h-5 w-5" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                       <path stroke-linecap="round" stroke-linejoin="round" d="M9 9h.01M15 15h.01M16 8l-8 8m9.901-11.001a2.03 2.03 0 0 0 1.1 1.1l1.744.723a2.033 2.033 0 0 1 1.1 2.656l-.722 1.744a2.03 2.03 0 0 0 0 1.556l.722 1.744a2.033 2.033 0 0 1-1.1 2.656L19 17.901A2.033 2.033 0 0 0 17.9 19l-.723 1.745a2.032 2.032 0 0 1-2.656 1.1l-1.744-.722a2.032 2.032 0 0 0-1.555 0l-1.745.723a2.033 2.033 0 0 1-2.654-1.1L6.1 19.001A2.033 2.033 0 0 0 5 17.9l-1.744-.723a2.033 2.033 0 0 1-1.1-2.654l.721-1.744a2.033 2.033 0 0 0 0-1.556l-.722-1.746a2.033 2.033 0 0 1 1.1-2.657L5 6.098A2.03 2.03 0 0 0 6.1 5l.723-1.745a2.033 2.033 0 0 1 2.656-1.1l1.744.722a2.033 2.033 0 0 0 1.555-.001l1.746-.72a2.032 2.032 0 0 1 2.655 1.1l.723 1.746v-.003ZM9.5 9a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0Zm6 6a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0Z"/>
                    </svg>
                ');
            });
        });

        return $menu;
    }
}
